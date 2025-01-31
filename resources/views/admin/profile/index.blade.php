@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')

@section('page-script')
@vite(['resources/assets/js/pages-account-settings-account.js'])
@endsection

@php
  $img_src = asset('profile/user.jpg');  
@endphp

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card mb-6">
      <h5 class="card-header text-primary">General</h5>
      <div class="card-body pt-4">
        <form id="formAccountSettings" method="POST" action="{{route('user.update')}}" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="row g-6">
              <div class="row">
                <div class="col-md-6 mb-4">
                    <label for="imgfile" class="form-label">Image</label>
                    <div class="my-3">
                        <img id="imgPreview" 
                            alt="Preview" 
                            style="{{ isset($img_src) &&
                                        $img_src ?
                                        'display: block;' 
                                        : 'display: none;' 
                                    }} max-width: 100%; height: auto;"

                            src="{{ isset($img_src) &&
                                    $img_src ?
                                    asset($img_src)
                                    : '#' 
                                }}"
                        />
                    </div>
                    <input 
                        class="form-control" 
                        type="file" id="imgfile" 
                        name="imgfile"
                        onchange="previewImage(event)" 
                        accept=".jpg"
                    >

                </div>
              </div>


            <div class="col-md-6">
              <label for="username" class="form-label">Username</label>
              <input class="form-control" type="text" id="username" name="username" value="{{old("username",$user->username)}}" />
            </div>
            <div class="col-md-6">
              <label for="email" class="form-label">E-mail</label>
              <input class="form-control" type="text" id="email" name="email" value="{{old("emial",$user->email)}}" />
            </div>

          </div>
          <div class="mt-6">
            <button type="submit" class="btn btn-primary me-3">Save changes</button>
            {{-- <button type="reset" class="btn btn-outline-secondary">Cancel</button> --}}
          </div>
        </form>
      </div>
      <!-- /Account -->
    </div>
    <div class="card">
      {{-- Change password --}}
      <form method="POST" id="change-password-form" action="{{route('user.update.password')}}">
          @csrf
          <h5 class="card-header text-primary">Change Password</h5>
          <div class="card-body row">
    
            <div class="col-md-6 mb-6 form-password-toggle">
              <label class="form-label" for="password">New Password</label>
              <div class="input-group input-group-merge">
                <input 
                  type="password" 
                  id="password" 
                  class="form-control" 
                  name="new_password" 
                  placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                  aria-describedby="password" 
                />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>
            <div class="col-md-6 mb-6 form-password-toggle">
                <label class="form-label" for="password">Confirm New Password</label>
                <div class="input-group input-group-merge">
                  <input 
                    type="password"
                    id="password" 
                    class="form-control" 
                    name="new_password_confirmation" 
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" 
                    aria-describedby="password" />
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
            </div>
            <div>
              <button type="submit" class="btn btn-primary" >Change Password</button>
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