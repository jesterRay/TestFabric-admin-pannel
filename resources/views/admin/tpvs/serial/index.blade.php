@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')


@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="TPVS Serial's Management"
        :thead="['#', 'Serial','Status','Is Checked']" 
        :route="route('tpvs.serial.index')"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex", "searchable" => false],
            ["data" => "tpvs__serial", "name" => "tpvs__serial"],
            ["data" => "status", "name" => "status"],
            ["data" => "is_checked", "name" => "is_checked"]
        ]'
    />
    
  </div>
</div>
@endsection


@push('script')
  
  <script>
    function handleRowCheck(element) {
      var id = $(element).data("id");
      var isChecked = $(element).is(":checked");

      $.ajax({
          url: "/tpvs/serial/update", // Replace with your server route
          method: "POST",
          data: {
              id: id,
              checked: isChecked ? 1 : 0,
          },
          headers: {
              "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // For Laravel CSRF protection
          },
          success: function (response) {
            if (response.success) {
              // console.log("Toast Function Called:", response.message, type);
                showToast(response.message, "success"); // Show success toast
            } else {
                showToast(response.message, "error"); // Show error toast
            }
          },
          error: function (xhr, status, error) {
              showToast("AJAX Error: " + error, "error");
          },
      });
    }

    function showToast(message, type = "success") {
      // Determine the toast type (success or error)
      const toastType = type === "success" ? "bg-success" : "bg-danger";
      const icon = type === "success" ? "bx-check-circle" : "bx-bell";
      // Create the toast element
      const toast = document.createElement('div');
      toast.classList.add(
          'custom-toast',
          'bs-toast',
          'toast',
          'toast-placement-ex',
          'm-2',
          'fade',
          toastType,
          'top-3',
          'end-3',
          'show'
      );
      toast.setAttribute('role', 'alert');
      toast.setAttribute('aria-live', 'assertive');
      toast.setAttribute('aria-atomic', 'true');

      // Set the toast content
      toast.innerHTML = `
          <div class="toast-header">
              <i class='bx ${icon} me-2'></i>
              <div class="me-auto fw-medium">${message}</div>
              <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
      `;

      // Append the toast to the body
      document.body.appendChild(toast);

      // Automatically remove the toast after 5 seconds
      setTimeout(() => {
          toast.remove();
      }, 5000);
    }



  </script>

@endpush