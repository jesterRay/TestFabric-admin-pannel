@extends('layouts/commonMaster' )
@include('layouts/sections/scripts')
@php
/* Display elements */
$contentNavbar = true;
$containerNav = ($containerNav ?? 'container-xxl');
$isNavbar = ($isNavbar ?? true);
$isMenu = ($isMenu ?? true);
$isFlex = ($isFlex ?? false);
$isFooter = ($isFooter ?? true);

/* HTML Classes */
$navbarDetached = 'navbar-detached';

/* Content classes */
$container = ($container ?? 'container-xxl');

@endphp

@section('layoutContent')
<div class="layout-wrapper py-4  layout-content-navbar {{ $isMenu ? '' : 'layout-without-menu' }}">
    <div class="layout-container d-flex justify-content-center align-items-center">
        <!-- Layout page -->
        <div class="content-wrapper card">
            <div class="card-header d-flex flex-column justify-content-between align-items-center">
                <h4 class="text-primary "> <strong><i>Thank You for Your Response!</i></strong> </h4>
            </div>
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                <p>
                    We appreciate you taking the time to share your thoughts with us. Your responses are important and will help us improve.
                </p>
                <p>If you have any more feedback or questions, feel free to reach out to us.</p>
                <p>Stay tuned for future updates!</p>
            </div>
            
        
        <!--/ Content wrapper -->
        </div>
    </div>
</div>
  <!-- / Layout wrapper -->
@endsection
