@extends('layouts/contentNavbarLayout')

@section('title', 'Add Service')

@section('content')


@php
    $statusOptions = collect([
                        (object) ['id' => 'Show','name' => 'Show'],
                        (object) ['id' => 'Hide','name' => 'Hide'],
    ]);
@endphp

<div class="row">
  <div class="col-12 mb-6">
    <div class="card mb-6">
        <div class="card-body pt-4">
          <div class="card-title text-primary h4 mb-6">Edit Standard</div>
          <form 
            method="POST" 
            action='{{route('standard.update',['id' => $standard->standards__ID])}}'  
            enctype="multipart/form-data"
            >
            @csrf
            @method('PUT')
            <div class="row g-6">
                <div class="col-md-6">
                    <label for="standards__Name" class="form-label">Menu Item Name *</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        id="standards__Name" 
                        name="standards__Name" 
                        value="{{ old('standards__Name') ?? $standard->standards__Name}}" 
                        required
                    />
                </div>
                <div class="col-md-6">
                    <label for="standards__Sequence" class="form-label">Order *</label>
                    <input 
                        class="form-control" 
                        type="number" 
                        id="standards__Sequence" 
                        name="standards__Sequence" 
                        value="{{ old('standards__Sequence') ?? $standard->standards__Sequence}}" 
                        required
                    />
                </div>
                <div class="col-md-6">
                    <x-select-two-drop-down
                        name="standards__Status"
                        title="Select Status"
                        placeholder="Select Status"
                        :multiple="false"
                        :options="$statusOptions"
                        :selected="old('standards__Status', $standard->standards__Status)"
                    />
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="imgfile" class="form-label">Image</label>
                        <div class="my-3">
                            <img id="imgPreview" 
                                alt="Preview" 
                                style="{{ isset($standard->imgfile) &&
                                             $standard->imgfile ?
                                             'display: block;' 
                                             : 'display: none;' 
                                        }} max-width: 100%; height: auto;"
    
                                src="{{ isset($standard->imgfile) &&
                                        $standard->imgfile ?
                                        asset($standard->imgfile)
                                        : '#' 
                                    }}"
                            />
                        </div>
                        <input 
                            class="form-control" 
                            type="file" id="imgfile" 
                            name="imgfile"
                            
                            onchange="previewImage(event)" 
                            accept="image/*"
                        >
    
                    </div>
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