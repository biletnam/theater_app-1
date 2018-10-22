<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReservedSeat extends Model
{
    protected $fillable = [
        'row', 'column', 'reservation_id',
    ];

    public function reservation () {
        return $this->belongsTo('App\Reservation');
    }
}
