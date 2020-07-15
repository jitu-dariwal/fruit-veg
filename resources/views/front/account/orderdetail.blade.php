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
						<div class="yourOrderBlock">
							<h6>Order Information</h6>
							
							<div class="box-body">
								@include('layouts.errors-and-messages')
							</div>
							
							<table width="100%" cellspacing="0" cellpadding="0" border="0">
								<tbody>
									<tr>
										<td>
											<table width="100%" cellspacing="0" cellpadding="2" border="0">
												<tbody>
													<tr>
														<td class="main" colspan="2"><b>Order #{{$order->id}} <small>({{$order->statusName}})</small></b></td>
													</tr>
													<tr>
														<td><img src="{{url('images/pixel_trans.gif')}}" alt="" width="100%" height="10" border="0"></td>
													</tr>
													<tr>
														<td class="smallText" valign="top"><strong>Order Delivery Date: {{date('l jS F Y', strtotime($order->orderDetail->shipdate))}}</strong></td>
													</tr>
													<tr>
														<td class="smallText">Order Date: {{date('l jS F Y', strtotime($order->created_at))}}</td>
														<td class="smallText" align="right">Order Total: £{{$order->total}}</td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
									<tr>
										<td><img src="{{url('images/pixel_trans.gif')}}" alt="" width="100%" height="10" border="0"></td>
									</tr>
									<tr>
										<td>
										<table class="infoBox" width="100%" cellspacing="1" cellpadding="2" border="0">
										<tbody>
										<tr class="infoBoxContents">
											<td width="30%" valign="top">
												<table width="100%" cellspacing="0" cellpadding="2" border="0">
												<tbody>
												<tr>
													<td class="main"><b>Delivery Address</b></td>
												</tr>
												<tr>
													<td class="main">
													@if(!empty($order->orderDetail) && $shipAdd = $order->orderDetail)
															
														@if(!empty($shipAdd->shipping_add_name))
														&nbsp;{{$shipAdd->shipping_add_name}}<br/>
														@endif
														@if(!empty($shipAdd->shipping_add_company))
														&nbsp;{{$shipAdd->shipping_add_company}}<br/>
														@endif
														@if(!empty($shipAdd->shipping_street_address))
														&nbsp;{{$shipAdd->shipping_street_address}},<br/>
														@endif
														@if(!empty($shipAdd->shipping_address_line2))
														&nbsp;{{$shipAdd->shipping_address_line2}},<br/>
														@endif
														
														@if(!empty($shipAdd->shipping_city) || !empty($shipAdd->shipping_state))
														&nbsp;{{$shipAdd->shipping_city.', '.$shipAdd->shipping_state}}<br/>
														@endif
														
														@if(!empty($shipAdd->shipping_post_code))
														&nbsp;{{$shipAdd->shipping_post_code}},<br/>
														@endif
														
														@if(!empty($shipAdd->shipping_country))
														&nbsp;{{$shipAdd->shipping_country}},<br/>
														@endif
														
														@if(!empty($shipAdd->shipping_tel_num))
														&nbsp;{{$shipAdd->shipping_tel_num}},<br/>
														@endif
														
														@if(!empty($shipAdd->shipping_email))
														&nbsp;{{$shipAdd->shipping_email}},<br/>
														@endif
														
													@else
														Not available.
													@endif
													</td>
												</tr>
												<tr>
													<td class="main"><b>Shipping Method</b></td>
												</tr>
												<tr>
													<td class="main">{{$order->shipping_method}}</td>
												</tr>
												</tbody>
												</table>
											</td>
											<td width="70%" valign="top">
											<table width="100%" cellspacing="0" cellpadding="2" border="0">
											<tbody>
												<tr>
													<td class="main" colspan="2"><b>Products</b></td>
													<td class="main" align="right"><b>Base</b></td>
													<td class="main" align="right"><b>Final</b></td>
												</tr>
												
												@if(!$order->orderproducts->isEmpty() && $order->orderproducts->count() > 0)
													@foreach($order->orderproducts as $product)
													<tr class="">
														<td class="main" width="30" valign="top" align="right">{{$product->quantity}} x</td>
														<td class="main" valign="top">{{$product->product_name}}<br/>
														</td>
														<td class="main" valign="top" align="right">
														{{config('cart.currency_symbol_2')}} {{$product->product_price}}
														</td>
														<td class="main" valign="top" align="right">
														{{config('cart.currency_symbol_2')}}  {{$product->final_price}}
														</td>
													</tr>
													@endforeach
												@else
													<tr>
														<td>No products found</td>
													</tr>
												@endif
											</tbody>
											</table>
											</td>
										</tr>
										</tbody>
										</table>
										</td>
									</tr>
									<tr>
										<td><img src="{{url('images/pixel_trans.gif')}}" alt="" width="100%" height="10" border="0"></td>
									</tr>
									<tr>
										<td class="main"><b>Billing Information</b></td>
									</tr>
									<tr>
										<td><img src="{{url('images/pixel_trans.gif')}}" alt="" width="100%" height="10" border="0"></td>
									</tr>
									<tr>
										<td>
										<table class="infoBox" width="100%" cellspacing="1" cellpadding="2" border="0">
										<tbody>
										<tr class="infoBoxContents">
											<td width="30%" valign="top">
											<table width="100%" cellspacing="0" cellpadding="2" border="0">
											<tbody>
											<tr>
												<td class="main"><b>Billing Address</b></td>
											</tr>
											<tr>
												<td class="main">
												@if(!empty($order->orderDetail) && $billAdd = $order->orderDetail)
														
													@if(!empty($billAdd->billing_add_name))
													&nbsp;{{$billAdd->billing_add_name}}<br/>
													@endif
													@if(!empty($billAdd->billing_add_company))
													&nbsp;{{$billAdd->billing_add_company}}<br/>
													@endif
													@if(!empty($billAdd->billing_street_address))
													&nbsp;{{$billAdd->billing_street_address}},<br/>
													@endif
													@if(!empty($billAdd->billing_address_line2))
													&nbsp;{{$billAdd->billing_address_line2}},<br/>
													@endif
													
													@if(!empty($billAdd->billing_city) || !empty($billAdd->billing_state))
													&nbsp;{{$billAdd->billing_city.', '.$billAdd->billing_state}}<br/>
													@endif
													
													@if(!empty($billAdd->billing_post_code))
													&nbsp;{{$billAdd->billing_post_code}},<br/>
													@endif
													
													@if(!empty($billAdd->billing_country))
													&nbsp;{{$billAdd->billing_country}},<br/>
													@endif
													
													@if(!empty($billAdd->billing_tel_num))
													&nbsp;{{$billAdd->billing_tel_num}},<br/>
													@endif
													
													@if(!empty($billAdd->billing_email))
													&nbsp;{{$billAdd->billing_email}},<br/>
													@endif
													
												@else
													Not available.
												@endif
												</td>
											</tr>
											<tr>
												<td class="main"><b>Payment Method</b></td>
											</tr>
											<tr>
												<td class="main">{{$order->payment_method}}</td>
											</tr>
											</tbody>
											</table>
											</td>
											
											<td width="70%" valign="top">
											<table width="100%" cellspacing="0" cellpadding="2" border="0">
											<tbody>
											<tr>
												<td class="main" width="100%" align="right">Sub-Total:</td>
												<td class="main" align="right">£{{$order->sub_total}}</td>
											</tr>
											<tr>
												<td class="main" width="100%" align="right">Delivery</td>
												<td class="main" align="right">£{{$order->shipping_charges}}</td>
											</tr>
											<tr>
												<td class="main" width="100%" align="right">Customer Discount:</td>
												<td class="main" align="right">{{$order->customer_discount}}</td>
											</tr>
											<tr>
												<td class="main" width="100%" align="right">Total:</td>
												<td class="main" align="right">£{{$order->total}}</td>
											</tr>
											</tbody>
											</table>
											</td>
										</tr>
										</tbody>
										</table>
										</td>
									</tr>
									<tr>
										<td><img src="{{url('images/pixel_trans.gif')}}" alt="" width="100%" height="10" border="0"></td>
									</tr>
									<tr>
										<td class="main"><b>Order History</b></td>
									</tr>
									<tr>
										<td><img src="{{url('images/pixel_trans.gif')}}" alt="" width="100%" height="10" border="0"></td>
									</tr>
									<tr>
										<td colspan="2">
										<table class="infoBox" width="100%" cellspacing="1" cellpadding="2" border="0">
										<tbody>
										<tr class="infoBoxContents">
											<td valign="top">
											<table width="100%" cellspacing="0" cellpadding="2" border="0">
											<tbody>
											@if(!$order->order_status_historys->isEmpty() && $order->order_status_historys->count() > 0)
												@foreach($order->order_status_historys as $history)
												<tr>
													<td class="main" width="70" valign="top">
														{{date('d/m/Y',strtotime($history->created_at))}}
													</td>
													<td class="main" width="170" valign="top">
														{{$history->statusName}}
													</td>
													<td class="main" valign="top">&nbsp;</td>
												</tr>
												@endforeach
											@else
												<tr>
													<td>History not found</td>
												</tr>
											@endif
											</tbody>
											</table>
											</td>
										</tr>
										</tbody>
										</table>
										</td>
									</tr>
									<tr>
										<td><img src="{{url('images/pixel_trans.gif')}}" alt="" width="100%" height="10" border="0"></td>
									</tr>
									<tr>
										<td>
										<table class="infoBox" width="100%" cellspacing="1" cellpadding="2" border="0">
										<tbody>
										<tr class="infoBoxContents">
											<td width="100%">
											<table width="100%" cellspacing="0" cellpadding="2" border="0">
											<tbody>
											<tr>
												<td class="main" width="30%" align="left">
													<a class="btn btn-lightgreen btn-large" onclick="window.history.go(-1);" href="javascript:void(0)">BACK</a>
												</td>
												<td class="main" align="right">
													<a class="btn btn-lightgreen  btn-large" href="{{route('accounts.orderprintdownload', $order->id).'/'}}"  onclick="basicPopup(this.href);return false">Print</a>
												</td>
											</tr>
											</tbody>
											</table>
											</td>
										</tr>
										</tbody>
										</table>
										</td>
									</tr>
								</tbody>
							</table>
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