<div class="table-responsive">
<table class="table table-bordered table-striped">
<thead class="thead-light">
    <tr>
        <th width="50px;">Select</th>
        <th>Product Name</th>
        <th>Size</th>
        <th>Price(£)</th>
    </tr>
</thead>
<tbody>
@if(!empty($products_list))
@foreach ($products_list as $cat)        
    <tr>
        <td colspan='4' class="" style="padding:5px 10px;"><strong>{{strtoupper($cat['name'])}}</strong></td>
    </tr>
    @if(isset($cat['products']))
        @foreach ($cat['products'] as $product)
        @php
                $rowclass = '';
                if($product['productStatusBackend'] == '0')
                {
                    $rowclass = "style='background-color:#F0F1F1'";
                    $setproductname = "<font color=red><strong>".$product['productName']."</strong></font>";
                } else {
                    $rowclass = '';
                    $setproductname = $product['productName'];
                }
            @endphp 
            <tr {!!$rowclass!!}>
                <td><input type="hidden" name="products[{{$product['productId']}}][productId]" value="{{$product['productId']}}" /><input type="checkbox" name="products[{{$product['productId']}}][selected]" value="1"  /></td>
                <td><input type="hidden" name="products[{{$product['productId']}}][productName]" value="{{$product['productName']}}" />
                    {!!$setproductname!!} ({{$product['productType']}}) </td>
                <td><input type="hidden" name="products[{{$product['productId']}}][productSize]" value="{{$product['productSize']}}" />
                    {{$product['productSize']}} </td>
                <td><input type="hidden" name="products[{{$product['productId']}}][productPrice]" value="{{$product['productPrice']}}" />
                    {{$product['productPrice']}} 
                    <input type="hidden" name="products[{{$product['productId']}}][productStatusFront]" value="{{$product['productStatusFront']}}" />
                    <input type="hidden" name="products[{{$product['productId']}}][productStatusBackend]" value="{{$product['productStatusBackend']}}" />
                    <input type="hidden" name="products[{{$product['productId']}}][productType]" value="{{$product['productType']}}" />
                </td>
            </tr>
        @endforeach
    @endif
@endforeach
@else
<tr><td colspan="4" style="text-align: center">{{config('constants.NO_RECORD_FOUND')}}</td></tr>
@endif
</tbody>
</table>
</div>
<div class="box-footer">
<div class="btn-group">
 <input type="submit" name="addproduct" data-toggle="modal" data-target="#addproductmodal" value="Add Products" class="btn btn-primary" />&nbsp;
 <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sendpdf">Send Mail</button>
</div>