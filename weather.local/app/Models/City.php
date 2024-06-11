<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string $primaryKey
     */
    protected $primaryKey = 'id';

    /**
     * The table associated with the model.
     *
     * @var string $table
     */
    protected $table = 'cities';

    protected $fillable = [
        'latitude',
        'longitude',
        'city_name',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string> $casts
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function temperatures(): HasMany
    {
        return $this->hasMany(Temperature::class, 'city_id', 'id');
    }
}
