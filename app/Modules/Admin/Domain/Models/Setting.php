<?php

namespace App\Modules\Admin\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'name',
        'value',
    ];
}