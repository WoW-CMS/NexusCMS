<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Services\UpdateService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\UpdateLog;

class UpdateController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private readonly UpdateService $updates) {}

    public function index()
    {
        $this->authorize('viewAny', UpdateLog::class);

        $current    = config('app.version');
        $latest     = $this->updates->getLatestRelease();
        $outdated   = $this->updates->isOutdated($latest);
        $method     = $this->updates->detectMethod();
        $checks     = $this->updates->preflight();
        $releases   = $this->updates->getReleases(5);
        $history    = $this->updates->getHistory(10);
        // Fetch the release matching the currently running version for correct release notes link
        $currentRelease = $this->updates->getReleaseByTag($current)
                       ?? $this->updates->getReleaseByTag('v' . ltrim($current, 'v'));

        return view('admin::updates.index', compact(
            'latest', 'outdated', 'method', 'checks', 'releases', 'history', 'current', 'currentRelease'
        ));
    }

    public function apply(Request $request)
    {
        $this->authorize('apply', UpdateLog::class);

        if (!settings('update_enabled', config('update.enabled', true))) {
            return response()->json(['success' => false, 'error' => 'The auto-update system is disabled.'], 403);
        }

        $tag = trim((string) $request->input('tag', ''));

        if ($tag === '') {
            return response()->json(['success' => false, 'error' => 'No release tag specified.'], 422);
        }

        // Basic tag sanitization — allow semver + v-prefix + pre-release suffixes
        if (!preg_match('/^v?[\d]+\.[\d]+(\.[\d]+)?([-.][a-z0-9]+)*$/i', $tag)) {
            return response()->json(['success' => false, 'error' => 'Invalid tag format.'], 422);
        }

        $result = $this->updates->apply($tag, auth()->id());

        $status = $result['success'] ? 200 : 500;

        return response()->json($result, $status);
    }
}
