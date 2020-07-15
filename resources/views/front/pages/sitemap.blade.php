@extends('layouts.front.account-app')

@section('content')
	
	<header>
		<h2 class="sub-heading text-center mb-0"> Sitemap </h2>
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
		
		<div class="col-md-12">
			<div class="row">
				<div class="col-sm-12 mt-4">
					<div class="pl-0 pl-lg-3 pr-0 pr-lg-3">
						<img alt="Greengrocers" class="main-image img-fluid" src="{{ url('/images/static-page-images/shutterstock_27260131.jpg') }}" style="border-width: 0px; border-style: solid; margin: 0px; float: right;" title="Greengrocers">
						
						<p>Here you can easily navigate to the most important pages on our website.</p>
						
						<ul class="nav flex-column fruit-list">
							@foreach($categories as $category)
								<li class="nav-item">
									<a class="nav-link" href="{{ route('page.index', $category->slug).'/' }}">
										<img src="{{ url('images/litchi_icon.gif') }}" border="0">
										{{$category->name}}
									</a>
								</li>
								@if($category->children->count() >= 1) 
									<ul class="nav flex-column fruit-list pl-4">
										@foreach($category->children as $subcategory)
											<li class="nav-item">
												<a class="nav-link" href="{{ route('page.index', [$category->slug, $subcategory->slug]).'/' }}">
													<img src="{{ url('images/fruite_icon.gif') }}" border="0">
													{{ $subcategory->name }}
												</a>
											</li>
										@endforeach
									</ul>
								@endif
							@endforeach
						</ul>
						<br/>
						<p>	Here' some information pages</p>
						
						<ul class="nav flex-column fruit-list">
							<li class="nav-item">
								<a class="nav-link" href="{{route('page.index', '5-a-day').'/'}}">
									<img src="{{ url('images/litchi_icon.gif') }}" border="0">
									5 A Day
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{route('page.index', 'healthy-eating').'/'}}">
									<img src="{{ url('images/litchi_icon.gif') }}" border="0">
									Healthy Eating
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{route('page.index', 'list').'/'}}">
									<img src="{{ url('images/litchi_icon.gif') }}" border="0">
									Fruit and Veg List
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{route('page.index', 'pictures').'/'}}">
									<img src="{{ url('images/litchi_icon.gif') }}" border="0">
									Fruit and Veg Pictures
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{route('page.index', 'market').'/'}}">
									<img src="{{ url('images/litchi_icon.gif') }}" border="0">
									Fruit and Veg Market
								</a>
							</li>
						</ul>
						
						<br/>
						
						<p>	Here's where we offer fruit and veg delivery</p>
						
						<ul class="nav flex-column fruit-list">
							<li class="nav-item">
								<a class="nav-link" href="{{route('page.index', 'berkshire').'/'}}">
									<img src="{{ url('images/litchi_icon.gif') }}" border="0">
									Berkshire
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{route('page.index', 'sussex').'/'}}">
									<img src="{{ url('images/litchi_icon.gif') }}" border="0">
									Sussex
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{route('page.index', 'surrey').'/'}}">
									<img src="{{ url('images/litchi_icon.gif') }}" border="0">
									Surrey
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{route('page.index', 'hertforshire').'/'}}">
									<img src="{{ url('images/litchi_icon.gif') }}" border="0">
									Hertfordshire
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{route('page.index', 'suffolk').'/'}}">
									<img src="{{ url('images/litchi_icon.gif') }}" border="0">
									Suffolk
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{route('page.index', 'middlesex').'/'}}">
									<img src="{{ url('images/litchi_icon.gif') }}" border="0">
									Middlesex
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{route('page.index', 'kent').'/'}}">
									<img src="{{ url('images/litchi_icon.gif') }}" border="0">
									Kent
								</a>
							</li>
						</ul>
						
						<br/>
						
						<p>	Here's some pages about our different services</p>
						
						<ul class="nav flex-column fruit-list">
							<li class="nav-item">
								<a class="nav-link" href="{{route('page.index', 'greengrocers').'/'}}">
									<img src="{{ url('images/litchi_icon.gif') }}" border="0">
									Greengrocers London
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{route('page.index', 'schools').'/'}}">
									<img src="{{ url('images/litchi_icon.gif') }}" border="0">
									Fruit and Veg for Schools
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{route('page.index', 'restaurants').'/'}}">
									<img src="{{ url('images/litchi_icon.gif') }}" border="0">
									Fruit and Veg for Restaurants
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{route('page.index', 'wholesalers-london').'/'}}">
									<img src="{{ url('images/litchi_icon.gif') }}" border="0">
									Fruit and Veg Wholesalers London
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{route('page.index', 'suppliers-london').'/'}}">
									<img src="{{ url('images/litchi_icon.gif') }}" border="0">
									Fruit and Veg Suppliers London
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{route('page.index', 'delivery-london').'/'}}">
									<img src="{{ url('images/litchi_icon.gif') }}" border="0">
									Fruit and Veg Delivery London
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{route('page.index', 'supplier-london').'/'}}">
									<img src="{{ url('images/litchi_icon.gif') }}" border="0">
									Fruit and Veg Supplier
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{route('page.index', 'fresh').'/'}}">
									<img src="{{ url('images/litchi_icon.gif') }}" border="0">
									Fresh Fruit and Veg
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection