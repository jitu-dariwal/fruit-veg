@extends('layouts.front.app')

@section('content')
<section class="cart-page pt-4">
	<div class="container">
		@include('layouts.front.top-alert-message')
		<div class="row">
			<div class="col-lg-3 px-2 col-md-12">
		  
				@include('front.categories.left-categorysearch')
			  
				@include('front.categories.left-categorylist')
			  
			</div>
			
			<div class="col-lg-6 px-2 col-md-12 mb-lg-0 mb-md-4">
				<div class="product-listing">
					<h2 class="sub-heading">Your favourites </h2>
					
					@if(empty(\Auth::user()) || (1 > $favProductCount && 1 > count($products->items())))
						@include('front.products.how_to_add_favouriteproducts')
					@else
						<p>Browse your favourites by either scrolling or selecting a category from the drop down menu.</p>
					
						@include('front.products.favproducts-catselect',['category' => $category, 'parent_cat' => ((!empty($category)) ? $category->parent->slug : ''),'cat' => ((!empty($category)) ? $category->slug : '')])
						
						@php
						
							$dataArray['products'] = $products;
							$dataArray['parent_category'] = '';
							$dataArray['category_id'] = '';
							$dataArray['category'] = '';
							$dataArray['cartItems'] = $cartItems;
							$dataArray['callFrom'] = 'fav';
							
							if(!empty($category)){
								$dataArray['parent_category'] = $category->parent->name;
								$dataArray['category_id'] = $category->id;
								$dataArray['category'] = $category->name;
							}
							
						@endphp
					
						@include('front.products.product-list', $dataArray)
					@endif
				</div>
			</div>

			<div class="col-lg-3 d-flex px-1 col-md-12">
				@include('front.categories.right-cartinfo' , ['callFrom' => 'fav','cat_id' => (!empty($category)) ? $category->id : null, 'cartItems' => $cartItems, 'total_price' => $total_price, 'total_products_price' => $total_products_price, 'default_minimum_order' => $default_minimum_order])
			</div>
		</div>
	</div>
</section>
@endsection