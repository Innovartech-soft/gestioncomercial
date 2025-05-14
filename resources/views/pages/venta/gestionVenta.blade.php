@extends('layout.master')

@push('plugin-styles')
<!-- Plugin css import here -->
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/@mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
@endpush


@section('content')
<div id="venta-app">
    <div class="row mb-lg-2">
        <div class="card text-white blueGrayBG col-12 col-lg-6">
            <div class="card-body col-12">
                <div class="row">
                    <div class="col-6">
                        <h6 class="card-title">Tipo de Comprobante</h6>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <h5 class="card-title">{{ $todayDate }}</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <select class="form-select" id="tipoComprobante">
                            <option value="">Seleccione...</option>
                            @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        {{-- Aquí puedes agregar botones de acción --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="card text-white blueGrayBG col-12 col-lg-6">
            <div class="card-body col-12">
                <h6 class="card-title">Vendedor</h6>
                <div class="row col-12">
                    <div class="col-6">
                        <select class="form-select" id="vendedor">
                            <option value="">Seleccione...</option>
                            @foreach($vendedores as $vendedor)
                            <option value="{{ $vendedor->id }}">{{ $vendedor->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <button type="button" class="btn btn-success btn-icon" id="nuevaOperacion">
                            <i class="feather icon-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Continúa con el resto de la vista, usando los datos de Blade --}}
</div>
@endsection

@push('plugin-scripts')
<!-- Plugin js import here -->
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
<script>
    // Aquí puedes agregar la lógica JS para manejar los eventos y la lógica dinámica
    // Puedes usar jQuery o vanilla JS para manejar selects, inputs, etc.
</script>
@endpush