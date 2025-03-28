<!-- Contenido de la modal -->
<div class="modal fade" id="detalleVenta" tabindex="-1" role="dialog" aria-labelledby="detalleVentaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <!-- Encabezado de la modal -->
            <div class="modal-header">
                <h4 class="modal-title">Comprobante</h4>
            </div>

            <!-- Cuerpo de la modal con el contenido de la vista -->
            <div class="modal-body">
                @include('pages/venta/show-pdf') <!-- Ajusta la ruta según tu estructura -->
            </div>

            <!-- Pie de la modal -->
            <div class="modal-footer">
                <form action="{{ route('venta.exportarventa') }}" method="POST"
                    style="display: inline-block;">
                    @csrf
                    <input type="hidden" name="id_venta" value="">
                    <button type="submit" class="btn btn-success btn-sm"><i class="mdi mdi-file-excel mr-1" title="Exportar"></i>Exportar</button>
                  </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>