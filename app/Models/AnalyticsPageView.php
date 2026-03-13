<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalyticsPageView extends Model
{
    use HasFactory;

    protected $table = 'analytics_page_views';

    protected $fillable = [
        'session_id',
        'page_url',
        'page_title',
        'referrer',
        'time_on_page',
        'bounced',
    ];

    protected $casts = [
        'bounced' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the session this page view belongs to
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(AnalyticsSession::class, 'session_id');
    }
}
