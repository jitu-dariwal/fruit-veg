@extends('layouts.front.app')

@section('content')
<section class="cart-page pt-4">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 px-2 col-md-12">
          
				@include('front.categories.left-categorysearch')
		  
				@include('front.categories.left-categorylist')
        
			</div>
        
			<div class="col-lg-6 px-2 col-md-12 mb-lg-0 mb-md-4">
				<div class="product-listing">
					<h2 class="sub-heading">Shop</h2>
					<ul class="fruit-name list-unstyled">
						@foreach($categories as $category)
							<li>
								<a href="{{ route('page.index', [$category->slug]) }}">
									<div class="card"> 
										@if(isset($category->cover) && !empty(isset($category->cover)))
											<img class="card-img-top img-fluid" src="{{ asset('uploads/'.$category->cover) }}" alt="Card image">
										@else
											<img class="card-img-top img-fluid" src="{{ asset('images/noimg.jpg') }}" alt="" />
										@endif
								   
										<div class="card-body">
											<h4 class="card-title">{{$category->name}}</h4>
										</div>
									</div>
								</a>
							</li>
						@endforeach
					</ul>
				</div>
			</div>

			<div class="col-lg-3 d-flex px-1 col-md-12">
				@include('front.categories.right-cartinfo')
			</div>
		</div>
	</div>
</section>
@endsection