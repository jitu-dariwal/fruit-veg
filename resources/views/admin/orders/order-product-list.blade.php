<div class="box-body">
	<div class="row pb-2">
		<h4 class="col-sm-6"> <i class="fa fa-gift"></i> Add Products</h4>
		<div class="col-sm-6 text-right">
			<a href="{{route('admin.orders.tailor_made',$order->id)}}" class="btn  btn-success my-1" title="Tailor Made"> Tailor Made</a>
			<button type="button" class="btn  btn-success my-1" title="Add Product" data-toggle="modal" data-target="#addproduct"><i class="fa fa-plus"></i> Add Product</button>
		</div>
	</div>
	
	<div class="table-responsive">
		<table class="table table-bordered">
			<thead class="thead-light">
				<tr>
					<th>Code</th>
					<th>Product Description</th>
					<th>Amount</th>
					<th>Packet Size</th>
					<th>Type</th>
					<th>Current Price</th>
					<th>Actual Weight</th>
					<th>Packed</th>
					<th>Estimated Total({!! config('cart.currency_symbol') !!})</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				@php $sub_total=0; @endphp
				@if(count($order->orderproducts)>0)
					@foreach($order->orderproducts as $orderproduct)
						<tr>
						  <td>{{$orderproduct->product_code}}</td>
						  <td>
						  <input type="hidden" name="order_product[]" value="{{$orderproduct->product_id}}">
						  <input type="number" class="product_quantity" name="quantity[]" style="width: 50px;" value="{{$orderproduct->quantity}}"> x {{$orderproduct->product_name}}</td>
						  <td>1</td>
						  <td>{{$orderproduct->packet_size}}</td>
						  <td>{{$orderproduct->type}}</td>
						  <td>{{$orderproduct->product_price}}</td>
						  <td>{{$orderproduct->actual_weight}} {{$orderproduct->weight_unit}}</td>
						  <td><input type="radio" value="1" class="product_packed" name="packed_{{$orderproduct->product_id}}" {{ ($orderproduct->is_packed==1) ? 'checked' : '' }}> Yes <input type="radio" class="product_packed" value="0" name="packed_{{$orderproduct->product_id}}" {{ ($orderproduct->is_packed==0) ? 'checked' : '' }}>No </td>
						  @php $sub_total+=$orderproduct->final_price; @endphp
						  <td>{{$orderproduct->final_price}}</td>
						  <td>
						  
								<div class="btn-group">
									<a onclick="return confirm('You are about to delete this record?')" title="Remove Product" href="{{URL::to('admin/orders/product/remove/'.$orderproduct->product_id.'/'.$order->id)}}" class="btn btn-danger btn-sm"> <i class="fa fa-times" aria-hidden="true"></i></a>
								</div>
						  </td>
						</tr>
					@endforeach
					<tr>							
						<td colspan="8" align="right"><strong>Sub Total({!! config('cart.currency_symbol') !!}):</strong></td>
						<td colspan="2" style="text-align: -webkit-left;"><input type="hidden" name="sub_total" id="sub_total" value="{{$sub_total}}">{{$sub_total}}</td>
					</tr>
					<tr>
						<td colspan="8" align="right"><strong>Customer Discount({!! config('cart.currency_symbol') !!}):</strong></td>
						<td colspan="2"><input type="number" name="customer_discount" id="customer_discount" value="{{ !empty($order->customer_discount) ? $order->customer_discount : 0 }}" id="customer_discount" min="0" class="form-control"></td>
					</tr>
					<tr>
						<td colspan="8" align="right">
							<strong>Update Shipping Method:</strong>
							<select name="shipping_method" id="shipping_method" class="form-control">
								<option {{ ($order->shipping_method=='I am within the FREE delivery Zone') ? 'selected' : ''}} value="I am within the FREE delivery Zone">I am within the FREE delivery Zone</option>
								<option {{ ($order->shipping_method=='Chargeable Delivery') ? 'selected' : ''}} value="Chargeable Delivery">Chargeable Delivery</option>
							</select>
						</td>
						<td  colspan="2">
							<input type="number" id="shipping_charges" value="{{ !empty($order->shipping_charges) ? $order->shipping_charges : 0 }}" name="delivery_charges" id="delivery_charges" min="0" class="form-control">
						</td>
					</tr>
					<tr>							
						<td colspan="8" align="right">
							<strong>Total({!! config('cart.currency_symbol') !!}):</strong>
						</td>
						<td colspan="2">
							<input type="hidden" name="total_amount" id="total_amount" value="{{$sub_total+$order->shipping_charges-$order->customer_discount}}"> <span id="order_total">{{$sub_total+$order->shipping_charges-$order->customer_discount}}</span>
						</td>
					</tr>
					<tr>							
						<td colspan="8" align="right">
							<strong>Recurring Order Status:</strong>
						</td>
						<td colspan="2">
							<select name="recurr_order_status" id="recurr_order_status" class="form-control">
								<option {{ ($order->orderDetail->recurr_status=='One Time Order') ? 'selected' : ''}} value="One Time Order">One Time Order</option>
								<option {{ ($order->orderDetail->recurr_status=='Recurring') ? 'selected' : ''}} value="Recurring">Recurring</option>
								<option {{ ($order->orderDetail->recurr_status=='Stop Recurring') ? 'selected' : ''}} value="Stop Recurring">Stop Recurring</option>
							</select>
						</td>
					</tr>
					<tr>							
						<td colspan="8" align="right"><strong>Discount Detailed:</strong></td>
						<td colspan="2">
							<select class="form-control" name="discount_type">
								<option value="0">Select discount detailed</option>
								@foreach(Config::get('constants.DISCOUNT_TYPES') as $dkey=>$dval)
									<option {{ ($order->discount_type==$dkey) ? 'selected' : ''}} value="{{$dkey}}">{{$dval}}</option>
								@endforeach
							</select>
						</td>
					</tr>
				@endif
			</tbody>
		</table>
	</div> 
	<div class="box-footer">
		<div class="d-flex align-items-center justify-content-end">
			<strong class="mr-4">Please Update This Point!:</strong>	
			<button type="button" id="update-order-product-details" class="btn btn-primary ">Update</button>
		</div>
	</div>
</div>