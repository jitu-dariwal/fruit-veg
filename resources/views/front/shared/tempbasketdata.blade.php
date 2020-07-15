<input type="hidden" name="total_basket_price" id="total_basket_price" value="{{str_replace(',', '', $total_price)}}">
<input type="hidden" name="default_minimum_order" id="default_minimum_order" value="{{$default_minimum_order}}">
<input type="hidden" name="total_products_price" id="total_products_price" value="{{str_replace(',', '', $total_products_price)}}">
<ul class="cart-list list-unstyled">
	@php
			$totalqty = 0;
	@endphp
	@foreach ($cartItems as $key=>$cartItem)
		@php
			$totalqty = $totalqty+$cartItem->qty;
			
			//if(empty($cat_id))
				$cat_id = $cartItem->product->categories[0]->id;
		@endphp
		<li>
			<button data-url="{{ route('cart.destroy', $cartItem->rowId) }}" class="close removeCartProduct"><i class="fa fa-times"></i></button>
			<div class="name">{{$cartItem->name}}({{$cartItem->type}})</div>   
			<div class="size">({{$cartItem->packet_size}})</div>
			<div class="qty-price">
				<form action="add" class="quantity-block2">
					<a href="javascript:void(0)" class="quantity_update minusqty_{{$cartItem->product_id}}" onClick="minus('tempprd_qty_{{$cartItem->product_id}}', '{{auth()->user()->id}}', '{{$cartItem->product_id}}', '{{$cat_id}}', '{{$cartItem->price}}' , '{{$cartItem->type}}')"><span class="ds-minus"></span></a>
						
					<input type="text" name="prd_qty" data-cust-id="{{auth()->user()->id}}" data-prd-id="prd_qty_{{$cartItem->product_id}}" id="tempprd_qty_{{$cartItem->product_id}}" value="{{$cartItem->qty}}" data-cat-id="{{$cat_id}}" data-price="{{$cat_id}}" data-prd-type="{{$cartItem->type}}" size="1" class="form-control update_prd_bluk">
					<a href="javascript:void(0)" class="quantity_update plusqty_{{$cartItem->product_id}}" onClick="plus('tempprd_qty_{{$cartItem->product_id}}', '{{auth()->user()->id}}', '{{$cartItem->product_id}}', '{{$cat_id}}', '{{$cartItem->price}}', '{{$cartItem->type}}')"><span class="ds-pluase"></span></a>                            
				</form>
				<div class="price">{{ config('cart.currency_symbol_2') }}{{number_format($cartItem->price*$cartItem->qty, 2)}}</div>
			</div>                    
		</li>
	@endforeach
</ul>
<div class="total">
	<span>Total</span>
	<div class="total-prise"><input type="hidden" id="total_basket_qty" value="{{$totalqty}}">{{ config('cart.currency_symbol_2') }}{{number_format(str_replace(',', '', $total_price), 2)}}</div>
</div>
<div id="close_note">
	<div class="note mb-2">Please note:</div>
	<p>You are this close to qualifying for free delivery over {{ config('cart.currency_symbol_2') }}{{number_format($default_minimum_order, 2)}}</p>
</div>
<div class="progress">
@if((intval(str_replace(',', '', $total_price))/intval($default_minimum_order))*100>100)
	<div class="progress-bar" role="progressbar" style="width:100%;background-color:green;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
@else
	<div class="progress-bar" role="progressbar" style="width:{{(intval(str_replace(',', '', $total_price))/intval($default_minimum_order))*100}}%" aria-valuenow="{{(intval(str_replace(',', '', $total_price))/intval($default_minimum_order))*100}}" aria-valuemin="0" aria-valuemax="100"></div>
@endif
  
</div>
<div class="progress-prise">
	<span>{{ config('cart.currency_symbol_2') }}0</span>
	<span>{{ config('cart.currency_symbol_2') }}{{number_format($default_minimum_order, 2)}}</span>
</div>