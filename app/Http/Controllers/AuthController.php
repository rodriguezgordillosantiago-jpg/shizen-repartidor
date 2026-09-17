<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:254'],
            'password' => ['required', 'string', 'min:6', 'max:255'],
        ]);

        $user = DB::table('usuario')
            ->where('email', strtolower(trim($credentials['email'])))
            ->first();
        $courier = $user ? DB::table('repartidor')->where('id_usuario', $user->id_usuario)->first() : null;

        if (!$user || !$courier || !Hash::check($credentials['password'], $user->password_hash)) {
            return back()->withInput($request->only('email'))->withErrors([
                'email' => 'Correo o contraseña incorrectos.',
            ]);
        }

        $request->session()->regenerate();
        $request->session()->put('repartidor', [
            'id_usuario' => (int) $user->id_usuario,
            'id_repartidor' => (int) $courier->id_repartidor,
            'nombre' => $courier->nombre,
            'apellido' => $courier->apellido,
            'email' => $user->email,
            'vehiculo' => $courier->vehiculo,
        ]);

        return redirect()->route('inicio');
    }
}
