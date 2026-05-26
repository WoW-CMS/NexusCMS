<?php

namespace Modules\Admin\Services;

use App\Models\UpdateLog;
use Illuminate\Support\Facades\Http;

class UpdateService
{
    private const GITHUB_API = 'https://api.github.com';

    private string $owner;
    private string $repo;
    private ?string $githubToken;
    private string $channel;

    public function __construct()
    {
        $this->owner        = settings('update_repo_owner', config('update.repository.owner', 'wow-cms'));
        $this->repo         = settings('update_repo_name',  config('update.repository.name', 'nexuscms'));
        $this->githubToken  = settings('update_github_token', config('update.github_token'));
        $this->channel      = settings('update_channel',    config('update.channel', 'any'));
    }

    // ─── HTTP Client ─────────────────────────────────────────────────────────

    private function http(): \Illuminate\Http\Client\PendingRequest
    {
        $client = Http::timeout(15)
            ->acceptJson()
            ->withHeaders(['User-Agent' => 'NexusCMS-Updater/1.0']);

        if (!empty($this->githubToken)) {
            $client = $client->withToken($this->githubToken);
        }

        return $client;
    }

    // ─── Release Discovery ───────────────────────────────────────────────────

    /**
     * Fetch the latest release matching the configured channel.
     */
    public function getLatestRelease(): ?array
    {
        try {
            if ($this->channel === 'stable') {
                $response = $this->http()->get(
                    self::GITHUB_API . "/repos/{$this->owner}/{$this->repo}/releases/latest"
                );

                if (!$response->ok()) {
                    return null;
                }

                $data = $response->json();
                return is_array($data) ? $this->normalize($data) : null;
            }

            // For beta/any channel, fetch list and pick first matching
            $releases = $this->getReleases(20);
            return $this->matchChannel($releases);

        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Fetch the N most recent releases.
     */
    public function getReleases(int $limit = 10): array
    {
        try {
            $response = $this->http()->get(
                self::GITHUB_API . "/repos/{$this->owner}/{$this->repo}/releases",
                ['per_page' => min($limit, 30)]
            );

            if (!$response->ok()) {
                return [];
            }

            $data = $response->json();
            return is_array($data) ? array_map([$this, 'normalize'], $data) : [];

        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * Fetch a specific release by tag name.
     */
    public function getReleaseByTag(string $tag): ?array
    {
        try {
            $response = $this->http()->get(
                self::GITHUB_API . "/repos/{$this->owner}/{$this->repo}/releases/tags/{$tag}"
            );

            if (!$response->ok()) {
                return null;
            }

            $data = $response->json();
            return is_array($data) ? $this->normalize($data) : null;

        } catch (\Throwable) {
            return null;
        }
    }

    private function normalize(array $release): array
    {
        $rawTag = $release['tag_name'] ?? '';
        $tag    = ltrim($rawTag, 'v');

        return [
            'tag'          => $tag,
            'tag_name'     => $rawTag ?: $tag,
            'name'         => $release['name'] ?? $rawTag,
            'body'         => trim($release['body'] ?? ''),
            'prerelease'   => (bool) ($release['prerelease'] ?? false),
            'draft'        => (bool) ($release['draft'] ?? false),
            'published_at' => $release['published_at'] ?? null,
            'html_url'     => $release['html_url'] ?? '',
            'zipball_url'  => $release['zipball_url'] ?? '',
        ];
    }

    private function matchChannel(array $releases): ?array
    {
        foreach ($releases as $release) {
            if ($release['draft']) {
                continue;
            }

            if ($this->channel === 'stable' && $release['prerelease']) {
                continue;
            }

            return $release;
        }

        return null;
    }

    // ─── Version Comparison ──────────────────────────────────────────────────

    public function isOutdated(?array $latest = null): bool
    {
        $latest = $latest ?? $this->getLatestRelease();

        if (!$latest) {
            return false;
        }

        $current     = ltrim((string) config('app.version', '0.0.0'), 'v');
        $latestClean = ltrim($latest['tag_name'] ?? $latest['tag'] ?? '', 'v');

        // Compare base versions (strip pre-release suffix) first
        $currentBase = preg_replace('/[-+].*$/', '', $current);
        $latestBase  = preg_replace('/[-+].*$/', '', $latestClean);

        if (version_compare($latestBase, $currentBase, '>')) {
            return true;
        }

        if (version_compare($latestBase, $currentBase, '<')) {
            return false;
        }

        // Base versions are equal — compare full strings so that
        // 0.1.6-alpha.1 is considered newer than 0.1.6-alpha
        if ($latestClean === $current) {
            return false;
        }

        return version_compare($latestClean, $current, '>');
    }

    // ─── Pre-flight Checks ───────────────────────────────────────────────────

    public function preflight(): array
    {
        $method = $this->detectMethod();
        $checks = [];

        // For git-based updates, PHP itself does NOT write to base_path —
        // the git binary (running via exec) handles all file operations.
        // So this is a warning only, never a blocker.
        $checks[] = $this->check(
            'Write permission — base path',
            $this->probeWrite(base_path()),
            base_path(),
            required: $method !== 'git'
        );

        // storage/ is needed for cache, compiled views, migrations etc.
        // For zip: required. For git: artisan still needs it → required.
        $checks[] = $this->check(
            'Write permission — storage',
            $this->probeWrite(storage_path()),
            storage_path()
        );

        $execEnabled = function_exists('exec') &&
            !in_array('exec', array_map('trim', explode(',', (string) ini_get('disable_functions'))));

        $checks[] = $this->check(
            'PHP exec() enabled',
            $execEnabled,
            'Required to run Artisan commands post-update'
        );

        if ($method === 'git') {
            $checks[] = $this->check(
                'Git binary available',
                $this->commandExists('git'),
                'Used to checkout the target release tag'
            );
        } else {
            $free = @disk_free_space(base_path());
            $checks[] = $this->check(
                'Disk space (> 50 MB)',
                $free && $free > 50 * 1024 * 1024,
                $free ? number_format($free / 1024 / 1024, 1) . ' MB available' : 'Could not determine'
            );

            $checks[] = $this->check(
                'PHP ZipArchive extension',
                class_exists('ZipArchive'),
                'Required to extract the downloaded release archive'
            );
        }

        return $checks;
    }

    private function check(string $name, bool $ok, string $detail = '', bool $required = true): array
    {
        return compact('name', 'ok', 'detail', 'required');
    }

    private function probeWrite(string $path): bool
    {
        if (!is_dir($path)) {
            return false;
        }
        $probe = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
            . '.nexus_write_test_' . getmypid();
        try {
            $result = @file_put_contents($probe, '1');
            if ($result !== false) {
                @unlink($probe);
                return true;
            }
            return false;
        } catch (\Throwable) {
            return false;
        }
    }

    // ─── Update Method Detection ─────────────────────────────────────────────

    public function detectMethod(): string
    {
        if (is_dir(base_path('.git')) && $this->commandExists('git')) {
            return 'git';
        }

        return 'zip';
    }

    private function commandExists(string $command): bool
    {
        $test = PHP_OS_FAMILY === 'Windows' ? "where {$command}" : "which {$command}";
        exec($test . ' 2>&1', $output, $code);

        return $code === 0;
    }

    // ─── Apply Update ────────────────────────────────────────────────────────

    /**
     * Apply a specific release tag. Returns ['success', 'log', 'error?'].
     */
    public function apply(string $tag, ?int $userId = null): array
    {
        $fromVersion = (string) config('app.version', '0.0.0');
        $method      = $this->detectMethod();
        $log         = [];
        $inMaintenance = false;

        try {
            // 1 ─ Maintenance mode
            if (settings('update_maintenance_mode', config('update.maintenance_mode', true))) {
                $this->run(PHP_BINARY . ' artisan down --retry=60', $log);
                $inMaintenance = true;
            }

            // 2 ─ Apply files
            if ($method === 'git') {
                $this->applyViaGit($tag, $log);
            } else {
                $release = $this->getReleaseByTag($tag);

                if (!$release || empty($release['zipball_url'])) {
                    throw new \RuntimeException("Release '{$tag}' not found or has no downloadable archive.");
                }

                $this->applyViaZip($release['zipball_url'], $tag, $log);
            }

            // 3 ─ Composer (if composer.json changed)
            if ($method === 'zip' && $this->commandExists('composer')) {
                $this->run('composer install --no-dev --optimize-autoloader --no-interaction', $log);
            }

            // 4 ─ Migrations
            $this->run(PHP_BINARY . ' artisan migrate --force', $log);

            // 5 ─ Clear caches
            $this->run(PHP_BINARY . ' artisan optimize:clear', $log);

            // 6 ─ Update .env version
            $this->writeEnvVersion($tag);
            $log[] = "✔ Updated APP_VERSION={$tag} in .env";

            // 7 ─ Bring back up
            if ($inMaintenance) {
                $this->run(PHP_BINARY . ' artisan up', $log);
                $inMaintenance = false;
            }

            // 8 ─ Persist log
            UpdateLog::create([
                'from_version' => $fromVersion,
                'to_version'   => $tag,
                'method'       => $method,
                'status'       => 'success',
                'notes'        => implode("\n", $log),
                'executed_by'  => $userId,
            ]);

            return ['success' => true, 'log' => $log];

        } catch (\Throwable $e) {
            // Always attempt to bring the site back up
            if ($inMaintenance) {
                try {
                    $this->run(PHP_BINARY . ' artisan up', $log);
                } catch (\Throwable) {
                    $log[] = '⚠ Could not automatically bring the site back up — run `php artisan up` manually.';
                }
            }

            $log[] = '✘ ERROR: ' . $e->getMessage();

            UpdateLog::create([
                'from_version' => $fromVersion,
                'to_version'   => $tag,
                'method'       => $method,
                'status'       => 'failed',
                'notes'        => implode("\n", $log),
                'executed_by'  => $userId,
            ]);

            return ['success' => false, 'log' => $log, 'error' => $e->getMessage()];
        }
    }

    // ─── Git Strategy ────────────────────────────────────────────────────────

    private function applyViaGit(string $tag, array &$log): void
    {
        // Build the GitHub remote URL from configured owner/repo
        $token   = $this->githubToken;
        $repoUrl = $token
            ? "https://{$token}@github.com/{$this->owner}/{$this->repo}.git"
            : "https://github.com/{$this->owner}/{$this->repo}.git";

        // Silently remove the temporary remote if it already exists (ignore errors)
        $this->runSilent('git remote remove nexus-update', $log);

        $this->run("git remote add nexus-update {$repoUrl}", $log);
        $this->run('git stash', $log);
        $this->run('git fetch nexus-update --tags --force', $log);
        $this->run("git checkout tags/{$tag}", $log);

        // Clean up the temporary remote (ignore errors)
        $this->runSilent('git remote remove nexus-update', $log);
    }

    // ─── ZIP Strategy ────────────────────────────────────────────────────────

    private function applyViaZip(string $url, string $tag, array &$log): void
    {
        if (!class_exists('ZipArchive')) {
            throw new \RuntimeException('The PHP ZipArchive extension is required for ZIP-based updates.');
        }

        $log[] = "Downloading archive for {$tag}...";

        $zipBytes = $this->http()->get($url)->body();

        if (empty($zipBytes)) {
            throw new \RuntimeException('Downloaded archive is empty. The GitHub token may be missing or the release URL is invalid.');
        }

        $tmpZip = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "nexuscms-update-{$tag}.zip";
        file_put_contents($tmpZip, $zipBytes);
        $log[] = 'Archive saved to ' . $tmpZip;

        $tmpDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "nexuscms-update-{$tag}";
        $zip = new \ZipArchive();

        if ($zip->open($tmpZip) !== true) {
            throw new \RuntimeException('Failed to open the downloaded ZIP archive.');
        }

        $zip->extractTo($tmpDir);
        $zip->close();
        $log[] = 'Archive extracted.';

        // GitHub ZIP has a single top-level directory: owner-repo-{sha}/
        $topDirs = glob($tmpDir . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
        $sourceDir = !empty($topDirs) ? $topDirs[0] : $tmpDir;

        $log[] = 'Copying files to application root...';
        $this->copyDirectory($sourceDir, base_path(), $log);

        // Cleanup
        @unlink($tmpZip);
        $this->deleteDirectory($tmpDir);

        $log[] = 'Files applied.';
    }

    private function copyDirectory(string $source, string $dest, array &$log): void
    {
        $skipPaths = config('update.skip_paths', []);

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $relative = ltrim(str_replace($source, '', $item->getPathname()), DIRECTORY_SEPARATOR . '/');

            foreach ($skipPaths as $skip) {
                $skip = ltrim(rtrim($skip, '/'), '/');
                if ($relative === $skip || str_starts_with($relative, $skip . DIRECTORY_SEPARATOR) || str_starts_with($relative, $skip . '/')) {
                    continue 2;
                }
            }

            $target = $dest . DIRECTORY_SEPARATOR . $relative;

            if ($item->isDir()) {
                if (!is_dir($target)) {
                    mkdir($target, 0755, true);
                }
            } else {
                copy($item->getPathname(), $target);
            }
        }
    }

    private function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        foreach (scandir($dir) as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $entry;
            is_dir($path) ? $this->deleteDirectory($path) : @unlink($path);
        }

        @rmdir($dir);
    }

    // ─── Shell Execution ─────────────────────────────────────────────────────

    private function run(string $command, array &$log): void
    {
        $base = base_path();

        $wrapped = PHP_OS_FAMILY === 'Windows'
            ? 'cd /d ' . escapeshellarg($base) . ' && ' . $command . ' 2>&1'
            : 'cd ' . escapeshellarg($base) . ' && ' . $command . ' 2>&1';

        $output = [];
        $code   = 0;
        exec($wrapped, $output, $code);

        $log[] = '$ ' . $command;
        foreach ($output as $line) {
            if (trim($line) !== '') {
                $log[] = '  ' . $line;
            }
        }

        if ($code !== 0) {
            throw new \RuntimeException(
                "Command failed (exit {$code}): {$command}\n" . implode("\n", array_filter($output))
            );
        }
    }

    // Same as run() but never throws — failures are logged as warnings only
    private function runSilent(string $command, array &$log): void
    {
        try {
            $this->run($command, $log);
        } catch (\RuntimeException) {
            // Intentionally ignored
        }
    }

    // ─── .env Version Update ─────────────────────────────────────────────────

    private function writeEnvVersion(string $tag): void
    {
        $envPath = base_path('.env');

        if (!is_file($envPath) || !is_writable($envPath)) {
            return;
        }

        $version = ltrim($tag, 'v');
        $content = file_get_contents($envPath);

        if (str_contains($content, 'APP_VERSION=')) {
            $content = preg_replace('/^APP_VERSION=.*/m', "APP_VERSION={$version}", $content);
        } else {
            $content .= "\nAPP_VERSION={$version}";
        }

        file_put_contents($envPath, $content);
    }

    // ─── Update History ──────────────────────────────────────────────────────

    public function getHistory(int $limit = 15): \Illuminate\Database\Eloquent\Collection
    {
        return UpdateLog::with('executor')
            ->latest()
            ->limit($limit)
            ->get();
    }
}
