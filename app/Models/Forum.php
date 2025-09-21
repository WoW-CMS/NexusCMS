<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Forum extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'parent_id',
        'order',
        'is_category',
    ];

    /**
     * Get all threads for this forum
     */
    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    /**
     * Get the parent forum if this is a subforum
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Forum::class, 'parent_id');
    }

    /**
     * Get all subforums for this forum
     */
    public function subforums(): HasMany
    {
        return $this->hasMany(Forum::class, 'parent_id');
    }

    /**
     * Get the latest thread in this forum
     */
    public function latestThread(): BelongsTo
    {
        return $this->belongsTo(Thread::class, 'latest_thread_id');
    }
}