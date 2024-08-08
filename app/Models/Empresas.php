<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresas extends Model
{
    use HasFactory;
    protected $table = 'empresas';
    protected $primaryKey = 'id_empresa';
    protected $fillable = ['id_empresa','nombre', 'ruc','direccion','telefono','correo','id_tipo_contribuyente','base_datos','cadena_conexion','ruta_firma','clave_firma','ruta_logo','fecha_creacion','fecha_modificacion','es_activo'];
    public $timestamps = false;

    /*public function getDatabaseConnection(){  
        return $this->attributes['cadena_conexion'];  
    }*/
    
}



