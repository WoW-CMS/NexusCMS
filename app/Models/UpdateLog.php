<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UpdateLog extends Model
{
    protected $fillable = [
        'from_version',
        'to_version',
        'method',
        'status',
        'notes',
        'executed_by',
    ];

    public function executor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'executed_by');
    }

    public function isSuccess(): bool
    {
        return $this->status === 'success';
    }
}
