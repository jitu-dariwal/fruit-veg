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
							<h6>Payment methods</h6>
							<p>If you have a regular order in place you must contact us to change any details.</p>
							<p class="mb-0">You can however use your account to order  additional fruitboxes.</p>                           
							<hr>
							<div class="custom-table-div2">
								<ul class="custom-table-heading2">
									<li>Card type</li>
									<li> Details </li>
									<li>&nbsp;</li>
									<li>&nbsp;</li>
								</ul>
								@if(!empty($cards) && count($cards) > 0)
									@foreach($cards as $card)
										<ul class="custom-table-grid2">
											<li>
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
												
												@php
													$type = explode('_',$card->card_type);
												@endphp
												
												{{ $type[0] }}
												
											</li>
											<li> {{ $card->number_filtered }}</li>
											<li>
												<form method="post" action="{{ route('accounts.deleteCard', $card->id) }}">
													@csrf
													<button type="submit" class="btn btn-outline-primary btn-large mr-1 small-padding">remove</button>
												</form>
											</li>        
											<li>
												<a href="{{ route('accounts.accountpaymentsaddedit', $card->id).'/' }}" class="btn btn-greem small-padding">Edit</a>
											</li> 
										</ul>
									@endforeach
								@else
									<ul class="custom-table-grid2">
										<li>Cards not added</li>
									</ul>
								@endif
							</div>
							
							<a href="{{ route('accounts.accountpaymentsaddedit').'/' }}" class="btn btn-outline-primary btn-large mb-4 px-sm-5">Add Payment Method</a>
							
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