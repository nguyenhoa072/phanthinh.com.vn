@extends('home.layout')

@section('title') {{ $product['name'] }} @stop

@if ($product['short_description'])
@section('og_description'){{ strip_tags($product['short_description']) }}@stop
@endif

@section('after_style')
<style>
</style>
@stop

@section('content')
	<nav aria-label="breadcrumb" style="background-color: #e9ecef">
		<div class="container">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
				<li class="breadcrumb-item"><a href="/{!! \App\Helpers\General::toSlug($product['manufacturer_name']) !!}-m{!! $product['manufacturer_id'] !!}.html">{{ $product['manufacturer_name'] }}</a></li>
				<li class="breadcrumb-item"><a href="/{!! \App\Helpers\General::toSlug($product['category_name']) !!}-c{!! $product['category_id'] !!}.html">{{ $product['category_name'] }}</a></li>
				<li class="breadcrumb-item active" aria-current="page">{{ $product['name'] }}</li>
			</ol>
		</div>
	</nav>
	<div class="container">
		<div class="product-detail">
			<div class="row">
				<div class="col-xl-4 col-lg-5">
					<div class="app-figure text-center" id="zoom-fig">
						<a id="Zoom-1" class="MagicZoom" title="{{ $product['name'] }}" href="{{ $product['image'] }}">
							<img src="{{ $product['image'] }}" alt=""/>
						</a>
						@if(count($images))
							<div class="selectors my-3">
								@foreach($images as $image)
									<a data-zoom-id="Zoom-1" href="{{ $image['image'] }}" data-image="{{ $image['image'] }}">
										<img srcset="{{ $image['image'] }}" src="{{ $image['image'] }}" width="50">
									</a>
								@endforeach
							</div>
						@endif
					</div>
				</div>
				<div class="col-xl-8 col-lg-7">
					<h2 class="product-detail-name">{{ $product['name'] }}</h2>
					<p><span class="mr-5">Số lượng: {{ $product['amount'] }}</span> Giá tiền: {{ $product['price'] != 0 ? $product['price'] : 'Liên hệ' }}</p>
					<hr>
					<div class="product-detail-content">
						{!! $product['description'] !!}
					</div>
				</div>
			</div>

			<h4>Danh sách sản phẩm</h4>
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead class="thead-light">
						<tr>
							<th class="text-nowrap">Tên sản phẩm</th>
							<th style="min-width: 250px">Mô tả</th>
							<th class="text-nowrap">Tồn kho</th>
						</tr>
					</thead>
					<tbody>
				@foreach($products_same_code as $item)
					<tr>
						<th class="text-nowrap"><?=$item['name']?></th>
						<td><?=$item['short_description']?></td>
						<td><?=$item['amount']?></td>
					</tr>
				@endforeach
					</tbody>
				</table>
			</div>
		</div>
		@if(count($diffProducts))
			<div class="box">
				<h5 class="box-title mt-5"><span>Sản phẩm liên quan</span></h5>
				<div class="row box-card">
					@foreach($diffProducts as $item)
						<div class="col-xl-3 col-lg-4 col-md-6 mb-4">
							<div class="card h-100">
								<div class="card-body d-flex">
									<a href="/{!! \App\Helpers\General::toSlug($item['name']) !!}-p{!! $item['id'] !!}.html" class="align-self-center mx-auto"><img class="img-fluid" alt="{{ $item['name'] }}" src="{{ $item['image'] }}"></a>
								</div>
								<div class="card-footer">
									<h6 class="card-title m-0"><a href="/{!! \App\Helpers\General::toSlug($item['name']) !!}-p{!! $item['id'] !!}.html">{{ $item['name'] }}</a></h6>
								</div>
							</div>
						</div>
					@endforeach
				</div>
			</div>
		@endif
	</div>
@stop
@section('after_script')
@stop