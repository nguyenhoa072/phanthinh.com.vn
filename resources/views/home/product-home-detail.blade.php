@extends('home.layout')

@section('title') {{ $object['product_code'] }} @stop

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
                <li class="breadcrumb-item active" aria-current="page">{{ $object['product_code'] }}</li>
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
                            <h2 class="product-detail-name">{{ $object['product_code'] }}</h2>
                            <h5 class="product-detail-ctsp"><b>Chi Tiết Sản Phẩm:</b></h5>
                            <div>{!! $object['short_description'] !!}</div>
                        </div>
                    </div>
                    <div class="product-detail-content mb-4">
                        <hr>
                        {!! $object['description'] !!}
                    </div>
                </div>
                @if(isset($product_home_list))
                <div class="box mt-5">
                    <h5 class="box-title box-title-two mb-4"><span>Sản phẩm khác</span></h5>
                    <!-- <div id="spcl_detail1" class="owl-carousel11 owl-theme"> -->
                    <div class="row">
                    @foreach($product_home_list as $product_home_list_item)
                        <div class="col-xl-4">                        
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title text-center m-0">
                                        <a href="<?=route('product_home_detail', ['product_home_slug' => str_slug($product_home_list_item['product_code']), 'id' => $product_home_list_item['id']])?>">{{ $product_home_list_item['product_code'] }}</a>
                                    </h6>
                                </div>
                                <div class="card-body d-flex px-3 py-0">
                                    <a href="<?=route('product_home_detail', ['article_slug' => str_slug($product_home_list_item['product_code']), 'id' => $product_home_list_item['id']])?>"
                                        class="align-self-center mx-auto">
                                        <img class="img-fluid" alt="{{ $product_home_list_item['product_code'] }}" src="{{ $product_home_list_item['image_url'].$product_home_list_item['image'] }}">
                                    </a>
                                </div>
                                <div class="card-footer p-3">                            
                                    <div class="text-center"><p class="mb-1">Số Lượng: xem tồn kho</p>{!! $product_home_list_item['short_description'] !!}</div>
                                </div>
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