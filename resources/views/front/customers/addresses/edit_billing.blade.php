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
							<h6>Edit billing address</h6>
							
							<form action="{{ route('customer.address.billingUpdate', [$customer->id]) }}" method="post" class="form" enctype="multipart/form-data">
								<div class="controlar">
									<div class="left-textbox">
										@csrf
										<div id="custom-search-input3{{ $errors->has('post_code') ? ' has-error' : '' }}">
											<div class="input-group">
												<label>Business postcode</label> 
												<input class="search-query form-control" id="post_code" name="post_code" value="{{ old('post_code') ?? $customer->invoice_postcode }}" placeholder="Enter postcode" data-validation="required length" data-validation-length="1-191">
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
											<textarea name="street_address" id="street_address" placeholder="First line of address" class="form-control c-textarea" data-validation="length" data-validation-length="1-255" data-validation-optional="true">{{ old('street_address') ?? $customer->invoice_street_address }}</textarea>
											@if ($errors->has('street_address'))
												<span class="help-block text-danger">
													<strong>{{ $errors->first('street_address') }}</strong>
												</span>
											@endif
										</div>
										
										<div class="form-group">
											<textarea name="address_line_2" id="address_line_2" placeholder="Second line of address" class="form-control c-textarea" data-validation="length" data-validation-length="0-255">{{ old('address_line_2') ?? $customer->invoice_suburb }}</textarea>
										</div>
										
										<div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
											<input type="text" name="city" id="city" placeholder="Town" class="form-control" value="{{ old('city') ?? $customer->invoice_city }}" data-validation="required length" data-validation-length="1-191">
											@if ($errors->has('city'))
												<span class="help-block text-danger">
													<strong>{{ $errors->first('city') }}</strong>
												</span>
											@endif
										</div>
										<div class="form-group{{ $errors->has('county_state') ? ' has-error' : '' }}">
											<input type="text" name="county_state" id="county_state" placeholder="County" class="form-control" value="{{ old('county_state') ?? $customer->invoice_state }}" data-validation="required">
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
