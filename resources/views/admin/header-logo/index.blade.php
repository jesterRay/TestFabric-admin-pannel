@extends('layouts/contentNavbarLayout')

@section('title', 'Add Service')

@section('content')

<div class="row">
  <div class="col-12 mb-6">
    <div class="card mb-6">
        <div class="card-body pt-4">
          <div class="card-title text-primary h4 mb-6">Edit Header Logo</div>
          <form method="POST" action='{{route('header.logo.save')}}'  enctype="multipart/form-data">
            @csrf
            <div class="row g-6">
                <div class="col-md-6 mb-4">
                    <label for="imgfile" class="form-label">Image</label>
                    <div class="my-3">
                        <img id="imgPreview" 
                            alt="Preview" 
                            style="{{ isset($header_logo->name) && $header_logo->name ? 'display: block;' : 'display: none;' }} max-width: 100%; height: auto;" 
                            src="{{ isset($header_logo->name) && $header_logo->name ? asset($header_logo->name) : '#' }}"
                        />
                    </div>
                    <input class="form-control" type="file" id="imgfile" name="imgfile" onchange="previewImage(event)" accept="image/*">
                </div>
            </div>
              
            <div class="mt-6">
              <button type="submit" class="btn btn-primary me-3">Update</button>
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
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file');
                    fileInput.value = '';
                    preview.src = '#';
                    preview.style.display = 'none';
                    return;
                }

                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };

                reader.onerror = function() {
                    alert('Error reading file');
                    preview.src = '#';
                    preview.style.display = 'none';
                };

                reader.readAsDataURL(file);
            } else {
                preview.src = "#";
                preview.style.display = 'none';
            }
        }
    </script>
@endpush