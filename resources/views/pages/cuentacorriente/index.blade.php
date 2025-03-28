@extends('layout.master')

@push('plugin-styles')
<!-- Plugin css import here -->
<!-- Enlaces a los estilos de DataTables -->

  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
<link href="{{url('css/datatable-style.css')}}" rel="stylesheet" />
<!-- ... Tu código HTML y Blade ... -->
<style type="text/css">
  select[readonly] {
    background-color: #ebedf0; /* Desactiva el fondo */
    cursor: not-allowed; /* Cambia el cursor a "no permitido" */
    pointer-events: none; /* Desactiva eventos de puntero */
}

</style>
@endpush

@section('content')
<!-- Page content here -->
@if(isset($cliente))
  @include('pages/recibo/form-min-modal')
@else
  @include('pages/recibo/form-modal')
@endif
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  @include('pages.mensajesflash.index')
  <div>
    <h4 class="mb-3 mb-md-0">Cuenta Corriente</h4>
  </div>
  <div class="d-flex align-items-center flex-wrap text-nowrap">
      @if(isset($cuentasCorrientes) && isset($cuentaCorriente->id_cliente))
          <button type="button" class="btn btn-success mr-3 mr-md-2" data-bs-toggle="modal" data-cliente-id="{{ $cuentaCorriente->id_cliente ?? null }}" data-bs-target="#formExportar" style="margin-right: 10px;">
              <i class="mdi mdi-file-excel mr-1"></i> Exportar
          </button>
      @endif
    <button type="button" class="btn btn-primary mr-3 mr-md-2" data-bs-toggle="modal" data-bs-target="#formCrearRecibo">
      <i class="mdi mdi-plus-circle mr-1"></i> Recibo
    </button>

  </div>

  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Listado de Cuenta Corriente</h6><br>
        <form action="{{route('cuentacorriente.indexByCliente')}}" method="POST" class="form-inline">
          @csrf
          @php
            $flagCliente = $cliente;
          @endphp
          <div class="row">

            <div class="col-sm-4">
              <div class="mb-3">
                <label class="form-label">Filtrar por Cliente </label>
                <select class="js-select2-cliente form-select" name="id_cliente" required id="cliente">
                  <option value="">Seleccione un Cliente...</option>
                  @foreach ($clientes as $cliente )

                  @if(isset($cuentaCorriente))
                  <option value="{{$cliente->id}}" {{$cuentaCorriente->
                    id_cliente==$cliente->id?'selected':''}}>{{$cliente->codigo.' '.$cliente->razon_social}}</option>
                  @else
                  <option value="{{$cliente->id}}">{{$cliente->codigo.' '.$cliente->razon_social}}</option>
                  @endif
                  @endforeach
                </select>
              </div>
            </div><!-- Col -->
            <div class="col-sm-5">
              <div class="mb-3">
              <label class="form-label">&nbsp;</label>
              <div class="input-group-btn">
                  <button class="btn btn-primary btn-sm" type="submit" id=""><i data-feather="search"></i></button>
                  <a href="{{url('cuentacorriente/index')}}" class="btn btn-success btn-sm" type="button" id=""><i
                      data-feather="refresh-ccw"></i></a>
              </div>
          </div>

            </div><!-- Col -->
            @if(isset($cuentaCorriente))
            <div class="col-sm-3">
              <div class="mb-3">
                <label class="form-label"></label>
                <div class="input-group-btn">
                  @if($cuentaCorriente->saldo>=0)
                  <span class="badge text-bg-success w-60 p-2 py-2">
                    <p style="font-size: 18px">CORRECTO</p>
                    <p style="font-size: 16px">$ {{round($cuentaCorriente->saldo,1)}}</p>
                  </span>
                  @else
                  <span class="badge text-bg-warning w-60 p-2 py-2">
                  <p style="font-size: 18px">DEUDOR</p>
                  <p style="font-size: 16px">$ {{round($cuentaCorriente->saldo,1)}}</p>
                  </span>
                  @endif

                </div>
              </div>

            </div><!-- Col -->
            @endif
          </div>

        </form>
        <div class="row">

          <p class="text-muted mb-3"> <code></code></p>

          <div class="row dt-row">
            <div class="table-responsive">
              @if(isset($flagCliente))
                <table id="dataTableCuentasCorrientes" class="table table-hover">
              @else
                <table id="cuentasCorrientes" class="table table-hover">
              @endif
              
                <thead>
                    <tr>
                        <th>#Recibo</th>
                        <th>Fecha</th>
                        <th>Operación</th>
                        <th>Debe</th>
                        <th>Haber</th>
                        {!! isset($cuentaCorriente) ? '<th>Saldo</th>' : '' !!}
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cuentasCorrientes as $cc)
                        @php
                            $recibo = $cc->recibo;
                            $venta = optional($recibo)->venta;
                            $tipoRecibo = optional($recibo)->tipoRecibo;
                            $tipoVenta = optional($venta)->tipoVenta;
                        @endphp
                        <tr {!! $venta && !$recibo->es_cobro
                            ? ($venta->pagada != 1 && $tipoVenta && $tipoVenta->id == 3 ? 'style="background-color: #bbf0c6 !important;"' : '')
                            : ''
                        !!}>
                            <td><a href="{{ route('recibo.show', $recibo->id )}}">{{ $recibo->id }}</a></td>
                            <td>{{ date('d/m/Y', strtotime($cc->fecha)) }}</td>
                            <td>
                                @if ($tipoRecibo)
                                    {{ $venta ? "$tipoRecibo->nombre - $tipoVenta->nombre [$venta->numero_venta]" : $tipoRecibo->nombre }}
                                @else
                                    {{ $venta ? "$tipoVenta->nombre [$venta->numero_venta]" : 'Sin venta asociada' }}
                                @endif
                                <br><small class="text-muted">{{ optional($cc->cliente)->razon_social }}</small>
                            </td>
                            <td>{{ $cc->monto >= 0 ? round($cc->monto, 1) : '-' }}</td>
                            <td>{{ $cc->monto < 0 ? round($cc->monto * -1, 1) : '-' }}</td>
                            {!! isset($cuentaCorriente) ? '<td>'.round($cc->saldo, 1).'</td>' : '' !!}
                            <td class="text-end">
                                @if ($venta && !$tipoRecibo)
                                    <a href="#" title="Ver Comprobante" type="button" class="btn btn-data btn-sm btnVerComprobante" data-bs-toggle="modal"
                                        data-bs-target="#detalleVenta" data-id-venta="{{ $venta->id }}"><i class="mdi mdi-receipt-text-outline"></i></a>
                                    @if ($tipoVenta && $tipoVenta->id == 3)
                                        <a href="#" title="Ver Pagos" type="button" class="btn btn-info btn-sm btnPagos" data-bs-toggle="modal"
                                            data-bs-target="#formPagos" data-id-venta="{{ $venta->id }}" data-total="{{ round($venta->total, 1) }}"><i class="mdi mdi-cash-clock"></i></a>
                                    @endif
                                @endif
                                <a href="{{ route('recibo.show', $recibo->id )}}" title="Ver Recibo" class="btn btn-primary btn-sm"><i class="mdi mdi-clipboard-text-search-outline"></i></a>
                                @if ($venta &&
                                    (($venta->pagada == 0 && optional($tipoVenta)->id == 3 && !$recibo->es_cobro) || in_array(optional($tipoVenta)->id, [4, 5])))
                                    <form onsubmit="eliminarAlert(event)" action="{{ route('venta.destroy', $venta->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="from_view" value="cuentacorriente">
                                        <button title="Eliminar" type="submit" class="btn btn-danger btn-sm" {{ $venta->pagada == 1 ? 'disabled' : '' }}><i class="mdi mdi-delete"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @if(!isset($flagCliente))
              {{ $cuentasCorrientes->links() }}
            @endif
          </div>
        </div>

      </div>

    </div>
  </div>
    @include('pages.cuentaCorriente.egresosexport')
@include('pages/venta/show-modal-venta')
@include('pages/recibo/index-pagos-modal')
@endsection



  @push('plugin-scripts')
  <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>

        <script src="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>

  <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>

  @endpush

  @push('custom-scripts')
  <script src="{{ asset('assets/js/inputmask.js') }}"></script>
  <script src="{{ asset('assets/js/tags-input.js') }}"></script>
  <script src="{{ asset('assets/js/flatpickr.js') }}"></script>
  <script src="{{ asset('assets/js/register-delete.js') }}"></script>
  <script src="{{ asset('assets/js/pagos-venta-script.js') }}"></script>
  <script src="{{ asset('assets/js/ver-venta-script.js') }}"></script>
  <script src="{{ asset('assets/js/data-table-cuentacorriente.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/js/recibo-script.js') }}"></script>
    <script src="{{ asset('assets/js/select2-cliente.js') }}"></script>
  <script type="text/javascript">
    $(document).ready(function() {//Establezco el valor de from_view en el modal de Recibo
      $('#from_view').val('cuentacorriente');
    });
  </script>

        <script>
            $(document).ready(function () {

                    // Document ready
                    var botonExportar = $('.btn[data-bs-target="#formExportar"]');
                    var clienteId = botonExportar.data('cliente-id');

                    // Actualiza el valor del campo de entrada oculto
                    $('#clienteIdInput').val(clienteId);

                    // Agrega un console.log para verificar
                    // console.log('Cliente ID actualizado:', clienteId);
                    // console.log('Nuevo valor del campo de entrada oculto:', $('#clienteIdInput').val());
            });
        </script>
  @endpush
