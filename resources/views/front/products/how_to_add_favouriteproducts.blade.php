
@if(empty(\Auth::user()))		
	<h3 class="sub-heading-desc">You need an account to save favourites! Sign up for an account for free below to start favouriting items.</h3>
	<a href="{{ route('page.auth') }}" class="btn btn-brownish mb-4">CREATE ACCOUNT <span class="ds-right-arrow"></span></a>
@elseif(1 > count($products->items()))
	<h3 class="sub-heading-desc">Your currently have no favourites!</h3>
@else

@endif

<div class="how-to-add-block">
	<h4>Learn how to add favourites</h4>
	<ul class="how-to-add-steps">
		<li>
			<figure><img src="{{ url('images/yf-01.png') }}" alt=""></figure>
			<aside>
				<p>Step 1<br>Search or browse our products</p>
			</aside>
		</li>
		<li>
			<figure><img src="{{ url('images/yf-02.png') }}" alt=""></figure>
			<aside>
				<p>Step 2<br>Click on the star to add to favourites</p>
			</aside>
		</li>
		<li>
			<figure><img src="{{ url('images/yf-03.png') }}" alt=""></figure>
			<aside>
				<p>Step 3<br>Click on favourites in nav to see all your choices.</p>
			</aside>
		</li>
		<li>
			<figure><img src="{{ url('images/yf-04.png') }}" alt=""></figure>
			<aside>
				<p>Step 4<br>Then just simply add to basket </p>
			</aside>
		</li>
	</ul>
	
	
	@if(empty(\Auth::user()))		
		<a href="{{ route('page.auth') }}" class="btn btn-brownish">CREATE ACCOUNT <span class="ds-right-arrow"></span></a>
	@elseif(1 > count($products->items()))
		<a href="{{ route('page.shop').'/' }}" class="btn btn-brownish">Start Shopping<span class="ds-right-arrow"></span></a>
	@else

	@endif
	
</div>