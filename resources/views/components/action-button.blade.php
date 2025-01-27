<div class="dropdown">
    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="bx bx-dots-vertical-rounded"></i>
    </button>
    <div class="dropdown-menu">
        @if(isset($view_link) && $view_link != '')
            <a class="dropdown-item" href="{{$view_link}}"><i class="bx bx-show me-2"></i> View</a>
        @endif 
        
        @if (isset($edit_link) && $edit_link != '')
            <a class="dropdown-item" href="{{$edit_link}}"><i class="bx bx-edit-alt me-2"></i> Edit</a>
        @endif 

        @if (isset($delete_link) && $delete_link != '')
            <a class="dropdown-item" href="{{$delete_link}}"><i class="bx bx-trash me-2"></i> Delete</a>
        @endif 

    </div>
</div>
