@extends('layouts/contentNavbarLayout')

@section('title', 'Add Coc')

@section('content')

<div class="row">
  <div class="col-12 mb-6">
    <div class="card mb-6">
        <div class="card-body pt-4">
          <form method="POST" action='{{route('coc.save')}}'  enctype="multipart/form-data">
            @csrf
            <div class="row g-6">
              <div class="col-md-6">
                <label for="files__download" class="form-label">Coc Name</label>
                <input class="form-control" type="text" id="files__download" name="files__download" value="" />
              </div>
              <div class="col-md-6 mb-4">
                <label for="files__file" class="form-label">Coc file</label>
                <input class="form-control" type="file" id="files__file" name="files__file" accept=".pdf,application/pdf">
              </div>
            </div>
            <div class="mt-6">
              <button type="submit" class="btn btn-primary me-3">Add</button>
            </div>
          </form>
        </div>
      </div>
  </div>
</div>

@endsection
