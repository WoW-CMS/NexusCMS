<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Receipt PDF cleanup
|--------------------------------------------------------------------------
|
| Donation receipts are persisted to storage/app/receipts/ on demand
| whenever a user downloads one. To keep that folder from growing
| forever, prune any PDF older than 7 days once a day.
|
| The schedule is intentionally quiet during the day (runs at 03:30)
| and uses file mtime — *not* a database column — because the file
| itself is the source of truth for "when was this generated".
|
| On environments where the scheduler is not running, the cleanup
| can be triggered manually with:
|   php artisan schedule:run
| or for an explicit sweep:
|   php artisan tinker --execute="require base_path('routes/console.php');"
*/
Schedule::call(function () {
    $directory = storage_path('app/receipts');

    if (!is_dir($directory)) {
        return;
    }

    $cutoff   = now()->subDays(7)->timestamp;
    $deleted  = 0;
    $bytesFreed = 0;

    foreach (glob($directory . DIRECTORY_SEPARATOR . 'donation-receipt-*.pdf') ?: [] as $file) {
        if (!is_file($file)) {
            continue;
        }

        // Defense-in-depth: only delete files whose name matches the
        // expected pattern AND whose mtime is older than the cutoff.
        // This protects against an operator changing the directory
        // and the sweep clobbering something unrelated.
        if (filemtime($file) >= $cutoff) {
            continue;
        }

        $bytesFreed += @filesize($file) ?: 0;

        if (@unlink($file)) {
            $deleted++;
        } else {
            Log::warning('Receipt PDF cleanup: could not delete file', ['path' => $file]);
        }
    }

    if ($deleted > 0) {
        Log::info('Receipt PDF cleanup', [
            'deleted'      => $deleted,
            'bytes_freed'  => $bytesFreed,
            'directory'    => $directory,
        ]);
    }
})
->name('receipts:cleanup')
->dailyAt('03:30')
->withoutOverlapping()
->onOneServer();
