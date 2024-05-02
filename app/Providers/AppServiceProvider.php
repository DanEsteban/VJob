<?php

namespace App\Providers;

use App\Models\Empresas;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind('conexionBase', function () {
            if (Auth::check()) {
                //Obtiene el modelo del usuario autenticado
                $usuarioAutenticado = Auth::user();
            
                //Accede al valor del campo id_empresa
                $idEmpresa = $usuarioAutenticado->id_empresa;
            
                if ($idEmpresa) {
                        $datos = Empresas::Select('base_datos')->Where('id_empresa', $idEmpresa)->Where('es_activo', 1)->first();                   
                        $configuracion = config('database.connections.' . $datos->base_datos);
                        //return $datosEmpresa->cadena_conexion;
                        if ($configuracion) {
                            $variablesEnv = [
                                'DB_CONNECTION' => $configuracion['driver'],
                                'DB_HOST' => $configuracion['host'],
                                'DB_PORT' => $configuracion['port'],
                                'DB_DATABASE' => $configuracion['database'],
                                'DB_USERNAME' => $configuracion['username'],
                                'DB_PASSWORD' => $configuracion['password'],
                            ];
                        }
                    
                    }
                }
            
        });

        $this->app->bind('dataBase', function () {
            if (Auth::check()) {
                //Obtiene el modelo del usuario autenticado
                $usuarioAutenticado = Auth::user();
            
                //Accede al valor del campo id_empresa
                $idEmpresa = $usuarioAutenticado->id_empresa;
                //return $idEmpresa;
                if ($idEmpresa) {
                        $datos = Empresas::Select('base_datos')->Where('id_empresa', $idEmpresa)->Where('es_activo', 1)->first();                   
                        return  $datos->base_datos;
                    }
                }
        });

        app()->instance('codigo', '9999999999999');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       //
    }
}
