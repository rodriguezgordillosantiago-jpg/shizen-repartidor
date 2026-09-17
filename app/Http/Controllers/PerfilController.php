<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PerfilController extends Controller
{
    public function index()
    {
        return view('pages.perfil', ['repartidor' => session('repartidor', [
            'nombre' => 'Santiago', 'apellido' => 'Vargas',
            'email' => 'repartidor@shizen.test', 'vehiculo' => 'Moto',
        ])]);
    }

    public function configuracion()
    {
        return view('pages.configuracion', ['preferencias' => session('preferencias', [
            'notificaciones_push' => true, 'sonido_pedidos' => true,
        ])]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'min:2', 'max:100'],
            'apellido' => ['required', 'string', 'min:2', 'max:100'],
            'vehiculo' => ['required', 'in:Moto,Bicicleta,Carro,Monopatín'],
        ]);

        $repartidor = array_merge(session('repartidor', []), $data);
        $request->session()->put('repartidor', $repartidor);
        return redirect()->route('perfil')->with('status', 'Perfil actualizado correctamente.');
    }

    public function guardarConfiguracion(Request $request)
    {
        $request->session()->put('preferencias', [
            'notificaciones_push' => $request->boolean('notificaciones_push'),
            'sonido_pedidos' => $request->boolean('sonido_pedidos'),
        ]);
        return redirect()->route('configuracion')->with('status', 'Preferencias guardadas.');
    }
}
