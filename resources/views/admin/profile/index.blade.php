@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')

@section('page-script')
@vite(['resources/assets/js/pages-account-settings-account.js'])
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card mb-6">
      <div class="card-body pt-4">
        <form id="formAccountSettings" method="POST" onsubmit="return false">
          <div class="row g-6">
            <div class="col-md-6">
              <label for="username" class="form-label">Username</label>
              <input class="form-control" type="text" id="username" name="username" value="" />
            </div>
            <div class="col-md-6">
              <label for="email" class="form-label">E-mail</label>
              <input class="form-control" type="text" id="email" name="email" value="" />
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
      <h5 class="card-header">Change Password</h5>
      <div class="card-body row">
        <div class="col-md-6 mb-6 form-password-toggle">
            <label class="form-label" for="password">New Password</label>
            <div class="input-group input-group-merge">
              <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
              <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
            </div>
        </div>
        <div class="col-md-6 mb-6 form-password-toggle">
            <label class="form-label" for="password">Confirm New Password</label>
            <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
            </div>
        </div>
        <form id="formAccountDeactivation" onsubmit="return false">
          
          <button type="submit" class="btn btn-primary" >Change Password</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
