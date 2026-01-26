<?php

namespace App\Livewire\Venta;

use App\Lista;
use App\Marca;
use App\Rubro;
use App\Cliente;
use App\Producto;
use App\Vendedor;
use App\Proveedor;
use App\TipoVenta;
use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class GestionVenta extends Component
{
    public $tipos, $clientes, $vendedores, $marcas, $rubros, $proveedores;
    public $productos; // colección de resultados mostrados

    // Inputs UI
    public $tipoComprobante = '';
    public $cliente = '';
    public $vendedor = '';
    public $filtroMarca = '';
    public $filtroProveedor = '';
    public $filtroRubro = '';
    public $buscador = '';

    // Carrito
    public $carrito = [];
    public $productoSeleccionado = null;
    public $cantidad = 1;
    public $descuento = 0;
    public $listaSeleccionada = null;
    public $listasDescuento = [];
    public $descuento_total = 0;

    // Modal de pago
    public $pago = [
        'cuentaCorriente' => false,
        'efectivo' => 0,
        'tarjeta' => 0,
        'otro' => 0,
        'cheque' => 0,
    ];

    public $observacionesPago = '';

    // Totales discriminados
    public $subtotalSinIVA = 0;
    public $totalIVA = 0;
    public $totalConIVA = 0;

    protected $rules = [
        'cantidad' => 'integer|min:1',
        'descuento' => 'numeric|min:0'
    ];

    public function mount()
    {
        $this->tipos = TipoVenta::all();
        $this->clientes = Cliente::all();
        $this->vendedores = Vendedor::where('estado', true)->get();
        $this->marcas = Marca::all();
        $this->rubros = Rubro::all();
        $this->proveedores = Proveedor::all();
        $this->productos = collect();
        $this->listasDescuento = Lista::all();
    }

    public function updatedBuscador()
    {
        $this->buscarProductos();
    }

    public function updatedFiltroMarca()
    {
        $this->buscarProductos();
    }

    public function updatedFiltroProveedor()
    {
        $this->buscarProductos();
    }

    public function updatedFiltroRubro()
    {
        $this->buscarProductos();
    }

    public function updatedCliente($value)
    {
        $clienteObj = Cliente::find($value);

        if ($clienteObj && $clienteObj->lista) {
            $this->listaSeleccionada = $clienteObj->lista->id;
        } else {
            $this->listaSeleccionada = null;
        }
    }



    public function getTotalConIVAProperty()
    {
        return collect($this->carrito)->sum('subtotal_con_iva');
    }


    public function buscarProductos()
    {
        if (trim($this->buscador) === '' && !$this->filtroMarca && !$this->filtroProveedor && !$this->filtroRubro) {
            $this->productos = collect();
            return;
        }

        $term = trim($this->buscador);
        $query = Producto::query();

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('nombre', 'like', "%{$term}%")
                    ->orWhere('codigo', 'like', "%{$term}%")
                    ->orWhere('codigo_interno', 'like', "%{$term}%")
                    ->orWhere('codigo_barras', $term);
            });
        }

        if ($this->filtroMarca) {
            if (SchemaHasColumn('productos', 'id_marca')) {
                $query->where('id_marca', $this->filtroMarca);
            }
        }
        if ($this->filtroProveedor) {
            if (SchemaHasColumn('productos', 'id_proveedor')) {
                $query->where('id_proveedor', $this->filtroProveedor);
            }
        }
        if ($this->filtroRubro) {
            if (SchemaHasColumn('productos', 'id_rubro')) {
                $query->where('id_rubro', $this->filtroRubro);
            }
        }

        $this->productos = $query->orderBy('nombre')->limit(100)->get();

        // Si el término coincide con código de barras, seleccionar automáticamente
        if ($term && $this->productos->count() === 1 && $this->productos->first()->codigo_barras === $term) {
            $this->seleccionarProducto($this->productos->first()->id);
        }
    }

    public function seleccionarProductoUnico(): void
    {
        $this->buscarProductos();

        if (! $this->productos || $this->productos->count() !== 1) {
            return;
        }

        $this->seleccionarProducto($this->productos->first()->id);
    }

    public function seleccionarProducto($id)
    {
        $this->productoSeleccionado = Producto::find($id);
        $this->cantidad = 1;
        Log::info('Producto seleccionado - id: ' . $id);
        if ($this->listaSeleccionada) {

            $idLista = (int) $this->listaSeleccionada;

            $lista = collect($this->listasDescuento)->first(fn($l) => (string)$l->id === (string)$this->listaSeleccionada);

            if ($lista) {
                $this->descuento = (float) $lista->valor;
            }
        } else {
            $this->descuento = 0;
        }
    }


    public function updatedListaSeleccionada($value)
    {
        if (!$value) {
            return;
        }
        Log::info('Lista seleccionada - nombre: ' . $value);
        // FORZAR que la comparación no falle por tipo
        $lista = collect($this->listasDescuento)
                    ->first(function($l) use ($value) {
                        return (string)$l->id === (string)$value;
                    });

        if ($lista) {
            $this->descuento = floatval($lista->valor);
            Log::info('Lista valor - precio: ' . $this->descuento);
        }
    }



    public function agregarCarrito()
{
    if (! $this->puedeAgregarItems) {
        return;
    }

    if (! $this->productoSeleccionado) {
        return;
    }

    $idProd  = (int) $this->productoSeleccionado->id;
    $listaId = $this->listaSeleccionada ? (int) $this->listaSeleccionada : null;

    // Precio base
    $precioConIVA = (float) $this->productoSeleccionado->PrecioPesosConIva;
    $precioSinIVA = (float) $this->productoSeleccionado->getPrecioEnPesos();

    // Descuento % según lista seleccionada (0..100)
    $listaValor = 0.0;
    if ($listaId) {
        $lista = collect($this->listasDescuento)->firstWhere('id', $listaId);
        $listaValor = $lista ? (float) ($lista->valor ?? 0) : 0.0;
    }
    $descuentoLista = max(0, min(100, $listaValor));

    // Buscar si ya existe en el carrito
    $index = collect($this->carrito)->search(fn($p) => (string)$p['id'] === (string)$idProd);

    // ============================
    // CASO 1: ya existe
    // ============================
    if ($index !== false) {

        // 1) SUMAR cantidad
        $this->carrito[$index]['cantidad'] += max(1, (int) $this->cantidad);
$this->carrito[$index]['cantidad'] = $cantidad;
        // 2) REEMPLAZAR lista descuento
        $this->carrito[$index]['lista_descuento_id'] = $listaId;

        $cantidadTotal = (int) $this->carrito[$index]['cantidad'];

        // 3) Recalcular subtotales con la NUEVA lista
        $subConIVA = $precioConIVA * $cantidadTotal;
        $subSinIVA = $precioSinIVA * $cantidadTotal;

        if ($descuentoLista > 0) {
            $factor = 1 - ($descuentoLista / 100);
            $subConIVA *= $factor;
            $subSinIVA *= $factor;
        }

        // Si querés que el precio se actualice al vigente:
        $this->carrito[$index]['precio'] = $precioConIVA;
        $this->carrito[$index]['precio_sin_iva'] = $precioSinIVA;

        $this->carrito[$index]['subtotal_con_iva'] = round($subConIVA, 2);
        $this->carrito[$index]['subtotal_sin_iva'] = round($subSinIVA, 2);
        $this->carrito[$index]['subtotal']         = round($subConIVA, 2);
    }

    // ============================
    // CASO 2: nuevo
    // ============================
    else {
        $cantidad = max(1, (int) $this->cantidad);

        $subConIVA = $precioConIVA * $cantidad;
        $subSinIVA = $precioSinIVA * $cantidad;

        if ($descuentoLista > 0) {
            $factor = 1 - ($descuentoLista / 100);
            $subConIVA *= $factor;
            $subSinIVA *= $factor;
        }

        $this->carrito[] = [
            'id' => $idProd,
            'nombre' => $this->productoSeleccionado->nombre,
            'precio' => $precioConIVA,
            'precio_sin_iva' => $precioSinIVA,
            'cantidad' => $cantidad,
            'lista_descuento_id' => $listaId,
            'subtotal_con_iva' => round($subConIVA, 2),
            'subtotal_sin_iva' => round($subSinIVA, 2),
            'subtotal' => round($subConIVA, 2),
        ];
    }

    // Reset selección (no toques descuento_total)
    $this->productoSeleccionado = null;
    $this->cantidad = 1;
    $this->listaSeleccionada = null;

    $this->actualizarTotales();
}




    public function actualizarItem($index)
{
    if (!isset($this->carrito[$index])) return;

    $item = $this->carrito[$index];

    $cantidad = max(1, (int) ($item['cantidad'] ?? 1));
    $precio   = max(0, (float) ($item['precio'] ?? 0));
    $listaId  = !empty($item['lista_descuento_id']) ? (int) $item['lista_descuento_id'] : null;

    // Descuento de lista
    $listaValor = 0.0;
    if ($listaId) {
        $lista = collect($this->listasDescuento)->firstWhere('id', $listaId);
        $listaValor = $lista ? (float) ($lista->valor ?? 0) : 0.0;
    }
    $descuento = max(0, min(100, $listaValor));

    // Subtotales base
    $subConIVA = $precio * $cantidad;

    $precioSinIVAUnit = (float) ($item['precio_sin_iva'] ?? ($precio / 1.21));
    $subSinIVA = $precioSinIVAUnit * $cantidad;

    // Aplicar descuento
    if ($descuento > 0) {
        $factor = 1 - ($descuento / 100);
        $subConIVA *= $factor;
        $subSinIVA *= $factor;
    }

    $this->carrito[$index]['cantidad'] = $cantidad;
    $this->carrito[$index]['precio']   = $precio;
    $this->carrito[$index]['lista_descuento_id'] = $listaId;

    $this->carrito[$index]['subtotal_con_iva'] = round($subConIVA, 2);
    $this->carrito[$index]['subtotal_sin_iva'] = round($subSinIVA, 2);
    $this->carrito[$index]['subtotal']         = round($subConIVA, 2);

    $this->actualizarTotales();
}



    public function eliminarItem($index)
    {
        unset($this->carrito[$index]);
        $this->carrito = array_values($this->carrito);
        $this->actualizarTotales();
    }

    public function actualizarTotales()
    {
        // Totales base desde el carrito
        $subSinIva = collect($this->carrito)->sum('subtotal_sin_iva');
        $subConIva = collect($this->carrito)->sum('subtotal_con_iva');

        $iva = $subConIva - $subSinIva;

        // Descuento global del ticket (0..100)
        $dto = max(0, min(100, (float) $this->descuento_total));
        $factor = 1 - ($dto / 100);

        // Aplicar descuento global
        $this->subtotalSinIVA = round($subSinIva * $factor, 2);
        $this->totalIVA      = round($iva * $factor, 2);
        $this->totalConIVA   = round($subConIva * $factor, 2);
    }

    public function updatedDescuentoTotal()
    {
        $this->descuento_total = max(0, min(100, (float) $this->descuento_total));
        $this->actualizarTotales();
    }

    public function calcularTotal()
    {
        return $this->totalConIVA;
    }

    public function getTotalPagoProperty()
    {
        return collect($this->pago)->only(['efectivo', 'tarjeta', 'otro', 'cheque'])->sum();
    }

    public function getSaldoPendienteProperty()
    {
        return round($this->totalConIVA - $this->totalPago, 2);
    }

    public function abrirModalPago()
    {
        if (! $this->puedeGenerarVenta) {
            return;
        }

        $this->dispatch('show-modal-pago');
    }

    public function cerrarModalPago()
    {
        $this->dispatch('hide-modal-pago');
    }

    public function confirmarPago()
    {
        if (! $this->validarPago()) {
            return;
        }

        Log::info('Pago registrado (pendiente de guardado).', [
            'pago' => $this->pago,
            'total_pago' => $this->totalPago,
        ]);

        $this->dispatch('hide-modal-pago');
    }

    public function resetPago()
    {
        $this->pago['cuentaCorriente'] = false;
        $this->pago['efectivo'] = 0;
        $this->pago['tarjeta'] = 0;
        $this->pago['cheque'] = 0;
        $this->pago['otro'] = 0;
        $this->observacionesPago = '';
        $this->resetErrorBag('pago_total');
    }

    public function aplicarPagoTotal($metodo)
    {
        $metodo = (string) $metodo;
        if (!in_array($metodo, ['efectivo', 'tarjeta', 'cheque', 'otro'], true)) {
            return;
        }

        if ($this->pago['cuentaCorriente']) {
            return;
        }

        $this->setPagoMetodo($metodo, $this->totalConIVA);
    }

    public function updatedPagoCuentaCorriente($value)
    {
        if ($value) {
            $this->pago['efectivo'] = 0;
            $this->pago['tarjeta'] = 0;
            $this->pago['cheque'] = 0;
            $this->pago['otro'] = 0;
        }

        $this->resetErrorBag('pago_total');
    }

    public function updatedPagoEfectivo($value)
    {
        $this->syncPagoInput('efectivo', $value);
    }

    public function updatedPagoTarjeta($value)
    {
        $this->syncPagoInput('tarjeta', $value);
    }

    public function updatedPagoCheque($value)
    {
        $this->syncPagoInput('cheque', $value);
    }

    public function updatedPagoOtro($value)
    {
        $this->syncPagoInput('otro', $value);
    }

    private function syncPagoInput(string $metodo, $value): void
    {
        if ($this->pago['cuentaCorriente']) {
            $this->pago[$metodo] = 0;
            return;
        }

        $valor = is_numeric($value) ? (float) $value : 0;
        $this->pago[$metodo] = max(0, round($valor, 2));
        $this->resetErrorBag('pago_total');
    }

    private function setPagoMetodo(string $metodo, $value): void
    {
        $valor = is_numeric($value) ? (float) $value : 0;
        $valor = max(0, round($valor, 2));

        foreach (['efectivo', 'tarjeta', 'cheque', 'otro'] as $key) {
            $this->pago[$key] = $key === $metodo ? $valor : 0;
        }
    }

    public function getPuedeConfirmarPagoProperty()
    {
        if ($this->pago['cuentaCorriente']) {
            return true;
        }

        return $this->totalPago > 0 && $this->pagoCompleto();
    }

    private function pagoCompleto(): bool
    {
        return abs($this->totalConIVA - $this->totalPago) < 0.01;
    }

    private function validarPago(): bool
    {
        $this->resetErrorBag('pago_total');

        if ($this->pago['cuentaCorriente']) {
            return true;
        }

        if (! $this->pagoCompleto()) {
            $this->addError('pago_total', 'El total abonado debe coincidir con el total de la venta.');
            return false;
        }

        return true;
    }

    public function getPuedeAgregarItemsProperty()
    {
        return !empty($this->tipoComprobante)
            && !empty($this->cliente)
            && !empty($this->vendedor);
    }

    public function getPuedeGenerarVentaProperty()
    {
        return count($this->carrito) > 0;
    }

    public function render()
    {
        return view('livewire.venta.gestion-venta', [
            'productos' => $this->productos,
        ]);
    }
}

// Helper fuera de la clase
if (! function_exists('SchemaHasColumn')) {
    function SchemaHasColumn($table, $column) {
        try {
            return \Illuminate\Support\Facades\Schema::hasColumn($table, $column);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
