<form action="{{ route('cart.store') }}" class="form-inline" method="post">
    {{ csrf_field() }}
    <div class="right_shopping_listing">
        <h2>Shopping List</h2>
        <div id="sorted_shopping_list">
          <table class="table table-bordered">
             @if(!$temp_basket_data->isEmpty()) 
                @foreach ($temp_basket_data as $temp_data)
                <tr>
                    <td>{{$product_data[$temp_data->products_id]['product_name']}}({{$product_data[$temp_data->products_id]['product_type']}})
                        <br />{{$product_data[$temp_data->products_id]['packet_size']}}<br /><small>{{$product_data[$temp_data->products_id]['catname_prd']}}</small><br />
                        <a href="{{ route('accounts.deletebasketproduct', ['product_id' => $temp_data->products_id, 'catid' => $cat_id,  'tempid' => $temp_data->customers_basket_id]) }}">
                            <img class="delete_basket_product" data-id="{{$temp_data->products_id}}" src="{{asset('images/delete_img.png')}}"  />
                        </a>

                    </td>
                    <td><input type="text" namd="prd_qty" value="{{$temp_data->customers_basket_quantity}}" size="1" class="form-control" readonly="true"></td>
                </tr>
                @endforeach
                @else
                <tr><td colspan="2">No products in shopping list yet.</td></tr>
                @endif 
            </table>
        </div>              

        <h5>Total (£)</h5>
       £ <input name="total" type="text" id="total" value="{{$total_price}}" readonly="true" required="required" min="50" class="form-control">
        @if($total_products_price < $default_minimum_order)
            <h5 class="demoHeaders" id="minimum_order_meet" style="color:red;margin-top:10px;">You need to meet our minimum order of £ @if($customer->minimum_order != 0 && isset($customer->minimum_order)) {{$customer->minimum_order}} @else {{ config('constants.DEFAULT_MINIMUM_ORDER') }} @endif</h5>
        @endif
        <input type="hidden" name="catid" id="catid" value="{{$cat_id}}" />
       
        <button id="add-to-cart-btn" type="submit" class="btn btn-warning" style="margin-top: 10px;"> <i class="fa fa-cart-plus"></i> Add to cart</button>

       
        
    </div>
  </form>

