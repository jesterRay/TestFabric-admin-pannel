<!DOCTYPE html>

<html class="light-style layout-menu-fixed" data-theme="theme-default" data-assets-path="{{ asset('/assets') . '/' }}" data-base-url="{{url('/')}}" data-framework="laravel" data-template="vertical-menu-laravel-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>@yield('title') | Sneat - HTML Laravel Free Admin Template </title>
  <meta name="description" content="{{ config('variables.templateDescription') ? config('variables.templateDescription') : '' }}" />
  <meta name="keywords" content="{{ config('variables.templateKeyword') ? config('variables.templateKeyword') : '' }}">
  <!-- laravel CRUD token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Canonical SEO -->
  <link rel="canonical" href="{{ config('variables.productPage') ? config('variables.productPage') : '' }}">
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <!-- Include Styles -->

  @include('layouts/sections/styles')

  <!-- Include Scripts for customizer, helper, analytics, config -->
  @include('layouts/sections/scriptsIncludes')

  {{-- select 2 css (js is after body tag) --}}
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <style>
    .custom-success-toast,
    .custom-error-toast{
        top: 2%!important;
        right: 2%!important;
    }
    .response-card{
      max-width: 40rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 1rem;
    }
  </style>


  @stack("style")
</head>

<body>


 


  @if(session('success'))
  <div class="custom-success-toast bs-toast toast toast-placement-ex m-2 fade bg-success top-3 end-3 show" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <i class='bx bx-check-circle me-2'></i>
      <div class="me-auto fw-medium">{{ session('success') }}</div>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
  @endif

  @if(session('error'))
    <div class="custom-error-toast bs-toast toast toast-placement-ex m-2 fade bg-danger top-5 end-0 show" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <i class='bx bx-bell me-2'></i>
        <div class="me-auto fw-medium">{{session('error')}}</div>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  @endif



  <!-- Layout Content -->
  @yield('layoutContent')
  <!--/ Layout Content -->

  

  <!-- Include Scripts -->
  @include('layouts/sections/scripts')
  {{-- jquery --}}
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  {{-- datatable --}}
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  {{-- select 2 js(css is before body tag) --}}
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  @stack('script')
    <script type="text/javascript">
      document.addEventListener("DOMContentLoaded", function () {
        // Select all toasts with the class 'show'
        const successToast = document.querySelector('.custom-success-toast');
        const errorToast = document.querySelector('.custom-error-toast');
        
        // Hide success toast after 10 seconds
        if (successToast) {
          setTimeout(function() {
            successToast.classList.remove('show');
            errorToast.classList.add('hide');

          }, 3000); // 10000 ms = 10 seconds
        }

        // Hide error toast after 10 seconds
        if (errorToast) {
          setTimeout(function() {
            errorToast.classList.remove('show');
            errorToast.classList.add('hide');
          }, 10000); // 10000 ms = 10 seconds
        }
      });


      
    </script>


</body>

</html>
