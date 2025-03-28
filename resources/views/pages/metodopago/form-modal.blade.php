
<div class="row" id="divPago" style="display: none;">
<div class="col-md-12 grid-margin stretch-card">
          <div class="card">
          <div class="card-body">
    <h6 class="card-title">Metodos de Pago</h6>
    @foreach($metodosPago as $metodoPago)
    <div class="row mb-4">
        <label for="{{$metodoPago->nombre}}" class="col-sm-4 col-form-label"><b>{{$metodoPago->nombre}}</b></label>
        <div class="col-sm-7">
            @if(isset($recibo))
                @if($metodoPago->nombre=="Cheque")
                    <div class="input-group">
                        <input min="1" max="999999999" type="number" class="form-control montopago-input" id="{{$metodoPago->nombre}}" name="{{$metodoPago->nombre}}" placeholder="$" value="{{$recibo->metodoPago->find($metodoPago->id)->pivot->valor ?? ''}}" readonly>
                        <a class="btn btn-primary" id="btnBuscarCheque"><i data-feather="search"></i></a>
                    </div>
                @else
                    <input min="1" max="999999999" type="number" class="form-control montopago-input" id="{{$metodoPago->nombre}}" name="{{$metodoPago->nombre}}" placeholder="$" value="{{$recibo->metodoPago->find($metodoPago->id)->pivot->valor ?? ''}}">
                @endif
            @else
                @if($metodoPago->nombre=="Cheque")
                    <div class="input-group">
                        <input min="1" max="999999999" type="number" class="form-control montopago-input" id="{{$metodoPago->nombre}}" name="{{$metodoPago->nombre}}" placeholder="$" value="" readonly>
                        <a class="btn btn-primary" id="btnBuscarCheque" aria-disabled="true"><i data-feather="search"></i></a>
                    </div>
                @else
                    <input min="1" max="999999999" type="number" class="form-control montopago-input" id="{{$metodoPago->nombre}}" name="{{$metodoPago->nombre}}" placeholder="$" value="">
                @endif
            @endif
        </div>
    </div>
    @endforeach

    <div row="row mb-1">
        @if(!isset($recibo))
        <a class="btn btn-secondary w-100" id="limpiarBtn">Limpiar Campos</a>
        @endif
    </div>
</div>

          </div>
</div>
@include('pages/cheque/form-modal')
</div>