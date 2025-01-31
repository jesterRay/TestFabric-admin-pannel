@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')

@section('content')

<div class="row">
  <div class="col-12 mb-6">
        <x-table 
            title="Associations and Partners's Management"
            :thead="['#', 'Name', 'abbreviation', 'url', 'Actions']" 
            :route="route('association-and-partner.index')"
            :createLink="route('association-and-partner.create')"
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
