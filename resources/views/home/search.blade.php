@extends('home.layout')
@section('title') Tìm kiếm sản phẩm @stop

@section('content')
	<div class="container">
		<nav aria-label="breadcrumb" class="my-4">
      <ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
				<li class="breadcrumb-item active" aria-current="page">Từ khoá tìm kiếm: <strong>{{ $tukhoa }}</strong></li>
			</ol>
		</nav>
		<div class="box">
			<h3 class="box-cate-title"><span>Kết quả tìm kiếm</span></h3>
			<div class="row box-card">
				@if(count($objects['data']))
					@foreach($objects['data'] as $product)
						<div class="col-xl-3 col-lg-4 col-md-6 mb-4">
							<div class="card h-100">
								<div class="card-body d-flex">
									<a href="<?=route('article_detail', ['slug_article' => str_slug($product['name']), 'id' => $product['id']])?>" class="align-self-center mx-auto"><img class="img-fluid" alt="{{ $product['name'] }}" src="{{ $product['image'] }}"></a>
								</div>
								<div class="card-footer">
									<h6 class="card-title m-0">
										<a href="<?=route('article_detail', ['slug_article' => str_slug($product['name']), 'id' => $product['id']])?>">{{ $product['name'] }}</a></h6>
								</div>
							</div>
						</div>
					@endforeach
				@endif
			</div>

			@include('home.paginator')
		</div>
	</div>
@stop