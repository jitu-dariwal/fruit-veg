<!-- @if(!empty($products) && !collect($products)->isEmpty()) -->
	@php
		$favProducts = [];
		if(!empty(\Auth::user()->id))
			$favProducts = \Finder::getFavProducts(\Auth::user()->id);
	@endphp
	
	@if(!empty($category))
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb my-breadcrumb">
				@if(isset($parentOfParent_category))
					<li class="breadcrumb-item"><a href="{{route('page.index', Request::segment(1))}}">{{$parentOfParent_category}}</a></li>
					<li class="breadcrumb-item"><a href="{{route('page.index', [Request::segment(1), Request::segment(2)])}}">{{$parent_category}}</a></li>
				@else
					<li class="breadcrumb-item"><a href="{{route('page.index', Request::segment(1))}}">{{$parent_category}}</a></li>
				@endif
				
				<li class="breadcrumb-item active" aria-current="page">{{ $category }}</li>
			</ol>
		</nav>
	@endif
	
	@if(count($products->items()) > 0)
		<ul class="product-list list-unstyled">			
			@foreach($products as $key=>$product)
				<li>
					<div class="inner-product-list">
						<div class="card ">
							@php
								$action = 'add';
								$class = 'ds-star-outline';
								if(in_array($product->id, $favProducts)){
									$action = 'remove';
									$class = 'ds-star';
								}
							@endphp
							<a href="JavaScript:void(0);" class="favorite {{ (isset($callFrom) && !empty($callFrom)) ? 'listFav' : '' }} {{ (!empty(\Auth::user()) && \Auth::user()->id) ? 'addFavorite' : '' }}" data-id="{{$product->id}}" data-action="{{$action}}"><span class="{{$class}}"></span></a>
							@if(isset($product->cover))
								<img src="{{ asset('uploads/'.$product->cover) }}" alt="{{ $product->name }}" class="card-img-top">
							@else
								<img style="width:80px;height:120px;" src="{{ asset('images/noimg.jpg') }}" alt="{{ $product->name }}" class="card-img-top" />
							@endif
							<div class="card-body p-2">
								<h6 class="card-title">{{ $product->name }}</h6>
								<p class="card-text">
									<span class="size">({{ $product->packet_size }})</span>
									@if(isset(auth()->user()->id) && !empty(auth()->user()->id))
										<span class="price">
											@if(!is_null($product->attributes->where('default', 1)->first()))
												@if(!is_null($product->attributes->where('default', 1)->first()->sale_price))
													{{ config('cart.currency_symbol_2') }}{{ number_format($product->attributes->where('default', 1)->first()->sale_price, 2) }}
													<p class="text text-danger">Sale!</p>
												@else
													{{ config('cart.currency_symbol_2') }}{{ number_format($product->attributes->where('default', 1)->first()->price, 2) }}
												@endif
											@else
												{{ config('cart.currency_symbol_2') }}{{ number_format($product->price, 2) }}
											@endif
										</span>
									@endif
								</p>                        
							</div>
							@if(isset(auth()->user()->id) && !empty(auth()->user()->id))
				   
								@if($product->products_status_2 == 1)
									@php
										if(empty($category_id))
											$category_id = $product->pivot->category_id;
									@endphp
					   
									<form action="add" class="quantity-block">
									<a href="javascript:void(0)" class="quantity_update minusqty_{{$product->id}}" onClick="minus('prd_qty_{{$product->id}}', '{{auth()->user()->id}}', '{{$product->id}}', '{{$category_id}}', '{{$product->price}}' , '{{$product->type}}')"><span class="ds-minus"></span></a>
									
									@php
										$value = 1;
										
										if(isset($cartItems) && !empty($cartItems)){
											foreach($cartItems as $item){
												if($item->product_id == $product->id)
													$value = $item->qty;
											}
										}
									@endphp
									
									<input type="text" value="{{ $value }}" id="prd_qty_{{$product->id}}">
									
									<a href="javascript:void(0)" class="quantity_update plusqty_{{$product->id}}" onClick="plus('prd_qty_{{$product->id}}', '{{auth()->user()->id}}', '{{$product->id}}', '{{$category_id}}', '{{$product->price}}', '{{$product->type}}')"><span class="ds-pluase"></span></a>                            
									</form>
									<a href="javascript:void(0)" class="btn btn-lightgreen p-2 prd_qty_{{$product->id}} quantity_update addbulkqty_{{$product->id}}" onClick="addbulkqty('prd_qty_{{$product->id}}', '{{auth()->user()->id}}', '{{$product->id}}', '{{$category_id}}', '{{$product->price}}', '{{$product->type}}')">add</a>
							  @else
									<span style="color:red">Out Of Stock</span> 
							  @endif
				  
							@else
				  
								<a href="{{route('page.auth')}}" class="btn btn-outline-primary">CREATE ACCOUNT TO SEE PRICE</a>
				  
							@endif
						</div>
					</div>                   
				</li>
			@endforeach
		</ul>
		@if($products instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
			<div class="row">
				<div class="col-md-12">
					<div class="pull-left">{{ $products->appends(request()->except('page'))->links() }}</div>
				</div>
			</div>
		@endif
	@else
		<p class="alert alert-warning">No products found yet.</p>
	@endif
<!--@else 
    <p class="alert alert-warning">No products yet.</p>
 @endif -->