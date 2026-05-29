<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use ZipArchive;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Backup;

class BackupController extends Controller
{
    use AuthorizesRequests;

    protected string $backupPath;

    public function __construct()
    {
        $this->backupPath = storage_path('app/backups');
    }

    public function index()
    {
        $this->authorize('viewAny', Backup::class);

        File::ensureDirectoryExists($this->backupPath);

        $backups = collect(File::glob($this->backupPath . '/backup-*.zip'))
            ->map(function ($file) {
                return [
                    'filename' => basename($file),
                    'size' => File::size($file),
                    'date' => File::lastModified($file),
                ];
            })
            ->sortByDesc('date')
            ->values();

        $totalSize = $backups->sum('size');
        $latestBackup = $backups->first();

        return view('admin::backups.index', compact('backups', 'totalSize', 'latestBackup'));
    }

    public function create()
    {
        $this->authorize('create', Backup::class);

        File::ensureDirectoryExists($this->backupPath);

        $timestamp = now()->format('Y-m-d-His');
        $filename = "backup-{$timestamp}.zip";
        $zipPath = $this->backupPath . '/' . $filename;
        $tempDir = storage_path("app/backup-temp-{$timestamp}");

        try {
            File::ensureDirectoryExists($tempDir);

            // Database dump
            $this->dumpDatabase($tempDir);

            // Create ZIP
            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new \RuntimeException('Could not create ZIP archive.');
            }

            // Add DB dump
            $dumpFile = $tempDir . '/database.sql';
            if (File::exists($dumpFile)) {
                $zip->addFile($dumpFile, 'database.sql');
            }

            // Add .env
            $envPath = base_path('.env');
            if (File::exists($envPath)) {
                $zip->addFile($envPath, '.env');
            }

            // Add config files
            $this->addDirectoryToZip($zip, config_path(), 'config');

            // Add migrations
            $this->addDirectoryToZip($zip, database_path('migrations'), 'migrations');

            // Add public uploads
            $publicStorage = storage_path('app/public');
            if (File::isDirectory($publicStorage)) {
                $this->addDirectoryToZip($zip, $publicStorage, 'storage/public');
            }

            $zip->close();

            // Cleanup temp
            File::deleteDirectory($tempDir);

            return redirect()->route('admin.backups.index')
                ->with('success', "Backup '{$filename}' created successfully.");

        } catch (\Exception $e) {
            File::deleteDirectory($tempDir);
            if (File::exists($zipPath)) {
                File::delete($zipPath);
            }

            return redirect()->route('admin.backups.index')
                ->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function download(string $filename)
    {
        $this->authorize('download', Backup::class);

        if (!preg_match('/^backup-[\d-]+\.zip$/', $filename)) {
            abort(403);
        }

        $path = $this->backupPath . '/' . $filename;

        if (!File::exists($path)) {
            abort(404);
        }

        return response()->download($path);
    }

    public function destroy(string $filename)
    {
        $this->authorize('delete', Backup::class);

        if (!preg_match('/^backup-[\d-]+\.zip$/', $filename)) {
            abort(403);
        }

        $path = $this->backupPath . '/' . $filename;

        if (!File::exists($path)) {
            abort(404);
        }

        File::delete($path);

        return redirect()->route('admin.backups.index')
            ->with('success', "Backup '{$filename}' deleted successfully.");
    }

    protected function dumpDatabase(string $targetDir): void
    {
        $connection = config('database.default');
        $config = config("database.connections.{$connection}");

        if ($config['driver'] !== 'mysql') {
            return;
        }

        $dumpFile = $targetDir . '/database.sql';

        $process = new Process([
            'mysqldump',
            '--host=' . $config['host'],
            '--port=' . ($config['port'] ?? 3306),
            '--user=' . $config['username'],
            '--password=' . $config['password'],
            $config['database'],
        ]);

        $process->setTimeout(300);
        $process->run();

        if ($process->isSuccessful()) {
            File::put($dumpFile, $process->getOutput());
        } else {
            throw new \RuntimeException('Database dump failed: ' . $process->getErrorOutput());
        }
    }

    protected function addDirectoryToZip(ZipArchive $zip, string $directory, string $prefix): void
    {
        if (!File::isDirectory($directory)) {
            return;
        }

        $files = File::allFiles($directory);
        foreach ($files as $file) {
            $relativePath = $prefix . '/' . $file->getRelativePathname();
            $zip->addFile($file->getRealPath(), str_replace('\\', '/', $relativePath));
        }
    }
}
