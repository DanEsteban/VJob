<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Impuesto extends Model
{
    use HasFactory;

    protected $table = 'p_impuestos';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'codigo_impuesto',
        'codigo_tarifa',
        'porcentaje',
        'comentario',
        'desde',
        'hasta',
        'activo',
    ];

    protected $casts = [
        'desde' => 'date',
        'hasta' => 'date',
        'activo' => 'boolean',
    ];
        
    public $timestamps = false;
}

