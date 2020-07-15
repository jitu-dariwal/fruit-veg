@extends('layouts.front.account-app')

@section('og')
	<meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Ensures optimal rendering on mobile devices. -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge" /> <!-- Optimal Internet Explorer compatibility -->
	
	<script src="https://www.paypal.com/sdk/js?client-id=AQklJAoBs5JcYuPGGEncu8pJ7HH8YTT8RDgvBJRjOReg8t38Vi3U9ZDNwg4pH1Uueci3S2IAEnDp8Unm&currency=GBP"></script>
@endsection

@section('content')
	
	<header>
		<ul class="steps list-unstyled">
			<li><a href="{{ route('checkout.index') }}">1</a></li>
			<li><a href="{{ route('checkout.confirm') }}">2</a></li>
			<li class="active"><a href="javascript:void(0)">3</a></li>
			<li><a href="javascript:void(0)">4</a></li>           
		</ul>
		<h2 class="sub-heading text-center">Select payment method</h2>
	</header>
	
	<div class="row flex-lg-row">
		<div class="col-md-12 col-sm-12">
			@if (session('status'))
				<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Success!</strong> {!! session('status') !!}
				</div>
			@endif
				
			@if (session('warning'))
				<div class="alert alert-warning alert-dismissible">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Warning!</strong> {!! session('warning') !!}
				</div>
			@endif
			
			@if (session('error'))
				<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong> {!! session('error') !!}
				</div>
			@endif
		</div>
		
		<div class="col-lg-6 b-border-right-1"> 
            <div class="px-0 px-md-3">		
				
				<ul class="list-group payment-list">
					@php
						$customer_payment_module = explode(',', $customer->customers_payment_allowed);
					@endphp
				
				
					@if(!empty($paymentMethods) && count($paymentMethods) > 0)
						@foreach($paymentMethods as $key => $method)
							@if(empty($customer->customers_payment_allowed) || in_array($key,$customer_payment_module))
								<li class="list-group-item">
									<div class="custom-control custom-radio">
										<input type="radio" id="{{$key}}" name="payBy" class="custom-control-input" value="{{$key}}" {{ (old('pay_by') == $key) ? 'checked' : '' }}/>
										<label class="custom-control-label" for="{{$key}}">
											{{ $method }}
											@if($key == 'paypal')
												<img src="{{ url('images/paypal-1.png') }}" width="94"  alt=""/>
											@elseif($key == 'pay_by_card')
												<img src="{{ url('images/payment-mastercard.png') }}" width="30"  alt=""/>
												<img src="{{ url('images/payment-visa.png') }}" width="30"  alt=""/>
												<img src="{{ url('images/american-express.png') }}" width="30"  alt=""/>
												<img src="{{ url('images/payment-discover.png') }}" width="30"  alt=""/>
											@endif
										</label>        
									</div>      
								</li>
							@endif
						@endforeach
					@endif
				</ul>         
			</div>
		</div>
		
		<div class="col-lg-6">
			<form class="paymentForm" action="{{ route('checkout.storePayment', $order_id) }}" method="post">
				@csrf
				<input type="hidden" name="pay_by" value="{{old('pay_by')}}"/>
				<div class="px-0 px-md-3">
					<div class="PayByCard" style="display: {{ (old('pay_by') == 'pay_by_card') ? 'block' : 'none' }};">
						@if(!empty($cards) && count($cards) > 0)
							<h6 class="mb-2">Your wallet</h6>
											
							<ul class="list-group payment-list ">
								@foreach($cards as $card)
									<li class="list-group-item wallet">
										<div class="custom-control custom-radio">
											<input type="radio" id="Radio{{$card->id}}" name="payBySaveCard" class="custom-control-input saveCard" data-validation="required" data-validation-depends-on="pay_by" data-validation-depends-on-value="pay_by_card" value="{{$card->id}}" {{ (old('payBySaveCard') == $card->id) ? 'checked' : '' }} />
											<label class="custom-control-label" for="Radio{{$card->id}}">
												@if(in_array($card->card_type, config('constants.card_type.VISA')))
													<img src="{{ url('images/payment-visa.png') }}" width="48"  alt=""/>
												@elseif(in_array($card->card_type, config('constants.card_type.MASTERCARD')))
													<img src="{{ url('images/payment-mastercard.png') }}" width="48"  alt=""/>
												@elseif(in_array($card->card_type, config('constants.card_type.AMEX')))
													<img src="{{ url('images/american-express.png') }}" width="48"  alt=""/>
												@elseif(in_array($card->card_type, config('constants.card_type.JCB')))
													<img src="{{ url('images/payment-jcb.png') }}" width="48"  alt=""/>
												@elseif(in_array($card->card_type, config('constants.card_type.DISCOVER')))
													<img src="{{ url('images/payment-discover.png') }}" width="48"  alt=""/>
												@elseif(in_array($card->card_type, config('constants.card_type.DINERS')))
													<img src="{{ url('images/payment-diners.jpg') }}" width="48"  alt=""/>
												@else
													<img src="{{ url('images/payment-all-card.png') }}" width="48"  alt=""/>
												@endif
												
												card ending {{ $card->number_filtered }}
												
												<div class="form-group">
													<input type="number" name="cvv_{{$card->id}}" class="form-control" style="display: {{ (old('payBySaveCard') == $card->id) ? '' : 'none' }} " placeholder="cvv" data-validation="required" data-validation-depends-on="payBySaveCard" data-validation-depends-on-value="{{$card->id}}">
													@if ($errors->has('cvv_'.$card->id))
														<span class="help-block text-danger"> {{ $errors->first('cvv_'.$card->id) }} </span>
													@endif
												</div>
												
											</label>
										</div>      
									</li>
								@endforeach
								<li class="list-group-item wallet">
									<div class="custom-control custom-radio">
										<input type="radio" id="Radio0" name="payBySaveCard" class="custom-control-input saveCard" data-validation="required" data-validation-depends-on="pay_by" data-validation-depends-on-value="pay_by_card" value="new" {{ (old('payBySaveCard') == 'new') ? 'checked' : '' }} />
										<label class="custom-control-label" for="Radio0">
											Add New
										</label>
									</div>
								</li>
							</ul>
						@endif
						<div class="addNewCard" style="display: {{ (old('payBySaveCard') == 'new') ? 'block' : 'none' }};" >
							<h6>Pay by card</h6>
							<div class="form-group">
								<label >Please enter your name</label>
								<input class="form-control" name="name" placeholder="Enter card holder name" value="{{ old('name') }}" data-validation="required" data-validation-depends-on="payBySaveCard" data-validation-depends-on-value="new" />
								@if ($errors->has('name'))
									<span class="help-block text-danger"> {{ $errors->first('name') }} </span>
								@endif
							</div>
							<div class="form-group">
								<label >Card number</label>
								<input name="number" class="form-control" placeholder="XXXX XXXX XXXX XXXX" value="{{ old('number') }}"  data-validation="required" data-validation-depends-on="payBySaveCard" data-validation-depends-on-value="new" />
								@if ($errors->has('number'))
									<span class="help-block text-danger"> {{ $errors->first('number') }} </span>
								@endif
							</div>
							<div class="row card-details">
								<div class="col-8">
									<div class="form-group">
										<label >Expiration date</label>
										<div class="form-row">
											<div class="col">
												<div class="c-select">
													<select name="exp_month" class="form-control" data-validation="required" data-validation-depends-on="payBySaveCard" data-validation-depends-on-value="new">
														<option value="">Month</option>
														@for($i = 01;$i <= 12; $i++)
															<option {{ (old('exp_month') == $i) ? 'selected' : '' }} value="{{ $i }}"> {{ sprintf('%02d', $i) }} </option>
														@endfor
													</select>
													@if ($errors->has('exp_month'))
														<span class="help-block text-danger"> {{ $errors->first('exp_month') }} </span>
													@endif
												</div>	
											</div>
											<div class="col">
												<div class="c-select">
													<select name="exp_year" class="form-control"  data-validation="required" data-validation-depends-on="payBySaveCard" data-validation-depends-on-value="new">
														<option value="">Year</option>
														@for($i = (int)date('Y');$i <= ((int)date('Y') + 10); $i++)
															<option {{ (old('exp_year') == $i) ? 'selected' : '' }} value="{{ $i }}"> {{ sprintf('%02d', $i) }} </option>
														@endfor
													</select>
													@if ($errors->has('exp_year'))
														<span class="help-block text-danger"> {{ $errors->first('exp_year') }} </span>
													@endif
												</div>  
											</div>
										</div>
									</div>
								</div>
								<div class="col-4 col-md-4">
									<div class="form-group">
										<label >CVV</label>
										<input type="number" name="cvv" class="form-control"  placeholder="cvv" data-validation="required" data-validation-depends-on="payBySaveCard" data-validation-depends-on-value="new">
										@if ($errors->has('cvv'))
											<span class="help-block text-danger"> {{ $errors->first('cvv') }} </span>
										@endif
									</div>
								</div>
							</div>
							<div class="custom-control-outer">
								<div class="custom-control custom-checkbox mb-4">
									<input name="save_card" type="checkbox" class="custom-control-input" id="customCheck1" {{ (old('save_card') == 'on') ? 'checked' : '' }}>
									<label class="custom-control-label" for="customCheck1">Save for future payments</label>
								</div>
							</div>                
						</div>                
					</div>                
					<div class="PayByInvoice" style="display: {{ (old('pay_by') == 'invoice') ? 'block' : 'none' }};">
						<h6>Pay by invoice</h6>
						<div class="custom-control-outer">
							<div class="custom-control custom-checkbox mb-2">
								<input name="pay_by_invoice" type="checkbox" class="custom-control-input" id="pay_by_invoice" data-validation="required" data-validation-depends-on="pay_by" data-validation-depends-on-value="invoice">
								<label class="custom-control-label" for="pay_by_invoice">If you want to pay for orders on invoice, please check this box and we process your credit application usually within 24 hours.</label>
							</div>
							@if ($errors->has('pay_by_invoice'))
								<span class="help-block text-danger"> {{ $errors->first('pay_by_invoice') }} </span>
							@endif
						</div> 
					
						<div class="mb-2">Once approved, you will be confirmed for this order.</div>
						<div class="mb-2">If in any doubt, feel free to call our team on 01708 300 128 (9am - 5pm)</div>
					</div>
					
					<div class="PayByPaypal" style="display: {{ (old('pay_by') == 'paypal') ? 'block' : 'none' }};">
						<h6>Pay by paypal</h6>
						<div id="paypal-button" class="payPalCheckout">
							
						</div>	
					</div>
					
					<div class="confirm_btn" style="display:{{ (!empty(old())) ? '' : 'none' }};">
						<button type="submit" class="btn site-btn d-block min-w100">complete order <span class="ds-right-arrow"></span> </button>
						<p class="small-text-outer text-center"><small>make payment</small></p>
					</div>
				</div>
			</form>
		</div>
	</div>
@endsection
@section('js')
	<script>
		$(document).ready(function(){
			setTimeout(function(){
				$(".callPayPal").click(function(){
					$("iframe.component-frame").contents().find("div.paypal-button").trigger("click");
				});
			},5000);
		});
		
		$('input[name="payBy"]').click(function(){
			$('.PayByInvoice').find('span.help-block').remove();
			$('.PayByCard input,.PayByCard select').css({'border-color' : ''}).nextAll('span').remove();
			$('input[name^="cvv_"]').val('').slideUp().nextAll('span').remove();
			$('input[name="payBySaveCard"]').prop('checked', false);
			$(".addNewCard").slideUp();
			if($(this).val() == 'pay_by_card'){
				$('.PayByCard').slideDown();
				$('.PayByInvoice').slideUp();
				$('.PayByPaypal').slideUp();
				$('.confirm_btn').slideDown().find('button').removeClass('payPalCheckoutBtn');
			}else if($(this).val() == 'invoice'){
				$('.PayByCard').slideUp();
				$('.PayByPaypal').slideUp();
				$('.PayByInvoice').slideDown();
				$('.confirm_btn').slideDown().find('button').removeClass('payPalCheckoutBtn');
			}else{
				$('.PayByCard').slideUp();
				$('.PayByInvoice').slideUp();
				$('.PayByPaypal').slideDown();
				$('.confirm_btn').slideDown().find('button').addClass('payPalCheckoutBtn');
			}
			
			$('input[name="pay_by"]').val($(this).val());
		});

		$('input[name="payBySaveCard"]').click(function(){
			$('.PayByInvoice').find('span.help-block').remove();
			$('.PayByCard input,.PayByCard select').css({'border-color' : ''}).nextAll('span').remove();
			$('input[name="payBySaveCard"]').closest('div').nextAll('span').remove();
			$('input[name^="cvv_"]').val('').slideUp().nextAll('span').remove();
			if($(this).val() == 'new'){
				$(".addNewCard").slideDown();
			}else{
				$('input[name="cvv_'+$(this).val()+'"]').slideDown();
				$(".addNewCard").slideUp();
			}
		});
		
		var order_id = '{{ $order_id }}';
		
		paypal.Buttons({
			style: {
				layout:  'horizontal',
				color:   'white',
				shape:   'rect',
				label:   'buynow',
				height:   55,
				tagline: false,
				branding: true
			},
			
			createOrder: function(data, actions) {
				$("#cover-spin").show(0);
				return fetch('{{ route("checkout.storePayment", $order_id) }}', {
					method: 'post',
					headers: {
						'content-type': 'application/json'
					},
					body: JSON.stringify({
						_token: "{{ csrf_token() }}",
						pay_by: 'paypal',
						type: 'saveOrder',
					})
				}).then(function(res) {
					return res.json();
				}).then(function(details) {
					//console.log("details : ", details);
					$("#cover-spin").hide(0);
					
					if(details.status == false){
						//alert(details.message);
						window.location.href = '{{ route("checkout.confirm") }}';
						return false;
					}else{
						order_id = details.order.id;
					
						return actions.order.create({
							purchase_units: [{
								amount: {
									value: Number(details.order.total),
								},
							}]
						});
					}
				}).catch(function() {
					$("#cover-spin").hide(0);
				});
			},
			
			onApprove: function(data, actions) {
			
				$("#cover-spin").show(0);
				
				return actions.order.capture().then(function(details) {
					//alert('Transaction completed by ' + details.payer.name.given_name);
					
					$("#cover-spin").show(0);
					
					// Call your server to save the transaction
					return fetch('{{ route("checkout.storePayment") }}/'+order_id, {
						method: 'post',
						headers: {
							'content-type': 'application/json'
						},
						body: JSON.stringify({
							_token: "{{ csrf_token() }}",
							pay_by: 'paypal',
							orderID: data.orderID,
						})
					}).then(function(res) {
						return res.json();
					}).then(function(details) {
						//console.log('details : ',details);
						if(details.status){
							window.location.href = '{{ url("thank-you") }}/'+order_id;
						}else{
							if (details.error_code === 'INSTRUMENT_DECLINED') {
								return actions.restart();
							}else{
								setModalPopup(details.message)
							}
						}
					}).catch(function() {
						$("#cover-spin").hide(0);
					});
				}).catch(function() {
					$("#cover-spin").hide(0);
				});
			},
			
			onCancel: function (data) {
				//alert("Payment cancel.");
				setModalPopup('<p>Payment cancel</p>')
			},
			
			onError: function (err) {
				//alert(err);
				setModalPopup(err)
			}
		}).render('#paypal-button');
		
	</script>
@endsection 