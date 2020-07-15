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
						<div class="yourOrderBlock">
							<h6>Edit delivery address</h6>
							
							<form action="{{ route('customer.address.update', [$customer->id, $address->id]) }}" method="post" class="form" enctype="multipart/form-data">
								<input type="hidden" name="_method" value="put">
								
								<div class="controlar">
									<div class="left-textbox">
										@csrf
										<div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
											<input type="text" name="first_name" id="first_name" placeholder="First Name" class="form-control" value="{{ old('first_name') ?? $address->first_name }}" data-validation="required alphanumeric length" data-validation-length="1-255" />
											@if ($errors->has('first_name'))
												<span class="help-block text-danger">
													<strong>{{ $errors->first('first_name') }}</strong>
												</span>
											@endif
										</div>
										<div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
											<input type="text" name="last_name" id="last_name" placeholder="Last Name" class="form-control" value="{{ old('last_name') ?? $address->last_name }}" data-validation="alphanumeric length" data-validation-length="1-255" data-validation-optional="true" />
											@if ($errors->has('last_name'))
												<span class="help-block text-danger">
													<strong>{{ $errors->first('last_name') }}</strong>
												</span>
											@endif
										</div>
										<div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
											<input type="text" name="company_name" id="company_name" placeholder="Company Name" class="form-control" value="{{ old('company_name') ?? $address->company_name }}" data-validation="required length" data-validation-length="1-255" />
											@if ($errors->has('company_name'))
												<span class="help-block text-danger">
													<strong>{{ $errors->first('company_name') }}</strong>
												</span>
											@endif
										</div>
										
										<div id="custom-search-input3">
											<div class="input-group{{ $errors->has('post_code') ? ' has-error' : '' }}">
												<label>Business postcode</label> 
												<input class="search-query form-control" id="post_code" name="post_code" value="{{ old('post_code') ?? $address->post_code }}" placeholder="Enter postcode" data-validation="required length" data-validation-length="1-191">
												<span class="input-group-btn">
													<button type="button" id="searchPostCode">
														<span class="ds-search"></span>
													</button>
												</span>
											</div>
											@if ($errors->has('post_code'))
												<span class="help-block text-danger">
													<strong>{{ $errors->first('post_code') }}</strong>
												</span>
											@endif
										</div>
															
										<div class="form-group">
											<div class="position-relative" id="postcode_lookup"></div>
										</div>
										
										<div class="form-group{{ $errors->has('street_address') ? ' has-error' : '' }}">
											<textarea name="street_address" id="street_address" placeholder="First line of address" class="form-control c-textarea" data-validation="required length" data-validation-length="1-255">{{ old('street_address') ?? $address->street_address }}</textarea>
											@if ($errors->has('street_address'))
												<span class="help-block text-danger">
													<strong>{{ $errors->first('street_address') }}</strong>
												</span>
											@endif
										</div>
										
										<div class="form-group">
											<textarea name="address_line_2" id="address_line_2" placeholder="Second line of address" class="form-control c-textarea" data-validation="length" data-validation-length="0-255" data-validation-optional="true">{{ old('address_line_2') ?? $address->address_line_2 }}</textarea>
										</div>
										
										<div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
											<input type="text" name="city" id="city" placeholder="Town" class="form-control" value="{{ old('city') ?? $address->city }}" data-validation="required length" data-validation-length="1-191">
											@if ($errors->has('city'))
												<span class="help-block text-danger">
													<strong>{{ $errors->first('city') }}</strong>
												</span>
											@endif
										</div>
										<div class="form-group{{ $errors->has('county_state') ? ' has-error' : '' }}">
											<input type="text" name="county_state" id="county_state" placeholder="County" class="form-control" value="{{ old('county_state') ?? $address->county_state }}" data-validation="required">
											@if ($errors->has('county_state'))
												<span class="help-block text-danger">
													<strong>{{ $errors->first('county_state') }}</strong>
												</span>
											@endif
										</div>
										<div class="form-group">
											<label for="country_id">Country </label>
											<input type="hidden" name="country_id" id="country_id" value="225">
											United Kingdom
										</div>
										<div class="form-group form-row align-items-center{{ $errors->has('delivery_window') ? ' has-error' : '' }}">
											<label class="col-lg-6 col-form-label custom-label">Preferred delivery window:</label>
											<div class="col-lg-6">
													
												@php
													$selectOption = str_replace(' ','',date("g:i a", $address->Access_Time)).'__'.str_replace(' ','',date("g:i a", $address->Access_Time_latest));
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
												<input type="checkbox" class="custom-control-input" id="primary_address" name="primary_address" @if(old('primary_address') == 1 || $customer->default_address_id == $address->id) checked="checked" @endif value="1" />
												<label class="custom-control-label" for="primary_address">Check this box if you want make this address as primary.</label>
											</div>
										</div>
										<div class="custom-control-outer mb-2">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="access_24_hours" name="access_24_hours" @if($address->access_24_hours == 1) checked="checked" @endif value="1" />
												<label class="custom-control-label" for="access_24_hours">Check this box if there's 24 hour access for deliveries.</label>
											</div>
										</div>
									</div>
									<div class="right-btn">
										<button type="submit" class="btn btn-greem">Update</button>
									</div>
								</div>
							</form>
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
			$('#postcode_lookup').getAddress({
				api_key: 'pSOX8tK_j0iLsygM3sqdUw4845', 
				output_fields:{
					line_1: '#street_address',
					line_2: '#address_line_2',
					line_3: '',
					post_town: '#city',
					county: '#county_state',
					postcode: '#postcode'
				},
				//Optionally register callbacks at specific stages 
				
				input: '#post_code',
				button: '#searchPostCode',
				button_label : '<span class="ds-search"></span>',
				error_message_postcode_not_found: "We were not able to your address from your Postcode. Please input your address manually",
				
				onLookupSuccess: function(data){ 
					// Your custom code /
				},
				
				onAddressSelected: function(elem,index){
					// Your custom code /
				}
			});
		});
	</script>
@endsection
