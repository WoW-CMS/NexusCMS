<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::query()->orderBy('name')->get();
        return view('admin::permissions.index', compact('permissions'));
    }
}

