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
                <h4 class="text-primary "> <strong><i>Survey Not Available</i></strong> </h4>
            </div>
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                <p>
                    This survey is currently not accessible. It may have expired or is not yet active.
                </p>
                <p>For any queries, please contact our team at support@example.com</p>
            </div>
            
        
        <!--/ Content wrapper -->
        </div>
    </div>
</div>
  <!-- / Layout wrapper -->
@endsection
