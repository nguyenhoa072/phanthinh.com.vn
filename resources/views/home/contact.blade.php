<?php
$settings = \App\Helpers\General::get_settings();
?>
@extends('home.layout')
@section('title') Liên hệ @stop

@section('after_style')
<style>

</style>
@stop

@section('content')
	<div class="container">
		<div class="box contact mt-4">
			<h3 class="box-cate-title"><span>Liên hệ</span></h3>
			<h4 class="text-uppercase"><?=@$settings['company_name']['value']?></h4>
			<address>                
				MST: <b><?=@$settings['tax_code']['value']?></b>
				<br> Địa chỉ: <strong><?=@$settings['address']['value']?></strong>
				<br> Điện thoại: <strong><?=@$settings['phone']['value']?></strong>
				<br> Tài khoản: <b><?=@$settings['bank']['value']?></b>
				<br> Mail: <a href="mailto:<?=@$settings['email']['value']?>"><strong><?=@$settings['email']['value']?></strong></a>
			</address>
			<iframe src="<?=@$settings['address_map']['value']?>" width="100%" height="550" frameborder="0" style="border:0" allowfullscreen></iframe>
		</div>
	</div>
@stop
@section('after_script')
@stop