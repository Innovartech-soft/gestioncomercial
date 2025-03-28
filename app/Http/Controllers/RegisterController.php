<?php

namespace App\Http\Controllers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Auth;
use App\Logs;
use App\User;
use Log;

class RegisterController extends Controller
{
    //
    public function register(Request $request): RedirectResponse
    {
        $user = new User();
        $user->nombre = $request->nombre;
        $user->password =  Hash::make($request->password);
        $user->estado = $request->estado;
        $user->administrador = $request->administrador;
        $user->save();
        // Autenticar al usuario
        // Auth::login($user);
        $this->registrarEnLog('success', $request->nombre);
        return redirect('auth/index')->with('success','El usuario '.$request->nombre.' ha sido creado correctamente');

    }


    private function registrarEnLog($estado, $nombreRegistro){
        switch ($estado) {
            case 'success':
                $mensaje = "Se registro el usuario ".$nombreRegistro." por el usuario ".Auth::user()->nombre;
                break;
            
            case 'error':
                $mensaje = "No se pudo registrar el usuario ".$nombreRegistro." por el usuario ".Auth::user()->nombre;
                break;
            
            case 'updated':
                $mensaje = "Se actualizo el usuario ".$nombreRegistro." por el usuario ".Auth::user()->nombre;
                break;

            case 'deleted':
                $mensaje = "Se elimino el usuario ".$nombreRegistro." por el usuario ".Auth::user()->nombre;
                break;
        }

        $log = Logs::create([
            'mensaje' => $mensaje,
            'id_usuario' => Auth::user()->id,
        ]);
    }
}
