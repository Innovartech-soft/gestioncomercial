<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\RedirectResponse;
//import session
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Services\DiarioCajaService;
use App\User;
use App\Logs;
use Log;
class LoginController extends Controller
{
    protected $diarioCajaService;
    //
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __construct(DiarioCajaService $diarioCajaService){
        $this->diarioCajaService = $diarioCajaService;
        $this->middleware('administrador')->only('destroy','update');
    }

    public function login(Request $request): RedirectResponse
    {

        $credentials = $request->validate([
            'nombre' => ['required'],
            'password' => ['required'],
        ]);
            $user = User::where('nombre', $credentials['nombre'])->first();
            if ($user && is_null($user->deleted_at) && $user->estado == 1) {
                //User tiene que cumplir estas condiciones para poder logearse
                if (Auth::attempt($credentials)) {
                    $request->session()->regenerate();
                    $user = Auth::user();
                    $token = $user->createToken('token')->plainTextToken;
                    Log::debug($token);
                    $this->registrarEnLog('success', $request->nombre);

                    if(!$this->diarioCajaService->getEstadoCaja()){ //Si la caja esta cerrada, abro nueva
                        try {
                            $this->diarioCajaService->setAperturaCajaAutomatica();
                        } catch (Exception $e) {
                            Log::alert('Error al abrir caja. '.$e->getMessage());
                        }                        
                    }

                    $request->session()->put('token', $token);
                    return redirect('/');
                }else {
                    return back()->withErrors([
                        'nombre' => 'Las credenciales no corresponden.',
                        ])->onlyInput('nombre');
                    }
            }else{
                return back()->withErrors([
                    'nombre' => 'Su cuenta ha sido desactivada',
                ])->onlyInput('nombre');
            }
        } 

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('dashboard.index');
    }

    public function index()
    {
        $usuarios = User::all()->where('deleted_at', null);
        return view('pages.auth.index', compact('usuarios'));
    }

    public function destroy($id)
    {
        $usuario = User::find($id);
        $usuario->deleted_at = now();
        $usuario->save();
        $this->registrarEnLog('deleted', $usuario->nombre);
        return redirect()->route('auth.index')->with('success', 'El usuario ' . $usuario->nombre . ' ha sido eliminado correctamente');
    }

    public function edit($id)
    {
        $usuario = User::find($id);
        return view('pages.auth.register', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        $usuario = User::find($id);
        $usuario->nombre = $request->nombre;
        if($request->password != null){
            $usuario->password = Hash::make($request->password);
        }else {
            $usuario->password = $usuario->password;
        }
        $usuario->estado = $request->estado;
        $usuario->administrador = $request->administrador;
        $usuario->save();
         // Autenticar al usuario solo si no hay una sesión activa
        $this->registrarEnLog('updated', $request->nombre);
        return redirect()->route('auth.index')->with('updated', 'El usuario ' . $usuario->nombre . ' ha sido actualizado correctamente');
    }


    private function registrarEnLog($estado, $nombreLogin){
        switch ($estado) {
            case 'success':
                $mensaje = "Se logeo el usuario ".$nombreLogin;
                break;
            
            case 'error':
                $mensaje = "No se pudo loguear el usuario ".$nombreLogin;
                break;
            
            case 'updated':
                $mensaje = "Se actualizo el usuario ".$nombreLogin." por el usuario ".Auth::user()->nombre;
                break;

            case 'deleted':
                $mensaje = "Se elimino el usuario ".$nombreLogin." por el usuario ".Auth::user()->nombre;
                break;
        }

        $log = Logs::create([
            'mensaje' => $mensaje,
            'id_usuario' => Auth::user()->id,
        ]);
    }
}
