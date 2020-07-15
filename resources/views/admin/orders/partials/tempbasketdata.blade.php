<div class="sorted_shopping_list">
	<input type="hidden" name="total_basket_price" id="total_basket_price" value="{{$total_price}}" />
	<table class="table table-bordered">
		@foreach ($temp_basket_data as $temp_data)
			<tr>
				<td>
					{{ $product_data[$temp_data->products_id]['product_name'] }} ({{ $product_data[$temp_data->products_id]['product_type'] }})<br/>
					
					{{$product_data[$temp_data->products_id]['packet_size']}}
					
					<small>{{$product_data[$temp_data->products_id]['catname_prd']}}</small><br/>
					
					<a href="{{ route('admin.orders.tailor_made.removeproducts', ['product_id' => $temp_data->products_id,  'tempid' => $temp_data->customers_basket_id]) }}">
						<img class="delete_basket_product" data-id="{{$temp_data->products_id}}" src="{{asset('images/delete_img.png')}}"  />
					</a>
				</td>
				<td>
					<input type="text" namd="prd_qty" value="{{$temp_data->customers_basket_quantity}}" size="1" class="form-control" readonly="true" />
				</td>
			</tr>
		@endforeach
	</table>
</div>