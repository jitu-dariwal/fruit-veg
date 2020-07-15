@extends('layouts.admin.app')

@section('content')

	@section('css')
		<style>
			.quantity_update.disabled {pointer-events: none;}
			
			.wrapper2{cursor : pointer}
			.wrapper2.active {
				background: rgba(255,255,255,0.3);
				cursor: grabbing;
				cursor: -webkit-grabbing;
				transform: scale(1);
			}
		</style>
	@endsection
	<!-- Main content -->
	<section class="content">
		@include('layouts.errors-and-messages')

		<div class="row">
			<div class="col-md-3" style="">
				<div class="multi-order-box">
					<h2>Categories</h2>

					<ul class="multi-order-list">

						@if(!empty($categories))
							@foreach ($categories as $category)
								<li>
									<h3>{{$category['name']}}</h3>
									@if (!empty($category['subcategories']))
										<ul> 
											@foreach ($category['subcategories'] as $subcategory)
												@if (!empty($subcategory['childcategories']))
													<li>
														<h3>{{$subcategory['name']}}</h3>
												
														<ul>
														@foreach ($subcategory['childcategories'] as $childCat)
															<li>
																<a href="{{ URL::to('admin/order-product/'.$order_id.'/add-multi-products/'. $childCat['cat_id']) }}">{{$childCat['name']}}</a>
															</li>
														@endforeach
														</ul>
													</li>
												@else											
													<li>
														<a href="{{ URL::to('admin/order-product/'.$order_id.'/add-multi-products/'. $subcategory['cat_id']) }}">{{$subcategory['name']}}</a>
													</li>
												@endif
											@endforeach
										</ul>
									@endif
								</li>
							@endforeach
						@endif
					</ul>    
				</div>
			</div>
			
			<div class="col-md-6 " >
				<div class="multi-order-box tailorMade">
					<h2>{{$catname->name}}</h2>
					
					<div class="wrapper1">
						<div class="div1"></div>
					</div>
					<div class="table-responsive wrapper2">
						<table class="table table-bordered div2">
							<thead class="thead-light"> 
								<tr>
									<th>Code</th>
									<th>Name</th>
									<th>Size</th>
									<th>Type</th>
									<th>Price(£)</th>
									<th>Status</th>
									<th>Quantity</th>
								</tr>
							</thead>
							<tbody> 
								@if(!$products->isEmpty())
									<input type="hidden" name="shoppinglisturl" id="shoppinglisturl" value="{{route('admin.orders.tailor_made.updateproduct')}}" />
									@foreach ($products as $key=>$product)
										<tr>
											<td>{{$product->product_code}}</td>
											<td @if($product->products_status != 1) style="color:red" @endif>{{$product->name}}</td>
											<td>{{$product->packet_size}}</td>
											<td>{{$product->type}}</td>
											<td>{{$updated_price_with_markup[$product->id]}}</td>
											<td>
												@if($product->products_status != 1) 
													<span style="color:red">Out Of Stock</span> 
												@else
													In Stock
												@endif
											</td>
											<td align="center">
												<div class="input-group txtboxquantout">
													@if($product->products_status == 1)
													<div class="input-group-btn">
														<button class="btn btn-success quantity_update product_{{$product->catid}}_{{$product->id}}" onClick="plus('qty_{{$product->id}}_{{++$key}}', '{{$order->customer_id}}', '{{$product->id}}', '{{$product->catid}}', '{{$product->price}}', '{{$product->type}}', {{$order->id}})">
															<i class="fa fa-plus"></i>
														</button>
													</div>
													@endif
													
													<input name="qty_{{$product->id}}_{{$key}}" class="form-control"   type="text" id="qty_{{$product->id}}_{{$key}}" value="{{ (isset($product_data[$product->id]) && !empty($product_data[$product->id]['product_qty'])) ? $product_data[$product->id]['product_qty'] : 0 }}" size="2" readonly @if($product->products_status != 1) disabled @endif >
													
													@if($product->products_status == 1)
													<div class="input-group-btn">
														<button class="btn btn-danger quantity_update product_{{$product->catid}}_{{$product->id}}" onClick="minus('qty_{{$product->id}}_{{$key}}', '{{$order->customer_id}}', '{{$product->id}}', '{{$product->catid}}', '{{$product->price}}' , '{{$product->type}}', {{$order->id}})">
															<i class="fa fa-minus"></i>
														</button>
													</div>
													@endif
												</div>
											</td>

											<!-- <td>
											   <form action="{{ route('cart.store') }}" class="form-inline" method="post">
												   {{ csrf_field() }}
												<input name="quantity"  style="height:23px;" type="hidden" id="qty_{{$product->id}}_{{$key}}_1" value="0">
												<input type="hidden" name="product" value="{{ $product->id }}">
												<button id="add-to-cart-btn" type="submit" class="btn btn-warning" data-toggle="modal" data-target="#cart-modal"> <i class="fa fa-cart-plus"></i> Add to cart</button>
												</form>
											</td> -->
										</tr>

									@endforeach
								@else
									<tr>
										<td colspan="7" class="no_record_found">
											{{ 	config('constants.NO_RECORD_FOUND') }}
										</td>
									</tr>
								@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
			
			<div class="col-md-3">
				<div class="multi-order-box">
					<form action="{{route('admin.orders.tailor_made.addproducts',$order_id)}}" class="form-inline" method="post">
						@csrf
						<div class="right_shopping_listing">
							<h2>Shopping List</h2>
							<div id="sorted_shopping_list">

								@if(count($temp_basket_data)>0)
									@include('admin.orders.partials.tempbasketdata')
								@else
									<table class="table table-bordered">
										<tbody>
											<tr>
												<td colspan="2">
													No products in shopping list yet.
												</td>
											</tr>
										</tbody>
									</table>	
								@endif
							</div>     

							<h5>Total (£)</h5>
							£ <input name="total" type="text" id="total" value="{{$total_price}}" readonly required="required" min="50" class="form-control">
							<input name="customer_id" type="hidden" value="{{$order->customer_id}}">
							<input name="order_id" type="hidden" value="{{$order->id}}">
							
							@if($total_price < $default_minimum_order)
								<h5 class="demoHeaders" id="minimum_order_meet" style="color:red;margin-top:10px;">You need to meet our minimum order of {{ config('cart.currency_symbol_2') }} {{number_format($default_minimum_order, 2)}} </h5>
							@endif
							
							@php
								if($default_minimum_order != 0 && ((intval(str_replace(',', '', $total_price))/intval($default_minimum_order))*100 < 100)) {
									$disabled_class="disabled";
								} else if(count($temp_basket_data) < 1){
									$disabled_class="disabled";
								} else {
									$disabled_class="";
								}
								
							@endphp

							<button id="add-to-cart-btn" type="submit" class="btn btn-warning {{ $disabled_class }}" style="margin-top: 10px;" {{$disabled_class}}> <i class="fa fa-cart-plus"></i> Add to cart</button>
							
							<a onclick="window.history.go(-1);" style="margin-top: 10px;" class="btn btn-default  pull-right">Back</a>
						</div>
					</form> 
				</div>	   
			</div>
		</div>

	</section>
    <!-- /.content -->
@endsection

@section('js')
	<script>
	$(function(){
		$(".wrapper1").scroll(function(){
			$(".wrapper2")
				.scrollLeft($(".wrapper1").scrollLeft());
		});
		$(".wrapper2").scroll(function(){
			$(".wrapper1")
				.scrollLeft($(".wrapper2").scrollLeft());
		});
	});
	
	function plus(fieldid, customerid, productid, catid, price, prdtype, orderid) {
		
		var curr_val = $("input#"+fieldid).val();
		var nextval = parseInt(curr_val)+1;
		$("input#"+fieldid).val(nextval);
		
		var qty = $("input#"+fieldid).val();
		
		var shoppinglisturl = $("#shoppinglisturl").val();
		
		$('.product_'+catid+'_'+productid).addClass('disabled');
		
		purl = shoppinglisturl+'?catid='+catid+'&pid='+productid+'&pvalue='+qty+'&uid='+customerid+'&price='+price+'&prdtype='+prdtype+'&order_id='+orderid;
		
		$.ajax({ 
			url: purl, 
			success: function(data){
				$("#sorted_shopping_list").html(data);
				$("#total").val($("#total_basket_price").val());
					 
				if($("#total").val() < Number('{{ $default_minimum_order }}')) {
					$("#minimum_order_meet").show();
					$("#add-to-cart-btn").addClass('disabled').attr('disabled', true);
					
					if($("#total").val() < 1) {
						$("#sorted_shopping_list").html("<table class='table table-bordered'><tr><td colspan='2'>No products in shopping list yet.</td></tr></table>");
					}
				} else {   
					$("#minimum_order_meet").hide();
					$("#add-to-cart-btn").removeClass('disabled').attr('disabled', false);
				}
				
				$('.product_'+catid+'_'+productid).removeClass('disabled');
				return false;	   
			}
		});
	}

	function minus(fieldid, customerid, productid, catid, price, prdtype, orderid) {
		var nextval = 0;
		var curr_val = $("input#"+fieldid).val();
		
		if(curr_val > 0) {
			$('.product_'+catid+'_'+productid).addClass('disabled');
			nextval = parseInt(curr_val)-1;
			$("input#"+fieldid).val(nextval);

			var qty = $("input#"+fieldid).val();
			
			var shoppinglisturl = $("#shoppinglisturl").val();
			
			purl = shoppinglisturl+'?catid='+catid+'&pid='+productid+'&pvalue='+qty+'&uid='+customerid+'&price='+price+'&prdtype='+prdtype+'&order_id='+orderid;
			
			$.ajax({ 
				url: purl,
				success: function(data){
					$("#sorted_shopping_list").html(data);
					$("#total").val($("#total_basket_price").val());
					
					if($("#total").val() < Number('{{ $default_minimum_order }}')) {
							 
						$("#minimum_order_meet").show();
						$("#add-to-cart-btn").addClass('disabled').attr('disabled', true);
						
						if($("#total").val() < 1) {
							$("#sorted_shopping_list").html("<table class='table table-bordered'><tr><td colspan='2'>No products in shopping list yet.</td></tr></table>");
					   }
					} else {
						$("#add-to-cart-btn").removeClass('disabled').attr('disabled', false);
						$("#minimum_order_meet").hide();
					}
					$('.product_'+catid+'_'+productid).removeClass('disabled');
					return false;

				}
			});
		}
	}
	
	/* Below code for table scrol drag by click event */
		const slider = document.querySelector('.wrapper2');
		let isDown = false;
		let startX;
		let scrollLeft;

		slider.addEventListener('mousedown', (e) => {
			isDown = true;
			slider.classList.add('active');
			startX = e.pageX - slider.offsetLeft;
			scrollLeft = slider.scrollLeft;
		});
		slider.addEventListener('mouseleave', () => {
			isDown = false;
			slider.classList.remove('active');
		});
		slider.addEventListener('mouseup', () => {
			isDown = false;
			slider.classList.remove('active');
		});
		slider.addEventListener('mousemove', (e) => {
			if(!isDown) return;
			e.preventDefault();
			const x = e.pageX - slider.offsetLeft;
			const walk = (x - startX) * 3; //scroll-fast
			slider.scrollLeft = scrollLeft - walk;
			//console.log(walk);
		});
	/* End code for table scrol drag by click event */
	</script>
@endsection