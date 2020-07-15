@extends('layouts.front.account-app')

@section('content')
	<!-- Main content -->
	
	<header>
		<h2 class="sub-heading text-center mb-0">Billing information</h2>  
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
		
		<div class="col-lg-6 col-md-12">
			<div class="pl-0 pl-lg-3">
                <form class="account-form" method="POST" action="{{ route('registerStep2') }}">
					@csrf
					<input type="hidden" name="customer_id" value="{{ session('tempCustomer.id') }}"/>
					
					<h6>Please enter your billing postcode <span class="text-danger">*</span></h6>
                    
					<div class="form-group">
						<input type="hidden" class="form-control" id="postcode" name="postcode" placeholder="Enter postcode" value="{{ old('postcode', ((!empty($billingAdd) && isset($billingAdd->post_code)) ? $billingAdd->post_code : '')) }}" data-validation="required length" data-validation-length="1-255">
					</div>
					
					<div class="form-group">
						<div class="position-relative" id="postcode_lookup"></div>
					</div>
					      
					<div class="mb-4 addressBlock" style="display:{{ (!empty(old()) || !empty($billingAdd)) ? '' : 'none' }};">
						<div class="form-group">
							<label>Business address</label>                
						</div>
						
						<div class="form-group">                
							<input class="form-control" id="line1"  name="line1" placeholder="First line of address" value="{{ old('line1', ((!empty($billingAdd) && isset($billingAdd->street_address)) ? $billingAdd->street_address : '')) }}" data-validation="required length" data-validation-length="1-255">
						</div>
						
						<div class="form-group">                
							<input class="form-control" id="line2" name="line2" placeholder="Second line of address" value="{{ old('line2', ((!empty($billingAdd) && isset($billingAdd->address_line_2)) ? $billingAdd->address_line_2 : '')) }}" data-validation="length" data-validation-optional="true" data-validation-length="1-255">
						</div>
						
						<div class="form-group mb-4">                
							<input class="form-control" id="town" name="town" placeholder="Town" value="{{ old('town', ((!empty($billingAdd) && isset($billingAdd->city)) ? $billingAdd->city : '')) }}" data-validation="required length" data-validation-length="1-255">               
						</div>
						
						<div class="form-group mb-4">                
							<input class="form-control" id="county" name="county" placeholder="County" value="{{ old('county', ((!empty($billingAdd) && isset($billingAdd->county_state)) ? $billingAdd->county_state : '')) }}" data-validation="required length" data-validation-length="1-255">               
						</div>
					</div>
					
					<input type="hidden" name="country_id" value="{{ config('constants.country_id') }}"/>
					
					<div class="float-lg-right mt-3 mt-lg-0 continueBtn" style="display:{{ (!empty($billingAdd)) ? '' : 'none' }};">
						<button type="submit" class="float-lg-right btn site-btn  mb-2">
							continue <span class="ds-right-arrow"></span>
						</button>
						<p class="mb-0 text-center text-md-left clear">To select your delivery address</p>
					</div>
					
                </form>
            </div>
		</div>
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
			@if(!empty($billingAdd) && isset($billingAdd->post_code))
				setTimeout(function(){
					$('#opc_input').val('{{$billingAdd->post_code}}');
				},500);
			@endif

			$('#postcode_lookup').getAddress({
				api_key: 'pSOX8tK_j0iLsygM3sqdUw4845', 
				output_fields:{
					line_1: '#line1',
					line_2: '#line2',
					line_3: '#line3',
					post_town: '#town',
					county: '#county',
					postcode: '#postcode'
				},
				//Optionally register callbacks at specific stages 
				
				//input: '#postcode',
				input_label : 'Please enter your postcode for the billing address',
				input_class : 'form-control mb-4',
				error_message_postcode_not_found: "We were not able to your address from your Postcode. Please input your address manually",
				
				onLookupSuccess: function(data){ 
					$("#opc_button").css('margin-top','10px');
					$("#opc_button").css('margin-bottom','10px');
					//$(".addressBlock").slideDown();
					// Your custom code /
				},
				
				onLookupError: function(data){
					$(".addressBlock").slideDown();
					$(".continueBtn").slideDown();
					$("#postcode").val($('#opc_input').val());
				},
				
				onAddressSelected: function(elem,index){
					
					$("#first_name").val('');
					$("#last_name").val('');
					$("#opc_button").css('display','none');
					$(".addressBlock").slideDown();
					$(".continueBtn").slideDown();
					// Your custom code /
				}
			});
		});
	</script>
@endsection
