<table class="table table-bordered">

                            <tr>
                                <td colspan="3">
                                    <div class="form-group">
                                        <label for="name">Customer Name <span class="text-danger">*</span></label>
                                        <input type="text" name="customer_name" id="customer_name" placeholder="Customer Name" required="required" class="form-control"  value="{{ old('cname') }}">
                                    </div>  
                                </td>
                                
                            </tr>
 <tr>
        <th>Product Name</th>
        <th>Size</th>
        <th>Price(£)</th>
    </tr>                           
@php $key = 1; $countproducts = 1;   @endphp                    
@foreach($product_selected_list as $productsarr)
    
        @if(isset($productsarr['selected']) && !empty($productsarr['selected']))
        
            @php
                $rowclass = '';
                $countproducts++;
                $key++;
                if($productsarr['productStatusBackend'] == '0')
                {
                    $rowclass = "style='background-color:#F0F1F1'";
                    $setproductname = "<font color=red><strong>".$productsarr['productName']."</strong></font>";
                } else {

                    $rowclass = '';
                    $setproductname = $productsarr['productName'];

                }

              @endphp   
        
          <tr {!!$rowclass!!}>
              
                    <td style="padding: 5px;"><input name="{{$key}}_productType" value="{{$productsarr['productType']}}"  type="hidden"><input name="{{$key}}_productName" value="{{$productsarr['productName']}}"  type="hidden">{!!$setproductname!!} ({{$productsarr['productType']}})</td>
                    <td style="padding: 5px;"><input name="{{$key}}_productSize" value="{{$productsarr['productSize']}}"  type="hidden">{{$productsarr['productSize']}}</td>
                    <td style="padding: 5px;"><input name="{{$key}}_productPrice" value="{{$productsarr['productPrice']}}"  type="hidden">{{$productsarr['productPrice']}}</td>
          </tr>
       @endif
        
@endforeach
<input name="total_products" value="{{$countproducts}}"  type="hidden">
</table>

 <div class="box-footer">
                            <div class="btn-group">

                                <input type="submit" name="submit" value="Generate PDF" class="btn btn-primary" />
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sendpdf" data-dismiss="modal">Send Mail</button>
                            </div>
</div>