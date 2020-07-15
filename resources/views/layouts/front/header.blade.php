<header class="header">
    <div class="container">
        <div class="header-outer">
            <a href="{{URL::to('/')}}" class="logo"><img src="{{ asset('images/fruit-for-the-office.svg') }}" alt=""/></a>
            <div class="center-nav">
                <div class="call-details">
                    <div class="call-number"><span>CALL US ON</span> 0800 019 4037<small>Our phone lines are open Monday – Friday 9am – 5pm</small></div>
                    <p class="d-block d-lg-none">Mon-Fri 9am - 5pm</p>
                </div>
                <nav class="main-navbar navbar navbar-expand-lg navbar-light">
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					
					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<ul class="navbar-nav">
							
							<li class="nav-item dropdown">
								<a class="nav-link" href="{{ url('/shop') }}">
									shop now
								</a>
								
								<?php /* ?>
								<a class="nav-link dropdown-toggle" href="{{config('shop.url')}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									shop now
								</a>
								
								<ul class="dropdown-menu">
									@foreach(config('constants.PARENTCATEGORIES') as $parent_cat)
										<li>
											<a class="dropdown-item" href="{{ route('page.index', $parent_cat->slug).'/' }}">{{$parent_cat->name}}</a>
											<span class="dropdown-toggle"></span>
											@if($parent_cat->children->count() > 0)
												<ul class="dropdown-menu">
													@foreach($parent_cat->children as $child)
														<li>
															@if($child->children->count() > 0)
																<a class="dropdown-item" href="{{ route('page.index', [$child->slug]).'/' }}">{{$child->name}}</a>
																<span class="dropdown-toggle"></span>
																<ul class="dropdown-menu">
																@foreach($child->children as $childOfChild)
																	<li>
																		<a class="dropdown-item" href="{{ route('page.index', [$child->slug, $childOfChild->slug]).'/' }}">
																		{{$childOfChild->name}}</a>
																	</li>
																@endforeach
																</ul>
															@else
																<a class="dropdown-item" href="{{ route('page.index', [$parent_cat->slug, $child->slug]).'/' }}">{{$child->name}}</a>
															@endif					
														</li>
													@endforeach
												</ul>
											@endif
										</li>
									@endforeach
								</ul>
								<?php */ ?>
							</li>
						
							<li class="nav-item active"> <a class="nav-link" href="{{ route('front.favproducts').'/' }}">FAVOURITES </a> </li>
                            <li class="nav-item"> <a class="nav-link" href="{{ route('faq').'/' }}">faq</a> </li>
                            <li class="nav-item"> <a class="nav-link" href="{{route('page.index', 'about-us').'/' }}">About</a> </li>
                            <li class="nav-item"> <a class="nav-link" href="{{route('page.contactUs').'/'}}">contact us</a> </li>
                            <li class="nav-item"> <a class="nav-link" target="_blank" href="http://blog.fruitandveg.co.uk/">blog</a> </li>
							
						</ul>
					</div>
				</nav>
            </div>
            <ul class="account-link">
                
				<li class=""><a href="{{ route('front.favproducts').'/' }}"><span class="ds-star"></span></a></li>
				
                @guest
					<li class=""><a href="{{route('login').'/'}}"><span class="ds-account"></span></a></li>
				@else
					<li class=""><a href="{{route('accounts').'/'}}"><span class="ds-account"></span></a></li>
                @endguest
				<li class="cartIcon"><a href="{{ route('cart.index').'/' }}"><span class="ds-cart"></span></a><span class="cart-number" id="total_cart_val">{{ $cartCount }}</span></li>
            </ul>
        </div>
    </div>
</header>