
<!-- Modal -->
<div class="modal fade" id="formGestionStock" tabindex="-1" role="dialog" aria-labelledby="formGestionStock" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formGestionStockLabel">Editar Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="" id="formStock">
                    @csrf
                    <input type="hidden" name="cantidad" id="cantidad" value="0">
                    <div class="col-sm-12">
                        <div class="mb-3">
                        <label for="stockInput">Stock Ingresante:</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="stockInput" placeholder="Ingrese stock...">
                            <div class="input-group-append">
                                <button class="btn btn-outline-success" type="button" id="addStockButton"><i class="mdi mdi-arrow-down-bold"></i></button>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="mb-3">
                            <label for="totalStock">Total Stock:</label>
                            <input type="number" class="form-control" id="totalStock" name="totalStock" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-success historico-stock" href="">
                        <i class="mdi mdi-history"></i>Exportar Historial
                    </a>
                  <button type="submit" class="btn btn-primary">Guardar</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
