<div class="table-responsive">
<table class="table table-bordered table-striped">
    <thead class="thead-light">
        <tr>
            <th>Product Name</th>
            <th>Bulk Size</th>
            <th>Stock <img src="{{ asset("images/action_check.png") }}">(In Stock) <img src="{{ asset("images/action_delete.png") }}">(Out Stock)</th>
            <th>Price(£)</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php 
        $i = 0;
        @endphp
   @if(!empty($products_list))
        @foreach ($products_list as $cat)        
        <tr>
            <td colspan='6' class="" style="padding:5px 10px;"><strong>{{strtoupper($cat['name'])}}</strong></td>
        </tr>

        @if(isset($cat['products']))

        @foreach ($cat['products'] as $product)
        @php 
        $i++;
        @endphp
        <tr>
            <td>{{$product['productName']}} ({{$product['productType']}})</td>
            <td>{{$product['productSize']}}</td>
            <td>Front End 

                @if ($product['productStatusFront'] != 1)
                <span id="show_image_{{$i}}" class="showimg_u_link"><img src="{{ asset("images/action_delete.png") }}" name="Image1_{{$i}}" alt="Add to In Stock" title="Add to In Stock"  border="0" onClick="Stock_frontend('{{$product['productId']}}', '1', '{{$i}}')"></span>
                @else
                <span id="show_image_{{$i}}" class="showimg_u_link"> <a href=""></a><img src="{{ asset("images/action_check.png") }}" alt="Add to Out of Stock" title="Add to Out of Stock" name="Image1_{{$i}}" border="0" onClick="Stock_frontend('{{$product['productId']}}', '0', '{{$i}}')"></span>
                @endif
                &nbsp;Back End 
                @if ($product['productStatusBackend'] != 1)

                <span id="show_image2_{{$i}}" class="showimg_u_link">
                    <img src="{{ asset("images/action_delete.png") }}" alt="Add to In Stock" title="Add to In Stock" name="Image2_{{$i}}" border="0" onClick="Stock_backend('{{$product['productId']}}', '1', '{{$i}}')"></span>

                @else

                <span id="show_image2_{{$i}}" class="showimg_u_link"><img src="{{ asset("images/action_check.png") }}" alt="Add to Out of Stock" title="Add to Out of Stock" name="Image2_{{$i}}" border="0" onClick="Stock_backend('{{$product['productId']}}', '0', '{{$i}}')"></span>

                @endif


            </td>
            <td>
                <input name="price_{{$i}}" type="text" id="price_{{$i}}" value="{{$product['productPrice']}}" size="1" class="form-control" onblur="ProductPriceUpdate({{$product['productId']}}, {{$i}})">
                <div id="bulkprice_updated_{{$i}}" class="bulk_priceupdate_success"></div>
            </td>
            <td> 
                <form action="{{ route('admin.products.destroy', $product['productId']) }}" method="post" class="form-horizontal">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="delete">
                    <input type="hidden" name="deletefrombulk" value="1">
                    <div class="btn-group">
                        <a href="{{ route('admin.products.edit', $product['productId']) }}" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                        <button onclick="return confirm('You are about to delete this record?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Delete</button>
                    </div>
                </form> 
            </td>

        </tr>

        @endforeach
        @endif
        @endforeach
    @else
    <tr><td colspan="5" style="text-align: center">{{config('constants.NO_RECORD_FOUND')}}</td></tr>
    @endif
    </tbody>
</table>
</div>
</form>


</div>