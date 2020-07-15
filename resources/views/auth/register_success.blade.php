@extends('layouts.front.account-app')

@section('content')
	<!-- Main content -->
	
	<header>
		<h2 class="sub-heading text-center mb-0">Delivery information</h2>  
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
	
	<div class="row">
		<div class="col-sm-12">
            <div class="pl-0 px-lg-3">
                <form class="account-form">
					<h6>Delivery information</h6>
					<div class="col-md-6 px-0">Congratulations your account has been created. We can deliver to your business on the following days.</div>

					<ul class="delivery-days">
						@if(!empty($addresses) && $addresses->count() >0)	
							@foreach($addresses as $add)
								<li>
									<h4 style="word-break:break-all"> {{ $add->company_name . ', ' . $add->post_code }}</h4>
									<div class="weekdays-name">
										@if(array_key_exists($add->post_code, $postCodesDeliveries) && !empty($postCodesDeliveries[$add->post_code]))
											@php
												$days = explode(',' , $postCodesDeliveries[$add->post_code]);
											@endphp
											
											@foreach($days as $day)
												@if(isset(config('constants.week_days_short_name')[$day]))
													<span> {{ config('constants.week_days_short_name')[$day] }}</span>
												@endif
											@endforeach
										@else
											<span>No delivery days available.</span>
										@endif
									</div>
								</li>
							@endforeach
						@else
							<li>
								<h4>No delivery-addresses found.</h4>
							</li>
						@endif
					</ul>
					<div class="float-lg-right mt-4 ">
						<a href="{{route('login')}}" class="btn site-btn  mb-2">START SHOPPING </a>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@section('js')
	<script>
		$(document).ready(function(){
			
		});
	</script>
@endsection
