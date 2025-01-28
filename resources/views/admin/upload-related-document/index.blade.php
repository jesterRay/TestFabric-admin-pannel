@extends('layouts/contentNavbarLayout')

@section('title', 'Related Document Management')

@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="Related Document Management"
        :thead="['#', 'File', 'Product', 'Actions']" 
        :route="route('upload-related-document.index')"
        :createLink="route('upload-related-document.create')"
        createLinkText="Add"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex","searchable" => false],
            ["data" => "file", "name" => "file","searchable" => true],
            ["data" => "files__Product", "name" => "files__Product","searchable" => true],
            ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
        ]'
    />
  </div>
</div>
@endsection
