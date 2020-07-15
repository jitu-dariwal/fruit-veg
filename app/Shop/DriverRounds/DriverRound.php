<?php

namespace App\Shop\DriverRounds;

use Illuminate\Database\Eloquent\Model;

class DriverRound extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'round_date',
        'round_name',
        'driver_name',
		];
}
