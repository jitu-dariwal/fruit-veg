@extends('layouts.front.account-app')

@section('content')
	<!-- Main content -->
	
	<header>
		<h2 class="sub-heading text-center mb-0">Delivery address</h2>  
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
			
			@if($errors->any())
				{!! implode('', $errors->all('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>:message</div>')) !!}
			@endif
		</div>
	</div>
	
	<div class="owl-carousel owl-theme my-carousel d-block d-md-none">
		@if(!empty($deliveryAddresses) && $deliveryAddresses->count() > 0)
			@foreach($deliveryAddresses as $add)
				<div class="item">
					<span> {{$add->company_name}} </span>
					<a href="javascript:void(0)" class="link editAdd">Edit</a>
				</div>
			@endforeach
		@endif
	</div>
	<div class="d-md-block d-none" style="display:{{ (!empty($deliveryAddresses) && $deliveryAddresses->count() > 0) ? '' : 'none !important'}}">
		<h6 class="mb-2 mt-2">Address you've entered</h6>          
		<ul class="mobile-edit-address list-unstyled ">
			@if(!empty($deliveryAddresses) && $deliveryAddresses->count() > 0)
				@foreach($deliveryAddresses as $add)
					@php
						$add->delivery_window = str_replace(' ','',date("g:i a", $add->Access_Time)).'__'.str_replace(' ','',date("g:i a", $add->Access_Time_latest));
					@endphp
					<li class="mea-inner">
						<span> {{$add->company_name}} </span>
						<a href="javascript:void(0)" class="link editAdd" data-form="{{json_encode($add->toArray())}}">Edit</a>
					</li>
				@endforeach
			@endif
		</ul>
	</div>
	
	<div class="row">
		<form class="col-sm-12 account-form" id="step3Form" method="POST" action="{{ route('registerStep3') }}">
			@csrf
			<input type="hidden" name="customer_id" value="{{ session('tempCustomer.id') }}"/>
			<input type="hidden" id="id" name="id" value=""/>
			<div class="row">
				<div class="col-md-6 col-sm-12 d-border-right">
					<div class="pl-0 pl-lg-3">
						<h6>Please enter your delivery address</h6>
						
						<div class="form-group mb-4">
							<label>You can enter multiple delivery addresses. Enter first and then press 'Save and Add Additional Address</label>              
							<input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name" data-validation="required length" data-validation-length="1-255"/>             
						</div>
						
						<div id="custom-search-input3">
							<div class="input-group">
								<label>Business postcode <span class="text-danger">*</span></label> 
								<input class="search-query form-control" id="postcode" name="postcode" placeholder="Enter postcode" data-validation="required length" data-validation-length="1-255">
								<span class="input-group-btn">
									<button type="button" id="searchPostCode">
										<span class="ds-search"></span>
									</button>
								</span>
							</div>
						</div>
						
						<div class="form-group">
							<div class="position-relative" id="postcode_lookup"></div>
						</div>
							  
						<div class="mb-4 addressBlock" style="display:{{ (true) ? 'none' : 'none' }};">
							<div class="form-group">
								<label>Business address <span class="text-danger">*</span></label>                
							</div>
							
							<div class="form-group">                
								<input class="form-control" id="line1"  name="line1" placeholder="First line of address" data-validation="required length" data-validation-length="1-255">
							</div>
							
							<div class="form-group">                
								<input class="form-control" id="line2" name="line2" placeholder="Second line of address" data-validation="length" data-validation-optional="true" data-validation-length="1-255">
							</div>
							
							<div class="form-group mb-4">                
								<input class="form-control" id="town" name="town" placeholder="Town" data-validation="required length" data-validation-length="1-255">               
							</div>
							
							<div class="form-group mb-4">                
								<input class="form-control" id="county" name="county" placeholder="County" data-validation="required length" data-validation-length="1-255">               
							</div>
						</div>
						
						<input type="hidden" name="country_id" value="{{ config('constants.country_id') }}"/>
					</div>
				</div>
				<div class="col-md-6 col-sm-12">
					<div class="pr-0 pr-lg-3">
						<h6>Delivery notes</h6>
						<div class="form-group mb-4">
							<label>Please provide any delivery notes below <span class="text-danger">*</span></label>              
							<textarea class="form-control c-textarea" id="delivery_notes" name="delivery_notes" rows="4" placeholder="Please provide any delivery notes here. This could be an security measures in the building or who we should ask for when making delivery" data-validation="required"></textarea>
						</div>
						
						<div class="form-group form-row align-items-center">
							<label class="col-lg-6 col-form-label custom-label">Preferred delivery window: <span class="text-danger">*</span></label>
							<div class="col-lg-6">
								@php
									$selectOption = old('delivery_window');
								@endphp
								
								<select name="delivery_window" id="delivery_window" class="form-control" data-validation="required">
									<option value="">Select time</option>
									@if(!empty(config('constants.delivery_window_options')))
									@foreach(config('constants.delivery_window_options') as $key => $option)
										<option value="{{ $key }}" @if($selectOption == $key) selected="selected" @endif>{{ $option }}</option>
									@endforeach
									@endif
								</select>
								@if ($errors->has('delivery_window'))
									<span class="help-block text-danger">
										<strong>{{ $errors->first('delivery_window') }}</strong>
									</span>
								@endif
							</div>
						</div>
						
						<div class="custom-control-outer mb-2">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="primary_address" name="primary_address" @if(old('primary_address') == 1) checked="checked" @endif value="1" />
								<label class="custom-control-label" for="primary_address">Check this box if you want make this address as primary.</label>
							</div>
						</div>
						
						<div class="custom-control-outer mb-2">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="access_24_hours" name="access_24_hours" @if(old('access_24_hours') == 1) checked="checked" @endif value="1" />
								<label class="custom-control-label" for="access_24_hours">Check this box if there's 24 hours access for deliveries</label>
							</div>
						</div>
						<br>
						
						<a href="javascript:void(0)" class="btn btn-outline-primary btn-large mb-4 addAddress">Save and add additional address</a>
						
						<a href="javascript:void(0)" data-url="{{route('registerStep',4)}}" class="float-lg-right btn site-btn mb-2 continueBtn addAddress">continue <span class="ds-right-arrow"></span></a>
						
						<!--<a href="{{route('registerStep',4)}}" class="float-lg-right btn site-btn mb-2 continueBtn {{(count($deliveryAddresses) <= 0)?'d-none':''}}">continue <span class="ds-right-arrow"></span></a>-->
						
					</div>
				</div>
			</div>
		</form>
	</div>
	
	<style>
		#opc_error_message{
			width: 100%;
			margin-top: 0.25rem;
			font-size: 80%;
			color: #dc3545;
		}
	</style>
@endsection

@section('js')
	<script>
		$(document).ready(function(){
			var addressCount = Number('{{ ($deliveryAddresses->count() > 0) ?:0}}');
			
			$('#postcode_lookup').getAddress({
				api_key: 'pSOX8tK_j0iLsygM3sqdUw4845', 
				output_fields:{
					line_1: '#line1',
					line_2: '#line2',
					line_3: '#line3',
					post_town: '#town',
					county: '#county',
					postcode: '#post_code'
				},
				//Optionally register callbacks at specific stages 
				
				input: '#postcode',
				button: '#searchPostCode',
				button_label : '<span class="ds-search"></span>',
				error_message_postcode_not_found: "We were not able to your address from your Postcode. Please input your address manually",

				onLookupSuccess: function(data){ 
					$(".addressBlock").slideDown();
				},
				
				onLookupError: function(data){
					$(".addressBlock").slideDown();
				},
				
				onAddressSelected: function(elem,index){
					$(".addressBlock").slideDown();
				}
			});
			
			$('#postcode').blur(function(){
				$('#searchPostCode').trigger('click');
			});
			
			$(document).on('click', '.editAdd', function(){
				var form_data = $.parseJSON($(this).attr('data-form'));
				
				$('#id').val(form_data.id);
				$('#company_name').val(form_data.company_name);
				$('#postcode').val(form_data.post_code);
				$('#line1').val(form_data.street_address);
				$('#line2').val(form_data.address_line_2);
				$('#town').val(form_data.city);
				$('#county').val(form_data.county_state);
				$('#delivery_notes').val(form_data.delivery_notes);
				$('#delivery_window').val(form_data.delivery_window);
				
				if(form_data.primary_address == 1)
					$('#primary_address').prop('checked',true);
				else
					$('#primary_address').prop('checked',false);
				
				if(form_data.access_24_hours == 1)
					$('#access_24_hours').prop('checked',true);
				else
					$('#access_24_hours').prop('checked',false);
				
				$(".addressBlock").slideDown();
			});
			
			$('.addAddress').click(function(){
				lang = {};
				conf = {
					inlineErrorMessageCallback: function($input, errorMessage, config) {
						if (errorMessage) {
							if($input.closest('div').hasClass('input-group')){
								$input.closest('div').nextAll().remove();
								
								$('<span class="help-block text-danger">'+errorMessage+'</span>').insertAfter($input.closest('div'));
							}else{
								$('<span class="help-block text-danger">'+errorMessage+'</span>').insertAfter($input);
							}
						}else {
							if(!$input.hasClass('search-query')){
								$input.nextAll().remove();
							}else{
								$input.closest('div').nextAll().remove();
							}
						}
						return false; // prevent default behaviour
					},
				};
				
				if(addressCount > 0 && $(this).hasClass('continueBtn')){
					window.location.href = $(this).attr('data-url');
				}
				else{
					if( $('#step3Form').isValid(lang, conf, true) ) {
						var _this = $(this);
						
						var addressForm = $('#step3Form').serialize();
						var i = 0;
						
						$.ajax({
							url : '{{route("registerStep3")}}',
							type : 'post',
							data : addressForm,
							dataType : 'json',
							beforeSend: function() {
								$("#cover-spin").show(0);
								i++;
							},
							success : function(data){
								if(data.status){
									$('div.owl-carousel').html(data.carousel_add);
									$('ul.mobile-edit-address').html(data.simple_add).closest('div').css('display','');
									$('input[name="id"]').val(0);
									$('#step3Form').closest('form').get(0).reset();
									
									if(_this.hasClass('continueBtn')){
										window.location.href = _this.attr('data-url');
									}
									
									$('.continueBtn').removeClass('d-none');
									addressCount++;
								}else{
									$('.form-control').each(function(){
										if($(this).closest('div').hasClass('input-group'))
											$(this).closest('div').nextAll().remove();
										else
											$(this).nextAll().remove();
									});
									
									$.each(data.messages,function(k,v){
										if($('#'+k).closest('div').hasClass('input-group')){
											$('#'+k).closest('div').nextAll().remove();
											
											$('<span class="help-block text-danger">'+v+'</span>').insertAfter($('#'+k).closest('div'));
										}else{
											$('<span class="help-block text-danger">'+v+'</span>').insertAfter($('#'+k));
										}
									});
								}
							},
							error: function(xhr) { // if error occured
								$("#cover-spin").hide(0);
							},
							complete: function() {
								i--;
								if (i <= 0) {
									$("#cover-spin").hide(0);
								}
							}
						});
					}
				}
			});
		});
	</script>
@endsection
