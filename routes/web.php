<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//Al lanzar a producción el proyecto de Angular, añadir el middleware('auth') a las rutas.
//Al lanzar a producción se debe colocar el middleware('permission:<nombre del permiso>') para prohibier peticiones a rutas específicas en base a los permisos de un usuario.

Route::get('/', function () {
    return view('index');
});

Route::get('/{any}', function () {
    return view('index');
})->where('any', '^(?!api|profile_photos).*$');

Route::get('api/getCSRF', function(){
    return response()->json([
        "token"=> csrf_token()
    ]);
});

Route::controller(UserController::class)->group(function(){
    Route::get('api/users', 'index');
    Route::get('api/user/{user}', 'show');
    Route::get('api/{user}/roles', 'rolesAvailablesForUser');
    Route::post('api/user/create', 'store');
    Route::delete('api/user/delete/{user}', 'destroy');
    Route::post('api/user/{user}/addRole', 'addRoleToUser');
    Route::delete('api/user/deleteRole/{user}/{role}', 'deleteRoleFromUser');
    Route::post('api/user/login', 'login'); //Ruta para manejar el intento de logeo de un usuario
    Route::delete('api/user/logout', 'logout'); //Ruta para deslogear a un usuario
});

Route::controller(ProfileController::class)->group(function(){
    Route::post('api/profile/create', 'create');
    Route::put('api/profile/{profile}/modify', 'update');
});

//Rutas para peticiones referentes a la tabla Role
Route::controller(RoleController::class)->group(function(){
    Route::get('api/roles', 'index');
    Route::get('api/role/{role}', 'show');
    Route::post('api/role/create', 'store');
    Route::put('api/edit/role/{role}', 'update');
    Route::delete('api/delete/role/{role}', 'destroy');
});

//Rutas agrupadas para el controlador de las categorías.
Route::controller(CategoryController::class)->group(function(){
    Route::get('api/category/index', 'index');
    Route::post('api/category/create', 'store');
    Route::put('api/category/{category}/update', 'update');
    Route::delete('api/category/delete/{category}', 'destroy');
});

//Rutas para peticiones referentes a la tabla permission
Route::controller(PermissionController::class)->group(function(){
    Route::get('api/permissions/{role}', 'index'); //Petición GET para obtener los permisos disponibles para un rol
    Route::post('api/assignpermission/{permission}', 'store');//Petición POST para relacionar un permiso hacia un role
    Route::delete('api/quitpermission/{permission}/{role}', 'destroy');//Petición DELETE para quitar la relación de un permission hacia un role.
});