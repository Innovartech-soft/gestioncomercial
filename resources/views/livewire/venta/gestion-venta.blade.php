<div class="container-fluid mt-4">

    {{-- FILTROS SUPERIORES --}}
    <div class="row mb-4">
        <div class="col-12 col-lg-4">
            <label class="form-label fw-bold">Tipo de Comprobante</label>
            <select class="form-select" wire:model="tipoComprobante">
                <option value="">Seleccione...</option>
                @foreach($tipos as $tipo)
                <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12 col-lg-4">
            <label class="form-label fw-bold">Cliente</label>
            <select class="form-select" wire:model="cliente">
                <option value="">Seleccione...</option>
                @foreach($clientes as $cliente)
                <option value="{{ $cliente->id }}">{{ $cliente->razon_social ?? $cliente->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12 col-lg-4">
            <label class="form-label fw-bold">Vendedor</label>
            <select class="form-select" wire:model="vendedor">
                <option value="">Seleccione...</option>
                @foreach($vendedores as $vendedor)
                    <option value="{{ $vendedor->id }}">{{ $vendedor->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row g-3">

        {{-- ================== IZQUIERDA ================== --}}
        <div class="col-12 col-lg-7 p-3 border rounded">

            <div class="row mb-3">
                <div class="col-4">
                    <select class="form-select" wire:model="filtroMarca">
                        <option value="">Marca...</option>
                        @foreach($marcas as $marca)
                            <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-4">
                    <select class="form-select" wire:model="filtroProveedor">
                        <option value="">Proveedor...</option>
                        @foreach($proveedores as $proveedor)
                            <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-4">
                    <select class="form-select" wire:model="filtroRubro">
                        <option value="">Rubro...</option>
                        @foreach($rubros as $rubro)
                            <option value="{{ $rubro->id }}">{{ $rubro->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <input type="text" class="form-control mb-3" placeholder="🔎 Buscar por nombre o escanear código de barras…"
            wire:model.live="buscador">

            <div class="border p-2" style="min-height:250px;">
                <table class="table table-sm table-hover">
                    <thead>
                        <th>Nombre</th>
                        <th class="text-end">Precio</th>
                        <th class="text-end">Stock</th>
                    </thead>
                    <tbody>
                        @if($productos && $productos->count())
                            @foreach($productos as $producto)
                                <tr wire:click="seleccionarProducto({{ $producto->id }})" style="cursor:pointer;">
                                    <td>{{ $producto->nombre }}</td>
                                    <td class="text-end">${{ number_format($producto->precio_pesos_con_iva,1) }}</td>
                                    <td class="text-end">{{$producto->stock}}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="2">No hay resultados para la búsqueda.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>

        {{-- ================== DERECHA ================== --}}
        <div class="col-12 col-lg-5 p-3 border rounded">

            @if($productoSeleccionado)
            <div class="text-center mb-3">
                <h5>{{ $productoSeleccionado->nombre }}</h5>
                <h6 class="fw-bold">${{ number_format($productoSeleccionado->precio,2) }}</h6>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <input type="number" min="1" class="form-control" wire:model="cantidad">
                    <small>Cantidad</small>
                </div>
                <div class="col-6">
                    <input type="number" class="form-control" wire:model="descuento">
                    <small>Descuento %</small>
                </div>
            </div>

            <button class="btn btn-success w-100 mb-3" wire:click="agregarCarrito">
                Añadir
            </button>
            @endif

            <table class="table table-sm align-middle">
    <thead>
        <tr>
            <th>Producto</th>
            <th width="70">Cant.</th>
            <th width="70">Desc %</th>
            <th class="text-end" width="120">Subtotal</th>
            <th width="40"></th>
        </tr>
    </thead>

    <tbody>
        @foreach($carrito as $index => $item)
        <tr>
            <td>{{ $item['nombre'] }}</td>

            {{-- CAMPO CANTIDAD --}}
            <td>
                <input type="number"
                    min="1"
                    class="form-control form-control-sm"
                    wire:model.lazy="carrito.{{ $index }}.cantidad"
                    wire:change="actualizarItem({{ $index }})">
            </td>

            {{-- CAMPO DESCUENTO --}}
            <td>
                <input type="number"
                    min="0" max="100"
                    class="form-control form-control-sm"
                    wire:model.lazy="carrito.{{ $index }}.descuento"
                    wire:change="actualizarItem({{ $index }})">
            </td>

            {{-- SUBTOTAL --}}
            <td class="text-end">
                ${{ number_format($item['subtotal_con_iva'], 2) }}
            </td>

            {{-- ELIMINAR --}}
            <td>
                <button class="btn btn-danger btn-sm"
                    wire:click="eliminarItem({{ $index }})">
                    X
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


            {{-- ================== RESUMEN DE TOTALES ================== --}}
            <div class="border-top mt-3 pt-3 small">
                <div class="d-flex justify-content-between">
                    <span>Subtotal (sin IVA):</span>
                    <span class="fw-bold text-muted">
                        ${{ number_format(collect($carrito)->sum('subtotal_sin_iva'), 2) }}
                    </span>
                </div>

                <div class="d-flex justify-content-between">
                    <span>IVA:</span>
                    <span class="fw-bold text-muted">
                        ${{ number_format(
                            collect($carrito)->sum('subtotal_con_iva') - collect($carrito)->sum('subtotal_sin_iva'), 
                            2
                        ) }}
                    </span>
                </div>

                <div class="d-flex justify-content-between fw-bold border-top pt-2 mt-2">
                    <span>Total (con IVA):</span>
                    <span class="text-success">
                        ${{ number_format(collect($carrito)->sum('subtotal_con_iva'), 2) }}
                    </span>
                </div>
            </div>

            {{-- ================== BOTÓN DE GENERAR ================== --}}
            <button class="btn btn-primary w-100 mt-3" wire:click="finalizarVenta">
                Generar
            </button>

        </div>
    </div>
</div>
