@extends('home.layout')
@section('title') {{ $manufacturer['name'] }} @stop

@section('after_style')
    <style>

    </style>
@stop

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb" class="my-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item">{{ $manufacturer['name'] }}</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-lg-9 order-lg-9">
                <div class="box">
                <!-- <h5 class="box-title"><span>{{ $manufacturer['name'] }}</span></h5> -->
                    <h3 class="box-cate-title"><span>{{ $manufacturer['name'] }}</span></h3>
                    <div class="row box-card">
                        @if(count($manufacturerCategories))
                            @foreach($manufacturerCategories as $category)
                                <div class="col-xl-4 col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body d-flex">
                                            <a href="/{!! \App\Helpers\General::toSlug($category['name']) !!}-c{!! $category['id'] !!}.html"
                                               class="align-self-center mx-auto"><img class="img-fluid"
                                                                                      alt="{{ $category['name'] }}"
                                                                                      src="{{ $category['image_url']. $category['image'] }}"></a>
                                        </div>
                                        <div class="card-footer">
                                            <h6 class="card-title text-center m-0"><a
                                                        href="/{!! \App\Helpers\General::toSlug($category['name']) !!}-c{!! $category['id'] !!}.html">{{ $category['name'] }}</a>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
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