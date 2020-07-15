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
							<h6>Delivery addresses</h6>
							
							<div class="box-body">
								@include('layouts.errors-and-messages')
							</div>
							
							<!--<b>Primary Address</b>
							
							<p>This address is used as the pre-selected shipping and billing address for orders placed on this store. This address is also used as the base for product and service tax calculations.</p>-->
							
							@if(!$addresses->isEmpty() && $addresses->count() > 0)
								@foreach($addresses as $address)
									<ul class="my-grid-block list-unstyled customBtn">
										<li>
											<h6>{{$address->company_name}}</h6>
											<p class="mb-0">
												{{$address->first_name.' '.$address->last_name}} <br>
												{{$address->street_address}} <br>
												{{$address->address_line_2}} <br>
												{{$address->city.', '.$address->county_state}} <br>
												{{$address->country_name}} <br>
												{{$address->post_code}} <br>
											</p>
											<?php /*<a href="{{ route('customer.address.updateprimary', [auth()->user()->id, $address->id]) }}">(Make Primary / Billing Address)</a> */ ?>
										</li>
										<li>
											<form method="post" action="{{ route('customer.address.destroy', [auth()->user()->id, $address->id]) }}" class="form-horizontal">
												
												<input type="hidden" name="_method" value="delete">
												@csrf
												<button onclick="return confirm('You are about to delete this address?')" type="submit" class="btn btn-outline-primary btn-large mr-2"> <i class="fa fa-trash"></i> Remove</button>
												
												<a href="{{ route('customer.address.edit', [auth()->user()->id, $address->id]).'/' }}" class="btn btn-greem"> <i class="fa fa-pencil"></i> Edit</a>
											</form>
										</li>
									</ul>
								@endforeach
							@else
								<ul class="my-grid-block list-unstyled">
									<li>Address not found.</li>
								</ul>
							@endif
							<a href="{{ route('customer.address.create', auth()->user()->id).'/' }}" class="btn btn-outline-primary btn-large px-sm-5">add another address</a>
						</div>
						<div class="yourOrderBlock">
							<h6>Billing address</h6>
							@if(!empty($billingAdd))
								<ul class="my-grid-block list-unstyled customBtn">
									<li>
										<h6>{{$billingAdd['first_name'].' '.$billingAdd['last_name']}}</h6>
										<p class="mb-0">
											{{$billingAdd['street_address']}} <br>
											{{$billingAdd['address_line_2']}} <br>
											{{$billingAdd['city'].', '.$billingAdd['county_state']}} <br>
											{{$billingAdd['post_code']}} <br>
										</p>
									</li>
									<li>
										<a href="{{ route('customer.address.billing', [auth()->user()->id]).'/' }}" class="btn btn-greem"> <i class="fa fa-pencil"></i> Edit</a>
									</li>
								</ul>
							@else
								<ul class="my-grid-block list-unstyled">
									<li>Address not found.</li>
								</ul>
							@endif
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
<!-- /.content -->
@endsection