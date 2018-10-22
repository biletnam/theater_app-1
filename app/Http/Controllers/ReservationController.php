<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Reservation;
use App\ReservedSeat;
use App\Http\Resources\Reservation as ReservationResource;
use Carbon\Carbon;

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
        $reservation->reservation_date = Carbon::parse($request->input('reservation_date'))->startOfDay();
        $reservation->user_id = $request->input('user_id');
        $reservation->people_number = $request->input('people_number');
        $reserved_seats = $request->input('reserved_seats');
        
        $conflicted_seats = $this->conflicted_seats($reserved_seats, $reservation);
        if (!empty($conflicted_seats)) {
            return response()->json([
                'conflicts' => true,
                'message' => $this->conflicted_seats_message($conflicted_seats)
            ]);
        }

        if ($reservation->save()) {
            // if exists remove all reservedSeat 
            if ($request->isMethod('put')) {
                ReservedSeat::where('reservation_id', $reservation->id)->delete();
            }
            // reserve again
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

    private function conflicted_seats_message($conflicted_seats) {
        $message = 'Las siguientes butacas ya se encuentran ocupadas para esta fecha: ';
        foreach ($conflicted_seats as $key => $conflicted_seat) {
            $message = $message . "F " . $conflicted_seat['row'] . " - C " . $conflicted_seat['column'];
            if ($key < count($conflicted_seats) - 1) {
                $message = $message . ', ';
            }
        }
        return $message;
    }

    private function conflicted_seats ($reserved_seats, $reservation) {
        $date_reserved_seats = DB::table('reserved_seats')
            ->join('reservations', 'reserved_seats.reservation_id', '=', 'reservations.id')
            ->whereDate('reservations.reservation_date', '=', $reservation->reservation_date)
            ->get();
        
        $conflicted_seats = array();
        foreach($date_reserved_seats as $date_reserved_seat) {
            foreach($reserved_seats as $reserved_seat) {
                if ($date_reserved_seat->row == $reserved_seat['row'] && 
                    $date_reserved_seat->column == $reserved_seat['column'] &&
                    $date_reserved_seat->reservation_id !== $reservation->id) {
                    $conflicted_seats[] = $reserved_seat;
                }
            }
        }

        return $conflicted_seats;
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
            return new ReservationResource($reservation);
        }
    }
}
