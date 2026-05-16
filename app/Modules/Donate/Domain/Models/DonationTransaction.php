<?php

namespace Modules\Donate\Domain\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

