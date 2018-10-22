<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use App\Http\Requests;
use App\User;
use App\Reservation;
use App\ReservedSeat;
use App\Http\Resources\Reservation as ReservationResource;
use Carbon\Carbon;
use Storage;

class ReservationController extends Controller
{
    var $reservations_log;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservations = Reservation::orderBy('reservation_date', 'asc')->paginate(6);
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
        $user = User::find($reservation->user_id);

        // validate user
        if (!$user) {
            return response()->json([
                'error' => true,
                'message' => 'Debe seleccionar un usuario.'
            ]);
        }

        // validate seats
        if (!count($reserved_seats)) {
            return response()->json([
                'error' => true,
                'message' => 'Debe seleccionar por lo menos una butaca.'
            ]);
        }
        
        if ($request->isMethod('post')) {
            // validate reservation date for the same user
            $existing_reservations = DB::table('reservations')
            ->where('reservations.user_id', '=', $reservation->user_id)
            ->whereDate('reservations.reservation_date', '=', $reservation->reservation_date)
            ->get();

            if (count($existing_reservations)) {
                return response()->json([
                    'error' => true,
                    'message' => 'Se ha encontrado una reservación para este usuario y esta fecha.',
                    'reservation_id' => $existing_reservations[0]->id
                ]);
            }
        }
        // check for conflicts
        $conflicted_seats = $this->conflicted_seats($reserved_seats, $reservation);
        if (!empty($conflicted_seats)) {
            return response()->json([
                'error' => true,
                'message' => $this->conflicted_seats_message($conflicted_seats)
            ]);
        }
        
        // if no conflicts, we proceed to reserve
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
            // log reservation
            $log_action = $request->isMethod('post') ? 'creado' : 'modificado';
            $this->logger()->info('El usuario ' . 
                $user->name . ' ha ' . 
                $log_action . ' la reserva nº ' . 
                $reservation->id);
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
            return new ReservationResource($reservation);
        }
    }

    /**
     * Private members
     */
    private function logger() {
        if ($this->reservations_log == null) {
            $this->reservations_log = new Logger('reservations');
            $this->reservations_log->pushHandler(new StreamHandler(storage_path().'/logs/reservations.txt'));
        }
        return $this->reservations_log;
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
}
