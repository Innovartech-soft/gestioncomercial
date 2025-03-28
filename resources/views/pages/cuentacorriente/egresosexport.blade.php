<!-- Modal Crear Recibo-->

<!-- Modal -->
<div class="modal fade" id="formExportar" tabindex="-1" aria-labelledby="formExportar" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="padding:10px">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="formExportar">Exportar Gastos y Pagos</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 stretch-card">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">{{ date('d/m/Y') }}</h5>
                <form method="POST" id="formExport" action="{{route('cuentacorriente.exportarExcel')}}">
                  @csrf
                    <input type="hidden" name="cliente_id" id="clienteIdInput" value="0">

                    <div class="row">
                    <div class="col-sm-12">
                      <div class="mb-3">
                        <label for="fecha_desde" class="form-label">Fecha Desde</label>
                        <div class="input-group flatpickr" id="flatpickr-date">
                          <input name="fecha_desde" type="date" class="form-control"
                            placeholder="Seleccione una fecha desde" data-input value="" required>
                          <span class="input-group-text input-group-addon" data-toggle><i
                              data-feather="calendar"></i></span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="mb-3">
                        <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                        <div class="input-group flatpickr" id="flatpickr-date">
                          <input name="fecha_hasta" type="date" class="form-control"
                            placeholder="Seleccione una fecha hasta" data-input value="" required>
                          <span class="input-group-text input-group-addon" data-toggle><i
                              data-feather="calendar"></i></span>
                        </div>
                      </div>
                    </div>

                  </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="formExport">Exportar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>

