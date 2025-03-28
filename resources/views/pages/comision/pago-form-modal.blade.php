
<!-- Modal -->
<div class="modal fade" id="formPagoComision" tabindex="-1" role="dialog" aria-labelledby="formPagoComision" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formPagoComisionLabel">Pago de Comisión</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form onsubmit="saveAlertNoBack(event)" method="POST" action="" id="formComision">
                    @csrf
                    <div class="col-sm-12">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="comisionFecha" value="{{ date('d/m/Y') }}" disabled>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="mb-3">
                        <label for="montoComision">Comisión a Pagar</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="montoComision" value="" disabled>                            
                        </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="mb-3">
                        <label for="notas">Nota</label>
                        <div class="input-group">
                            <textarea class="form-control" name="notas" id="notas" value="" rows="2" placeholder="Ingrese una nota..."></textarea>                           
                        </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Pagar</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
