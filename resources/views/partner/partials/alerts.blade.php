@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if($errors->any())
  <div class="alert alert-danger rounded-4">
    <div class="fw-semibold mb-2"><i class="fa-solid fa-triangle-exclamation me-2"></i>Verifique os campos:</div>
    <ul class="mb-0">
      @foreach($errors->all() as $e)
        <li>{{ $e }}</li>
      @endforeach
    </ul>
  </div>
@endif
