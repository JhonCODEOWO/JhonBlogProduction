<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Role $role)
    {
        $id = $role->id;
        $availablePermissions = Permission::whereDoesntHave('roles', function($query) use ($id){
            $query->where('role_id', $id);
        })->get();

        return response()->json($availablePermissions);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     *  Realiza la relación de un permiso con un rol
     */
    public function store(Permission $permission, Request $request)
    {
        try {
            //Ejecutar relación
            $permission->roles()->attach($request->role);
            return response()->json([
                "status"=>'ok',
                "message"=>'Se ha relacionado el permiso '.$permission->name.' correctamente'
            ]);
        } catch (Exception $ex) {
            return response()->json([
                "status"=>'error',
                "message"=>$ex->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     *  Quitar el permiso relacionado a un role
     */
    public function destroy(Permission $permission, Role $role)
    {
        try {
            $permission->roles()->detach($role->id);
            return response()->json([
                "status"=>'ok',
                "message"=>"Se ha desasignado ".$permission->name." correctamente"
            ]);
        } catch (Exception $ex) {
            return response()->json([
                "status"=>'error',
                "message"=>$ex->getMessage()
            ]);
        }
    }
}
