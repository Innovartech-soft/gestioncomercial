<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use App\Http\Controllers\Api\Response;
use Illuminate\Http\Response;
use App\Producto;
use App\Rubro;
use App\Marca;
use App\Proveedor;
use Log;

class ProductoApiController extends Controller
{
    //
    public function listadoProductos()
{
    try {
        $productos = Producto::whereNull('deleted_at')->get();


        $productos = $productos->map(function ($producto) {
            $producto->precio_costo_final = $producto->precio_pesos_con_iva;
            $producto->en_oferta = $producto->isOffer();
            return $producto;
        });

        // Convierte la colección de productos a un array
        $productosArray = $productos->toArray();

        return response()->json($productosArray, Response::HTTP_OK);
    } catch (\Throwable $th) {
        // Manejar la excepción aquí si es necesario
    }
}

    public function obtenerProductoId($id)
    {
        try {
            $producto = Producto::where('deleted_at',null)->find($id);
            $producto->precio_costo_final = $producto->precio_pesos_con_iva;
            $producto->id_rubro = $producto->rubro->nombre;
            $producto->id_marca = $producto->marca->nombre;
            $producto->id_proveedor = $producto->proveedor->nombre;
            $producto->en_oferta = $producto->isOffer();
                // Log::info($producto);
            return response()->json(

                     $producto->toArray()
                    //  $producto->rubro->nombre,
                    //  $producto->marca->nombre,
                    //  $producto->proveedor->nombre

            , Response::HTTP_OK);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Producto no encontrado',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        }

    }
    public function findProductoById($id)
    {
      $producto = Producto::findOrFail($id);
      return $producto;
    }

    public function updateStock($id,$stock){
        try {
            $producto = Producto::findOrFail($id);
            $producto->stock = $producto->stock+$stock;
            $producto->update();

            return response()->json(
                     $producto->toArray(),
                     $producto->rubro->nombre,
                     $producto->marca->nombre,
                     $producto->proveedor->nombre
            , Response::HTTP_OK);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'No se pudo actualizar el stock',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function obtenerStockDeProducto(Request $request){

        $json = $request->input('json_param');
        $data = json_decode($json, true);
        $stockDeProductos = [];
        foreach ($data['productos'] as $productoStock) {
            $id = $productoStock;
            $stockDeProductos[] = Producto::select('id','stock',)->find($id);
        }

        return response()->json([
            'message' =>'Productos por debajo del stock',
            'data' => $stockDeProductos
        ], Response::HTTP_OK);

    }
}
