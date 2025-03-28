<!-- Modal Crear Recibo-->

<!-- Modal -->
<div class="modal fade" id="formCrearRecibo" tabindex="-1" aria-labelledby="formCrearRecibo" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="padding:10px">
      <div class="modal-header">
        <h1 class="modal-title fs-4" id="formCrearReciboLabel">Recibos</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 stretch-card">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">{{ date('d/m/Y') }}</h5>
                  <form method="POST" id="formRecibo" action="{{route(isset($recibo)?'recibo.update/'.$recibo->id:'recibo.store')}}">
                    @csrf

                    <input type="hidden" id="id_cliente_form" name="id_cliente" value="{{isset($cliente)?$cliente->id:''}}">
                    <input type="hidden" id="from_view" name="from_view" value="">
                    <input type="hidden" name="cheques_seleccionados" id="cheques_seleccionados" value="">

                    <div class="row">
                      <div class="col-sm-12">
                        <div class="mb-3">
                          <label class="form-label">Tipo de Recibo</label>
                          <select class="form-select" name="id_tipo_recibo" id="id_tipo_recibo" required readonly>
                              @foreach ($tiposRecibos as $tipo )
                                <option value="{{$tipo->id}}" {{$tipo->nombre=='Cobro'?'selected':''}}>{{$tipo->nombre}} [{{$tipo->activo_pasivo?'ingreso':'egreso'}}]</option>
                              @endforeach
                            </select>
                        </div>
                      </div><!-- Col -->
                    </div><!-- Row -->
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="mb-4">
                          <label for="id_cliente" class="form-label">Cliente</label>
                          <select class="form-select form-control" name="id_cliente" id="id_cliente" required disabled readonly>
                              <option value="">Seleccione...</option>
                              @foreach ($clientes as $cli )
                                  <option id='cliente{{$cli->id}}' {{$cliente->id==$cli->id?"selected":""}} value="{{$cli->id}}">{{$cli->codigo.' '.$cli->razon_social}}</option>

                              @endforeach
                            </select>
                        </div>
                      </div><!-- Col -->
                      
                      <div class="col-sm-12">
                        <div class="mb-4">
                          <label class="form-label">Venta</label>
                          <select class="form-select" name="id_venta" id="id_venta">
                              <option value="">Seleccione...</option>
                            </select>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="mb-4" id="div_cliente_saldo" style="display:none">
                          <label class="form-label">Saldo Cuenta</label>
                          <p id="cliente_saldo"></p>
                        </div>
                      </div>
                    </div><!-- Row -->
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="mb-3">
                          <label for="detalle" class="form-label">Detalle</label>
                          <textarea class="form-control" name="detalle" id="detalle" rows="3" placeholder="Ingrese un detalle...">{{(isset($recibo)?$recibo->detalle:"")}}</textarea>
                      </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="mb-3 text-center">
                          <a class="btn btn-primary w-100" id="toggleBtn">
                            <i class="mdi mdi-arrow-right"></i>
                            <i class="mdi mdi-currency-usd mr-1"></i>Ingrese el Pago
                            <i class="mdi mdi-arrow-left"></i>
                          </a>
                        </div>
                      </div>
                    </div>
                    @include('pages/metodopago/form-modal')
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="mb-3">
                          <label class="form-label"><h6>Monto Total</h6></label>
                          <input type="numeric" name="monto" class="form-control" id="monto" max="99999999" placeholder="$" readonly required>
                        </div>
                      </div>
                    </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="crearRecibo" onclick="this.disabled=true; this.form.submit();">Crear</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>

      </form>
    </div>
  </div>
</div>


