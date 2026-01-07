<?php

namespace Modules\Donate\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class DonationTransaction extends Model
{
    protected $table = 'donation_transactions';

    protected $fillable = [
        'user_id',
        'gateway',
        'transaction_id',
        'amount',
        'currency',
        'dp_awarded',
        'status',
        'meta',
    ];

    protected $casts = [
        'amount' => 'integer',
        'dp_awarded' => 'integer',
        'meta' => 'array',
    ];
}

