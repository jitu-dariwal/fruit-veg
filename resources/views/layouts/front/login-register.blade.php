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
		
		<title>{{ config('app.name') }}</title>
		
		<meta name="description" content="Modern open-source e-commerce framework for free">
		<meta name="tags" content="modern, opensource, open-source, e-commerce, framework, free, laravel, php, php7, symfony, shop, shopping, responsive, fast, software, blade, cart, test driven, adminlte, storefront">
		<meta name="author" content="Jeff Simons Decena">
		
		<!-- Bootstrap -->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.9.0/css/all.css" integrity="sha384-i1LQnF23gykqWXg6jxC2ZbCbUMxyw5gLZY6UiUS98LYV5unm8GWmfkIS6jqJfb4E" crossorigin="anonymous">
		<link rel="stylesheet" href="https://use.typekit.net/liv4xkh.css">
		<link href="{{ asset('css/style.min.css') }}" rel="stylesheet">
		
		@yield('css')
		
		<meta property="og:url" content="{{ request()->url() }}"/>
		
		@yield('og')
		
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
	</head>
	<body>
		<header class="rmr-header"><img src="images/fruit-for-the-office.svg" width="100" alt=""/></header>

		<div class="container">
			<div class="row">
				<div class="col-xl-10 offset-xl-1">
					@yield('content')
					
					<p class="bottom-line">
						If you're having any problems logging in please contact us on 0808 141 2828 or email <a href="#">info@fruitandveg.co.uk	</a>
					</p>
				</div>
			</div>
		</div>
		
		<script src="{{ asset('js/front.min.js') }}"></script>
		<script src="{{ asset('js/custom.js') }}"></script>
		<script>
			if(navigator.userAgent.indexOf('Mac') > 0)
				$('body').addClass('mac-os');    
		</script>
	</body>
</html>