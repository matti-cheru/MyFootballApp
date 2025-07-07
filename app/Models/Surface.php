<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surface extends Model
{
    /** @use HasFactory<\Database\Factories\SurfaceFactory> */
    use HasFactory;
    protected $table = 'surfaces';
    protected $fillable = ['nome'];

    public function fields() {
        return $this->hasMany(Field::class, 'id_superficie', 'id');
    }
}
