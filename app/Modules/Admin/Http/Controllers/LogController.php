<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $logPath = storage_path('logs');
        $logFiles = collect(File::glob($logPath . '/laravel*.log'))
            ->map(fn($file) => basename($file))
            ->sortDesc()
            ->values();

        $selectedFile = $request->get('file', $logFiles->first());

        if ($selectedFile && !preg_match('/^laravel[a-zA-Z0-9\-_.]*\.log$/', $selectedFile)) {
            abort(403);
        }

        $entries = collect();
        $filePath = $logPath . '/' . $selectedFile;

        if ($selectedFile && File::exists($filePath)) {
            $content = File::get($filePath);
            $pattern = '/\[(\d{4}-\d{2}-\d{2}[T ]\d{2}:\d{2}:\d{2}\.?\d*\+?\d*:?\d*)\]\s+\w+\.(\w+):\s(.*?)(?=\[\d{4}-\d{2}-\d{2}[T ]|\z)/s';

            preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

            $entries = collect($matches)->map(function ($match) {
                return [
                    'timestamp' => $match[1],
                    'level' => strtolower($match[2]),
                    'message' => trim($match[3]),
                ];
            })->reverse()->values();
        }

        $levelFilter = $request->get('level');
        if ($levelFilter) {
            $entries = $entries->where('level', $levelFilter)->values();
        }

        $searchQuery = $request->get('search');
        if ($searchQuery) {
            $entries = $entries->filter(function ($entry) use ($searchQuery) {
                return stripos($entry['message'], $searchQuery) !== false;
            })->values();
        }

        $page = (int) $request->get('page', 1);
        $perPage = 25;
        $total = $entries->count();
        $entries = $entries->slice(($page - 1) * $perPage, $perPage)->values();

        return view('admin::logs.index', compact(
            'logFiles',
            'selectedFile',
            'entries',
            'levelFilter',
            'searchQuery',
            'page',
            'perPage',
            'total',
        ));
    }
}
