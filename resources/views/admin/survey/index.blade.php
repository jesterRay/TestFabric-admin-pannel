@extends('layouts/contentNavbarLayout')

@section('title', 'Surveys')

@section('content')

<div class="row">
  <div class="col-12 mb-6">
        <x-table
            title="Survey Management"
            :thead="['#', 'Title','Description', 'Status', 'Available From', 'Available Until', 'Action']" 
            :createLink="route('survey.create')"
            createLinkText="New"
            :route="route('survey.index')"
            :columns='[
                ["data" => "DT_RowIndex", "name" => "DT_RowIndex","searchable" => false,"orderable" => false],
                ["data" => "title", "name" => "title"],
                ["data" => "description", "name" => "description"],
                ["data" => "status", "name" => "status"],
                ["data" => "available_from", "name" => "available_from"],
                ["data" => "available_until", "name" => "available_until"],
                [ "data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
            ]'
        />
  </div>
</div>

@endsection

@push('script')
<script>
  document.addEventListener('DOMContentLoaded', function () {
      // Delegate the click event to the parent container (e.g., the document or table body)
      document.body.addEventListener('click', function (event) {
          // Check if the clicked element is a "Copy Response Link" button
          if (event.target && event.target.classList.contains('copy-link')) {
              // Get the link from the data-link attribute
              const link = event.target.getAttribute('data-link');
              
              // Call the function to copy the link to the clipboard
              copyToClipboard(link);
          }
      });
  });

  // Function to copy the link to clipboard
  function copyToClipboard(text) {
      const tempInput = document.createElement('input');
      document.body.appendChild(tempInput);
      tempInput.value = text;
      tempInput.select();
      document.execCommand('copy');
      document.body.removeChild(tempInput);

      // Optionally, you can log to the console for debugging
      console.log('Link copied to clipboard:', text);

      // Show success toast dynamically after copying
      showToast('Survey link copied to clipboard!');
  }

  // Function to display toast
  function showToast(message) {
      const toast = document.createElement('div');
      toast.classList.add('custom-success-toast', 'bs-toast', 'toast', 'toast-placement-ex', 'm-2', 'fade', 'bg-success', 'top-3', 'end-3', 'show');
      toast.setAttribute('role', 'alert');
      toast.setAttribute('aria-live', 'assertive');
      toast.setAttribute('aria-atomic', 'true');

      toast.innerHTML = `
          <div class="toast-header">
              <i class='bx bx-check-circle me-2'></i>
              <div class="me-auto fw-medium">${message}</div>
              <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
      `;
      document.body.appendChild(toast);
      
      // Automatically remove the toast after 5 seconds
      setTimeout(() => {
          toast.remove();
      }, 5000);
  }
</script>



@endpush