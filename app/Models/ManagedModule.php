<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagedModule extends Model
{
    protected $table = 'managed_modules';

    protected $fillable = [
        'module_name',
        'enabled',
        'module_type',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];
}
