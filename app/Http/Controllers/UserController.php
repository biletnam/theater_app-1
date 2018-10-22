<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\Reservation;
use App\Http\Resources\User as UserResource;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(6);
        return UserResource::collection($users);
    }

    public function all() {
        $users = User::orderBy('id', 'desc')->get();
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->isMethod('put') ? User::findOrFail($request->id) : new User;
        $user->id = $request->input('id');
        $user->name = $request->input('name');
        $user->lastname = $request->input('lastname');

        if ($user->save()) {
            return new UserResource($user);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user_reservations = Reservation::where('user_id', '=', $user->id)->get();

        if (count($user_reservations) > 0) {
            return response()->json([
                'error' => true,
                'message' => 'El usuario tiene una más reservaciones.',
            ]);
        }

        if ($user->delete()) {
            return new UserResource($user);
        }
    }
}
