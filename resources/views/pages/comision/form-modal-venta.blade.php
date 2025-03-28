<!-- Modal -->
<div class="modal fade" id="formCerrarVenta" tabindex="-1" role="dialog" aria-labelledby="formCerrarVenta"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formCerrarVentaLabel">Cierre de Venta y Comisión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('comision.store')}}" id="cerrarVenta"
                    onsubmit="saveAlertNoBack(event)">
                    @csrf
                    <input type="hidden" name="id_vendedor" id="id_vendedor" />
                    <input type="hidden" name="id_venta" id="id_venta" />
                    <div class="col-sm-12 ">
                        <div class="row form-inline mb-3">
                            <div class="col-sm-4">
                                <label for="id_venta" class="form-label">Nº Venta</label>
                                <input type="text" class="form-control" id="ventaNumero" name="id_venta" value=""
                                    readonly>
                            </div>
                            <div class="col-sm-4">
                                <label for="fecha" class="form-label">Fecha</label>
                                <input type="text" class="form-control" id="ventaFecha" name="ventaFecha" value=""
                                    readonly>
                            </div>

                            <div class="col-sm-4">
                                <label class="form-label">Estado</label>
                                <span id="ventaEstado" class="form-control btn-lg text-white bg-success">ABIERTA</span>
                            </div>
                        </div>
                        <div class="row form-inline mb-3">
                            <div class="col-sm-12">
                                <label for="nombreVendedor" class="form-label">Vendedor</label>
                                <input type="text" class="form-control" id="ventaVendedor" name="nombreVendedor"
                                    value="" disabled>
                            </div>
                        </div>
                        <div class="row form-inline mb-3">
                            <div class="col-sm-4">
                                <label for="montoTotal" class="form-label">Monto Total</label>
                                <input type="text" class="form-control" id="ventaTotal" name="montoTotal" value=""
                                    disabled>
                            </div>
                            
                        </div>

                    </div>
                    <div class="col-sm-12">
                        <small class="text-muted">* Al presionar Guardar, la Venta pasará a Cerrada y se asignará la
                            comisión al Vendedor</small>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" onclick="this.disabled=true; this.form.submit();"
                    class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
            </form>
        </div>
    </div>
</div>
</div>