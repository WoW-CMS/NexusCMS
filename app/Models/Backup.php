<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Backup file model (represents a backup ZIP file on disk).
 *
 * @property string $filename
 * @property int $size
 * @property int $date
 */
class Backup extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'filename',
        'size',
        'date',
    ];
}