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
            <select class="form-select" wire:model.change="cliente">
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
        <div class="col-12 col-lg-6 p-3 border rounded">

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
                    <th>Cod Int.</th>
                    <th>Producto</th>
                    <th class="text-end">Precio</th>
                    <th class="text-end">IVA</th>
                    <th class="text-end">Stock</th>
                </thead>

                <tbody>
                    @if($productos && $productos->count())
                        @foreach($productos as $producto)
                            <tr wire:click="seleccionarProducto({{ $producto->id }})" style="cursor:pointer;">

                                <td> {{ $producto->codigo_interno ?? '-' }}</td>
                                {{-- NOMBRE + ICONO OFERTA --}}
                                <td>
                                    <div class="fw-semibold">{{ $producto->nombre }}</div>

                                    @if($producto->isOffer())
                                        <div>
                                            <span class="badge bg-danger mt-1" style="font-size: .50rem;">
                                                <i class="fas fa-fire"></i> OFERTA
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                
                                {{-- PRECIO (normal / oferta) --}}
                                <td class="text-end">

                                    @if($producto->isOffer())

                                        {{-- Precio normal tachado --}}
                                        <div class="text-muted" style="text-decoration: line-through; font-size: .8rem;">
                                            ${{ number_format($producto->precio_pesos_con_iva, 2) }}
                                        </div>

                                        {{-- Precio oferta --}}
                                        <div class="fw-bold text-success" style="font-size: .9rem;">
                                            ${{ number_format($producto->precio_costo_oferta, 2) }}
                                        </div>

                                    @else

                                        {{-- Precio normal (sin oferta) --}}
                                        <div class="fw-bold">
                                            ${{ number_format($producto->precio_pesos_con_iva, 2) }}
                                        </div>

                                    @endif

                                </td>
                                 {{-- IVA --}}
                                <td class="text-end">
                                    {{ $producto->getTipoIva() }}%
                                </td>

                                {{-- STOCK --}}
                                <td class="text-end">
                                    {{ $producto->stock }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr><td colspan="4">No hay resultados para la búsqueda.</td></tr>
                    @endif
                </tbody>
            </table>

            </div>

        </div>

        {{-- ================== DERECHA ================== --}}
        <div class="col-12 col-lg-6 p-3 border rounded">

            @if($productoSeleccionado)

            {{-- NOMBRE DEL PRODUCTO --}}
            <div class="text-center mb-2">
                <h5 class="fw-bold">{{ $productoSeleccionado->nombre }}</h5>
            </div>

            {{-- PRECIO ACTUAL --}} 
            <div class="text-center mb-3">

                @if($productoSeleccionado->isOffer())
                    {{-- Precio normal tachado --}}
                    <div class="text-muted" style="text-decoration: line-through;">
                        ${{ number_format($productoSeleccionado->precio_pesos_con_iva, 2) }}
                    </div>

                    {{-- Precio con oferta --}}
                    <div class="fw-bold text-success" style="font-size: 1.2rem;">
                        ${{ number_format($productoSeleccionado->precio_costo_oferta, 1) }}
                    </div>
                @else
                    <div class="fw-bold" style="font-size: 1.2rem;">
                        ${{ number_format($productoSeleccionado->precio_pesos_con_iva, 2) }}
                    </div>
                @endif
            </div>

            {{-- LISTA DE DESCUENTOS --}}
            <div class="row mb-3">
                <div class="col-4">
                    <input type="number" min="1" class="form-control" wire:model="cantidad">
                    <small>Cantidad</small>
                </div>
                <div class="col-4">
                    <input 
                        type="number" 
                        class="form-control" 
                        wire:model="descuento"
                        wire:key="descuento-{{ $listaSeleccionada }}-{{ $productoSeleccionado->id ?? 0 }}"
                    >
                    <small>Descuento %</small>
                </div>
                <div class="col-4">
                    <select class="form-select" wire:model.change="listaSeleccionada">
                        <option value="">Lista descuento...</option>

                        @foreach($listasDescuento as $lista)
                            <option value="{{ $lista->id }}">
                                {{ $lista->nombre }} ({{ $lista->valor }}%)
                            </option>
                        @endforeach
                    </select>
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
            <th width="120">Precio</th>
            <th width="70">Desc %</th>
            <th class="text-end" width="120">Subtotal</th>
            <th width="10"></th>
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

            {{-- PRECIO UNITARIO (editable) --}}
            <td>
                <input type="number"
                    step="0.1"
                    class="form-control form-control-sm"
                    wire:model.lazy="carrito.{{ $index }}.precio"
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
            <button class="btn btn-primary w-100 mt-3" wire:click="abrirModalPago">
                Generar
            </button>

        </div>
    </div>
</div>

<div wire:ignore.self class="modal fade" id="modalPagoVenta" tabindex="-1" aria-labelledby="modalPagoVentaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPagoVentaLabel">Cierre de venta y método de pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar" wire:click="cerrarModalPago"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Total venta</label>
                        <input type="text" class="form-control" value="${{ number_format($totalConIVA, 2) }}" disabled>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Total pago</label>
                        <input type="text" class="form-control" value="${{ number_format($totalPago, 2) }}" disabled>
                    </div>
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="pagoCuentaCorriente" wire:model="pago.cuentaCorriente">
                            <label class="form-check-label" for="pagoCuentaCorriente">Cuenta Corriente</label>
                        </div>
                        <small class="text-muted">Marcar si el saldo queda a cuenta corriente.</small>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Efectivo</label>
                        <input type="number" min="0" step="0.01" class="form-control" wire:model.lazy="pago.efectivo">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Tarjeta</label>
                        <input type="number" min="0" step="0.01" class="form-control" wire:model.lazy="pago.tarjeta">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Cheque</label>
                        <input type="number" min="0" step="0.01" class="form-control" wire:model.lazy="pago.cheque">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Otro</label>
                        <input type="number" min="0" step="0.01" class="form-control" wire:model.lazy="pago.otro">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Observaciones</label>
                        <textarea class="form-control" rows="3" wire:model.defer="observacionesPago"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" wire:click="cerrarModalPago">
                    Cancelar
                </button>
                <button type="button" class="btn btn-primary" wire:click="confirmarPago">
                    Confirmar pago
                </button>
            </div>
        </div>
    </div>
</div>

@push('custom-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modalElement = document.getElementById('modalPagoVenta');
            if (!modalElement) {
                return;
            }
            const modal = new bootstrap.Modal(modalElement);

            window.addEventListener('show-modal-pago', () => {
                modal.show();
            });

            window.addEventListener('hide-modal-pago', () => {
                modal.hide();
            });
        });
    </script>
@endpush
