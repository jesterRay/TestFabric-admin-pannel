@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')

@section('content')

<div class="row">
  <div class="col-12 mb-6">
        <x-table 
            title="Chat Management"
            :thead="['#', 'Name', 'Email', 'Actions']" 
            :route="route('chat.index')"
            :columns='[
                ["data" => "DT_RowIndex", "name" => "DT_RowIndex"],
                ["data" => "name", "name" => "name"],
                ["data" => "email", "name" => "email"],
                ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
            ]'
        />
  </div>
</div>

@endsection
