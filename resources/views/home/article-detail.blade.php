@extends('home.layout')

@section('title') {{ $object['name'] }} @stop

@if ($object['short_description'])
@section('og_description'){{ strip_tags($object['short_description']) }}@stop
@endif

@section('after_style')
    <style>
    </style>
@stop

@section('content')
    <div class="container detail">
        <nav aria-label="breadcrumb" class="my-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item"><a
                            href="/{!! \App\Helpers\General::toSlug($object['manufacturer_name']) !!}-m{!! $object['manufacturer_id'] !!}.html">{{ $object['manufacturer_name'] }}</a>
                </li>
                <li class="breadcrumb-item"><a
                            href="/{!! \App\Helpers\General::toSlug($object['category_name']) !!}-c{!! $object['category_id'] !!}.html">{{ $object['category_name'] }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $object['name'] }}</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-lg-9 order-lg-9">
                <div class="product-detail">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="app-figure text-center" id="zoom-fig">
                                <a id="Zoom-1" class="MagicZoom" title="{{ $object['name'] }}"
                                   href="{{ $object['image_url'].$object['image'] }}">
                                    <img src="{{ $object['image_url'].$object['image'] }}" alt=""/>
                                </a>
                                @if(count($images))
                                <div class="selectors my-3">
                                    @foreach($images as $image)
                                    <a data-zoom-id="Zoom-1" href="{{ $image }}" data-image="{{ $image }}">
                                        <img srcset="{{ $image }}" src="{{ $image }}" width="50">
                                    </a>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <h1 class="product-detail-name">{{ $object['name'] }}</h1>
                            <h5 class="product-detail-ctsp"><b>Chi Tiết Sản Phẩm:</b></h5>
                            <div>{!! $object['short_description'] !!}</div>
                        </div>
                    </div>
                    <div class="product-detail-content mb-4">
                        <hr>
                        {!! $object['description'] !!}
                    </div>
                    <div class="box">
                        <h5 class="box-title box-title-one mb-4"><span>Sản phẩm thông dụng</span></h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-nowrap">Tên sản phẩm</th>
                                        <th style="min-width: 250px">Mô tả</th>
                                        <th class="text-nowrap">Tồn kho</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($products_same_code as $item)
                                    <tr>
                                        <td class="text-nowrap table-msp"><strong><?=$item['name']?></strong></td>
                                        <td><?=$item['short_description']?></td>
                                        <td class="d-none"><?=number_format($item['price'])?></td>
                                        <td class="text-right"><?=isset($item['amount']) && $item['amount'] ? $item['amount'] : 'Liên hệ'?></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @if(count($objects_same_category))
                <div class="box mt-5">
                    <h5 class="box-title box-title-two mb-4"><span>Sản phẩm cùng loại</span></h5>
                    <div id="spcl_detail" class="owl-carousel owl-theme">
                        @foreach($objects_same_category as $item)
                        <div class="card">
                            <div class="card-body d-flex">
                                <a href="<?=route('article_detail', ['article_slug' => str_slug($item['name']), 'id' => $item['id']])?>"
                                    class="align-self-center mx-auto">
                                    <img class="img-fluid" alt="{{ $item['name'] }}" src="{{ $item['image_url'].$item['image'] }}">
                                </a>
                            </div>
                            <div class="card-footer">
                                <h2 class="card-title text-center m-0">
                                    <a href="<?=route('article_detail', ['article_slug' => str_slug($item['name']), 'id' => $item['id']])?>">{{ $item['name'] }}</a>
                                </h2>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            <div class="col-lg-3 order-lg-3">
                <div class="sidebar">
                    <?php
                    $manufacturers = \App\Helpers\General::getManufacturer();
                    $product_categories = \App\Helpers\General::getProductCategories();
                    $articles = \App\Helpers\General::getArticles();
                    ?>
                    @foreach($manufacturers as $mid => $mname)
                        @if(@$product_categories[$mid])
                            <div class="sidebar-box">
                                <div class="sidebar-header">{!! $mname !!}</div>
                                <div class="" id="collapse_{!! $mname !!}">
                                    <?php $i = 0; ?>
                                    @foreach($product_categories[$mid] as $pc)
                                        @if (isset($articles[$pc['id']]))
                                            <a class="sidebar-link-two d-block" href="#" data-toggle="collapse"
                                               data-target="#collapse_{!! $mname !!}_{!! $i !!}">{!! $pc['name'] !!}</a>
                                            <ul class="list-unstyled px-4 m-0 collapse"
                                                id="collapse_{!! $mname !!}_{!! $i !!}"
                                                data-parent="#collapse_{!! $mname !!}" style="">
                                                @foreach($articles[$pc['id']] as $cat)
                                                    <li>
                                                        <a href="<?=route('article_detail', ['slug_article' => str_slug($cat['name']), 'id' => $cat['id']])?>">{{$cat['name']}}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                        @endif
                                        <?php $i++ ?>
                                    @endforeach
                                </div>
                            </div>
                        @else
                        @endif
                    @endforeach
                </div>
                <div class="box mt-5">
                    @include('home.supports')
                </div>
            </div>
        </div>
    </div>
@stop
@section('after_script')
@stop