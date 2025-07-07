<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use \App\Models\DataLayer;
use \App\Models\User;
use \App\Models\Reservation;

class UserController extends Controller
{
    public function index()
    {
        $dl = new DataLayer();
        $players = $dl->listUsers();

        return view('admin.users')->with('players', $players);
    }

    public function destroy(string $id)
    {
        $dl = new DataLayer();
        $user = $dl->deleteUser($id);

        return Redirect::to(route('users.index'));
    }

    public function confirmDestroy(string $id)
    {
        $dl = new DataLayer();
        $player = $dl->findUserById($id);

        if ($player !== null) {
            return view('admin.deleteUser')->with('player', $player);
        } else {
            return view('errors.404')->with('message','Wrong user ID has been used!');
        }
    }

    public function showUserReservations(string $id)
    {
        $dl = new DataLayer();
        $user = $dl->findUserById($id);
        
        if(!$user)
        {
            return view('errors.404')->with('message','Utente non trovato');
        }

        $reservations = $dl->listMyReservations($id);
        $userName = $user->name;

        return view('admin.userReservationsHistory')
                    ->with('reservations', $reservations)
                    ->with('userName', $userName);
    }

    public function showMyReservations(string $id)
    {
        $dl = new DataLayer();
        $user = $dl->findUserById($id);

        if(!$user)
        {
            return view('errors.404')->with('message','Utente non trovato');
        }

        $reservations = $dl->listMyReservations($id);
        $userName = $user->name;

        return view('reservation.showMyReservations')
                    ->with('reservations', $reservations)
                    ->with('userName', $userName);
    }
}
