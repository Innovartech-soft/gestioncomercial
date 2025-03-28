<!-- Modal -->
<div class="modal fade" id="formCajaDiaria" tabindex="-1" role="dialog" aria-labelledby="formCajaDiaria" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-4" id="formCajaDiariaLabel">Caja Diaria</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="" id="formCaja">
                    @csrf
                    <div class="col-sm-12 mb-3 timeLineCaja">
                        @if(count($recibosHoy)>0)
                        <div class="mb-3">
                            <div id="content ">
                              <ul class="timeline">
                                @foreach($recibosHoy as $recibo)
                                <li class="event" data-date="{{ date('H:i:s', strtotime($recibo->created_at)) }}">
                                  <h3 class="title">{{ isset($recibo->tipoRecibo)
                          ? $recibo->tipoRecibo->nombre
                          : ($recibo->venta
                              ? $recibo->venta->tipoVenta->nombre.' N°'.$recibo->venta->numero_venta
                              : 'Sin venta asociada')
                          }}</h3>
                                  <p><a href="{{ route('recibo.show',$recibo->id )}}">#{{$recibo->id}}</a> {{($recibo->es_cobro==1?'Ingreso de $'.$recibo->monto:'Egreso de $'.$recibo->monto)}}</p>
                                </li>
                                @endforeach
                              </ul>
                            </div>
                        </div>
                        @endif
                    </div>
                        
                        <div class="col-sm-12">
                            <div class="row" id="divAbrirCaja" style="display: {{$estadoCaja?'none':'flex'}}">
                                <div class="col-sm-1"></div>
                                <div class="col-sm-10">
                                    <label for="form-label totalEfectivo"><b>Ingrese un monto:</b></label>
                                    <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                      </div>
                                      <input type="number" class="form-control" id="abrirCajaMonto" name="abrirCajaMonto" />
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="button" class="btn btn-success w-100" id="abrirCaja" name="abrirCaja" value="Abrir Caja">
                                    </div>
                                </div>
                                <div class="col-sm-1"></div>
                            </div>
                            <div class="row" id="divCerrarCaja" style="display: {{$estadoCaja?'flex':'none'}}">
                                <div class="col-sm-1"></div>
                                   <div class="col-sm-10">
                                        <div class="row form-inline mb-3">
                                            <div class="form-group col-sm-6">
                                                <label for="totalCaja"><b>Total Caja:</b></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                    <input type="number" class="form-control" id="totalCaja" name="totalCaja"  value="{{$totalCajaHoy}}" readonly disabled >
                                                </div>
                                            </div>

                                            <div class="form-group col-sm-6" >
                                                <label for="totalEfectivo"><b>Total Efectivo:</b></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                    <input type="number" class="form-control" id="totalEfectivo" name="totalEfectivo" value="{{$totalCajaHoyEfectivo}}" readonly disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="button" class="btn btn-danger w-100" id="cerrarCaja" name="cerrarCaja" value="Cerrar Caja">
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
                </form>
            </div>
        </div>
    </div>
</div>
