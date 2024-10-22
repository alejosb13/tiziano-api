<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        "nombre",
        "apellido",
        "telefono",
        "email",
        "clasificacion",
        "descripcion_clasificacion",
        'estado',
    ];

    // many to many inversa
    // public function usuarios()
    // {
    //     return $this->belongsToMany(User::class,"cliente_usuario");
    // }
}
