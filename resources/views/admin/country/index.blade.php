@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')


@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="{{$continent->map__Name}}'s Countries Management"
        :thead="['#', 'Name','Actions']" 
        :route="route('country.index',['id' => $continent->map__ID])"
        :createLink="route('country.create')"
        createLinkText="Add"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex"],
            ["data" => "countries__Name", "name" => "countries__Name"],
            ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
        ]'
    />
    
  </div>
</div>
@endsection
