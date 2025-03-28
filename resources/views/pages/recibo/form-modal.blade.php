<!-- Modal Crear Recibo-->
        
<!-- Modal -->
<div class="modal fade" id="formCrearRecibo" tabindex="-1" aria-labelledby="formCrearRecibo" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="padding:10px">
      <div class="modal-header">
        <h1 class="modal-title fs-4" id="formCrearReciboLabel">Recibo</h1>
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
                   
                    <input type="hidden" name="id_cliente" value="">
                    <input type="hidden" id="from_view" name="from_view" value="">
                    <input type="hidden" name="cheques_seleccionados" id="cheques_seleccionados" value="">

                    <div class="row">
                      <div class="col-sm-12">
                        <div class="mb-3">
                          <label class="form-label required">Tipo de Recibo</label>
                          <select class="form-select" name="id_tipo_recibo" id="id_tipo_recibo" required>
                              <option value="">Seleccione...</option>
                              @foreach ($tiposRecibos as $tipo )
                                @if(isset($recibo))
                                  <option {{$recibo->tipoRecibo->id==$tipo->id?"selected":""}} value="{{$tipo->id}}">{{$tipo->nombre}} [{{$tipo->activo_pasivo?'ingreso':'egreso'}}]</option>
                                @else
                                  <option value="{{$tipo->id}}">{{$tipo->nombre}} [{{$tipo->activo_pasivo?'ingreso':'egreso'}}]</option>
                                @endif
                              @endforeach
                            </select>
                        </div>
                      </div><!-- Col -->
                    </div><!-- Row -->
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="mb-4">
                          <label class="form-label required">Cliente</label>
                          <select class="form-select" name="id_cliente" id="id_cliente" required disabled>
                              <option value="">Seleccione...</option>
                              @foreach ($clientes as $cliente )
                                @if(isset($recibo))
                                  <option {{$recibo->cliente->id==$cliente->id?"selected":""}} value="{{$cliente->id}}">{{$cliente->codigo.' '.$cliente->razon_social}}</option>
                                @else
                                  <option value="{{$cliente->id}}">{{$cliente->codigo.' '.$cliente->razon_social}}</option>
                                @endif
                              @endforeach
                            </select>
                        </div>
                      </div><!-- Col -->
                      <div class="col-sm-6">
                        <div class="mb-4">
                          <label class="form-label required">Proveedor</label>
                          <select class="form-select" name="id_proveedor" id="id_proveedor" required disabled>
                              <option value="">Seleccione...</option>
                              @foreach ($proveedores as $proveedor )
                                @if(isset($recibo))
                                  <option {{$recibo->proveedor->id==$proveedor->id?"selected":""}} value="{{$proveedor->id}}">{{$proveedor->nombre}}</option>
                                @else
                                  <option value="{{$proveedor->id}}">{{$proveedor->nombre}}</option>
                                @endif
                              @endforeach
                            </select>
                        </div>
                      </div>
                      <div class="col-sm-8" id="div_venta">
                        <div class="mb-4">
                          <label class="form-label">Venta</label>
                          <select class="form-select" name="id_venta" id="id_venta" disabled>
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
                          <label class="form-label required"><h6>Monto Total</h6></label>
                          <input type="numeric" name="monto" class="form-control" id="monto" max="99999999" placeholder="$" readonly required>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-6" id="div_afectar_caja" style="display:none">
                          <div class="form-check form-switch">
                          <label class="form-label"><h6>No afectar caja</h6></label>
                          <input type="checkbox" class="form-check-input" name="afectar_caja">
                        </div>
                      </div>
                    </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="crearRecibo" >Crear</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
      
      </form>
    </div>
  </div>
</div>