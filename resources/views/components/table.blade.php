
<div class="card mb-6" style="min-height: 80vh;">
    <div class="card-header w-100 d-flex justify-content-between align-items-center">
        <div class="card-title text-primary mb-3 h3">{{$title}}</div>    
        @if (isset($createLink) && $createLink != '')
            <div class="btn btn-primary text-white" onclick="window.location.href='{{$createLink}}';">
                {{$createLinkText}}
            </div>
        @endif
    </div>
    <div class="card-body table-responsive">
        <table class="data-table">
            <thead class="">
                <tr>
                    @foreach ($thead as $heading)
                        <th >{{$heading}}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody></tbody>
        </table>
    </div>
</div>

@push('script')
    <script type="text/javascript">
        var $jq = jQuery.noConflict();
        $jq(document).ready(function(){
            
            // parsing value from component
            let route = "{{ $route }}";
            let columns = @json($columns);

            
            // showing table
            $jq('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: route,
                columns: columns
            });
        });


    </script>
@endpush    