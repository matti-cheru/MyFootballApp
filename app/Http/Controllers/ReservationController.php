<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use \App\Models\DataLayer;
use \App\Models\Field;
use \App\Models\Reservation;
use \App\Models\User;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dl = new DataLayer();
        $userId = $dl->getUserID($_SESSION['email']);
        $role = $dl->getUserRole($_SESSION['email']);

        if ($role == "registered_player")
        {
            //$reservations = $dl->listReservations();
            $reservations = $dl->listPendingReservations($userId);
        }
        if ($role == "admin")
        {
            $reservations = $dl->listReservations();
        }
        return view('reservation.reservations')->with('reservations',$reservations);
    }

    public function create()
    {
        $dl = new DataLayer();
        $fields = $dl->listFields();

        return view('reservation.addReservation')->with('fields',$fields);
        //return view('reservation.addReservation')->with('fields',$fields);

    }

    public function store(Request $request)
    {
        $dl = new DataLayer();
        $id_campo = $request->input('id_campo');
        $data_prenotazione = $request->input('data_prenotazione');
        $ora_inizio = $request->input('ora_inizio');
        $ora_fine = Carbon::createFromFormat('H:i', $ora_inizio)->addHour()->format('H:i');
        $stato = 'attesa';
        $user_id = $_SESSION["loggedID"];

        $dl->addReservation($user_id, $id_campo, $data_prenotazione, $ora_inizio, $ora_fine, $stato);

        return Redirect::to(route('home'))->with('success', 'La tua prenotazione è stata inserita con successo!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dl = new DataLayer();
        $reservation = $dl->findReservationById($id);

        return view('reservation.details')->with('reservation', $reservation);
    }

    public function edit(string $id)
    {
        $dl = new DataLayer();
        $reservation = $dl->findReservationById($id);
        $fields = $dl->listFields();

        if($reservation !== null) {
            return view('reservation.editReservation')->with('reservation', $reservation)->with('fields', $fields);
        } else {
            return view('errors.404')->with('message','Wrong reservation ID has been used');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dl = new DataLayer();
        $id_campo = $request->input('id_campo');
        $data_prenotazione = $request->input('data_prenotazione');
        $ora_inizio = $request->input('ora_inizio');
        $ora_fine = Carbon::createFromFormat('H:i', $ora_inizio)->addHour()->format('H:i');
           
        $dl->editReservation($id, $id_campo, $data_prenotazione, $ora_inizio, $ora_fine);

        return Redirect::to(route('reservation.index'));
    }

    public function destroy(string $id)
    {
        $dl = new DataLayer();
        $dl->deleteReservation($id);

        return Redirect::to(route('home'))->with('error', 'Prenotazione eliminata con successo');
    }

    public function confirmDestroy(string $id)
    {
        $dl = new DataLayer();
        $reservation = $dl->findReservationById($id);

        if($reservation !== null) {
            return view('reservation.deleteReservation')->with('reservation', $reservation);
        } else {
            return view('errors.404')->with('message','Wrong reservation ID has been used');
        }
    }

    public function accept($id)
    {
        $dl = new DataLayer();
        $reservation = $dl->findReservationById($id);

        if($reservation !== null) {
            $reservation->stato = 'accettata';
            $dl->saveReservation($reservation); 

            return Redirect::to(route('home'))->with('success', 'La prenotazione è stata Accettata');
        } else {
            return view('errors.404')->with('message','Wrong reservation ID has been used');
        }
    }

    public function reject($id)
    {
        $dl = new DataLayer();
        $reservation = $dl->findReservationById($id);

        if($reservation !== null) {
            $reservation->stato = 'rifiutata';
            $reservation->save();

            return view('reservation.rejectReservation')->with('reservation', $reservation);           
        } else {
            return view('errors.404')->with('message','Wrong field ID has been used');
        }
    }

    public function ajaxCheckForReservations(Request $request)
    {
        $dl = new DataLayer();
        $id_campo = $request->input('id_campo');
        $data_prenotazione = $request->input('data_prenotazione');
        $ora_inizio = $request->input('ora_inizio');

        if ($dl->findReservationByParameters($id_campo, $data_prenotazione, $ora_inizio))
        {
            $response = array('found'=>true);
        } else {
            $response = array('found'=>false);
        }

        return response()->json($response);
    }
}
