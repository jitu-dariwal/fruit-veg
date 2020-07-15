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
							<h6>Your statements</h6>
							<p>If you have a regular order in place you must contact us to change any details.</p>
							<p class="mb-0">You can however use your account to order  additional fruitboxes.</p>                           
							<hr>
							
							<div class="custom-table-div">
								<ul class="custom-table-heading">
									<li>Date</li>
									<li>Invoice <span class="d-none d-md-inline-block">no. </span></li>
									<li>Status</li>
									<li>Amount</li>
									<li>&nbsp;</li>
								</ul>
								@if(!$orders->isEmpty() && $orders->count() > 0)
									@foreach($orders as $order)
										<ul class="custom-table-grid">
											<li> {{ date('d/m/Y', strtotime($order->created_at))}} </li>
											<li>#{{$order->id}}</li>
											<li> £ {{$order->total}} </li>
											<li>
												@if($order->order_status_id < 2)
													<a href="{{ route('checkout.payment', $order->id).'/' }}" class="btn btn-outline-success btn-sm table-btn">Pay now</a>
												@else
													<span class="badge badge-pill">PAID</span>
												@endif
											</li>
											<li>
												<a href="{{route('accounts.orderprintdownload', [$order->id,'download']).'/'}}" class="link" target="_blank">Download</a>
											</li>
										</ul>
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