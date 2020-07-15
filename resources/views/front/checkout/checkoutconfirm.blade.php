@extends('layouts.front.app')

@section('content')

<section class="content">
    <div class="row">
        <div class="box-body">
<!--            @include('layouts.errors-and-messages')-->
        </div>
        <div class="col-md-12">
            <h2> <i class="fa fa-home"></i> Order Confirmation</h2>
            <hr>
            <p>When you place your order, this is an estimated total price. Final prices will be on your delivery invoice and also availabe to view within your account page once the order has been delivered.
            </p>
        </div>
    </div>
    
  <div class="row">
      
    <div class="col-md-12">
        @include('front.products.checkout-product-list-table', compact('products'))
    </div>
      
 </div>
    
 <form class="form-horizontal" role="form" method="POST" action="{{ route('checkout.addorder', $customer->id) }}">
     
     {{ csrf_field() }}   
     <input type="hidden" name="discount_price" value="{{$discount_coupon_amount}}">
     <input type="hidden" name="total_amount" value="{{$total}}">
    <div class="row"><hr /></div>   
<div class="row">
    <div class="col-md-6">
        <h4>Delivery Address <a href="{{route('checkout.index')}}">Edit</a></h4>
        <table>
            <tbody>
                <tr>
                   <td>{{ $deliveryAddress->first_name }} {{ $deliveryAddress->last_name }}<br>
                        {{ $deliveryAddress->company_name }}
                        {{$deliveryAddress->street_address}}<br />
                        {{$deliveryAddress->address_line_2}}<br />{{$deliveryAddress->city}} {{$deliveryAddress->post_code}}<br />
                        {{$deliveryAddress->county_state}}, United Kingdom
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <h4>Billing Address <a href="{{route('checkout.index')}}">Edit</a></h4>
        <table>
            <tbody>
                <tr>
                   
                    <td>{{ $billingAddress->first_name }} {{ $billingAddress->last_name }}<br />
                        {{ $billingAddress->company_name }}
                        {{$billingAddress->street_address}}<br />
                        {{$billingAddress->address_line_2}}<br />{{$billingAddress->city}} {{$billingAddress->post_code}}<br />
                        {{$billingAddress->county_state}}, United Kingdom
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
 <div class="row"><hr /></div> 
    <div class="row">
     <div class="col-md-12">
        <h4>Payment method <a href="{{route('checkout.index')}}">Edit</a></h4>
        {{ucwords(str_replace("-", " ", $infos['payment_name']))}}
        
        
        @if($infos['payment_name'] == "secpay")
            
            <input type="hidden" name="amount" value="{{ $total }}">
            <input type="hidden" name="bill_name" value="{{ $billingAddress->first_name }} {{ $billingAddress->last_name }}">
            <input type="hidden" name="bill_addr_1" value="{{$billingAddress->street_address}}">
            <input type="hidden" name="bill_addr_2" value="{{$billingAddress->address_line_2}}">
            <input type="hidden" name="bill_city" value="{{$billingAddress->city}}">
            <input type="hidden" name="bill_state" value="{{$billingAddress->county_state}}">
            <input type="hidden" name="bill_post_code" value="{{$billingAddress->post_code}}">
            <input type="hidden" name="bill_country" value="United Kingdom">
            <input type="hidden" name="bill_tel" value="{{$billingAddress->tel_num}}">
            <input type="hidden" name="bill_email" value="{{$billingAddress->email}}">
            <input type="hidden" name="ship_name" value="{{ $deliveryAddress->first_name }} {{ $deliveryAddress->last_name }}">
            <input type="hidden" name="ship_addr_1" value="{{$deliveryAddress->street_address}}">
            <input type="hidden" name="ship_addr_2" value="{{$billingAddress->address_line_2}}">
            <input type="hidden" name="ship_city" value="{{$deliveryAddress->city}}">
            <input type="hidden" name="ship_state" value="{{$deliveryAddress->county_state}}">
            <input type="hidden" name="ship_post_code" value="{{$deliveryAddress->post_code}}">
            <input type="hidden" name="ship_country" value="United Kingdom">
        @endif
        
        
        
        
        
     </div> 
    </div>
 <div class="row"><hr /></div> 
    <div class="row"><div class="col-md-12">
        <h4>Comment <a href="{{route('checkout.index')}}">Edit</a></h4>
        {{$infos['comments']}}
    </div>
    </div>
 <div class="row"><hr /></div> 
    <div class="row"><div class="col-md-12">
        <h4>Delivery Procedure <a href="{{route('checkout.index')}}">Edit</a></h4>
       {{$infos['delivery_procedure']}}
    </div>
    </div>
 <div class="row"><hr /></div>
 <div class="row">
     <div class="col-md-12">
         <button type="submit" class="btn btn-primary">
                                            Confirm Order
         </button>
     </div> 
     
 </div>
 </form>
</section>
    
@endsection