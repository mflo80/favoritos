<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Favoritos extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $table = 'favoritos';

    protected $fillable = [
        'clienteIP',
        'url',
        'nombre',
        'imagenFondo',
        'colorFondoA',
        'colorFondoB',
        'colorTexto',
        'tipo',
        'fechaCreacion',
        'fechaActualizacion'
    ];
}
