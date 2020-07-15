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
							<h6>Your orders</h6>
							
							<div class="box-body">
								@include('layouts.errors-and-messages')
							</div>
							
							<p>If you have a regular order in place you must contact us to change any details.</p>
							<p class="mb-0">You can however use your account to order  additional fruitboxes.</p>                           
							<hr>
							<h6>Current order</h6>

							@if(!empty($order))
								<ul class="my-grid-block list-unstyled">
									<li>
										<h6>Date</h6>
										<p class="mb-0">{{date('l jS F Y', strtotime($order->created_at))}}</p>
									</li>
									<li>
										<h6>Order Total.</h6>
										<p class="mb-0">£ {{$order->total}}</p>
									</li>
									<li>
										<h6>Order Status</h6>
										<p class="mb-0">{{$order->name}}</p>
									</li>
									<li>
										<a href="{{ route('accounts.orderdetail', $order->id) }}" class="btn btn-greem">View</a>
									</li>
								</ul>
							@else
								<p>No order created yet.</p>
							@endif
                           
							<div class="custom-right">
								<a href="{{ route('accounts.orders') }}" class="link">view all ></a>
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

@endsection