<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $table = 'reservations';
    protected $fillable = [
        'user_id',
        'id_campo',
        'data_prenotazione',
        'ora_inizio',
        'ora_fine'
    ];

    public function field() {
        return $this->belongsTo(Field::class, 'id_campo', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
