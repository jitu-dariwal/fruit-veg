@extends('layouts.front.app')

@section('content')
 <section class="cart-page pt-4">
  <div class="container">
	<div class="row">
      <div class="col-lg-3 px-2 col-md-12">
          
		  @include('front.categories.left-categorysearch')
		  
		  @include('front.categories.left-categorylist')
        
      </div>
		
	<div class="col-lg-9 px-2 col-md-12 mb-lg-0 mb-md-3">
        <div class="product-listing">
			<h2 class="sub-heading">Shopping Cart</h2>
		
			<div class="box-body">
				@include('layouts.errors-and-messages')
			</div>
		
			<div id="sorted_shopping_list">
				@if(!$cartItems->isEmpty())
					<table class="table table-striped">
						<thead>
							<th class="col-md-2 col-lg-4">Product(s)</th>
							<th class="col-md-2 col-lg-2">Qty</th>
							<th class="col-md-2 col-lg-1"></th>
							<th class="col-md-2 col-lg-2">Total</th>
							<th class="col-md-2 col-lg-2"></th>
						</thead>
						<tfoot>
						<tr>
							<td class="bg-cardtotal-bottom"></td>
							<td class="bg-cardtotal-bottom"></td>
							<td class="bg-cardtotal-bottom"></td>
							<td class="bg-cardtotal-bottom">Total</td>
						   <td class="bg-cardtotal-bottom text-nowrap">{{config('cart.currency_symbol_2')}} {{ $subtotal }}</td>
						</tr>
						</tfoot>
						<tbody><input type="hidden" name="shoppinglisturl" id="shoppinglisturl" value="{{route('carts.updateshoppingcart')}}">
						@php
								$totalqty = 0;
						@endphp
						@foreach($cartItems as $cartItem)
							@php
								$totalqty = $totalqty+$cartItem->qty;
								$cat_id = $cartItem->product->categories[0]->id;
							@endphp
							<tr>
							   <td>
									{{ $cartItem->name }}
							   </td>
								<td>
									<form action="add" class="quantity-block">
										<a href="javascript:void(0)" class="quantity_update minusqty_{{$cartItem->id}}" onClick="minus_shopping_cart('prd_qty_{{$cartItem->id}}', '{{auth()->user()->id}}', '{{$cartItem->id}}', '{{$cat_id}}', '{{$cartItem->price}}' , '{{$cartItem->type}}')"><div style="width:50px;display:inline-block;"><span class="ds-minus"></span></div></a>
										<input type="text" name="quantity" data-cust-id="{{auth()->user()->id}}" data-prd-id="prd_qty_{{$cartItem->id}}" id="prd_qty_{{$cartItem->id}}" value="{{ $cartItem->qty }}" data-cat-id="{{$cat_id}}" data-price="{{$cartItem->price}}" data-prd-type="{{$cartItem->type}}" size="1" class="update_cart_prd_bluk">
										<a href="javascript:void(0)" class="quantity_update plusqty_{{$cartItem->id}}" onClick="plus_shopping_cart('prd_qty_{{$cartItem->id}}', '{{auth()->user()->id}}', '{{$cartItem->id}}', '{{$cat_id}}', '{{$cartItem->price}}' , '{{$cartItem->type}}')"><div style="width:50px;display:inline-block;"><span class="ds-pluase"></span></div></a>                            
									</form>
								</td>
								<td></td>
								<td class="text-nowrap">{{config('cart.currency_symbol_2')}} {{ number_format($cartItem->price*$cartItem->qty, 2) }}</td>
							   
								<td>
								   
									<button data-from="cart" data-url="{{ route('cart.destroy', $cartItem->rowId) }}" class="close removeCartProduct"><i class="fas fa-times"></i></button>

								</td>
							   
							</tr>
						@endforeach
						<input type="hidden" id="total_basket_qty" value="{{$totalqty}}">
						</tbody>
					</table>
				
					<hr>
					<div class="row">
						<div class="col-md-6">
							<div class="">
								<a href="{{ route('page.index', ['fruit', 'main-line']).'/' }}" class="btn btn-outline-primary mt-4 mb-4 py-2 d-block">Back to Shop</a>
							   
							</div>
						</div>
						<div class="col-md-6">
							<div class="">
								 <a href="{{ route('checkout.index').'/' }}" class="btn btn-brownish mt-4 mb-4 py-2 d-block">Continue <span class="ds-right-arrow"></span></a>
							</div>
						</div>
					</div>
				@else
					<div class="row">
						<div class="col-md-12">
							<p class="alert alert-warning">No products in cart yet. <a href="{{ route('home') }}">Shop now!</a></p>
						</div>
					</div>
				@endif
			</div>          
		</div>
	</div>

</div>
     
	   
    </div>
 </section>
@endsection
@section('css')
    <style type="text/css">
        .product-description {
            padding: 10px 0;
        }
        .product-description p {
            line-height: 18px;
            font-size: 14px;
        }
    </style>
@endsection