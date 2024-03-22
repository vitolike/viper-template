<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provider extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'providers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'rtp',
        'status',
        'views',
    ];

    /**
     * Fivers Game
     * @return HasMany
     */
    public function games(): HasMany
    {
        return $this->hasMany(Game::class, 'provider_id', 'id')
            ->where('status', 1)
            ->orderBy('views', 'asc');
    }

}
