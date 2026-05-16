<?php

namespace Modules\Store\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class StoreProduct extends Model
{
    protected $table = 'store_products';

    protected $fillable = [
        'key',
        'name',
        'description',
        'cost',
        'type',
        'requires_character',
        'active',
        'sort_order',
    ];

    protected $casts = [
        'requires_character' => 'boolean',
        'active'             => 'boolean',
        'cost'               => 'integer',
        'sort_order'         => 'integer',
    ];
}
