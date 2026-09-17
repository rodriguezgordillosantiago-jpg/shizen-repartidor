<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:254'],
            'password' => ['required', 'string', 'min:6', 'max:255'],
        ]);

        $request->session()->regenerate();
        $request->session()->put('repartidor', [
            'nombre' => $request->session()->get('repartidor.nombre', 'Santiago'),
            'apellido' => $request->session()->get('repartidor.apellido', 'Vargas'),
            'email' => strtolower($credentials['email']),
            'vehiculo' => $request->session()->get('repartidor.vehiculo', 'Moto'),
        ]);

        return redirect()->route('inicio');
    }
}
