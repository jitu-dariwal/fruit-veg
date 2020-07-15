@extends('layouts.front.app')

@section('content')
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="box-body">
<!--            @include('layouts.errors-and-messages')-->
        </div>
        <div class="col-md-12">
            <h2> <i class="fa fa-home"></i> My Account</h2>
            <hr>
            <p> If you have a regular order in place you must contact us to change any details.
                You can however use your account to order additional fruitboxes.
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 my-account-categories">
            
            @include('front.shared.categories')
            
         </div>
        <div class="col-md-8">
           <table class="table table-bordered">
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Size</th>
                    <th>Type</th>
                    <th>Price(£)</th>
                    <th>Status</th>
                    <th>Quantity</th>
                </tr>
             <tbody> 
                @if(!empty($products_data))
                <input type="hidden" name="shoppinglisturl" id="shoppinglisturl" value="{{route('accounts.updateshoppinglist')}}">
                <input type="hidden" name="default_minimum_order" id="default_minimum_order" value="{{$default_minimum_order}}">
                @foreach($products_data as $prd_data)
                <tr><td colspan="7"><strong>{{$prd_data['name']}}</strong></td></tr>
                    @foreach ($prd_data['products'] as $key=>$product)
                    <tr>
                       <td>{{$product['productCode']}}</td>
                       <td @if($product['productStatusFront'] != 1) style="color:red" @endif>{{$product['productName']}}</td>
                       <td>{{$product['productSize']}}</td>
                       <td>{{$product['productType']}}</td>
                       <td>{{$updated_price_with_markup[$product['productId']]}}</td>
                       <td>
                          @if($product['productStatusFront'] != 1) 
                          <span style="color:red">Out Of Stock</span> 
                          @else
                          In Stock
                          @endif  
                            
                        </td>
                        <td>
                           <div class="plusminus">
                            @if($product['productStatusFront'] == 1)
                            <img src="{{asset("images/greenplus.png")}}" alt="" width="13" height="12" class="quantity_update plusqty_{{$product['productId']}}" onClick="plus('qty_{{$product["productId"]}}_{{++$key}}', '{{auth()->user()->id}}', '{{$product["productId"]}}', '{{$product["catId"]}}', '{{$product["productPrice"]}}', '{{$product["productType"]}}')" />
                            <img src="{{asset("images/greenminus.png")}}" alt="" width="13" height="12" class="quantity_update minusqty_{{$product['productId']}}" onClick="minus('qty_{{$product["productId"]}}_{{$key}}', '{{auth()->user()->id}}', '{{$product["productId"]}}', '{{$product["catId"]}}', '{{$product["productPrice"]}}' , '{{$product["productType"]}}')" />
                            @endif
                          </div>
                            <div class="txtboxquantout">
                              <input name="qty_{{$product['productId']}}_{{$key}}"  style="height:23px;"   type="text" id="qty_{{$product['productId']}}_{{$key}}" value="0" size="2" @if($product['productStatusFront'] != 1) disabled @endif>
                            </div>
                           
                        </td>
                        
                     
                   </tr>
                    
                   @endforeach
                  @endforeach
                @else
                <tr><td colspan="7" class="no_record_found">{{ config('constants.NO_RECORD_FOUND') }}</td></tr>
                @endif
                </tbody>
            </table>
            
        </div>
    </div>
</section>
<!-- /.content -->
@endsection