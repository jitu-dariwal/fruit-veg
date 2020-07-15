@extends('layouts.front.app')

@section('content')
 <section class="cart-page pt-4">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 px-2 col-md-12">
          
		  @include('front.categories.left-categorysearch')
		  
		  @include('front.categories.left-categorylist')
        
      </div>
		
	</div>
        
      <div class="col-lg-6 px-2 col-md-12 mb-lg-0 mb-md-4">
        <div class="product-listing">
            <h2 class="sub-heading">Categories</h2>
			
			@include('front.categories.category-list', ['categories' => $categories])
             
        </div>
      </div>


       <div class="col-lg-3 d-flex px-1 col-md-12">
			@include('front.categories.right-cartinfo')
       </div>
	   
    </div>
  </div>
</section>
@endsection