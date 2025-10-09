<?php

namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    use Cacheable;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'author_id',
        'category_id',
        'is_published',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'category_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function relatedContents($limit = 2)
    {
        return static::query()
            ->where('id', '!=', $this->id)
            ->where('is_published', 1)
            ->when($this->category_id, function ($query) {
                $query->where('category_id', $this->category_id);
            })
            ->orderBy('published_at', 'desc')
            ->take($limit)
            ->get();
    }
}
