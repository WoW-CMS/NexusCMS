<?php

namespace Modules\Admin\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
    ];

    protected static function booted(): void
    {
        static::saved(function (): void {
            Cache::forget('site_settings');
        });

        static::deleted(function (): void {
            Cache::forget('site_settings');
        });
    }
}