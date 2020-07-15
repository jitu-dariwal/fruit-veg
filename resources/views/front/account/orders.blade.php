@extends('layouts.front.app')

@section('content')

<section class="myAccount-wrapper">
    <div class="container">
		@include('layouts.front.top-account')
		
        <div class="myAccount-block">

			@include('layouts.front.left-account')
            
			<div class="account-Content-outer">            
				<div class="row">
					<div class="col-xl-8">
						@include('layouts.front.top-alert-message')
						<div class="yourOrderBlock pb-0">
							<h6>Your orders</h6>
							
							<p>{{ __('content.orders.order_top') }}</p>
							
							<div class="row">
								<div id="accordionorders" class="@if(!$orders->isEmpty() && $orders->count() > 0) accordion @endif yourorders-list">
									<div class="card-header yourorders-heading">
										<h6>Order date</h6>
										<h6>Order id</h6>
										<h6>Order Status</h6>
										<h6>Price</h6>
									</div>
									@if(!$orders->isEmpty() && $orders->count() > 0)
										@foreach($orders as $order)
											<div class="card mb-0">
												<div class="card-header collapsed" data-toggle="collapse" data-parent="#accordionorders" href="#order_{{$order->id}}">
													<span class="orders-info">
														{{date('l jS F Y', strtotime($order->created_at))}}
													</span>
													<span class="orders-info">
														#{{$order->id}}
													</span>
													<span class="orders-info">
														{{$order->name}}
													</span>
													<span class="orders-info">
														£ {{$order->total}}
													</span>
												</div>
												<div id="order_{{$order->id}}" class="collapse orders-info-details" data-parent="#accordionorders" >
													<table class="table">
														<thead>
															<tr>
																<th>qty</th>
																<th>product</th>
																<th>cost</th>
															</tr>
														</thead>
														<tbody>
														@if(!$order->orderproducts->isEmpty() && $order->orderproducts->count() > 0)
															@foreach($order->orderproducts as $product)
																<tr>
																	<td class="qty">{{$product->quantity}}</td>
																	<td class="product-name">{{$product->product_name}}</td>
																	<td class="cost">£{{$product->final_price}}</td>
																</tr>
															@endforeach
														@else
															<tr>
																<td>No products found</td>
															</tr>
														@endif
														</tbody>
													</table>
													<div class="orders-total">
														<div class="total-wrap">
															<div class="total-title">Subtotal</div>
															<div class="total-price">
																£{{$order->sub_total}}
															</div>
														</div>
														<div class="total-wrap">
															<div class="total-title">Discount</div>
															<div class="total-price">
																£{{$order->customer_discount}}
															</div>
														</div>
														<div class="total-wrap">
															<div class="total-title">Delivery</div>
															<div class="total-price">
																£{{$order->shipping_charges}}
															</div>
														</div>
														<div class="total-wrap">
															<div class="total-title">Total</div>
															<div class="total-price">£{{$order->total}}</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-5">
															<div class="orders-delivery-address">
																<h6>Delivery address</h6>
																@if(!empty($order->orderDetail) && $shipAdd = $order->orderDetail)
																	<p>
																	@if(!empty($shipAdd->shipping_add_name))
																	{{$shipAdd->shipping_add_name}}<br/>
																	@endif
																	@if(!empty($shipAdd->shipping_add_company))
																	{{$shipAdd->shipping_add_company}}<br/>
																	@endif
																	@if(!empty($shipAdd->shipping_street_address))
																	{{$shipAdd->shipping_street_address}},<br/>
																	@endif
																	@if(!empty($shipAdd->shipping_address_line2))
																	{{$shipAdd->shipping_address_line2}},<br/>
																	@endif
																	@if(!empty($shipAdd->shipping_city) || !empty($shipAdd->shipping_state))
																	{{$shipAdd->shipping_city.', '.$shipAdd->shipping_state}}<br/>
																	@endif
																	@if(!empty($shipAdd->shipping_post_code))
																	{{$shipAdd->shipping_post_code}},<br/>
																	@endif
																	@if(!empty($shipAdd->shipping_country))
																	{{$shipAdd->shipping_country}},<br/>
																	@endif
																	@if(!empty($shipAdd->shipping_tel_num))
																	{{$shipAdd->shipping_tel_num}},<br/>
																	@endif
																	
																	@if(!empty($shipAdd->shipping_email))
																	{{$shipAdd->shipping_email}},<br/>
																	@endif
																	</p>
																@endif
															</div>
														</div>
														<div class="col-md-7 d-flex flex-column">
															@if($order->order_status_id > 1)
																<a href="{{ route('order.copy', $order->id).'/' }}" class="btn btn-brownish mb-3 py-2 px-5 align-self-end">re-order </a>
															@else
																<a href="{{ route('checkout.payment', $order->id).'/' }}" data-id="{{ $order->id }}" data-url="{{ route('checkout.payment', $order->id) }}" data-coupon="{{ $order->coupon_code }}" data-discount="{{ $order->customer_discount }}" class="btn btn-brownish mb-3 py-2 px-5 align-self-end completePayment_Removeforallpy">Complete order </a>
															@endif
															
															<div class="orders-delivery-btn"> 
																<a href="{{route('accounts.orderprintdownload', [$order->id,'download']).'/'}}" class="btn btn-outline-primary btn-large" target="_blank">download</a>
																<a href="{{route('accounts.orderprintdownload', $order->id).'/'}}" class="btn btn-lightgreen  btn-large" onclick="basicPopup(this.href);return false">print</a> 
															</div>
															<a href="{{route('accounts.orderdetail',$order->id).'/'}}" class="btn btn-brownish mb-3 py-2 px-5 align-self-end">View </a>
														</div>
													</div>
													<div class="order-notes">
														<h6>Notes</h6>
														<p>{{ __('content.orders.order_footer_notice') }}</p>
													</div>
												</div>
											</div>
										@endforeach
									@else
										<div class="card mb-0">
											<div class="card-header">
												<span class="orders-info">No orders found yet.</span>
											</div>
										</div>
												
									@endif
								</div>
							</div>
						</div>
					   
						@include('layouts.front.tell-us-friend')
                       
					</div>
					<div class="col-xl-4">
						@include('layouts.front.right-account')
                    </div>
				</div>
            </div>            
        </div>
    </div>    
</section>

@endsection