<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'reservation_date', 'people_number', 'user_id',
    ];

    protected $hidden = [];
}
