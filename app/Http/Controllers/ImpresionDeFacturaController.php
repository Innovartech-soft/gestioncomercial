<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
// use PDF;
// use ArrayObject;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Exports\VentaExcelExport;
use App\Services\VentaService;
use App\Venta;
use App\Cliente;
use App\DetalleVenta;
use App\Producto;
// use Illuminate\Support\Collection;
use Log;
use App\Parametro;


class ImpresionDeFacturaController extends Controller
{
    protected $ventaService;

    public function __construct(VentaService $ventaService){
        $this->ventaService = $ventaService;
    }

    private function generarContenidoDeFactura($venta, $parametro){
        $detalleVentaArray = [];

        foreach ($venta->detalleVenta as $detalle) {
//            Log::debug('venta'.$detalle);
            $detalleArray = $detalle->toArray();
            $detalleArray['nombre'] = $detalle->producto->nombre;
//            $detalleArray['precio'] = $detalle->producto->precio_venta_final_impresion;// - ($detalle->descuento/$detalle->cantidad);
            $detalleArray['codigo'] = $detalle->producto->codigo_interno;
            $detalleArray['precio_sin_descuento'] = $detalle->producto->precio_sin_descuento;
            $detalleArray['descuento_porcentual'] = $detalle->descuento_porcentual;
            $detalleArray['subtotal'] = ($detalle->producto->precio_venta_final_impresion * $detalle->cantidad) - $detalle->descuento; //Corregir este calculo (no incluye los descuentos)
            $detalleVentaArray[] = $detalleArray;
//            Log::debug('precio_sin_descuento'.$detalleArray['precio_sin_descuento']);
        }
        $buyer = new Collection([
            'nombre' => $venta->cliente->razon_social,
            'codigo' => $venta->cliente->codigo,
            'cuit'=> $venta->cliente->dni_cuit,
            'direccion' => $venta->cliente->direccion,
            'telefono' => $venta->cliente->tel_1,
            'notas' => $venta->cliente->notas,
        ]);

        $seller = new Collection([
            'nombre' => $parametro->nombre_empresa,
            'vendedor' => $venta->vendedor->nombre,
            'cuit'=> $parametro->cuit,
            'direccion' => $parametro->dir_1,
            'telefono' => $parametro->tel_1,
        ]);

        $data =  Array(
            'buyer' => $buyer,
            'seller' => $seller,
            'modificado' => $venta->wasModified(),
            'invoice_number' => $venta->numero_venta,
            'comprobante_tipo' => $venta->tipoVenta->nombre,
            'comprobante_tipo_id' => $venta->id_tipo_venta,
            'invoice_date' => '2020-10-10',
            'due_date' => '2020-10-10',
            'product' => $detalleVentaArray, //Si esto no anda, hacerle un to_array
            'subtotal' => round($this->ventaService->getTotalSinDescuento($venta),1),
            'discount' =>$venta->descuento,
            'tax' => '0',
            'total' => $venta->id_tipo_venta==4?round($venta->total,1)*-1:round($venta->total,1), //Si es Nota de Credito (4) lo paso a negativo
            'paid' => $venta->pagada,
            'balance' => '300',
            'notas'=> $venta->notas,
            'created_at' => \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $venta->created_at)->format('d/m/Y'),
            'total_descuento' => round($this->ventaService->getTotalSinDescuento($venta) - $venta->total,1),
        );
//
//        Log::debug($data['total_descuento']);
        return $data;

    }

    public function totalDiscount($final,$sinDescuento){

        $descuento = $sinDescuento - $final;
        return round($descuento, 1);

    }

    public function descargarFactura(Request $request){

        $idVenta = $request->segment(3);
        $venta = $this->ventaService->getById($idVenta);
//        Log::debug($venta);
        $parametro = Parametro::orderBy('id', 'desc')->first();
        $data = $this->generarContenidoDeFactura($venta, $parametro);
        // Assuming your content is in HTML format
        $content = view('invoice_pdf', compact('data'))->render();

        // Calculate the total number of pages based on content length
        $contentLength = mb_strlen($content);
        $charactersPerPage = 1000; // Adjust this value based on your content and styling
        $totalPages = ceil($contentLength / $charactersPerPage);

        // Pass $totalPages to the PDF view
        $pdf = PDF::loadView('invoice_pdf', compact('data', 'totalPages'));
        return $pdf->download('Venta'.now().'.pdf');

    }

    /**
     * Exporta factura en excel
     */
    public function exportarFactura(Request $request){

        $idVenta = $request->id_venta;
        $venta = $this->ventaService->getById($idVenta);
//        Log::debug($venta);
        $parametro = Parametro::orderBy('id', 'desc')->first();
        $data = $this->generarContenidoDeFactura($venta, $parametro);
        return Excel::download(new VentaExcelExport($data), 'Venta_'.$venta->numeroVenta.'.xlsx');
    }

    public function verFactura($idVenta)
    {
        $venta = $this->ventaService->getById($idVenta);
        $parametro = Parametro::orderBy('id', 'desc')->first();
        $data = $this->generarContenidoDeFactura($venta, $parametro);
        $pdf = PDF::loadView('invoice_pdf', compact('data'));

        // Encode the generated PDF to base64
        $pdfBase64 = base64_encode($pdf->output());
        // Return the base64 encoded PDF to the view
        return view('pages.venta.show-pdf', ['pdf' => $pdfBase64]);
    }

}
