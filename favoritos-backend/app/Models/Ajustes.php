<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ajustes extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'clienteIP';
    protected $table = 'ajustes';

    protected $fillable = [
        'clienteIP',
        'imagenFondo',
        'colorFondoA',
        'colorFondoB',
        'tipo',
        'boxSize',
        'boxColor',
        'fechaCreacion',
        'fechaActualizacion'
    ];
}
