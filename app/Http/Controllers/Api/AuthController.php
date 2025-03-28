<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use App\Http\Controllers\Api\Response;
use Illuminate\Http\Response;

// use Response;
class AuthController extends Controller
{
    public function register(Request $request){
        return response()->json([
            "Message" => "metodo registro ok"
        ]);
    }

     public function login(Request $request){
       
       $credenciales = $request->validate([
            'nombre' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credenciales)){
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            $cookie = cookie('cookie', $token, 60*24);
            return response(["token" => $token], Response::HTTP_OK)->withCookie($cookie); 
        }else {
            return response(["message" => "credenciales incorrectas"], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function userPerfile(Request $request){
        return response()->json([
            "Message" => "metodo user ok",
            "user" => auth()->user()
        ], Response::HTTP_OK);
    }
}
