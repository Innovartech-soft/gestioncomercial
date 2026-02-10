<?php

namespace App\Http\Controllers;
use App\Viaje;
use App\EstadoViaje;
use App\Cliente;
use App\Venta;
use App\DetalleViaje;
use App\Repartidor;
use App\Exports\ViajeExcelExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

class ViajeController extends Controller
{
    //
    public function index()
    {
        $Viajes = Viaje::with(['repartidor', 'estadoViaje'])
            ->orderBy('id', 'desc')
            ->get();
        $estados = EstadoViaje::all();
        return view('pages.viajes.index', compact('Viajes','estados'));
    }

    public function create()
    {
        $estadoViaje = EstadoViaje::all();
        $clientes = Cliente::all();
        $ventas = Venta::where('pagada', 0)
               ->whereIn('id_tipo_venta', [2, 3, 4])
               ->get();
        $repartidores = Repartidor::where('disponible', 1)->get();
        return view('pages.viajes.form', compact('estadoViaje', 'clientes', 'ventas', 'repartidores'));
    }

    public function store(Request $request)
    {
        $viaje = new Viaje;
        
        $viaje = Viaje::create([
            'id_repartidor' => $request->input('repartidor'),
            'id_estado' => 1, // Estado inicial
            // 'fecha_envio' => now(),
        ]);


        if ($request->has('items') && is_array($request->input('items'))) {
                foreach ($request->input('items') as $item) {
                    DetalleViaje::create([
                        'viaje_id' => $viaje->id,
                        'vendedor_id' => $item['vendedor_id'] ?? null,
                        'cliente_id' => $item['cliente_id'] ?? null,
                        'venta_id' => $item['venta_id'] ?? null,
                        'importe' => $item['importe'] ?? 0,
                        'monto' => $item['monto'] ?? 0,
                        'saldo' => $item['saldo'] ?? 0,
                    ]);
                }
            }

        return redirect()->route('viaje.index')->with('success', 'Repartidor creado correctamente.');
    }

    public function edit($viaje)
    {
    $viaje = Viaje::with(['detalleViajes.cliente', 'detalleViajes.vendedor'])
        ->findOrFail($viaje);
    $estadoViaje = EstadoViaje::all();
    $clientes = Cliente::all();
    $ventas = Venta::where('pagada', 0)
        ->when(isset($viaje), function($query) use ($viaje) {
            // Incluir las ventas ya asociadas al viaje actual
            return $query->orWhereIn('id', $viaje->detalleViajes->pluck('venta_id'));
        })
        ->get();
    $repartidores = Repartidor::where('disponible', 1)
        ->when(isset($viaje), function($query) use ($viaje) {
            // Incluir el repartidor actual aunque no esté disponible
            return $query->orWhere('id', $viaje->id_repartidor);
        })
        ->get();

    return view('pages.viajes.form', compact('viaje', 'estadoViaje', 'clientes', 'ventas', 'repartidores'));
    }

    public function update(Request $request, $id)
    {
        // Buscar el viaje existente
        $viaje = Viaje::findOrFail($id);

        $viaje->update([
            'id_repartidor' => $request->input('repartidor'),
        ]);

        // Eliminar los detalles anteriores (opcional, según lógica de tu sistema)
        DetalleViaje::where('viaje_id', $viaje->id)->delete();

        // Crear nuevos detalles si vienen en la solicitud
        if ($request->has('items') && is_array($request->input('items'))) {
            foreach ($request->input('items') as $item) {
                DetalleViaje::create([
                    'viaje_id' => $viaje->id,
                    'vendedor_id' => $item['vendedor_id'] ?? null,
                    'cliente_id' => $item['cliente_id'] ?? null,
                    'venta_id' => $item['venta_id'] ?? null,
                    'importe' => $item['importe'] ?? 0,
                    'monto' => $item['monto'] ?? 0,
                    'saldo' => $item['saldo'] ?? 0,
                ]);
            }
        }

        return redirect()->route('viaje.index')->with('success', 'Viaje actualizado correctamente.');
    }

    public function cambiarEstado(Request $request)
    {
        $request->validate([
            'viaje_id' => 'required|exists:viajes,id',
            'estado_id' => 'required|exists:estado_viaje,id',
        ]);

        $viaje = Viaje::findOrFail($request->viaje_id);
        $viaje->id_estado = $request->estado_id;
        $viaje->save();

        return redirect()->route('viaje.index')->with('success', 'Estado del viaje actualizado correctamente.');
    }


    public function destroy(Viaje $viaje)
    {
        
            $detalleViajes = DetalleViaje::where('viaje_id', $viaje->id)->get();
            $viaje = Viaje::findOrFail($viaje->id);
            foreach ($detalleViajes as $detalle) {
                $detalle->update(['deleted_at' => now()]);
            }
            $viaje->update(['deleted_at' => now()]);
           
               $viaje->save();
               $detalleViajes->each->save();
                return redirect()->route('viaje.index')->with('success', 'La planilla de viaje ha sido eliminada correctamente');
       
    }
    
    public function view($viajeId)
{
    $viaje = Viaje::with([
        'repartidor',
        'detalleViajes',
        'detalleViajes.cliente',
        'detalleViajes.vendedor'
    ])->findOrFail($viajeId);

    $data = [
        'viaje' => [
            'id' => $viaje->id,
            'creacion' => $viaje->created_at,
            'estado' => $viaje->estadoViaje->nombre ?? 'Sin estado',
            'repartidor' => $viaje->repartidor->nombre ?? 'No asignado',
            'cantidad_boletas' => count($viaje->detalleViajes),
        ],
        'detalles' => $viaje->detalleViajes->map(function ($detalle) {
            return [
                'cliente_id' => $detalle->cliente ? $detalle->cliente->getCodigoAttribute() : 'Sin cliente',
                'cliente' => $detalle->cliente->razon_social ?? 'Sin cliente',
                'vendedor' => $detalle->vendedor->nombre ?? 'Sin vendedor',
                'venta_id' => $detalle->venta_id,
                'importe' => $detalle->importe,
                'monto' => $detalle->monto,
                'saldo' => $detalle->saldo,
            ];
        })
    ];

    return response()->json($data);
}


    public function exportExcel($id)
    {
        $viaje = Viaje::with([
            'repartidor',
            'detalleViajes' => function($query) {
                $query->orderBy('id'); // Ordenar por cliente
            },
            'detalleViajes.cliente',
            'detalleViajes.vendedor'
        ])->findOrFail($id);

        $data = [
            'viaje' => $viaje,
            'detalles' => collect($viaje->detalleViajes)->map(function ($detalle) {
                return [
                    'cliente_id' => $detalle->cliente ? $detalle->cliente->getCodigoAttribute() : 'Sin cliente',
                    'cliente' => $detalle->cliente->razon_social ?? 'Sin cliente',
                    'vendedor' => $detalle->vendedor->nombre ?? 'Sin vendedor',
                    'venta_id' => $detalle->venta_id,
                    'importe' => $detalle->importe,
                    'monto' => $detalle->monto,
                    'saldo' => $detalle->saldo,
                    'to' => $detalle->to,
                    'e' => $detalle->e,
                    'te' => $detalle->te,
                    'np' => $detalle->np
                ];
            })->sortBy('id')
        ];

        return Excel::download(
            new ViajeExcelExport($data),
            'planillaViaje_' . $id . '_' . date('Y-m-d') . '.xlsx'
        );
    }
}
