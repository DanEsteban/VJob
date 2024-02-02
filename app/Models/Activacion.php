<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activacion extends Model
{
    use HasFactory;
    protected $table = 'activacion';
    protected $primaryKey = 'id_activacion';
    protected $fillable = ['id_activacion', 'ruc', 'codigo_activacion', 'correo', 'es_activo'];

    public $timestamps = false;
}
