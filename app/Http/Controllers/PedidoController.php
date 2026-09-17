<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function pedidos()
    {
        return view('pages.pedidos');
    }

    public function activos()
    {
        return view('pages.activos');
    }

    public function historial()
    {
        return view('pages.historial');
    }
}
