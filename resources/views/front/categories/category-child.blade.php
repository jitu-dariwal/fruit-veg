@extends('layouts.front.app')

@section('meta_tags')
	@if(!empty($category->meta_title))
		<title>{{ $category->meta_title }}</title>
	@else
		<title>{{ config('app.name') }}</title>
	@endif
	
	<meta name="description" content="{!! $category->meta_description !!}">
	<meta name="tags" content="{!! $category->meta_keyword !!}">
@endsection

@section('og')
    <meta property="og:type" content="category"/>
    @if(!empty($category->meta_title))
		<meta property="og:title" content="{!! $category->meta_title !!}"/>
	@else
		<meta property="og:title" content="{!! config('app.name') !!}"/>
	@endif
	
    <meta property="og:description" content="{!! $category->meta_description !!}"/>
@endsection

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
						
						<form action="{{ route('page.index', [$parentOfParent_category->slug, $parent_category->slug, $category->slug]) }}">
							<div id="custom-search-input">
								<div class="input-group">
									<input type="text" name="cat-product" class="search-query form-control" placeholder="Search category products" value="{{ (isset($search_product)) ? $search_product : '' }}" />
									<span class="input-group-btn">
										<button type="submit"> <span class="ds-search"></span> </button>
									</span>
								</div>
							</div>
						</form>
						
						<h2 class="sub-heading">{{$category->name}}</h2>
						@include('front.products.product-list', ['cartItems' => $cartItems,'parentOfParent_category' => $parentOfParent_category->name,'parent_category' => $parent_category->name, 'category_id' => $category->id, 'category' => $category->name, 'products' => $products])
					</div>
				</div>
				<div class="col-lg-3 d-flex px-1 col-md-12">
					@include('front.categories.right-cartinfo', ['cartItems' => $cartItems, 'total_price' => $total_price, 'total_products_price' => $total_products_price, 'default_minimum_order' => $default_minimum_order, 'cat_id' => $cat_id])
				</div>
			</div>
		</div>
	</section>
@endsection