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
						
						<form action="{{ route('page.index', [$parent_category->slug, $category->slug]) }}">
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
						@if($category->children->count() > 0)
							<nav aria-label="breadcrumb">
								<ol class="breadcrumb my-breadcrumb">
									<li class="breadcrumb-item"><a href="{{route('page.index', Request::segment(1))}}">{{$parent_category->name}}</a></li>
									<li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
								</ol>
							</nav>
							
							<ul class="fruit-name list-unstyled">
								@foreach($category->children as $subcategory)
									<li>
										<a href="{{ route('page.index', [$parent_category->slug, $category->slug, $subcategory->slug]) }}">
										   <div class="card"> 
											   @if(isset($subcategory->cover) && !empty(isset($subcategory->cover)))
													<img class="card-img-top img-fluid" src="{{ asset('uploads/'.$subcategory->cover) }}" alt="Card image">
												@else
													<img class="card-img-top img-fluid" src="{{ asset('images/noimg.jpg') }}" alt="" />
												@endif
										   
											  <div class="card-body">
												<h4 class="card-title">{{$subcategory->name}}</h4>
											  </div>
											</div>
										</a>
									</li>
								@endforeach
							</ul>
						@else
							@include('front.products.product-list', ['cartItems' => $cartItems,'parent_category' => $parent_category->name, 'category_id' => $category->id, 'category' => $category->name, 'products' => $products])
						@endif
					</div>
				</div>
				<div class="col-lg-3 d-flex px-1 col-md-12">
					@include('front.categories.right-cartinfo', ['cartItems' => $cartItems, 'total_price' => $total_price, 'total_products_price' => $total_products_price, 'default_minimum_order' => $default_minimum_order, 'cat_id' => $cat_id])
				</div>
			</div>
		</div>
	</section>
@endsection