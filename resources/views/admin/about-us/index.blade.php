@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')


@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="About us management"
        :thead="['#', 'Name', 'Abbreviation', 'Url','Actions']" 
        :route="route('about-us.index')"
        :createLink="route('about-us.create')"
        createLinkText="Add"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex", "searchable" => false,"orderable" => false],
            ["data" => "associations_and_partners__Name", "name" => "associations_and_partners__Name"],
            ["data" => "associations_and_partners__Abbriviation", "name" => "associations_and_partners__Abbriviation"],
            ["data" => "associations_and_partners__Url", "name" => "associations_and_partners__Url"],
            ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
        ]'
    />
    
  </div>
</div>
@endsection
