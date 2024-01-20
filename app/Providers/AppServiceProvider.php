<?php

namespace App\Providers;

use App\Models\Empresas;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('variableGlobal', function () {
            return 'northwind';
        });

        if (Auth::check()) {
            // Obtiene el modelo del usuario autenticado
            $usuarioAutenticado = Auth::user();
    
            // Accede al valor del campo id_empresa
            $idEmpresa = $usuarioAutenticado->id_empresa;
    
            if ($idEmpresa) {
                $dataBase = Empresas::Select('base_datos')->Where('id_empresa', $idEmpresa)->Where('es_activo', 1)->first();                   
                $configuracion = config('database.connections.' + $dataBase);
                //return $datosEmpresa->cadena_conexion;

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
