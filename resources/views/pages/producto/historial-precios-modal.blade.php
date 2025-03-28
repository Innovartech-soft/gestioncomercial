<!-- Modal -->
<div class="modal fade" id="formHistorial" tabindex="-1" role="dialog" aria-labelledby="formHistorial" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-4" id="formHistorialLabel">Historial de Precios</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="col-sm-12 mb-3">
                        <div class="mb-3">
                            <div id="content" >
                              <ul class="timeline" id="precioFila" style="display: none"> 
                               
                              </ul>
                            </div>
                        </div>
                    </div>                        
                        <div class="col-sm-12">                            
                           <div class="row" id="divVerPrecios" style="display: flex">
                                <div class="col-sm-1"></div>
                                   <div class="col-sm-10">
                                        <div class="row form-inline mb-3">
                                            <div class="form-group col-sm-5">
                                                <label for="precioActual"><b>Precio Actual s/IVA:</b></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                    <input type="number" class="form-control" id="precioActual" name="precioActual" readonly disabled >
                                                </div>
                                            </div>
                                        </div>
                                       
                                    </div>

                                <div class="col-sm-1"></div>
                            </div>

                        </div>
                    <div class="row">
                        
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
