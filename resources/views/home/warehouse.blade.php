@extends('home.layout')
@section('title') Kho hàng có sẵn @stop

@section('after_style')
<style>

</style>
@stop

@section('content')
	<div class="container">
		<div class="box store mt-4">
			<h3 class="box-cate-title"><span>Tồn kho</span></h3>
			<table id="demo-custom-toolbar" class="table table-bordered table-striped table-hover" cellspacing="0"
				   data-toggle="table"
				   data-locale="vi-VN"
				   data-toolbar="#table-toolbar"
				   data-striped="true"
				   data-url="{!! route('ajax-warehouse') !!}"
				   data-search="true"
				   data-trim-on-search="false"
				   data-show-refresh="false"
				   data-show-toggle="false"
				   data-show-columns="true"
				   data-pagination="true"
				   data-side-pagination="server"
				   data-page-size="20"
				   data-page-list="[10, 20, 40, 80, ALL]"
				   data-query-params="queryParams"
				   data-cookie="true"
				   data-sort-name="amount"
				   data-sort-order="desc"
				   data-cookie-id-table="warehouse"
				   data-cookie-expire="{!! config('params.bootstrapTable.extension.cookie.cookieExpire') !!}">
				<thead>
				<tr>
					<th data-field="check_id" data-checkbox="true">ID</th>
					<th data-field="name" data-sortable="true">Tên sản phẩm</th>
					<th data-field="category" data-sortable="true">Nhóm hàng</th>
					<th data-class="text-right" data-field="amount" data-formatter="fmAmount" data-sortable="true">Số lượng</th>
				</tr>
				</thead>
			</table>
		</div>
	</div>
@stop

@section('after_script')
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.12.1/bootstrap-table.min.css">
	<!-- Latest compiled and minified JavaScript -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.12.1/bootstrap-table.min.js"></script>
	<!-- Latest compiled and minified Locales -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.12.1/locale/bootstrap-table-vi-VN.min.js"></script>

	<script type="text/javascript">
        function fmAmount(value, row, index) {
            return numeral(value).value() > 0 ? value : 'Liên hệ';
        }
        function fmUnit(value, row, index) {
            return value || 'Cái';
        }
        function fmPrice(value, row, index) {
            return numeral(value).format();
        }

		function queryParams(params) {
            return params;
        }

        $(document).ready(function() {
            var $table = $('#demo-custom-toolbar');

            $table.on('load-success.bs.table', function () {
            });
        });

	</script>
@stop