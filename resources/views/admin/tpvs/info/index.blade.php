@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')


@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="TPVS Information's Management"
        :thead="['#', 'Serial','IP','City','State','Country','Country Code']" 
        :route="route('tpvs.info')"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex", "searchable" => false, "orderable" => false],
            ["data" => "tpvs__serial", "name" => "tpvs__serial"],
            ["data" => "Ip", "name" => "Ip"],
            ["data" => "City", "name" => "City"],
            ["data" => "State", "name" => "State"],
            ["data" => "Country", "name" => "Country"],
            ["data" => "Country_code", "name" => "Country_code"]
        ]'
    />
    
  </div>
</div>
@endsection
