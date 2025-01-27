

@extends('layouts/contentNavbarLayout')

@section('title', 'Add Service')

@section('content')

<div class="row">
  <div class="col-12 mb-6">
    <div class="card mb-6">
        <div class="card-body pt-4">
          <div class="card-title text-primary h4 mb-6">Edit Next Step</div>
          <form method="POST" action='{{route('next-step.update',['id' => $nextStep->associations_and_partners__ID])}}'  enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row g-6">
              <div class="col-md-6">
                <label for="associations_and_partners__Name" class="form-label">Name *</label>
                <input class="form-control" type="text" 
                    id="associations_and_partners__Name" 
                    name="associations_and_partners__Name" 
                    value="{{$nextStep->associations_and_partners__Name ?? ''}}" 
                    required
                  />
              </div>
              <div class="col-md-6">
                <label for="associations_and_partners__Abbriviation" class="form-label">Abbrevation *</label>
                <input class="form-control" type="text" 
                    id="associations_and_partners__Abbriviation" 
                    name="associations_and_partners__Abbriviation" 
                    value="{{$nextStep->associations_and_partners__Abbriviation ?? ''}}" 
                    required
                />
              </div>
              <div class="col-12">
                <label for="associations_and_partners__Description" class="form-label" required>Description *</label>
                <textarea class="form-control" 
                  name="associations_and_partners__Description" 
                  id="associations_and_partners__Description" 
                  rows="3" 
                  required
                >{{$nextStep->associations_and_partners__Description}}</textarea>
              </div>
                <div class="col-md-6 mb-4">
                    <label for="imgfile" class="form-label">Image</label>
                    <div class="my-3">
                        <img id="imgPreview" 
                            alt="Preview" 
                            style="{{ isset($nextStep->imgfile) && $nextStep->imgfile ? 'display: block;' : 'display: none;' }} max-width: 100%; height: auto;" 
                            src="{{ isset($nextStep->imgfile) && $nextStep->imgfile ? asset($nextStep->imgfile) : '#' }}"
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