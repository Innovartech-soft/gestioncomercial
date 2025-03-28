@extends('layout.master')

@push('plugin-styles')

<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/pickr/themes/classic.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">{{isset($cheque)?'Editar Cheque':'Nuevo Cheque'}}</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="{{url('cheque/index')}}" class="menu-icon">
            <i class="mdi mdi-backburger"></i>
        </a>
    </div>
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title"></h6>
                <form id="formularioCheque" method="POST" action="{{isset($cheque)?'update':'store'}}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="banco_emisor" class="form-label required">Banco Emisor</label>
                                <input type="text" class="form-control" name="banco_emisor" id="banco_emisor"
                                    autocomplete="off" value="{{isset($cheque)?$cheque->banco_emisor:''}}"
                                    placeholder="Ingrese un banco emisor..." required>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_emision" class="form-label required">Fecha De Emisión</label>
                                <div class="input-group flatpickr" id="flatpickr-date">
                                    <input name="fecha_emision" type="date" class="form-control"
                                        placeholder="Seleccione una fecha de emision" data-input
                                        value="{{isset($cheque)?$cheque->fecha_emision:''}}" required>
                                    <span class="input-group-text input-group-addon" data-toggle><i
                                            data-feather="calendar"></i></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_pago" class="form-label">Fecha De Pago</label>
                                <div class="input-group flatpickr" id="flatpickr-date">
                                    <input name="fecha_pago" type="date" class="form-control"
                                        placeholder="Seleccione una fecha de pago" data-input
                                        value="{{isset($cheque)?$cheque->fecha_pago:''}}">
                                    <span class="input-group-text input-group-addon" data-toggle><i
                                            data-feather="calendar"></i></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="titular_librador" class="form-label">Titular Librador</label>
                                <input type="text" class="form-control" name="titular_librador" id="titular_librador"
                                    autocomplete="off" value="{{isset($cheque)?$cheque->titular_librador :''}}"
                                    placeholder="Ingrese un titular librador...">
                            </div>
                            <div class="mb-3">
                                <label for="nombre_beneficiario" class="form-label">Nombre Beneficiario</label>
                                <input type="text" class="form-control" name="nombre_beneficiario"
                                    id="nombre_beneficiario" autocomplete="off"
                                    value="{{isset($cheque)?$cheque->nombre_beneficiario:''}}"
                                    placeholder="Ingrese un nombre de beneficiario...">
                            </div>
                            <div class="mb-3">
                                <label for="id_cliente" class="form-label required">Cliente</label>
                                <select class="js-select2-cliente form-select" name="id_cliente" required id="id_cliente">
                                    <option value="" selected>Seleccione un cliente...</option>
                                    @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ isset($cheque) && $cheque->id_cliente ===
                                        $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->razon_social }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="serie" class="form-label">N° De Serie</label>
                                <input type="number" class="form-control" name="serie" id="serie" autocomplete="off"
                                    value="{{isset($cheque)?$cheque->serie:''}}"
                                    placeholder="Ingrese un numero de serie...">
                            </div>
                            <div class="mb-3">
                                <label for="numero" class="form-label required">Numero</label>
                                <input type="number" class="form-control" name="numero" id="numero" autocomplete="off"
                                    value="{{isset($cheque)?$cheque->numero:''}}" placeholder="Ingrese un numero..."
                                    required>
                            </div>


                            <div class="mb-3">
                                <label for="cruzado" class="form-label">Cruzado</label>
                                <select class="form-select" name="cruzado" id="cruzado">
                                    <option value="">Seleccione una opción...</option>
                                    <option value="1" {{isset($cheque) && $cheque->cruzado === '1' ? 'selected' :
                                        ''}}>Sí</option>
                                    <option value="0" {{isset($cheque) && $cheque->cruzado === '0' ? 'selected' :
                                        ''}}>No</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="endosado" class="form-label">Endosado</label>
                                <select class="form-select" name="endosado" id="endosado">
                                    <option value="">Seleccione una opción...</option>
                                    <option value="1" {{isset($cheque) && $cheque->endosado === '1' ? 'selected' :
                                        ''}}>Sí</option>
                                    <option value="0" {{isset($cheque) && $cheque->endosado === '0' ? 'selected' :
                                        ''}}>No</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="importe" class="form-label required">Importe</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="importe" id="importe"
                                        autocomplete="off" value="{{isset($cheque)?$cheque->importe:''}}"
                                        placeholder="Ingrese un importe..." required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" onclick="this.disabled=true; this.form.submit();"
                        class="btn btn-primary me-2">{{isset($cheque)?'Guardar':'Crear'}}</button>
                    <button type="button" class="btn btn-secondary" id="cancelButton">Cancelar</button>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection

@push('plugin-scripts')
<!-- Plugin js import here -->
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
<script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>
<script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@endpush

@push('custom-scripts')
<!-- Custom js here -->
<script src=""></script>
<script src="{{ asset('assets/js/inputmask.js') }}"></script>
<script src="{{ asset('assets/js/tags-input.js') }}"></script>
<script src="{{ asset('assets/js/flatpickr.js') }}"></script>
<script src="{{ asset('assets/js/select2-cliente.js') }}"></script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>
@endpush
