@extends('layouts.front.account-app')

@section('content')
	
	<header>
		<ul class="steps list-unstyled">
			<li><a href="javascript:void(0)">1</a></li>
			<li><a href="javascript:void(0)">2</a></li>
			<li><a href="javascript:void(0)">3</a></li>
			<li class="active"><a href="javascript:void(0)">4</a></li>           
		</ul>
		<h2 class="sub-heading text-center">Thank you for placing the following order</h2>
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
							@if(!empty($order->orderproducts) && $order->orderproducts->count() > 0)
								@foreach($order->orderproducts as $product)
									<li>
										<div class="name">{{ $product->product_name }}</div>                        
										<div  class="quantity-block5">
											<input readonly type="text" name="quantity" value="{{ $product->quantity }}" />
										</div>                        
										<div class="price">
											{{config('cart.currency_symbol_2')}} {{ number_format($product->product_price*$product->quantity, 2) }}
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
									{{config('cart.currency_symbol_2')}} {{ $order->sub_total }}
								</div>
							</li>
							<li>
								<div class="name">&nbsp;</div>                        
								<div  class="quantity-block5">
									<strong>Discount:</strong>
								</div>                        
								<div class="price text-nowrap">
									{{config('cart.currency_symbol_2')}} {{ $order->customer_discount }}
								</div>
							</li>
							<li>
								<div class="name">&nbsp;</div>                        
								<div  class="quantity-block5">
									<strong>Estimated total:</strong>
								</div>                        
								<div class="price text-nowrap">
									{{config('cart.currency_symbol_2')}} {{ $order->total }}
								</div>
							</li>
						</ul>                
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4 d-flex justify-content-between flex-column">
			<div class="top-head">
				<ul class="order-details list-unstyled">
					<li>
						<h6>Billing address</h6>
						<p>
							{{ $order->orderDetail->billing_name}}<br/>
							{{ $order->orderDetail->billing_street_address}}<br/>
							
							@if(!empty($order->orderDetail->billing_address_line_2))
								{{ $order->orderDetail->billing_address_line_2 }}<br/>
							@endif
							
							{{ $order->orderDetail->billing_city.', '.$order->orderDetail->billing_state }} <br/>
							{{ $order->orderDetail->billing_postcode }} <br/>
						</p>
					</li>
					<li>
						<h6>Delivery address</h6>
						<p>
							{{ $order->orderDetail->shipping_add_company}}<br/>
							{{ $order->orderDetail->shipping_add_name}}<br/>
							{{ $order->orderDetail->shipping_street_address}}<br/>
							
							@if(!empty($order->orderDetail->shipping_address_line2))
								{{ $order->orderDetail->shipping_address_line2 }}<br/>
							@endif
							
							{{ $order->orderDetail->shipping_city.', '.$order->orderDetail->shipping_state }} <br/>
							{{ $order->orderDetail->shipping_post_code }} <br/>
						</p>
					</li>
					<li>
						<h6>Delivery date</h6>
						<p>
							{{ date('d F Y',strtotime($order->orderDetail->shipdate)) }}
						</p>
					</li> 
					@if($order->payment_method == 'credit-card')
					<li>
						<h6>Payment method</h6>
						<p>
							@if(in_array($order->card_type, config('constants.card_type.VISA')))
								<img src="{{ url('images/payment-visa.png') }}" width="48"  alt=""/>
							@elseif(in_array($order->card_type, config('constants.card_type.MASTERCARD')))
								<img src="{{ url('images/payment-mastercard.png') }}" width="48"  alt=""/>
							@elseif(in_array($order->card_type, config('constants.card_type.AMEX')))
								<img src="{{ url('images/american-express.png') }}" width="48"  alt=""/>
							@elseif(in_array($order->card_type, config('constants.card_type.JCB')))
								<img src="{{ url('images/payment-jcb.png') }}" width="48"  alt=""/>
							@elseif(in_array($order->card_type, config('constants.card_type.DISCOVER')))
								<img src="{{ url('images/payment-discover.png') }}" width="48"  alt=""/>
							@elseif(in_array($order->card_type, config('constants.card_type.DINERS')))
								<img src="{{ url('images/payment-diners.jpg') }}" width="48"  alt=""/>
							@else
								<img src="{{ url('images/payment-all-card.png') }}" width="48"  alt=""/>
							@endif
							
							card ending {{ $order->number_filtered }}
						</p>                    
					</li>
					@endif
				</ul>
			</div>
			<div class="bottom-foot">
				<button type="button" onclick="window.location.href = '{{ route('accounts.orders') }}'" class="btn site-btn d-block min-w100">My Account</button>
			</div>
			<p>&nbsp;</p>
		</div> 
	</div>
@endsection
