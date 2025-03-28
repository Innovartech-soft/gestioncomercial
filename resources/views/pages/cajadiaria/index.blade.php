@extends('layout.master')

@push('plugin-styles')
    <!-- Plugin css import here -->
    <link href="{{ asset('assets/plugins/@mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />

    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush


@section('content')

    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Detalle Historico De Caja</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <button type="button" class="btn btn-success mr-3 mr-md-2" data-bs-toggle="modal" data-bs-target="#formExportar"
                    style="margin-right: 10px;">
                <i class="mdi mdi-file-excel mr-1"></i> Exportar
            </button>
        </div>
    </div>
    <div class="row">
        @include('pages.mensajesflash.index')
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title"></h6>
                    {{-- <p class="text-muted mb-3">Read the <a href="https://datatables.net/" target="_blank"> Official DataTables
                        Documentation </a>for a full list of instructions and other options.</p> --}}
                    <div class="table-responsive">
                        <table id="dataTableCajaDiaria" class="table table-hover">
                            <thead>
                            <tr>
                                <th style="display:none">#ID</th>
                                <th>Fecha</th>
                                <th>Caja Apertura</th>
                                <th>Caja Actual</th>
                                <th>Caja Cierre</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($cajaDiarias as $cajaDiaria)
                                <tr>
                                    <td style="display:none">{{$cajaDiaria->id}}</td>
                                    <td>{{ date('d/m/Y', strtotime($cajaDiaria->fecha)) }}</td>
                                    <td>{{is_null($cajaDiaria->caja_apertura)?'' :'$'. $cajaDiaria->caja_apertura  }}</td>
                                    <td>{{is_null($cajaDiaria->caja_actual) ? '' :'$'.$cajaDiaria->caja_actual }}</td>
                                    <td>{{is_null($cajaDiaria->caja_cierre) ? '' :'$'. $cajaDiaria->caja_cierre }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('pages.recibo.egresosexport')

    {{--    @include('pages/cajadiaria/exportacion/form-modal')--}}
@endsection


@push('plugin-scripts')
    <!-- Plugin js import here -->
    <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>

@endpush

@push('custom-scripts')
    <!-- Custom js here -->
{{--    <script>--}}
{{--        // Este script se encargará de abrir el modal cuando hagas clic en el botón--}}
{{--        $(document).ready(function () {--}}
{{--            $('#dataTableExportar').modal('hide'); // Esto asegura que el modal esté oculto inicialmente--}}
{{--            $('button.btn-success').click(function () {--}}
{{--                $('#dataTableExportar').modal('show'); // Muestra el modal cuando se hace clic en el botón--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}
    <script src="{{ asset('assets/js/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/js/data-table-caja-diaria.js') }}"></script>
{{--    <script src="{{ asset('assets/js/usuario-delete.js') }}"></script>--}}
@endpush
