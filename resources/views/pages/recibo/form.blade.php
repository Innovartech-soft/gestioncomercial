@extends('layout.master')

@push('plugin-styles')
  <!-- Plugin css import here -->
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/pickr/themes/classic.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/@mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
@endpush

@section('content')
<div class="row">
  <div class="col-md-3"></div>
  <div class="col-md-6">
  <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
      <div>
          <h4 class="mb-3 mb-md-0">Recibo</h4>
      </div>
      <div class="d-flex align-items-center flex-wrap text-nowrap">
          <a href="{{url('recibo/index')}}" class="menu-icon">
              <i class="mdi mdi-backburger"></i>
          </a>
      </div>
  </div>
  </div>
  <div class="col-md-3"></div>
</div>
<div class="d-flex justify-content-center align-items-center">
<div class="col-md-6 grid-margin">
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ date('d/m/Y', strtotime($recibo->created_at)) }}</h5>
            <fieldset {{isset($show)?'disabled':''}}>
                <form method="POST" action="{{isset($recibo)?'update/'.$recibo->id:'store'}}">
                    @csrf
                    <input type="hidden" name="id_venta" value="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Recibo</label>
                                <select class="form-select" name="id_tipo_recibo" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($tiposRecibos as $tipo )
                                    @if(isset($recibo))
                                    <option id="id_tipo_recibo" {{isset($recibo->tipoRecibo)?($recibo->tipoRecibo->id==$tipo->id?"selected":""):""}} value="{{$tipo->id}}">{{$tipo->nombre}} [{{$tipo->activo_pasivo?'ingreso':'egreso'}}]</option>
                                    @else
                                    <option id="id_tipo_recibo" value="{{$tipo->id}}">{{$tipo->nombre}} [{{$tipo->activo_pasivo?'ingreso':'egreso'}}]</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @if($recibo->cliente)
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label">Cliente</label>
                                <select class="js-select2-cliente form-select" name="id_cliente" id="id_cliente" {{isset($recibo)?'disabled':''}}>
                                    <option value="">Seleccione...</option>
                                    @foreach ($clientes as $cliente )
                                    @if(isset($recibo->cliente))
                                    <option id="id_cliente{{$cliente->id}}" {{$recibo->cliente->id==$cliente->id?"selected":""}} value="{{$cliente->id}}">{{$cliente->codigo.' '.$cliente->razon_social}}</option>
                                    @else
                                    <option id="id_cliente{{$cliente->id}}" value="{{$cliente->id}}">{{$cliente->codigo.' '.$cliente->razon_social}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        @if($recibo->proveedor)
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label">Proveedor</label>
                                <select class="js-select2-proveedor form-select" name="id_proveedor" id='id_proveedor'>
                                    <option value="">Seleccione...</option>
                                    @foreach ($proveedores as $proveedor )
                                    @if(isset($recibo->proveedor))
                                    <option id="id_proveedor{{$proveedor->id}}" {{$recibo->proveedor->id==$proveedor->id?"selected":""}} value="{{$proveedor->id}}">{{$proveedor->nombre}}</option>
                                    @else
                                    <option id="id_proveedor{{$proveedor->id}}" value="{{$proveedor->id}}">{{$proveedor->nombre}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                    </div>
                    @if($recibo->venta)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label">{{$recibo->venta->tipoVenta->nombre}}</label>
                                <input type="text" name="id_venta" class="form-control" value="{{$recibo->venta->numero_venta}}">
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="detalle" class="form-label">Detalle</label>
                                <textarea class="form-control" name="detalle" id="detalle" rows="3"
                                    placeholder="Ingrese un detalle...">{{(isset($recibo)?$recibo->detalle:"")}}</textarea>
                            </div>
                        </div>
                    </div>
                    @if(!isset($recibo))
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3 text-center">
                                <a class="btn btn-primary w-100" id="toggleBtn">
                                    <i class="mdi mdi-currency-usd mr-1"></i>Ingrese el Pago
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                    @include('pages/metodopago/form-modal')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><h6>Monto Total</h6></label>
                                <input type="numeric" name="monto" class="form-control" id="monto" placeholder="$"
                                    readonly required value="{{isset($recibo)?'$'.round($recibo->monto,1):''}}">
                            </div>
                        </div>
                    </div>
                  </div>
                </form>
            </fieldset>
        </div>
       
        <div class="d-flex justify-content-end">
             @if(!($recibo->venta&&$recibo->es_cobro==0))
                @if($recibo->venta)
                    @if(!(optional($recibo->venta)->tipoVenta->id==4||optional($recibo->venta)->tipoVenta->id==5))
                    <form onsubmit="eliminarAlert(event)" action="{{ route('recibo.destroy',$recibo->id) }}"
                        method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"><i class="mdi mdi-delete"></i> Eliminar</button>
                    </form>
                    @else
                    <small>Este es el comprobante de emisión de una Nota de Credito / Debito - Para eliminarlo, elimine la operacion asociada [{{optional($recibo->venta)->numero_venta}}]</small>
                    @endif
                @else
                    <form onsubmit="eliminarAlert(event)" action="{{ route('recibo.destroy',$recibo->id) }}"
                        method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"><i class="mdi mdi-delete"></i> Eliminar</button>
                    </form>
                @endif
            @else
                <small>Este es el comprobante de emisión de una venta - Para eliminarlo, elimine la venta asociada [{{optional($recibo->venta)->numero_venta}}]</small>
            @endif
        </div>
       
    </div>
</div>
@endsection

@push('plugin-scripts')
<!-- Plugin js import here -->
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>

@endpush

@push('custom-scripts')
<!-- Custom js here -->
<script src="{{ asset('assets/js/inputmask.js') }}"></script>
<script src="{{ asset('assets/js/tags-input.js') }}"></script>
  <script src="{{ asset('assets/js/register-delete.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>
 <script src="{{ asset('assets/js/select2-proveedor.js') }}"></script>
  <script src="{{ asset('assets/js/select2-cliente.js') }}"></script>

<script>
    $(document).ready(function () {
        $("#divPago").show();

        $('#btnBuscarCheque').click(function () {
            $('#modalListadoCheques').modal('show');
        });
    });
</script>
@endpush
