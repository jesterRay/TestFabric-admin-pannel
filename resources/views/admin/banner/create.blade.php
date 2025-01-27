@extends('layouts/contentNavbarLayout')

@section('title', 'Add Service')

@section('content')

<div class="row">
  <div class="col-12 mb-6">
    <div class="card mb-6">
        <div class="card-body pt-4">
          <div class="card-title text-primary h4 mb-6">Create Banner</div>
          <form method="POST" action='{{route('banner.save')}}'  enctype="multipart/form-data">
            @csrf
            <div class="row g-6">
              <div class="col-md-6">
                <label for="files__download" class="form-label">Banner Name *</label>
                <input class="form-control" type="text" id="files__download" name="files__download" 
                    value="{{old('files__download')}}" required/>
              </div>
              <div class="col-md-6">
                <label for="files__Link" class="form-label">Banner Link *</label>
                <input class="form-control" type="text" id="files__Link" name="files__Link" 
                    value="{{old('files__Link')}}" required/>
              </div>

              <div class="col-md-6 mb-4">
                  <label for="files__file" class="form-label">Image</label>
                <div class="my-3">
                    <img id="imgPreview" src="#" alt="Selected Image" style="display: none; max-width: 100%; height: auto;" />
                </div>
                <input class="form-control" type="file" id="files__file" name="files__file"  onchange="previewImage(event)">
                <p>Please upload banner in jpg,jpeg, or png format. Size must be off 990x250</p>
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


@push('script')
    <script>
        function previewImage(event) {
            const fileInput = event.target;
            const file = fileInput.files[0];
            const preview = document.getElementById('imgPreview');

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result; // Set the image source to the file content
                    preview.style.display = 'block'; // Make the image visible
                };

                reader.readAsDataURL(file); // Read the file content
            } else {
                preview.src = "#"; // Reset the image source
                preview.style.display = 'none'; // Hide the image
            }
        }
    </script>
@endpush