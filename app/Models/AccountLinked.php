<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountLinked extends Model
{
    protected $table = 'account_linked';

    protected $fillable = [
        'user_id',
        'realm_id',
        'target_id'
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function realm()
    {
        return $this->belongsTo(Realm::class);
    }
}