@extends('layouts/contentNavbarLayout')

@section('title', 'Country Agent Management')

@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="Country Agent Management"
        :thead="['#', 'Name', 'Email', 'Country', 'Status', 'Actions']" 
        :route="route('country-agent.index')"
        :createLink="route('country-agent.create')"
        createLinkText="Add Agent"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex", "searchable" => false, "orderable" => false],
            ["data" => "agent__Name", "name" => "agent__Name"],
            ["data" => "agent__Email", "name" => "agent__Email"],
            ["data" => "agent__Country", "name" => "agent__Country"],
            ["data" => "agent__Status", "name" => "agent__Status"],
            ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
        ]'
    />
  </div>
</div>
@endsection
