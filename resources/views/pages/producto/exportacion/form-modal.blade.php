<!-- Modal Crear Recibo-->

<!-- Modal -->
<div class="modal fade" id="formExportar" tabindex="-1" aria-labelledby="formExportarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="padding: 10px;">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="formExportarLabel">Exportacion Excel</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 stretch-card">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Seleccione los filtros para la exportación</h5>
                <form method="POST" id="formExport" action="{{ route('producto.exportarExcel') }}">
                  @csrf
                  <div class="row">
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="form-label">Marcas</label>
                        <select class="form-select" name="id_marca" id="id_marca">
                          <option value="">Seleccione...</option>
                          @foreach ($marcas as $marca)
                            @if(isset($marca))
                              <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                            @endif
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="form-label">Rubros</label>
                        <select class="form-select" name="id_rubro" id="id_rubro">
                          <option value="">Seleccione...</option>
                          @foreach ($rubros as $rubro)
                            @if(isset($rubro))
                              <option value="{{ $rubro->id }}">{{ $rubro->nombre }}</option>
                            @endif
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="form-label">Proveedores</label>
                        <select class="form-select" name="id_proveedor" id="id_proveedor">
                          <option value="">Seleccione...</option>
                          @foreach ($proveedores as $proveedor)
                            @if(isset($proveedor))
                              <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                            @endif
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="form-label">Listas</label>
                        <select class="form-select" name="id_lista" id="id_lista" required>
                          <option value="">Seleccione...</option>
                          @foreach ($listas as $lista)
                            @if(isset($lista))
                              <option value="{{ $lista->id }}">{{ $lista->nombre }}</option>
                            @endif
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="crearExportacion">Exportar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
