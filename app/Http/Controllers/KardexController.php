<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class KardexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nombreBD = App::make('dataBase');
        $dsn = 'mysql:host=localhost;dbname='. $nombreBD;
        $usuario = "root";
        $contrasena = "";
        try {
            
            $conexion = new \PDO($dsn, $usuario, $contrasena);
            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $consulta = "SELECT * FROM products WHERE is_active = 1"; 
            $result= $conexion->query($consulta);
            
            foreach ($result as $fila) {
                $items[]=[
                    "id" => $fila['id'],
                    "id_type" => $fila['id_type'],
                    "id_group" => $fila['id_group'],
                    "item_name" => $fila['item_name'],
                    "bar_code" => $fila['bar_code'],
                    "si_iva" => $fila['si_iva'],
                    "iva" => $fila['iva'],
                    "id_unit_measure" => $fila['id_unit_measure'],
                    "notes" => $fila['notes'],
                    "is_active" => $fila['is_active'],
                ];
            }

            return view('kardex.index', compact('items'));

        }catch (\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }  
    }
    
}
