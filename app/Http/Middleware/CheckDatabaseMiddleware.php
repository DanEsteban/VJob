<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Models\Empresas;


class CheckDatabaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
    
        if (Auth::check()) {
            try {
                $user = Auth::user();
                $empresaId = $user->id_empresa;
                $empresa = Empresas::find($empresaId);

                if ($empresa) {
                    $empresaConexion = $empresa->cadena_conexion;
                    return $empresaConexion;
                    Config::set('database.default', $empresaConexion);
                }
            } catch (\Exception $e) {
                // Manejar la excepción, por ejemplo, registrar un mensaje de error
                Log::error('Error al cambiar la conexión de la base de datos: ' . $e->getMessage());
            }
        }
        else{
            Log::error('Error: ' . $request);
        }

        return $next($request);
    }
}
