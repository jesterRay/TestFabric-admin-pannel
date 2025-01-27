@extends('layouts/contentNavbarLayout')

@section('title', 'Add Service')

@section('content')

<div class="row">
  <div class="col-12 mb-6">
    <div class="card mb-6">
        <div class="card-body pt-4">
          <div class="card-title text-primary h4 mb-6">Create Download File</div>
          <form method="POST" action='{{route('download.save')}}'  enctype="multipart/form-data">
            @csrf
            <div class="row g-6">
                <div class="col-md-6">
                    <label for="name" class="form-label">Name *</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{old('name')}}" 
                        required
                    />
                </div>
                

                <div class="row mt-4">
                    <div class="col-md-6 mb-4">
                        <label for="imgfile" class="form-label">File *</label>
                        
                        <input class="form-control mb-1" type="file" id="imgfile" name="file"  required>
                        <small>Please upload file in pdf, ppt, xlsx, doc, docx, or txt format only.</small>
                    </div>    
                </div>

                <div class="col-12">
                    <label for="description" class="form-label" required>Description *</label>
                    <textarea 
                        class="form-control" 
                        id="description" 
                        name="description" 
                        rows="5">{{old('description')}}</textarea>
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