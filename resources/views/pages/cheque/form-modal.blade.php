<!-- Modal de Listado de Cheques -->
<div class="modal fade" id="modalListadoCheques" tabindex="-1" role="dialog" aria-labelledby="modalListadoChequesLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <!-- Contenido del listado de cheques -->
      <div class="modal-header">
        <h5 class="modal-title" id="modalListadoChequesLabel">Cheques</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="col-md-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              @csrf
              <div class="row">
                <div class="col-sm-6">
                  <div class="row">
                    <div class="mb-3 col-sm-4">
                      <label for="serie" class="form-label">N° De Serie</label>
                      <input type="number" class="form-control" name="serie" id="serie" autocomplete="off" value="" readonly>
                    </div>
                    <div class="mb-3 col-sm-8">
                      <label for="numero" class="form-label">Numero</label>
                      <input type="number" class="form-control" name="numero" id="numero" autocomplete="off" readonly>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="mb-3">
                    <label for="banco_emisor" class="form-label">Banco</label>
                    <input type="text" class="form-control" name="banco_emisor" id="banco_emisor" autocomplete="off" readonly>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="mb-3">
                    <label for="titular_librador" class="form-label">Titular Librador</label>
                    <input type="text" class="form-control" name="titular_librador" id="titular_librador" autocomplete="off" readonly>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="mb-3">
                    <label for="fecha_emision" class="form-label">Fecha De Emisión</label>
                    <div class="input-group flatpickr" id="flatpickr-date">
                      <input name="fecha_emision" id="fecha_emision" type="date" class="form-control" readonly>
                      <span class="input-group-text input-group-addon" data-toggle><i data-feather="calendar"></i></span>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="mb-3">
                    <label for="id_cliente_cheque" class="form-label">Cliente</label>
                    <select class="form-select" name="id_cliente_cheque" id="id_cliente_cheque" readonly>
                      <option value="">-</option>
                      @foreach($clientes as $cliente)
                      <option value="{{ $cliente->id }}">
                        {{ $cliente->codigo.' '.$cliente->razon_social }}
                      </option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="mb-3">
                    <label for="importe" class="form-label">Importe</label>
                    <div class="input-group">
                      <span class="input-group-text">$</span>
                      <input type="number" class="form-control" name="importe" id="importe" autocomplete="off" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center" >
            <h5 class="modal-title" >Listado</h5>
              <a name='crearCheque' href="{{route('cheque.create')}}" target="_blank" type="button" class="btn btn-success"><i class="mdi mdi-plus-circle mr-1"></i></a>
            </div>
            <div class="card-body">
              <div class="table-responsive">
        <table id="dataTablecheques" class="table table-hover">
                  <thead>
                    <tr>
                      <th>Número</th>
                      <th>Cliente</th>
                      <th>Fecha Pago</th>
                      <th>Fecha Emisión</th>
                      <th>Importe</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if(isset($recibo))
                      @foreach ($cheques as $cheque)
                        @if($cheque->id_recibo == $recibo->id)
                          <tr {{ $cheque->estado == 0 ? "class=fila-color-rojo" : '' }}>
                            <td>{{ $cheque->serie.' - '.$cheque->numero }}</td>
                            <td>{{ ($cheque->cliente?$cheque->cliente->razon_social:'')}}</td>
                            <td>{{ date('d/m/Y', strtotime($cheque->fecha_pago)) }}</td>
                            <td>{{ date('d/m/Y', strtotime($cheque->fecha_emision)) }}</td>
                            <td>$ {{ $cheque->importe }}</vtd>
                            <td class="text-end">   
                              <a href="#" class="btn btn-primary btn-sm btnVerDetalle" data-id-cheque="{{ $cheque->id }}"><i
                                  class="mdi mdi-eye-outline"></i></a>
                            </td>
                          </tr>
                        @endif
                      @endforeach
                    @else
                      @foreach ($chequesDisponibles as $cheque)
                      <tr {{ $cheque->estado == 0 ? "class=fila-color-rojo" : '' }}>
                        <td>{{ $cheque->serie.' - '.$cheque->numero }}</td>
                        <td>{{ ($cheque->cliente?$cliente->codigo.' '.$cheque->cliente->razon_social:'')}}</td>
                        <td>{{ date('d/m/Y', strtotime($cheque->fecha_pago)) }}</td>
                        <td>{{ date('d/m/Y', strtotime($cheque->fecha_emision)) }}</td>
                        <td>$ {{ $cheque->importe }}</td>
                        <td class="text-end">
                          @if($cheque->estado == 1)
                          <a href="#" class="btn btn-success btn-sm sumar-cheque" data-monto="{{ $cheque->importe }}" data-id-cheque="{{ $cheque->id }}" data-id-cliente="{{ ($cheque->cliente?$cheque->cliente->id:'')}}"  data-id-recibo="{{ ($cheque->recibo?$cheque->id_recibo:'')}}"> <i
                              class="mdi mdi-clipboard-check"></i></a>
                          <a href="#" class="btn btn-danger btn-sm cancelar-cheque" data-monto="{{ $cheque->importe }}" data-id-cheque="{{ $cheque->id }}" data-id-cliente="{{ ($cheque->cliente?$cheque->cliente->id:'')}}" style="display:none"><i
                              class="mdi mdi-cancel"></i></a>    
                          <a href="#" class="btn btn-primary btn-sm btnVerDetalle" data-id-cheque="{{ $cheque->id }}"><i
                              class="mdi mdi-eye-outline"></i></a>
                          @endif
                        </td>
                      </tr>
                      @endforeach
                    @endif

                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <!-- Puedes agregar botones adicionales o acciones según tus necesidades -->
      </div>
    </div>
  </div>
</div>

@push('custom-scripts')
<script src="{{ asset('assets/js/data-table-cheques.js') }}"></script>
<script src="{{ asset('assets/js/cheque-script.js') }}"></script>
@endpush