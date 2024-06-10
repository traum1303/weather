<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temperature extends Model
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
    protected $table = 'temperatures';

    protected $fillable = [
        'city_id',
        'temperature',
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

}
