<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class checkpermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $requiredPermission): Response
    {
        $user = User::with('roles.permissions')->find(Auth::user()->id); //Obtener usuario

        foreach ($user->roles as $role) { //Recorrer roles
            foreach ($role->permissions as $permission) { //Recorrer los permissions de cada role
                //Verificar si algún role coíncide con el requerido.
                if ($permission->name === $requiredPermission) {
                    log::info('Se ha encontrado una coincidencia.');
                    return $next($request);
                }
            }
        }

        // Si no se encuentra el permiso, redireccionar o retornar una respuesta de error
        Log::warning('Permiso no encontrado para el usuario: '.$user->name .' '.$requiredPermission);
        return response()->json(['error' => 'No tienes los permisos necesarios'], 403); // Respuesta de acceso denegado
    }
}
