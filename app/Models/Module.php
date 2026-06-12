<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Module model (represents a NexusCMS module).
 *
 * @property string $name
 * @property string $folder
 * @property bool $enabled
 * @property string $module_type
 */
class Module extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'folder',
        'enabled',
        'module_type',
    ];

    protected $casts = [
        'enabled' => 'bool',
    ];
}