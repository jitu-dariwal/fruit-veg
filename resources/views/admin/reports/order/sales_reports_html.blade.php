<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

   <title>{{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ asset('css/admin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.datepicker.css') }}">
	
	<style>
	.packer-menu a {
		font-size: x-large;
	}
	</style>
    <style>
.dataTableHeadingRow {
    background-color: rgb(201, 201, 201);
}
.product_items {
	display:none;
}
.show_items {
	background-color: rgb(241, 241, 241);
}
</style>
	
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicons/apple-icon-57x57.png')}}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicons/apple-icon-60x60.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicons/apple-icon-72x72.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicons/apple-icon-76x76.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicons/apple-icon-114x114.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/apple-icon-120x120.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicons/apple-icon-144x144.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicons/apple-icon-152x152.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-icon-180x180.png')}}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('favicons/android-icon-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/favicon-96x96.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png')}}">
    <link rel="manifest" href="{{ asset('favicons/manifest.json')}}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('favicons/ms-icon-144x144.png')}}">
    <meta name="theme-color" content="#ffffff">
<style>.cke{visibility:hidden;}</style></head>
<body class="skin-purple sidebar-mini">
<noscript>
    <p class="alert alert-danger">
        You need to turn on your javascript. Some functionality will not work if this is disabled.
        <a href="https://www.enable-javascript.com/" target="_blank">Read more</a>
    </p>
</noscript>
<!-- Site wrapper -->
<div class="wrapper">
    
    <!-- =============================================== -->

<!-- Left side column. contains the sidebar -->


<!-- =============================================== -->
    <!-- Content Wrapper. Contains page content -->
    
            <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        
            <div class="box">
                <div class="box-body">
                    <h2>Sales Report</h2>
					@if(request()->has('report') && request('report')==3)
                        @include('admin.reports.partials.weekly_sales_reports')
				    @elseif(request()->has('report') && request('report')==1)
					    @include('admin.reports.partials.yearly_sales_reports')
					@elseif(request()->has('report') && request('report')==4)
					    @include('admin.reports.partials.daily_sales_reports')
					@else
						@include('admin.reports.partials.monthly_sales_reports')
					@endif
					</div>
                <!-- /.box-body -->
                <div class="box-footer">
                </div>
            </div>
            <!-- /.box -->
        

    </section>
    <!-- /.content -->
    
    <!-- /.content-wrapper -->

    
    <!-- Control Sidebar -->

<!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
</div>
<!-- ./wrapper -->

<script src="http://local.fruitandveg.co.uk/js/admin.min.js"></script>
<script src="http://local.fruitandveg.co.uk/js/bootstrap.datepicker.min.js"></script>
<script src="//cdn.ckeditor.com/4.8.0/standard/ckeditor.js"></script>
<script src="http://local.fruitandveg.co.uk/js/scripts.js?v=0.2"></script>



</body></html>