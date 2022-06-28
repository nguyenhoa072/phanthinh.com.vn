<?php
$settings = \App\Helpers\General::get_settings();
?>
@extends('home.layout')
@section('title') {{ $page['title'] }}@stop

@section('after_style')
    <style>

    </style>
@stop

@section('content')
    <div class="container">
        <div class="box introduce mt-4">
            <!-- <h3>{{ $page['page_name'] }}</h3> -->
            <h3 class="box-cate-title"><span>Giới thiệu</span></h3>
            <div>
                {!! $page['content'] !!}
            </div>
        </div>
    </div>
@stop
@section('after_script')
@stop