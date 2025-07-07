<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class DataLayer extends Model
{
    use HasFactory;

    public function getAllSurfaces() {
        return Surface::all();
    }

    public function listFields() {
        $fields = Field::orderBy('id')->orderBy('nome_campo')->get();
        return $fields;
    }

    public function findFieldById($id)
    {
        return Field::find($id);
    }

    public function findFieldByName($nome_campo)
    {
        $fields = Field::where('nome_campo', $nome_campo)->get();

        if (count($fields) == 0)
        {
            return false;
        } else {
            return true;
        }
    }

    public function addField($nome_campo, $descrizione, $id_superficie, $orario_apertura, $orario_chiusura)
    {
        $field = new Field;
        $field->nome_campo = $nome_campo;
        $field->descrizione = $descrizione;
        $field->id_superficie = $id_superficie;
        $field->orario_apertura = $orario_apertura;
        $field->orario_chiusura = $orario_chiusura;

        $field->save();
    }

    public function editField($id, $nome_campo, $descrizione, $id_superficie, $orario_apertura, $orario_chiusura)
    {
        $field = Field::find($id);
        $field->nome_campo = $nome_campo;
        $field->descrizione = $descrizione;
        $field->id_superficie = $id_superficie;
        $field->orario_apertura = $orario_apertura;
        $field->orario_chiusura = $orario_chiusura;

        $field->save();
    }

    public function deleteField($id)
    {
        $field = Field::find($id);
        $field->delete();
    }

    public function listReservations()
    {
        $reservations = Reservation::where('stato', 'attesa')->orderBy('id_campo')->orderBy('data_prenotazione')->get();
        return $reservations;
    }

    public function listMyReservations($userID)
    {
        $reservations = Reservation::where('user_id',$userID)->orderBy('id_campo')->orderBy('data_prenotazione')->get();
        return $reservations;
    }

    public function listPendingReservations($userID)
    {
        $pendingReservations = Reservation::where('user_id', $userID)->where('stato','attesa')->orderBy('id_campo')->orderBy('data_prenotazione')->get();
        return $pendingReservations;
    }

    public function findReservationById($id)
    {
        return Reservation::find($id);
    }

    public function findReservationByParameters($id_campo, $data_prenotazione, $ora_inizio)
    {   
        $reservations = Reservation::where('id_campo', $id_campo)->where('data_prenotazione', $data_prenotazione)->where('ora_inizio', $ora_inizio)->get();

        if (count($reservations) == 0)
        {
            return false;
        } else {
            return true;
        }
    }

    public function addReservation($user_id, $id_campo, $data_prenotazione, $ora_inizio, $ora_fine, $stato) {
        $reservation = new Reservation;
        $reservation->user_id = $user_id;
        $reservation->id_campo = $id_campo;
        $reservation->data_prenotazione = $data_prenotazione;
        $reservation->ora_inizio = $ora_inizio;
        $reservation->ora_fine = $ora_fine;
        $reservation->stato = $stato;

        $reservation->save();
    }

        public function editReservation($id, $id_campo, $data_prenotazione, $ora_inizio, $ora_fine) {
        $reservation = Reservation::find($id);

        $reservation->id_campo = $id_campo;
        $reservation->data_prenotazione = $data_prenotazione;
        $reservation->ora_inizio = $ora_inizio;
        $reservation->ora_fine = $ora_fine;

        $reservation->save();
    }

    public function deleteReservation($id) {
        $reservation = Reservation::find($id);
        $reservation->delete();
    }

    public function saveReservation(Reservation $reservation) {
        $reservation->save();
    }

    public function listUsers() {
        $players = User::where('role', 'registered_player')->get();
        return $players;
    }

    public function findUserById($id) {
        return User::find($id);
    }

    public function deleteUser($id) {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return true;
        }
        return false;
    }

    public function deleteBook($id) {
        $book = Book::find($id);
        $categories = $book->categories;
        foreach($categories as $cat) {
            $book->categories()->detach($cat->id);
        }
        $book->delete();
    }

    public function validUser($email, $password)
    {
        $user = User::where('email', $email)->first();

        if($user && Hash::check($password, $user->password))
        {
            return true;
        } else {
            return false;
        }
    }

    public function addUser($name, $password, $email)
    {
        $user = new User();
        $user->name = $name;
        $user->password = Hash::make($password);
        $user->email = $email;
        $user->role = "registered_player";
        $user->email_verified_at = now();
        $user->save();
    }

    public function getUserID($email) {
        $users = User::where('email',$email)->get(['id']);
        return $users[0]->id;
    }

    public function getUserName($email) {
        $users = User::where('email',$email)->get(['name']);
        return $users[0]->name;
    }

    public function getUserRole($email) {
        $users = User::where('email',$email)->get(['role']);
        return $users[0]->role;
    }

    public function getUserMail($email) {
        $users = User::where('email',$email)->get(['email']);
        return $users[0]->email;
    }

    public function findUserByemail($email) {
        $users = User::where('email', $email)->get();
        
        if (count($users) == 0) {
            return false;
        } else {
            return true;
        }
    }
    
}
