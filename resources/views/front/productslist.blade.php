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
        <div class="col-md-8 my-account-information">
            <h3>{{$category_name}}</h3>
           
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
                @if(!$products->isEmpty())
                <input type="hidden" name="shoppinglisturl" id="shoppinglisturl" value="{{route('accounts.updateshoppinglist')}}">
                <input type="hidden" name="default_minimum_order" id="default_minimum_order" value="{{$default_minimum_order}}"> 
                @foreach ($products as $key=>$product)
                    <tr>
                       <td>{{$product->product_code}}</td>
                       <td @if($product->products_status_2 != 1) style="color:red" @endif>{{$product->name}}</td>
                       <td>{{$product->packet_size}}</td>
                       <td>{{$product->type}}</td>
                       <td>{{$updated_price_with_markup[$product->id]}}</td>
                       <td>
                          @if($product->products_status_2 != 1) 
                          <span style="color:red">Out Of Stock</span> 
                          @else
                          In Stock
                          @endif  
                            
                        </td>
                        <td>
                           <div class="plusminus">
                            @if($product->products_status_2 == 1)
                                <img src="{{asset("images/greenplus.png")}}" alt=""  width="13" height="12" class="quantity_update plusqty_{{$product->id}}" onClick="plus('qty_{{$product->id}}_{{++$key}}', '{{auth()->user()->id}}', '{{$product->id}}', '{{$product->catid}}', '{{$product->price}}', '{{$product->type}}')" />
                                <img src="{{asset("images/greenminus.png")}}"  alt="" width="13" height="12" class="quantity_update minusqty_{{$product->id}}" onClick="minus('qty_{{$product->id}}_{{$key}}', '{{auth()->user()->id}}', '{{$product->id}}', '{{$product->catid}}', '{{$product->price}}' , '{{$product->type}}')" />
                            @endif
                          </div>
                            <div class="txtboxquantout">
                              <input name="qty_{{$product->id}}_{{$key}}"  style="height:23px;"   type="text" id="qty_{{$product->id}}_{{$key}}" value="0" size="2" @if($product->products_status_2 != 1) disabled @endif>
                            </div>
                            @if($product->products_status_2 == 1)
                                <button class="btn btn-primary quantity_update addbulkqty_{{$product->id}}" style="width:48px;height:30px;margin-top:2px;" onClick="addbulkqty('qty_{{$product->id}}_{{$key}}', '{{auth()->user()->id}}', '{{$product->id}}', '{{$product->catid}}', '{{$product->price}}' , '{{$product->type}}')">Add</button>
                            @endif
                           
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
                <tr><td colspan="7" class="no_record_found">{{ config('constants.NO_RECORD_FOUND') }}</td></tr>
                @endif
                </tbody>
            </table>
            
        </div>
    </div>
</section>
<!-- /.content -->
@endsection