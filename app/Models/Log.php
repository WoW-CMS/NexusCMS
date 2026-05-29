<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Log model (represents a log file entry).
 *
 * @property string $filename
 * @property string $level
 * @property string $message
 * @property string $timestamp
 */
class Log extends Model
{
    public $timestamps = false;
}