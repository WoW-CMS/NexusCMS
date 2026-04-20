<?php

namespace Modules\Forum\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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

    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    public function posts(): HasManyThrough
    {
        return $this->hasManyThrough(Post::class, Thread::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Forum::class, 'parent_id');
    }

    public function subforums(): HasMany
    {
        return $this->hasMany(Forum::class, 'parent_id');
    }

    public function latestThread(): BelongsTo
    {
        return $this->belongsTo(Thread::class, 'latest_thread_id');
    }
}
