@extends('layouts.front.account-app')

@section('css')
	<style>
		.ui-datepicker{
			width : 100%;
		}
		.ui-datepicker table.ui-datepicker-calendar tr th:first-child,.ui-datepicker table.ui-datepicker-calendar tr td:first-child{
			display : none;
		}
	</style>
@endsection

@section('content')
	
	<header>
		<ul class="steps list-unstyled">
			<li class="active"><a href="javascript:void(0)">1</a></li>
			<li><a href="javascript:void(0)">2</a></li>
			<li><a href="javascript:void(0)">3</a></li>
			<li><a href="javascript:void(0)">4</a></li>           
		</ul>
		<h2 class="sub-heading text-center">Delivery information</h2>
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
		
		<div class="col-md-12">
			<form action="{{ route('checkout.storeDeliveryAdd') }}" method="post" class="" enctype="multipart/form-data" onsubmit=" if($('#delivery_date').val() == ''){ $('div#selectDatepicker').closest('div.form-group').find('h6').find('br,span').remove();$('div#selectDatepicker').closest('div.form-group').find('h6').append('<br/><br/><span class=\'text-danger\'>Please select a date for your shipment to arrive on.</span>');$( window).scrollTop(0);return false;} ">
				@csrf
				
				<input type="hidden" id="delivery_date" name="delivery_date" value="{{ old('delivery_date',((!empty(session('checkout')) && !empty(session('checkout.step1'))) ? session('checkout.step1.delivery_date') : '')) }}"/>
				
				<div class="form-group form-row align-items-center">			  
					<h6 class="col-form-label mb-0">Select your delivery address</h6>
					<div class="custom-dd">
						<select name="address" id="address" class="form-control" data-validation="required" onchange=" if(this.value != '') { window.location.href = '{{ route('checkout.index').'?add=' }}'+this.value }">
							<option value="">Select address</option>
							@if(isset($addresses))
								@foreach($addresses as $key => $address)
									<option value="{{ $address->id }}" @if(old('address', $default_address_id) == $address->id) selected="selected" @endif >
										{{ $address->company_name }}
										<span class="pull-right">
											( {{$address->post_code}} )
										</span>
									</option>
								@endforeach
							@endif
						</select>
						@if ($errors->has('address'))
							<span class="text-danger">{{ $errors->first('address') }}</span>
						@endif
					</div>
				</div>
				
				<div class="form-group">
					<h6 class="my-3">
						Click the day you would like to receive your order.
						
						@if ($errors->has('delivery_date'))
							<br/><br/>
							<span class="text-danger">{{ $errors->first('delivery_date') }}</span>
						@endif
					</h6>
					<div id = "selectDatepicker"></div>
				</div>
				  
				<div class="form-group form-row align-items-center">
					<h6 class="col-form-label mb-0 ">Preferred delivery window:</h6>
					<div class="col-md-4 col-lg-4">
						<div class="c-select">
							@php
								$selectOption = old('delivery_window',((!empty(session('checkout')) && !empty(session('checkout.step1'))) ? session('checkout.step1.delivery_window') : ''));
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
								<span class="text-danger">{{ $errors->first('delivery_window') }}</span>
							@endif
						</div>	  
					</div>
				</div>
				
				<div class="form-group">
					<h6 class="my-2">Here is the notes you provided us at sign up. Please feel free to edit as you wish.</h6>
					
					@php
						$notes = (!empty(session('checkout')) && !empty(session('checkout.step1'))) ? session('checkout.step1.delivery_notes') : $customer->customers_invoice_notes;
					@endphp
					<textarea class="form-control" name="delivery_notes" placeholder="">{{ old('delivery_notes', $notes) }}</textarea>				  
				</div>
				<div class="form-group mt-4 ">
					<div class="row">
						<div class="col-12 col-sm-6 my-1">
							<a href="{{ route('cart.index').'/' }}" class="btn site-outline-btn d-block d-sm-inline-block" >Back</a>
						</div>
						<div class="col-12 col-sm-6 my-1 text-right">
							<button class="btn site-btn d-block d-sm-inline-block">continue <span class="ds-right-arrow"></span></button>
						</div>				   
					</div>	
				</div>
			</form>
		</div>
	</div>
@endsection

@section('js')
	<!-- Javascript -->
	<script>
		var disableDates = {!! json_encode(explode(',', $customer->shipping_disabled_dates)) !!};
		var disableDay = [1,2,3,4,5,6,7];
		
		@if(isset($bankholidays) && !empty($bankholidays))
			disableDates = disableDates.concat({!! json_encode($bankholidays) !!});
		@endif
		
		@if(isset($postCodesDeliveries[$selectedPostCode]) && !empty($postCodesDeliveries[$selectedPostCode]))
			var postCodesDeliveries = {!! json_encode($postCodesDeliveries[$selectedPostCode]) !!};
			
			var disableDay = postCodesDeliveries.split(',');
			for (a in disableDay ) {
				disableDay[a] = parseInt(disableDay[a]);
			}
		@endif
		
		var default_date = {!! json_encode(explode('-', date('Y-m-d'))) !!};
		
		@if(!empty(session('checkout')) && !empty(session('checkout.step1')))
			default_date = {!! json_encode(explode('-', session('checkout.step1.delivery_date'))) !!};
		@endif
		
		var defaultDate = new Date(Number(default_date[0]),(Number(default_date[1]) - 1),Number(default_date[2]));
		
		if ($.inArray(defaultDate.getDay(), disableDay) == -1) {
			$('input[name="delivery_date"]').val('');
		}
		
		$( "#selectDatepicker" ).datepicker({
			//showButtonPanel: true,
			dateFormat: 'yy-mm-dd',
			minDate: 0,
			defaultDate : defaultDate,
			showMonthAfterYear: true,
			dayNamesMin: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
			beforeShowDay: function(date) {
				var getDate = String(date.getDate()).padStart(2, '0');
				var getMonth = String((date.getMonth() + 1)).padStart(2, '0');
				
				dmy = getDate + "-" + getMonth + "-" + date.getFullYear();
				var day = date.getDay();
				
				if ($.inArray(dmy, disableDates) == -1 && $.inArray(day, disableDay) >= 0) {
					return [true, ""];
				}
				else {
					return [false, "", "Unavailable"];
				}
			},
			onSelect: function(dateText, inst) { 
				var dateAsString = dateText; //the first parameter of this function
				$('input[name="delivery_date"]').val(dateAsString);
				
				$('div#selectDatepicker').closest('div.form-group').find('h6').find('br,span').remove();
				
				setTimeout(function(){
					addClassWeekHead();
				},2);
				return $(this).trigger('change');
			},
			onChangeMonthYear: function(year, month, inst){  
				setTimeout(function(){
					addClassWeekHead();
				},2);
				return $(this).trigger('change');
			},
		});
		
		setTimeout(function(){
			addClassWeekHead();
		},100);
		
		function addClassWeekHead(){
			$.each(disableDay, function(k,v){
				$('#selectDatepicker').find('table.ui-datepicker-calendar thead').find('tr th:nth-child('+(v+1)+')').addClass('enableDays');
			});
		}
	</script>
@endsection
