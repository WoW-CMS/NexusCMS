<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnalyticsSession extends Model
{
    use HasFactory;

    protected $table = 'analytics_sessions';

    protected $fillable = [
        'visitor_id',
        'ip_address',
        'user_agent',
        'country',
        'city',
        'duration_seconds',
        'page_views',
        'is_bot',
    ];

    protected $casts = [
        'is_bot' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the page views for this session
     */
    public function pageViews(): HasMany
    {
        return $this->hasMany(AnalyticsPageView::class, 'session_id');
    }
}
