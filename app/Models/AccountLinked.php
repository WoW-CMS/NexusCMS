<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AccountLinked
 *
 * @property int $id
 * @property int $user_id
 * @property int $realm_id
 * @property int $target_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Realm $realm
 *
 * @method static \Illuminate\Database\Eloquent\Builder|AccountLinked newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountLinked newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountLinked query()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountLinked whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountLinked whereRealmId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountLinked whereTargetId($value)
 */
class AccountLinked extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'account_linked';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'realm_id',
        'target_id'
    ];

    /**
     * Get the user that owns the account link.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, \App\Models\AccountLinked>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the realm that owns the account link.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Realm, \App\Models\AccountLinked>
     */
    public function realm()
    {
        return $this->belongsTo(Realm::class);
    }
}