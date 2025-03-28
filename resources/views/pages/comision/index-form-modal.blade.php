
<!-- Modal -->
<div class="modal fade" id="formIndexComision" tabindex="-1" role="dialog" aria-labelledby="formIndexComision" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formIndexComisionLabel">Comisiones a Pagar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form  onSubmit="changeAlertNoBack(event)" method="POST" id="formComisionPago" action="{{route('comision.pagarComisiones')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-md-12 stretch-card">
                            <div class="card">
                                <div class="card-body">
                                <h5 class="card-title">{{ date('d/m/Y') }}</h5>
                                
                                <input type="hidden" id="id_vendedor_comision" name="id_vendedor" value="{{$vendedor->id}}">
                                <input type="hidden" id="idsComisiones" name="idsComisiones">
                                <input type="hidden" id="administrador" name="" value="{{Auth::user()->administrador}}">

                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label for="fecha_desde" class="form-label">Fecha Desde</label>
                                        <div class="input-group flatpickr" id="flatpickr-date">
                                        <input name="fecha_desde" id="fecha_desde" type="date" class="form-control"
                                            placeholder="Seleccione una fecha desde" data-input value=" {{date('d-m-Y')}}" required>
                                        <span class="input-group-text input-group-addon" data-toggle><i
                                            data-feather="calendar"></i></span>
                                        </div>
                                    </div>
                                
                                    <div class="col-sm-6 mb-3">
                                        <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                                        <div class="input-group flatpickr" id="flatpickr-date">
                                        <input name="fecha_hasta" id="fecha_hasta" type="date" class="form-control"
                                            placeholder="Seleccione una fecha hasta" data-input value=" {{date('d-m-Y')}}" required>
                                        <span class="input-group-text input-group-addon" data-toggle><i
                                            data-feather="calendar"></i></span>
                                        </div>
                                    </div>     
                                    <div class="col-sm-3 mb-3">
                                        <button type="button" class="btn btn-primary btnBuscarComisiones" name="btnBuscarComisiones">Buscar</button>
                                    </div>
                                    <div class="col align-self-start">
                                        <small class="mb-3" id="resultadoMensaje"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        </div>
                        <div class="row" id="rowListado" style="display:none">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                    <h5 class="card-title">Listado</h5>
                                        <div class="table-responsive">
                                            <table id="dataComisiones" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                        <th>Nº Venta</th>
                                                        <th>Fecha Cierre</th>
                                                        <th id="thGanancia">Ganancia Bruta</th>
                                                        <th>Monto</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="comisionesTBody">
                                                  
                                                    </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col align-self-end">
                                                <h5 class="text-end mb-3" style="margin-right: 2%;margin-top: 2%;" id="totalComisiones"></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="rowNotas" style="display:none">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Notas</h5>
                                        <textarea class="form-control" name="notas" id="notas"  rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btnPagar">Pagar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <div class="col-sm-12">
                            <small class="text-muted">* Al presionar Pagar, las Comisiones listadas pasarán a Pagadas.</small>
                        </div>
                    </div>
                
                </form>
                
            </div>
        </div>
</div>
