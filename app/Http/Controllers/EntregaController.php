<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntregaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $courier = $this->courier($request);
        $type = $request->query('type', 'available');

        $query = DB::table('entrega as e')
            ->join('compra as c', 'c.id_compra', '=', 'e.id_compra')
            ->join('pedido as p', 'p.id_pedido', '=', 'c.id_pedido')
            ->leftJoin('usuario as u', 'u.id_usuario', '=', 'p.id_usuario')
            ->leftJoin('negocios as n', 'n.id_negocio', '=', 'p.id_negocio')
            ->leftJoin('detalle_pedido as d', 'd.id_pedido', '=', 'p.id_pedido')
            ->whereNull('e.fecha_confirmacion')
            ->selectRaw(
                'e.id_entrega, p.id_pedido, p.direccion_entrega, p.estado as pedido_estado,
                 e.estado as entrega_estado, e.codigo_entrega, e.id_repartidor,
                 u.nombre as cliente_nombre, u.apellido as cliente_apellido,
                 n.nombre as negocio_nombre, c.total,
                 SUM(d.valor * d.cantidad) as detalle_total'
            )
            ->groupBy(
                'e.id_entrega', 'p.id_pedido', 'p.direccion_entrega', 'p.estado',
                'e.estado', 'e.codigo_entrega', 'e.id_repartidor',
                'u.nombre', 'u.apellido', 'n.nombre', 'c.total'
            );

        if ($type === 'active') {
            $query->where('e.id_repartidor', $courier->id_repartidor);
        } else {
            $query->whereNull('e.id_repartidor')
                ->whereIn('e.estado', ['Pendiente', 'Preparado']);
        }

        return response()->json([
            'items' => $query->orderByDesc('p.id_pedido')->get()->map(function ($delivery) {
                $delivery->id = (int) $delivery->id_entrega;
                $delivery->codigo_r = (string) $delivery->codigo_entrega;
                $delivery->codigo_c = (string) $delivery->codigo_entrega;
                $delivery->total = (int) ($delivery->detalle_total ?? $delivery->total ?? 0);
                return $delivery;
            }),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $courier = $this->courier($request);
        $action = (string) $request->input('action');
        $deliveryId = (int) $request->input('id_entrega');

        $delivery = DB::table('entrega as e')
            ->join('compra as c', 'c.id_compra', '=', 'e.id_compra')
            ->join('pedido as p', 'p.id_pedido', '=', 'c.id_pedido')
            ->where('e.id_entrega', $deliveryId)
            ->select('e.*', 'p.id_pedido')
            ->first();
        abort_unless($delivery, 404);

        if ($action === 'accept') {
            $updated = DB::table('entrega')
                ->where('id_entrega', $deliveryId)
                ->whereNull('id_repartidor')
                ->update([
                    'id_repartidor' => $courier->id_repartidor,
                    'fecha_asignacion' => now(),
                ]);

            return response()->json(['updated' => (bool) $updated], $updated ? 200 : 409);
        }

        abort_unless((int) $delivery->id_repartidor === (int) $courier->id_repartidor, 403);

        if ($action === 'advance') {
            $nextState = $delivery->estado === 'Pendiente' ? 'En camino' : 'Entregado';
            DB::table('entrega')->where('id_entrega', $deliveryId)->update([
                'estado' => $nextState,
                'fecha_entrega' => $nextState === 'Entregado' ? now() : $delivery->fecha_entrega,
            ]);
            return response()->json(['updated' => true, 'estado' => $nextState]);
        }

        if ($action === 'deliver_client_code') {
            $code = trim((string) $request->input('codigo_cliente'));
            abort_unless($code !== '' && hash_equals((string) $delivery->codigo_entrega, $code), 422, 'El código del cliente no coincide.');

            DB::transaction(function () use ($delivery, $deliveryId): void {
                DB::table('entrega')->where('id_entrega', $deliveryId)->update([
                    'estado' => 'Entregado',
                    'fecha_entrega' => now(),
                    'fecha_confirmacion' => now(),
                ]);
                DB::table('pedido')->where('id_pedido', $delivery->id_pedido)->update(['estado' => 'Entregado']);
            });

            return response()->json(['updated' => true, 'estado' => 'Entregado']);
        }

        return response()->json(['error' => 'Acción de entrega no válida.'], 422);
    }

    private function courier(Request $request): object
    {
        $userId = (int) $request->session()->get('repartidor.id_usuario');
        $courier = DB::table('repartidor')->where('id_usuario', $userId)->first();
        abort_unless($courier, 403);
        return $courier;
    }
}
