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
            <h3>Order Information</h3>
@if($customer_id == $order->customer_id)
<div class="order_num" style="margin-bottom: 10px;"><b>Order #{{$order->id}}</b> ({{$order_status[$order->id]->name}})</div>
            <table width="100%">
                
                
             
                <tr>
                    <td><b>Order Date -</b> {{date('l jS F Y', strtotime($order->created_at))}}</td>
                    <td align="right"><b>Order Total -</b> £ {{$order->total}}</td>
                    
                </tr>
                
      
    <tr>
        <td align="center" valign="top"><table align="center" style="margin-top: 20px;" width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor=#000000>
          <tr>
            <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><b>Billing Address</b></td>
              </tr>
              <tr class="dataTableRow">
                  <td class="dataTableContent">
                       @if($order_details[$order->id]->billing_name) {{$order_details[$order->id]->billing_name}}<br /> @endif
                       @if($order_details[$order->id]->billing_company) {{$order_details[$order->id]->billing_company}}<br /> @endif
                       @if($order_details[$order->id]->billing_street_address) {{$order_details[$order->id]->billing_street_address}}<br /> @endif
                       @if($order_details[$order->id]->billing_address_line_2) {{$order_details[$order->id]->billing_address_line_2}}<br /> @endif
                        {{$order_details[$order->id]->billing_city}} {{$order_details[$order->id]->billing_postcode}}<br />
                        {{$order_details[$order->id]->billing_state}}, {{$order_details[$order->id]->billing_country}}
                      
                      
                  </td>
              </tr>
            </table></td>
          </tr>
        </table></td>
        <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="1" style="margin-top: 20px;" bgcolor=#000000>
          <tr>
            <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><b>Shipping Address</b></td>
              </tr>
              <tr class="dataTableRow">
                  <td class="dataTableContent">
                        @if($order_details[$order->id]->shipping_add_name) {{$order_details[$order->id]->shipping_add_name}}<br />@endif
                        @if($order_details[$order->id]->shipping_add_company) {{ $order_details[$order->id]->shipping_add_company }}<br />@endif
                        @if($order_details[$order->id]->shipping_street_address) {{$order_details[$order->id]->shipping_street_address}}<br />@endif
                        @if($order_details[$order->id]->shipping_address_line2) {{$order_details[$order->id]->shipping_address_line2}}<br />@endif
                        {{$order_details[$order->id]->shipping_city}} {{$order_details[$order->id]->shipping_post_code}}<br />
                        {{$order_details[$order->id]->shipping_state}}, {{$order_details[$order->id]->shipping_country}}
                  </td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      
    <tr>
        <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="1" style="margin-top: 20px;" bgcolor=#000000>
      <tr>
        <td><table border="0" width="100%" cellspacing="5" cellpadding="10">
          <tr class="dataTableHeadingRow">
            <td><b>Products</b></td>
            <td><b>Price</b></td>
            <td><b>Total</b></td>
         </tr>
            @php $total_price = 0 @endphp
             @foreach($order_products[$order->id] as $order_product)
                @php $total_price += $order_product->product_price*$order_product->quantity @endphp
             <tr>
                <td>{{$order_product->quantity}} X {{$order_product->product_name}} ({{$order_product->type}})</td>
                <td>£ {{number_format($order_product->product_price, 2)}}</td>
                <td>£ {{number_format($order_product->product_price*$order_product->quantity, 2)}}</td>
             </tr>
             @endforeach
             <tr><td colspan="3" align="right" style="padding:10px;"><b>Subtotal:</b> £ {{number_format($order->sub_total, 2)}}</td></tr>
             @if($order->customer_discount > 0)
             <tr><td colspan="3" align="right" style="padding:10px;"><b>Coupon Discount:</b> £ {{number_format($order->customer_discount, 2)}}</td></tr>
             @endif
             <tr><td colspan="3" align="right" style="padding:10px;"><b>Total:</b> £ {{number_format($order->total, 2)}}</td></tr>
       </table></td>
      </tr>
    </table></td>
  </tr>
  
 <tr><td colspan="2"><b>Payment Method - </b> {{ucwords(str_replace("-", " ", $order->payment_method))}}</td></tr>
 @if($order_details[$order->id]->shipdate)<tr><td colspan="2"><b>Shipment Arrival Date - </b> {{date('l jS F Y', strtotime($order_details[$order->id]->shipdate))}}</td></tr>@endif
 @if($order_details[$order->id]->earliest_delivery)<tr><td colspan="2"><b>Earliest Delivery Time - </b> {{gmdate("H:i", $order_details[$order->id]->earliest_delivery)}}</td></tr>@endif
 @if($order_details[$order->id]->comment)<tr><td colspan="2"><b>Comments - </b> {{$order_details[$order->id]->comment}}</td></tr>@endif
 @if($order_details[$order->id]->delivery_procedure)<tr><td colspan="2"><b>Delivery Procedure - </b> {{$order_details[$order->id]->delivery_procedure}}</td></tr>@endif
    
 <tr>
     <td colspan="2" height="50">
     
     </td>
 </tr>            
 <tr>
     <td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#print_order">Print Order</button></td>
     <td align="right"><a class="btn btn-info" href="{{route('accounts', ['tab' => 'orders'])}}">Back</a></td>
 </tr>  
 
 
   <tr>
     <td colspan="2" height="50">
     
     </td>
 </tr>           
            </table>
@else
 <b style="color:red">Entry Access Error.</b>
 @endif
		</div>
	</div>
    
    
 <div id="print_order" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Print Order</h4>

            </div>
            <div class="modal-body">
               <!-- body_text //-->
               
 <a href="javascript:void(0);" onClick="printDiv('printableArea')" onMouseOut=document.imprim.src="{{ asset('images/printimage.gif') }}" onMouseOver=document.imprim.src="{{ asset('images/printimage_over.gif') }}"><img src="{{ asset('images/printimage.gif') }}" width="43" height="28" align="absbottom" border="0" name="imprim"> Print</a>
               
               
<div id="printableArea">              
<table width="600" border="0" align="center" cellpadding="2" cellspacing="0">
 <tr>
    <td><table border="0" align="center" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" align="center" width="100%" cellspacing="0" cellpadding="0">
                <tr><td colspan="2" height="20"></td></tr>
          <tr>
              <td class="pageHeading"><strong>{{ config('shop.warehouse.name') }}</strong><br />{{ config('shop.warehouse.address_1') }}<br />{{ config('shop.warehouse.address_2') }}<br />{{ config('shop.warehouse.city') }}<br />{{ config('shop.warehouse.state') }}<br />{{ config('shop.warehouse.zip') }}</td>
            <td class="pageHeading" align="right"><img src="{{ asset('img/logo.png') }}" class="logo" alt="{{ config('app.name') }}" /></td>
          </tr>
          <tr>
            <td colspan="2" align="center" class="titleHeading"><b>ORDER #{{$order->id}}</b></td>
          </tr>
          <tr align="left">
            <td colspan="2" class="titleHeading"><?php //echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr> 
  
 @if($customer_id == $order->customer_id) 
  <tr>
    <td align="left" class="main"><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
          <td class="main" colspan="2"><b>Payment Method:</b> {{ucwords(str_replace("-", " ", $order->payment_method))}}</td>
      </tr>
    </table></td>
  </tr>
  <tr>
      <td class="main"><b>Date Purchased:</b> {{date('l jS F Y', strtotime($order->created_at))}}</td>
  </tr>
  
  <tr><td height="30"></td></tr>
  <tr>
    <td align="center"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor=#000000>
          <tr>
            <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><b>Billing Address</b></td>
              </tr>
              <tr class="dataTableRow">
                  <td class="dataTableContent">
                       @if($order_details[$order->id]->billing_name) {{$order_details[$order->id]->billing_name}}<br /> @endif
                       @if($order_details[$order->id]->billing_company) {{$order_details[$order->id]->billing_company}}<br /> @endif
                       @if($order_details[$order->id]->billing_street_address) {{$order_details[$order->id]->billing_street_address}}<br /> @endif
                       @if($order_details[$order->id]->billing_address_line_2) {{$order_details[$order->id]->billing_address_line_2}}<br /> @endif
                        {{$order_details[$order->id]->billing_city}} {{$order_details[$order->id]->billing_postcode}}<br />
                        {{$order_details[$order->id]->billing_state}}, {{$order_details[$order->id]->billing_country}}
                      
                      
                  </td>
              </tr>
            </table></td>
          </tr>
        </table></td>
        <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor=#000000>
          <tr>
            <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><b>Shipping Address</b></td>
              </tr>
              <tr class="dataTableRow">
                  <td class="dataTableContent">
                        @if($order_details[$order->id]->shipping_add_name) {{$order_details[$order->id]->shipping_add_name}}<br />@endif
                        @if($order_details[$order->id]->shipping_add_company) {{ $order_details[$order->id]->shipping_add_company }}<br />@endif
                        @if($order_details[$order->id]->shipping_street_address) {{$order_details[$order->id]->shipping_street_address}}<br />@endif
                        @if($order_details[$order->id]->shipping_address_line2) {{$order_details[$order->id]->shipping_address_line2}}<br />@endif
                        {{$order_details[$order->id]->shipping_city}} {{$order_details[$order->id]->shipping_post_code}}<br />
                        {{$order_details[$order->id]->shipping_state}}, {{$order_details[$order->id]->shipping_country}}
                  </td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="30"></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="1" bgcolor=#000000>
      <tr>
        <td><table border="0" width="100%" cellspacing="5" cellpadding="10">
          <tr class="dataTableHeadingRow">
            <td><b>Products</b></td>
            <td><b>Price</b></td>
            <td><b>Total</b></td>
         </tr>
            @php $total_price = 0 @endphp
             @foreach($order_products[$order->id] as $order_product)
                @php $total_price += $order_product->product_price*$order_product->quantity @endphp
             <tr>
                <td>{{$order_product->quantity}} X {{$order_product->product_name}} ({{$order_product->type}})</td>
                <td>£ {{number_format($order_product->product_price, 2)}}</td>
                <td>£ {{number_format($order_product->product_price*$order_product->quantity, 2)}}</td>
             </tr>
             @endforeach
             <tr><td colspan="3" align="right" style="padding:10px;"><b>Subtotal:</b> £ {{number_format($order->sub_total, 2)}}</td></tr>
             @if($order->customer_discount > 0)
             <tr><td colspan="3" align="right" style="padding:10px;"><b>Coupon Discount:</b> £ {{number_format($order->customer_discount, 2)}}</td></tr>
             @endif
             <tr><td colspan="3" align="right" style="padding:10px;"><b>Total:</b> £ {{number_format($order->total, 2)}}</td></tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  @else
  <tr><td><b style="color:red">Entry Access Error.</b></td></tr>
  @endif
</table>
            </div>   
    
                
</section>
<!-- /.content -->
@endsection