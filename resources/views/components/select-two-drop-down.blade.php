
<label for="{{$name}}" class="form-label">{{$title}}</label>

<select  
    @if($multiple) multiple @endif 
    class="select-two-drop-down form-select" 
    name="{{$name}}"
>
    @if (isset($placeholder) && $placeholder != '')
        <option value="" disabled selected>{{$placeholder}}</option>      
    @endif
    
    @foreach ($options as $item)
        <option value="{{$item->id}}"
            @if(in_array($item->id, (array) $selected)) selected @endif
        >
            {{$item->name}}
        </option>      
    @endforeach
</select>




@push('script')
    <script type="text/javascript">
        var $jq = jQuery.noConflict();
        $jq(document).ready(function(){
            $jq('.select-two-drop-down').select2();
        });
    </script>
@endpush    