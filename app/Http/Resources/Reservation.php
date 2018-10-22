<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Reservation extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $reservation_date = new \DateTime($this->reservation_date);
        return [
            'id' => $this->id,
            'formatted_reservation_date' => date_format($reservation_date, 'd/m/Y'),
            'reservation_date' => $this->reservation_date,
            'people_number' => $this->people_number,
            'user_id' => $this->user_id,
            'user_complete_name' => $this->user->lastname . ', ' . $this->user->name,
            'reserved_seats' => $this->reserved_seats,
        ];
    }
}
