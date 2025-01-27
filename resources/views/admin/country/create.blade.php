@extends('layouts/contentNavbarLayout')

@section('title', 'Add Service')

@section('content')



<div class="row">
  <div class="col-12 mb-6">
    <div class="card mb-6">
        <div class="card-body pt-4">
          <div class="card-title text-primary h4 mb-6">Create Country</div>
          <form method="POST" action='{{route('country.save')}}'  enctype="multipart/form-data">
            @csrf
            <div class="row g-6">
                <div class="col-md-6">
                    <label for="countries__Name" class="form-label">Name *</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        id="countries__Name" 
                        name="countries__Name" 
                        value="{{old('countries__Name')}}" 
                        required
                    />
                </div>
                <div class="col-md-6">
                    <x-select-two-drop-down
                        name="countries__Map_ID"
                        title="Continent"
                        placeholder="Select Continent"
                        :options="$continent"
                        :selected="old('countries__Map_ID')"
                        :multiple="false"

                    />
                </div>

                <div class="row mt-4">
                    <div class="col-md-6 mb-4">
                        <label for="imgfile" class="form-label">Choose Flag</label>
                        <div class="my-3">
                            <img id="imgPreview" src="#" alt="Selected Image" style="display: none; max-width: 100%; height: auto;" />
                        </div>
                        <input class="form-control" type="file" id="imgfile" name="imgfile"  onchange="previewImage(event)">
                    </div>    
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