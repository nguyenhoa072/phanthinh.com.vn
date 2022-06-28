<?php
$settings = \App\Helpers\General::get_settings();
?>
@extends('home.layout')
@section('title') Trang chủ @stop

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-3 d-none">
                <div class="widget widget-hotline">
                    <div class="d-none d-lg-block">                        
                        <div class="px-5">
                            <img src="{{URL::asset('/html/images/system/call-now.gif')}}" class="img-fluid" alt="">
                        </div>
                        <span class="wghl-text">Hotline</span>
                        <span class="wghl-number text-nowrap"><?=@$settings['hotline']['value']?></span>
                    </div>
                    <div class="media mr-xl-4 d-none">
                        <div class="mr-1 align-self-center">
                            <img src="{{URL::asset('/html/images/system/call-now.gif')}}" class="img-fluid" alt="">
                        </div>
                        <div class="media-body align-self-center mr-3 mr-sm-5 mr-md-0">
                            <span class="wghl-text">Hotline</span>
                            <span class="wghl-number text-nowrap"><?=@$settings['hotline']['value']?></span>
                        </div>
                    </div>
                </div>                
                <?php
                $supports = \App\Helpers\General::get_supports();
                if ($supports) {
                ?>
                <div class="box mt-5">
                    <h5 class="box-title mb-4"><span>Hỗ trợ trực tuyến </span></h5>
                    <div class="row box-card">
                        @foreach($supports as $item)
                        <div class="col-6">
                            <div class="media mb-3">
                                <div class="media-body">
                                    <div class="row">
                                        <div class="col-auto">
                                            <a href="skype:{{$item['account']}}?chat" class="link-skype hvr-buzz-out"><i class="fab fa-skype fa-3x"></i></a>
                                        </div>
                                        <div class="col pl-xl-0">
                                            <a href="skype:{{$item['account']}}?chat" class="link-skype hvr-buzz-out">{{$item['name']}}</a>
                                            <br>({{$item['department']}})
                                            <br>{{$item['phone']}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <?php } ?>                
            </div>
            <div class="col-lg-12 about">
                <div class="ml-lg-3 d-none">
                    <h3 class="pt-title">GIỚI THIỆU</h3>
                    <hr>
                    <p><strong>Công ty TNHH Thương mại Dịch vụ Kỹ thuật Phan Thịnh</strong> là một trong những doanh
                        nghiệp chuyên cung cấp thiết bị và giải pháp trong lãnh vực Kỹ thuật điện - Đo lường - Điều
                        khiển tự động</p>
                    <p>Với chiến lược luôn đổi mới để phát triển, <strong>Công ty TNHH Thương mại Dịch vụ Kỹ thuật Phan
                            Thịnh</strong> không ngừng tìm hiểu sản phẩm mới, nâng cao chất lượng dịch vụ nhằm mang đến
                        lợi ích thiết thực cho khách hàng với những sản phẩm chất lương, dịch vụ tốt và uy tín.</p>
                    <p>Hiện nay, <strong>Công ty TNHH Thương mại Dịch vụ Kỹ thuật Phan Thịnh</strong> là nhà cung cấp và phân phối các thương hiệu sau:</p>
                    <div class="row mb-5">
                        @include('home.partners')
                    </div>
                </div>
                @if (@product_home)
                <div class="box">
                    <h5 class="box-title box-title-three mb-4"><span>Hotline (028) 22 537 797 - ZALO 0833 101 819</span></h5>
                    <div class="row">
                        @foreach($product_home as $product_home_item)
                            <?php
                                $link = route('product_home_detail', ['slug_article' => str_slug($product_home_item['product_code']), 'id' => $product_home_item['id']]);
                            ?>
                        <div class="col-xl-3 col-md-6 mb-4 pb-1">
                            <div class="card h-100">                        
                                <div class="card-header p-3">
                                    <h2 class="card-title text-center m-0"><a href="{{$product_home_item['link_static']}}">{{$product_home_item['product_code']}}</a></h2>
                                </div>
                                <div class="card-body px-3 pt-0 d-flex">                    
                                    <a href="{{$product_home_item['link_static']}}" class="align-self-center mx-auto">
                                        <img class="img-fluid" alt="{{ $product_home_item['product_code'] }}" src="{{ $product_home_item['image_url'].$product_home_item['image'] }}">
                                    </a>
                                </div>
                                @if($product_home_item['short_description'])
                                <div class="card-footer p-3">                            
                                    <div class="mb-4 text-center">{!! $product_home_item['short_description'] !!}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        <!-- @if (@$san_pham_moi) -->
        <div class="box d-none">
            <h5 class="box-title box-title-two mb-4"><span>Sản phẩm mới</span></h5>
            <div id="home_carousel" class="owl-carousel owl-theme">
                <!-- @foreach($san_pham_moi as $a) -->
                    <?php
                    // $link = route('article_detail', ['slug_article' => str_slug($a['name']), 'id' => $a['id']]);
                    ?>
                    <div class="card">
                        <div class="card-body d-flex">
                            <!-- <a href="{{$link}}" class="align-self-center mx-auto"> -->
                                <!-- <img class="img-fluid" alt="{{ $a['name'] }}" src="{{ $a['image_url'].$a['image'] }}"> -->
                            </a>
                        </div>
                        <div class="card-footer">
                            <h6 class="card-title m-0">
                                <!-- <a href="{{$link}}">{{ $a['name'] }}</a> -->
                            </h6>
                        </div>
                    </div>
                <!-- @endforeach -->
            </div>
        </div>
        <!-- @endif -->
    </div>
@stop

@section('after_script')
    <script type='text/javascript'>
        $(document).ready(function () {
        });
    </script>
@stop