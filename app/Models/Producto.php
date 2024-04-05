<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
 
    protected $fillable = [
        "nombre",
        "linea",
        "precio1",
        "precio2",
        "precio3",
        "precio4",
        "importacion",
        "estado",
    ];
}
