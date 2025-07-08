<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{

    use HasFactory;
    protected $table = 'fields';
    protected $fillable = [
        'nome_campo', 
        'descrizione',
        'id_superficie',
        'orario_apertura',
        'orario_chiusura'
    ];

    public function surface() {
        return $this->belongsTo(Surface::class, 'id_superficie', 'id');
    }

    public function reservations() {
        return $this->hasMany(Reservation::class, 'id_campo', 'id');
    }

        /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($field) {
            // Elimina tutte le prenotazioni associate a questo campo
            $field->reservations()->delete();
        });
    }
}
