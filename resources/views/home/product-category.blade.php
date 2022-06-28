@extends('home.layout')

@section('title') {{ $category['name'] }} @stop

@section('after_style')
    <style>

    </style>
@stop

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb" class="my-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item">
                    <a href="{{route('category_manufacturer', ['manufacturerName' => str_slug($manufacturer['name']), 'id' => $category['manufacturer_id']])}}">{{ $manufacturer['name'] }}</a>
                </li>
                <li class="breadcrumb-item">{{ $category['name'] }}</li>
            </ol>
            </ol>
        </nav>
        <div class="row">
            <div class="col-lg-9 order-lg-9">
                <div class="box">
                    <h3 class="box-cate-title"><span>{{ $category['name'] }}</span></h3>
                    <div class="row box-card">
                        @if(count($objects['data']))
                            @foreach($objects['data'] as $product)
                                <div class="col-xl-4 col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body d-flex">
                                            <a href="<?=route('article_detail', ['slug_article' => str_slug($product['name']), 'id' => $product['id']])?>"
                                               class="align-self-center mx-auto">
                                                <img class="img-fluid" alt="{{ $product['name'] }}"
                                                     src="{{ $product['image'] }}"></a>
                                        </div>
                                        <div class="card-footer">
                                            <h2 class="card-title text-center m-0"><a
                                                        href="/{!! \App\Helpers\General::toSlug($product['name']) !!}-p{!! $product['id'] !!}.html">{{ $product['name'] }}</a>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">Bài viết đang được cập nhật</div>
                        @endif
                    </div>
                    @if(count($objects['data'])) @include('home.paginator') @endif
                </div>
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