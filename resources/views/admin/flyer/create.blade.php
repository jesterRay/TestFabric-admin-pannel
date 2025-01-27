@extends('layouts/contentNavbarLayout')

@section('title', 'Add Service')

@section('content')

<div class="row">
  <div class="col-12 mb-6">
    <div class="card mb-6">
        <div class="card-body pt-4">
          <div class="card-title text-primary h4 mb-6">Create Flyer</div>
          <form method="POST" action='{{route('flyer.save')}}'  enctype="multipart/form-data">
            @csrf
            <div class="row g-6">


                <div class="row pt-6">
                    <div class="col-md-6 mb-4">
                        <label for="imgfile" class="form-label">Image</label>
                        <input 
                            class="form-control" 
                            type="file" 
                            id="imgfile" 
                            name="imgfile" 
                            accept=".pdf, .ppt, .xlsx, .doc, .docx, .jpg, .txt" 
                            onchange="previewImage(event)"
                        >
                    </div>
                </div>

                <div class="col-md-6">
                    <x-select-two-drop-down
                        name="files1__Product"
                        title="Product"
                        placeholder="Select Product"
                        :options="$products"
                        :selected="old('files1__Product')"
                        :multiple="false"

                    />
                </div>

                <div class="col-12">
                    <label for="files1__Description" class="form-label" required>Description *</label>
                    <textarea 
                        class="form-control" 
                        name="files1__Description" 
                        id="files1__Description" 
                        rows="3" required
                    >{{old('files1__Description')}}</textarea>
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

