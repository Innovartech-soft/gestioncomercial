<!-- Modal Crear Recibo-->
@push('plugin-styles')

    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/pickr/themes/classic.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush
<!-- Modal -->
<div class="modal fade" id="dataTableExportar" tabindex="-1" aria-labelledby="formExportarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="padding: 10px;">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="formExportarLabel">Exportacion Excel</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Seleccione las fechas para la exportación</h6>
                                <h5 class="card-title">{{ date('d/m/Y') }}</h5>
                                <form method="POST" id="dataTableExportar" action="{{route('cajadiaria.exportarExcel')}}" >
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="mb-6">
                                                <label for="fecha_desde" class="form-label">Fecha Desde</label>
                                                <div class="input-group flatpickr" id="flatpickr-date">
                                                    <input name="fecha_desde" type="date" class="form-control"
                                                           placeholder="Seleccione una fecha desde" data-input value="" required>
                                                    <span class="input-group-text input-group-addon" data-toggle><i
                                                            data-feather="calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="mb-6">
                                                <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                                                <div class="input-group flatpickr" id="flatpickr-date">
                                                    <input name="fecha_hasta" type="date" class="form-control"
                                                           placeholder="Seleccione una fecha hasta" data-input value="" required>
                                                    <span class="input-group-text input-group-addon" data-toggle><i
                                                            data-feather="calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                        <button type="submit" class="btn btn-primary" id="crearExportacion">Exportar</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

