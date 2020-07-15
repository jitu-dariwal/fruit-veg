@extends('layouts.front.account-app')

@section('content')
	
	<header>
		<ul class="steps list-unstyled">
			<li><a href="{{ route('checkout.index') }}">1</a></li>
			<li class="active"><a href="javascript:void(0)">2</a></li>
			<li><a href="javascript:void(0)">3</a></li>
			<li><a href="javascript:void(0)">4</a></li>           
		</ul>
		<h2 class="sub-heading text-center">Confirm order</h2>
	</header>
	
	<div class="row">
		<div class="col-md-12 col-sm-12">
			@if (session('status'))
				<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Success!</strong> {{ session('status') }}
				</div>
			@endif
				
			@if (session('warning'))
				<div class="alert alert-warning alert-dismissible">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Warning!</strong> {{ session('warning') }}
				</div>
			@endif
			
			@if (session('error'))
				<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong> {{ session('error') }}
				</div>
			@endif
		</div>
		
		<div class="col-md-8">              
            <div id="accordion51" class="accordion2 custom-accordion4">                
				<div class="card">           
					<div id="collapse61" class="card-body collapse show" data-parent="#accordion51">
						<ul class="order-grid list-unstyled">
							@if(!empty($cartItems) && $cartItems->count() > 0)
								@foreach($cartItems as $cartItem)
									<li>
										<div class="name">{{ $cartItem->name }}</div>                        
										<div  class="quantity-block5">
											<input readonly type="text" name="quantity" value="{{ $cartItem->qty }}" id="prd_qty_{{$cartItem->id}}"/>
										</div>                        
										<div class="price">
											{{config('cart.currency_symbol_2')}} {{ number_format($cartItem->price*$cartItem->qty, 2) }}
										</div>
									</li>
								@endforeach
							@endif
							<li>
								<div class="name">&nbsp;</div>                        
								<div  class="quantity-block5">
									<strong>Sub total:</strong>
								</div>                        
								<div class="price text-nowrap">
									{{config('cart.currency_symbol_2')}} {{ $subtotal }}
								</div>
							</li>
							<li>
								<div class="name">&nbsp;</div>                        
								<div  class="quantity-block5">
									<strong>Discount:</strong>
								</div>                        
								<div class="price text-nowrap">
									{{config('cart.currency_symbol_2')}} {{ $discount_coupon_amount }}
								
								</div>
							</li>
							<li>
								<div class="name">&nbsp;</div>                        
								<div  class="quantity-block5">
									<strong>Total:</strong>
								</div>                        
								<div class="price text-nowrap">
									{{config('cart.currency_symbol_2')}} {{ $total }}
								</div>
							</li>
						</ul>   
						<div class="row">
							<form class="form-horizontal" role="form" method="POST" action="{{ route('checkout.storeConfirmation') }}">
								{{ csrf_field() }}
								<div class="col-md-12 couponCodeDiv">
									<legend><i class="fa fa-coupon"></i> Discount Coupons</legend>
									Enter Redeem Code&nbsp;&nbsp;<input type="text" name="redeem_code" class="form-control" value="{{ $redeem_code }}"> and click <br><input type="submit" name="submit_coupon" value="Reedeem" class="btn site-btn">
									<br>
								</div>
							</form>
                        </div>
					</div>
				</div>
				
				<p class="pt-1">Because our products are purchased fresh on a daily basis from wholesale suppliers, prices can be subject to market fluctuations and, although not common, the estimated total price can change for orders after they are placed. We will always do our best to manage customer budget expectations but reserve the right to amend the total price at time of delivery if necessary</p>
			</div>
		</div>
		<div class="col-md-4 d-flex justify-content-between flex-column">
			<div class="top-head">
				<div>
					<button type="button" onclick="window.location.href = '{{ route("checkout.payment") }}'" class="btn site-btn d-block min-w100">continue <span class="ds-right-arrow"></span></button>
					<p class="small-text-outer text-center"><small>to choose payment method</small></p>
				</div>
				<ul class="order-details list-unstyled">
					<li>
						<h6>Billing address</h6>
						<p>
							{{ $billingAdd['first_name'].' '.$billingAdd['last_name']}}<br/>
							{{ $billingAdd['street_address'] }}<br/>
							
							@if(!empty($billingAdd['address_line_2']))
								{{ $billingAdd['address_line_2'] }}<br/>
							@endif
							
							{{ $billingAdd['city'].', '.$billingAdd['county_state'] }} <br>
							{{ $billingAdd['post_code'] }} <br>
						</p>
					</li>
					<li>
						<h6>Delivery address</h6>
						<p>
							{{ $deliveryAddress->company_name }}<br/>
							{{ $deliveryAddress->first_name.' '.$deliveryAddress->last_name}}<br/>
							{{ $deliveryAddress->street_address}}<br/>
							
							@if(!empty($deliveryAddress->address_line_2))
								{{ $deliveryAddress->address_line_2 }}<br/>
							@endif
							
							{{ $deliveryAddress->city.', '.$deliveryAddress->county_state }} <br>
							{{ $deliveryAddress->post_code }} <br>
						</p>
						<a href="{{ route('checkout.index') }}" class="edit">Edit</a>
					</li>
					<li>
						<h6>Delivery date</h6>
						<p>
							{{ date('d F Y',strtotime(session('checkout.step1.delivery_date'))) }}
						</p>
						<a href="{{ route('checkout.index') }}" class="edit">Edit</a>
					</li>                 
				</ul>
			</div>
			<div class="bottom-foot">
				<button type="button" onclick="window.location.href = '{{ route("checkout.payment") }}'" class="btn site-btn d-block min-w100">continue <span class="ds-right-arrow"></span></button>
				<p class="small-text-outer text-center"><small>to choose payment method</small></p>
			</div>
		</div> 
	</div>
	<div class="bottom-border-btn">
		<a href="{{ route('checkout.index').'/' }}" class="btn site-outline-btn d-block d-md-inline-block">Back</a>
	</div>
@endsection
