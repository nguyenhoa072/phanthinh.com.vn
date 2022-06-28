@extends('home.layout')

@section('title') Sản phẩm @stop

@section('after_style')
    <style>

    </style>
@stop

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb" class="my-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item">Sản phẩm</li>
            </ol>
            </ol>
        </nav>
        <?php
        $manufacturers = \App\Helpers\General::getManufacturer();
        $product_categories = \App\Helpers\General::getProductCategories();
        $articles = \App\Helpers\General::getArticles();
        ?>
        <div class="row">
            <div class="col-lg-9 order-lg-9">
                @foreach($manufacturers as $mid => $mname)
                    @if(@$products[$mid])
                <div class="box">
                    <h4 class="box-cate-title"><span>{{ @$mname }}</span></h4>
                    <div class="row box-card">
                        @foreach($products[$mid] as $product)
                            <?php
                            $link = route('article_detail', ['slug_article' => str_slug($product->name), 'id' => $product->id]);
                            ?>
                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-body d-flex">
                                        <a href="<?=$link?>"
                                           class="align-self-center mx-auto">
                                            <img class="img-fluid" alt="{{ $product->name }}"
                                                 src="{{ $product->image_url.$product->image }}"></a>
                                    </div>
                                    <div class="card-footer">
                                        <h2 class="card-title text-center m-0"><a
                                                    href="{{$link}}">{{ $product->name }}</a>
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                    @endif
                @endforeach
            </div>
            <div class="col-lg-3 order-lg-3">
                <div class="sidebar">
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