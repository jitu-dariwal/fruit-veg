<header class="rmr-header"><a href="{{URL::to('/')}}"><img src="{{ asset('images/fruit-for-the-office.svg') }}" width="100" alt=""/></a></header>
<div class="container {{ (class_basename(Route::current()->controller) == 'CheckoutController') ? 'checkout-process-wrapper' : '' }}">
	<div class="row">
		<div class="col-xl-10 offset-xl-1">