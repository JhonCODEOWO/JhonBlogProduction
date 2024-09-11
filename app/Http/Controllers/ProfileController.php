<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfile;
use App\Models\Profile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    function create(StoreProfile $request)
    {
        try {
            //Procesar la foto recibida y subirla al servidor.
            $file = $request->file('profile_photo'); //Recibir la foto de la petición.
            $namePath = $file->store('user_photos'); //Almacenar el archivo en la carpeta user_photos

            //Crear el perfil.
            $profile = Profile::create([
                "name" => $request->name,
                "last_name" => $request->last_name,
                "profile_photo" => $namePath,
                "biography" => $request->biography,
                "user_id" => $request->user_id
            ]);

            //Retorna el perfil creado hacia el front.
            return response()->json($profile);
            
        } catch (Exception $ex) {
            return response()->json([
                "status"=>'error',
                "message"=>$ex->getMessage()
            ]);
        }
    }
}
