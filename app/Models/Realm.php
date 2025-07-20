<?php

namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realm extends Model
{
    use HasFactory;
    use Cacheable;

    protected $fillable = [
        'name',
        'hostname',
        'expansion',
        'emulator',
        'port',
        'auth_database',
        'characters_database',
        'console_hostname',
        'console_port',
        'console_username',
        'console_password',
        'console_urn'
    ];

    protected $casts = [
        'published_at' => 'datetime'
    ];
}
