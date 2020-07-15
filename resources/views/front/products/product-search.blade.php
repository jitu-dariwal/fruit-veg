@extends('layouts.front.app')

@section('content')
<section class="cart-page pt-4">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 px-2 col-md-12">
		  
				@include('front.categories.left-categorysearch', ['search' => $data['search']])
			  
				@include('front.categories.left-categorylist')
			  
			</div>
        
			<div class="col-lg-6 px-2 col-md-12 mb-lg-0 mb-md-4">
				<div class="product-listing">
					@if(!empty($data['search']))
						<h2 class="sub-heading mb-3 custom-font-weight">Your results for the <strong> {{ $data['search'] }}.</strong></h2>
					@endif
					
					@php
						$dataArray['products'] = $products;
						$dataArray['parent_category'] = '';
						$dataArray['category_id'] = '';
						$dataArray['category'] = '';
						$dataArray['callFrom'] = '';
						$dataArray['cartItems'] = $cartItems;
						
						if(!empty($category)){
							$dataArray['parent_category'] = $category->parent->name;
							$dataArray['category_id'] = $category->id;
							$dataArray['category'] = $category->name;
						}
						
					@endphp
				
					@include('front.products.product-list', $dataArray)
				</div>
			</div>

			<div class="col-lg-3 d-flex px-1 col-md-12">
				@include('front.categories.right-cartinfo' , ['cat_id' => (!empty($category)) ? $category->id : null, 'cartItems' => $cartItems, 'total_price' => $total_price, 'total_products_price' => $total_products_price, 'default_minimum_order' => $default_minimum_order])
			</div>
		</div>
	</div>
</section>
@endsection