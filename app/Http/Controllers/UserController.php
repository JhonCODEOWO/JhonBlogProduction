<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUser;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->get();
        return response()->json($users);
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
    public function store(StoreUser $request)
    {
        try {
            User::create([
                "name"=>$request->name,
                "email"=>$request->email,
                "password"=>$request->password
            ]);

            return response()->json([
                "status"=>'ok',
                "message"=>'Se ha guardado el usuario correctamente'
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
    public function show(User $user)
    {
        return response()->json(User::find($user->id)->load('profile'));
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
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();

            return response()->json([
                "status"=>'ok',
                "message"=>'Se ha eliminado el usuario'
            ]);
        } catch (Exception $ex) {
            return response()->json([
                "status"=>'error',
                "message"=>$ex->getMessage()
            ]);
        }
    }

    //Obtiene los roles disponibles para un usuario
    function rolesAvailablesForUser(User $user){
        try {
            $id = $user->id;
            $roles = Role::whereDoesntHave('users', function($query) use ($id){
                $query->where('user_id', $id);
            })->get();

            return response()->json($roles);
        } catch (Exception $ex) {
            return response()->json([
                "status"=>'error',
                "message"=>$ex->getMessage()
            ]);
        }
    }

    //Asigna un rol a un usuario recibido por una petición post
    function addRoleToUser(User $user, Request $request){
        try {
            $user->roles()->attach($request->role);
            return response()->json([
                "status"=>'ok',
                "message"=>'Se ha asignado el rol exitosamente' 
            ]);
        } catch (Exception $ex) {
            return response()->json([
                "status"=>'error',
                "message"=>$ex->getMessage()
            ]);
        }
    }

    function deleteRoleFromUser(User $user, Role $role){
        try {
            $user->roles()->detach($role->id);
            return response()->json([
                "status"=>'ok',
                "message"=>'Se ha quitado el rol ' . $role->name . ' exitosamente'
            ]);
        } catch (Exception $ex) {
            return response()->json([
                "status"=>'error',
                "message"=>$ex->getMessage()
            ]);
        }
    }

    function login(Request $request){
        //Validar datos entrantes y asignarlos a otro arreglo con esos valores
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        //Intenta logear al usuario
        if (Auth::attempt($credentials)) {
            //Regenera la sesión
            $request->session()->regenerate();
            
            //Obtener el usuario que se ha logeado;
            $user = User::find(Auth::user()->id);

            //Cargar datos extras al usuario logeado
            $user->load('roles.permissions', 'profile');

            //Retornar el usuario logeado al front.
            return response()->json($user);
        }

        //Retorna una respuesta para el front en caso de error
        return response()->json([
            "status"=>"error",
            "message"=>'La contraseña o email son incorrectos, intenta de nuevo'
        ]);
    }

    function logout(Request $request){
        Auth::logout();
 
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();

        return response()->json([
            "status"=>'ok',
            "message"=>'Se ha cerrado la sesión'
        ]);
    }
}
