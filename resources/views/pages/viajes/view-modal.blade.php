<!-- Modal Detalles del Viaje -->
<div class="modal fade" id="modalDetallesViaje" tabindex="-1" aria-labelledby="modalDetallesViajeLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalles del Viaje #<span id="viajeNumero"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">

        <div id="contenidoViaje">
        <div class="mb-3">
          <strong>Repartidor:</strong> <span id="viajeRepartidor"></span><br>
          <strong>Fecha de creación:</strong> <span id="viajeFecha"></span>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered" id="tablaDetalleViaje" style="font-size: 12px;">
            <thead>
              <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>#Boleta</th>
                <th>Importe</th>
                <th>Monto</th>
                <th>Saldo</th>
              </tr>
            </thead>
            <tbody>
              {{-- Se cargan dinámicamente por JS --}}
            </tbody>
          </table>
        </div>

      </div>
      </div>
      <div class="modal-footer">
        <button onclick="imprimirDiv('contenidoViaje')" class="btn btn-primary">Imprimir</button>

        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
