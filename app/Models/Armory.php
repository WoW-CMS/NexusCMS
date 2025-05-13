<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Armory extends Model
{
    /**
     * Different database connection for characters
     * @var string
     */
    protected $connection = 'mysql_characters';

    /**
     * Defines the table name
     * @var string
     */
    protected $table = 'characters';

    /**
     * Defines the primary key of the table
     * @var string
     */
    protected $primaryKey = 'guid';

    /**
     * Specifies the type of the primary key
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Scope to search by name
     */
    public function scopeSearch($query, ?string $term)
    {
        if ($term) {
            $query->where('name', 'LIKE', "%{$term}%");
        }
        return $query;
    }
}
