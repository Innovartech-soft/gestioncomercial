 @if(session('success'))
      <div class="col-md-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
         <i data-feather="alert-circle"></i>
          <strong>{{session('success')}}</strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
      @elseif(session('error'))
      <div class="col-md-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
         <i data-feather="alert-circle"></i>
          <strong>{{session('error')}}</strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
      @elseif(session('updated'))
      <div class="col-md-12">
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
         <i data-feather="alert-circle"></i>
          <strong>{{session('updated')}}</strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
       @elseif(session('deleted'))
      <div class="col-md-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
         <i data-feather="alert-circle"></i>
          <strong>{{session('deleted')}}</strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
      @elseif($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif