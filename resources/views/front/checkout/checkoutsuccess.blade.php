@extends('layouts.front.app')

@section('content')

<section class="content">
    <div class="row">
        <div class="box-body">
<!--            @include('layouts.errors-and-messages')-->
        </div>
        <div class="col-md-12">
            <h2> <i class="fa fa-check"></i> Order Success</h2>
            <hr>
            <p><strong>Thanks for shopping with us online!</strong></p>
        </div>
    </div>
    
<div class="row">
      
    <div class="col-md-4">
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#print_order">Print Order</button>
    </div>
      
    <div class="col-md-3">
        <a href="{{route('accounts', ['tab' => 'orders'])}}" class="btn btn-info">Go to Orders</a>
    </div>
      
</div>

<!-- print order dialog box -->
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
            <td colspan="2" align="center" class="titleHeading"><b>ORDER #{{$order_details->id}}</b></td>
          </tr>
          <tr align="left">
            <td colspan="2" class="titleHeading"><?php //echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr> 
  
 @if($customer_id == $order_details->customer_id) 
  <tr>
    <td align="left" class="main"><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
          <td class="main" colspan="2"><b>Payment Method:</b> {{ucwords(str_replace("-", " ", $order_details->payment_method))}}</td>
      </tr>
    </table></td>
  </tr>
  <tr>
      <td class="main"><b>Date Purchased:</b> {{$order_details->created_at}}</td>
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
                       @if($order_details->billing_name) {{$order_details->billing_name}}<br /> @endif
                       @if($order_details->billing_company) {{$order_details->billing_company}}<br /> @endif
                       @if($order_details->billing_street_address) {{$order_details->billing_street_address}}<br /> @endif
                       @if($order_details->billing_address_line_2) {{$order_details->billing_address_line_2}}<br /> @endif
                        {{$order_details->billing_city}} {{$order_details->billing_postcode}}<br />
                        {{$order_details->billing_state}}, {{$order_details->billing_country}}
                      
                      
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
                        @if($order_details->shipping_add_name) {{$order_details->shipping_add_name}}<br />@endif
                        @if($order_details->shipping_add_company) {{ $order_details->shipping_add_company }}<br />@endif
                        @if($order_details->shipping_street_address) {{$order_details->shipping_street_address}}<br />@endif
                        @if($order_details->shipping_address_line2) {{$order_details->shipping_address_line2}}<br />@endif
                        {{$order_details->shipping_city}} {{$order_details->shipping_post_code}}<br />
                        {{$order_details->shipping_state}}, {{$order_details->shipping_country}}
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
             @foreach($order_products as $order_product)
                @php $total_price += $order_product->product_price*$order_product->quantity @endphp
             <tr>
                <td>{{$order_product->quantity}} X {{$order_product->product_name}} ({{$order_product->type}})</td>
                <td>£ {{number_format($order_product->product_price, 2)}}</td>
                <td>£ {{number_format($order_product->product_price*$order_product->quantity, 2)}}</td>
             </tr>
             @endforeach
             @if($order_details->customer_discount > 0)
                @php $total_price = $order_details->sub_total - $order_details->customer_discount;  @endphp
             @else
               @php $total_price = $order_details->sub_total; @endphp
             @endif
             
             <tr><td colspan="3" align="right" style="padding:10px;"><b>Sub Total:</b> £ {{number_format($order_details->sub_total, 2)}}</td></tr>
            @if($order_details->customer_discount > 0) <tr><td colspan="3" align="right" style="padding:10px;"><b>Coupon Discount:</b> £ {{number_format($order_details->customer_discount, 2)}}</td></tr>@endif
             <tr><td colspan="3" align="right" style="padding:10px;"><b>Total:</b> £ {{number_format($total_price, 2)}}</td></tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  @else
  <tr><td><b style="color:red">Entry Access Error.</b></td></tr>
  @endif
            </div>
</table>
<!-- body_text_eof //-->

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
    
 
</section>
    
@endsection