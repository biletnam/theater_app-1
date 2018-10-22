<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Reservation;
use App\ReservedSeat;
use App\Http\Resources\Reservation as ReservationResource;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservations = Reservation::orderBy('reservation_date', 'desc')->paginate(6);
        return ReservationResource::collection($reservations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reservation = $request->isMethod('put') ? 
            Reservation::findOrFail($request->id) : 
            new Reservation();
        $reservation->id = $request->input('id');
        $reservation->user_id = $request->input('user_id');
        if ($request->isMethod('post')) {
            $reservation->reservation_date = date("Y-m-d H:i:s");
        }
        $reservation->user_id = $request->input('user_id');
        $reservation->people_number = $request->input('people_number');
        $reserved_seats = $request->input('reserved_seats');

        if ($reservation->save()) {
            $reserved_seat = null;
            foreach($reserved_seats as $seat) {
                $reserved_seat = new ReservedSeat();
                $reserved_seat->row = $seat['row'];
                $reserved_seat->column = $seat['column'];
                $reservation->reserved_seats()->save($reserved_seat);
            }
        }

        return new ReservationResource($reservation);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reservation = Reservation::findOrFail($id);
        return new ReservationResource($reservation);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->delete()) {
            return new Reservation($reservation);
        }
    }
}
