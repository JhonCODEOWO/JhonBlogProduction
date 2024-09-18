<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return response()->json($roles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Role::create([
                "name"=>$request->name,
                "description"=>$request->description
            ]);

            return response()->json([
                "status" => 'ok',
                "message"=> "El rol se ha actualizado correctamente"
            ]);
        } catch (Exception $ex) {
            return response()->json([
                "status" => 'error',
                "message"=> $ex->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $role->load('permissions');
        return response()->json($role);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        try {
            $role->update([
                "name"=>$request->name,
                "description"=>$request->description
            ]);

            $role->save();

            return response()->json([
                "status" => 'ok',
                "message"=> "El rol se ha actualizado correctamente"
            ]);
        } catch (Exception $ex) {
            return response()->json([
                "status" => 'error',
                "message"=> $ex->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try {
            $role->delete();
            return response()->json([
                "status" => 'ok',
                "message"=> "El rol se ha eliminado correctamente"
            ]);
        } catch (Exception $ex) {
            return response()->json([
                "status" => 'error',
                "message"=> $ex->getMessage()
            ]);
        }
    }
}
