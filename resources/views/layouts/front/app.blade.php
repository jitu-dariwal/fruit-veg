<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-9325492-23"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', '{{ env('GOOGLE_ANALYTICS') }}');
		</script>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		
		@if (trim($__env->yieldContent('meta_tags')))
			@yield('meta_tags')
		@else
			<title>{{ config('app.name') }}</title>
			<meta name="description" content="{{ config('app.name') }}">
			<meta name="tags" content="{{ config('app.name') }}">
		@endif
		
		<meta name="author" content="{{ config('app.name') }}">
		
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.9.0/css/all.css" integrity="sha384-i1LQnF23gykqWXg6jxC2ZbCbUMxyw5gLZY6UiUS98LYV5unm8GWmfkIS6jqJfb4E" crossorigin="anonymous">
		<link rel="stylesheet" href="https://use.typekit.net/liv4xkh.css">
		<link href="{{ asset('css/style.min.css') }}" rel="stylesheet">
		
		<!-- jQuery UI css (necessary for calendar) -->
		<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css"
         rel = "stylesheet">
		
		@yield('css')
		<meta property="og:url" content="{{ request()->url() }}"/>
		
		@if (trim($__env->yieldContent('og')))
			@yield('og')
		@else
			<meta property="og:title" content="{!! config('app.name') !!}"/>
			<meta property="og:description" content="{!! config('app.name') !!}"/>
		@endif
		
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
	</head>
	<body>
		@include('layouts.front.header')

		@yield('content')

		@include('layouts.front.footer')
		
		<!-- The Modal -->
		<div class="modal fade" id="modalPopup">
			<div class="modal-dialog modal-sm modal-dialog-scrollable">
				<div class="modal-content">
					<!-- Modal Header -->
						<div class="modal-header pt-1 pb-1">
							<h4 class="modal-title"></h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

					<!-- Modal body -->
					<div class="modal-body"></div>

					<!-- Modal footer -->
					<div class="modal-footer d-none"></div>
				</div>
			</div>
		</div>
		
		<!-- Loader div -->
		<div id="cover-spin"> 
			<span>	
				<div class="spinner-grow spinner-grow-lg"></div>
				<b>Please wait.....</b>
			</span>
		</div>
		
		<!-- Jquery validations-->
		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
		
		<script src="{{ asset('js/front.min.js') }}"></script>
		
		<!-- Jquery UI Calendar-->
		<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
		
		<!-- Include all compiled plugins (below), or include individual files as needed --> 
		<script src="{{ asset('js/jquery.getAddress-2.0.1.min.js') }}"></script>
		
		<script src="{{ asset('js/custom.js') }}"></script>
		@yield('js')
	</body>
</html>