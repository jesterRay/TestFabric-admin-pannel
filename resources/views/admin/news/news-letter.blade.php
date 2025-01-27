@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')


@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="News Letter Management"
        :thead="['#', 'Name']" 
        :route="route('news.letter')"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex","searchable" => false],
            ["data" => "newsletter__Email", "name" => "newsletter__Email"]
        ]'
    />
    
  </div>
</div>
@endsection
