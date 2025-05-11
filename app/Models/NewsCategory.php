<?php

namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsCategory extends Model
{
    use HasFactory;
    use Cacheable;
    use SoftDeletes;

    protected $table = 'news_category';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    public function news()
    {
        return $this->hasMany(News::class, 'category_id');
    }
}
