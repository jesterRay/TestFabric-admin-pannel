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
            action='{{route('tpvs.content.update',['id' => $content->tpvs__ID])}}'  
            enctype="multipart/form-data"
            >
            @csrf
            @method('PUT')
            <div class="row g-6">

                {{-- TPVS Heading 1 --}}
                <div class="col-12">
                    <label for="heading_1" class="form-label" required>TPVS Heading 1 *</label>
                    <textarea 
                        class="form-control" 
                        name="heading_1" 
                        id="heading_1" 
                        rows="3" required
                    >{{ old('heading_1') ?? $content->heading_1}}</textarea>
                </div>

                {{-- TPVS Heading 2 --}}
                <div class="col-12">
                    <label for="heading_2" class="form-label" required>TPVS Heading 2 *</label>
                    <textarea 
                        class="form-control" 
                        name="heading_2" 
                        id="heading_2" 
                        rows="3" required
                    >{{ old('heading_2') ?? $content->heading_2}}</textarea>
                </div>

                {{-- Message 1 --}}
                <div class="col-12" >
                    <label 
                        for="pink_message" 
                        class="form-label" 
                        required
                    >Message 1 *</label>
                    <textarea 
                        class="form-control" 
                        name="pink_message" 
                        id="pink_message" 
                        rows="6" required
                        style="background-color: #FFC0CB;"
                    >{{ old('pink_message') ?? $content->pink_message}}</textarea>
                </div>

                {{-- Message 2 --}}
                <div class="col-12">
                    <label for="yellow_message" class="form-label" required>Message 2 *</label>
                    <textarea 
                        class="form-control" 
                        name="yellow_message" 
                        id="yellow_message" 
                        rows="6" required
                        style="background-color: #FFFF00;"
                    >{{ old('yellow_message') ?? $content->yellow_message}}</textarea>
                </div>

                {{-- Message 3 --}}
                <div class="col-12">
                    <label for="green_message" class="form-label" required>Message 3 *</label>
                    <textarea 
                        class="form-control" 
                        name="green_message" 
                        id="green_message" 
                        rows="6" 
                        style="background-color: #008000;"
                        
                    >{{ old('green_message') ?? $content->green_message}}</textarea>
                </div>

                {{-- Image Field --}}
                <div class="row pt-6">
                    <div class="col-md-6 mb-4">
                        <label for="imgfile" class="form-label">Image</label>
                        <div class="my-3">
                            <img id="imgPreview" 
                                alt="Preview" 
                                style="{{ isset($content->image) &&
                                             $content->image ?
                                             'display: block;' 
                                             : 'display: none;' 
                                        }} max-width: 100%; height: auto;"
    
                                src="{{ isset($content->image) &&
                                        $content->image ?
                                        asset($content->image)
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