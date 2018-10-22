<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'reservation_date', 'people_number', 'user_id', 'reserved_seats'
    ];

    protected $hidden = [];

    public function reserved_seats () {
        return $this->hasMany('App\ReservedSeat');
    }

    public function user () {
        return $this->belongsTo('App\User');
    }
}
