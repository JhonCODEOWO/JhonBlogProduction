<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfile;
use App\Models\Profile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

    function update(Profile $profile, Request $request){
        try {
            //Almacenar foto, si no se recibe nada el valor será null
            $file = $request->file('profile_photo')??null;
            
            log::info($request);
            //Verificar si se ha recibido una imagen
            if (isset($file)) {
                log::info("Se ha recibido una imagen");
                //Eliminar la anterior foto del servidor
                $exists = Storage::exists($profile->profile_photo);
                //Si el archivo existe..
                if ($exists) {
                    Storage::delete($profile->profile_photo); //Elimina la foto
                }

                $namePath = $file->store('user_photos'); //Almacenamos la nueva foto

                //Asignar la nueva ruta al modelo
                $profile->profile_photo = $namePath;
            }

            //Actualizar el modelo exceptuando la posible imagen recibida en el body de la petición
            $profile->update($request->except('profile_photo'));

            return response()->json($profile); //Retornamos el peril ahora ya actualizado.
        } catch (Exception $ex) {
            log::error($ex->getMessage());
            return response()->json([
                "status"=>'error',
                "message"=>$ex->getMessage()
            ]);
        }
    }
}
