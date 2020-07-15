<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=text/html; charset=iso-8859-1">
<title>{{env('APP_NAME')}} - Invoices</title>
<base href="{{URL::to('/')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/print.css')}}" media="screen" >
<link rel="stylesheet" type="text/css" href="{{asset('css/print.css')}}" media="print" >
</head>
<style type="text/css">
	html {
	      overflow: -moz-scrollbars-vertical;
	      overflow-y: scroll;
	}
	.smalltext_dan{
		font-size: 12px;
		font-family:Arial, Helvetica, sans-serif;
	}
	.break{ page-break-after:always;}
	/*.tablecontent_dan{
		font-size: 14px;
		font-family:Arial, Helvetica, sans-serif;
	}
	.pageHeading_dan { font-family:Arial, Helvetica, sans-serif; font-size: 14px; color: #727272; font-weight: bold; 
	}
	.dataTableHeadingContent_dan_dan { font-family: Verdana, Arial, sans-serif; font-size: 14px; color: #000000; font-weight: bold; }
	.break{ page-break-after:always;}*/
</style>

<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10">


<!-- body_text //-->
<table width="100%" border="0">
  <tbody><tr>
    <td align="left" valign="top">
@if(count($orders)>0)
@foreach($orders as $order)	
<table width="100%" border="0" class="break">
  <tbody><tr>
    <td width="200" align="left" valign="top">&nbsp;</td>
    <td width="601" align="left" valign="top"><table width="600" border="0" align="center" cellpadding="2" cellspacing="0">
  <tbody>
  <tr align="left">
    <td class="titleHeading"><img src="{{ asset("images/pixel_trans.gif")}}" border="0" alt="" width="1" height="25"></td>
  </tr>
  <tr>
    <td><table border="0" align="center" width="100%" cellspacing="0" cellpadding="0">
      <tbody><tr>
        <td><table border="0" align="center" width="100%" cellspacing="0" cellpadding="0">
          <tbody><tr>
            <td class="pageHeading" style="font-family:Arial, sans-serif;  font-size: 13px;  color: #666666;">Fruit And Veg.co.uk <br>UNIT 5D <br> Bates Industrial Estate  <br>The Old Brickworks, <br> Church Road,  <br>Harold Wood, <br> Essex. <br> RM3 0HU</td>
            <td class="pageHeading" align="right"><img src="{{ asset("img/logo.jpg") }}" border="0" alt="Fruit And Veg" title=" Fruit And Veg "></td>
          </tr>
          <tr>
            <td colspan="2" align="center" style="font-family: Verdana, Arial, sans-serif; font-size: 18px; color:#727272; font-weight: bold;"><b>ORDER NUMBER : {{$order->id}}</b></td>
          </tr>
          <tr align="left">
            <td colspan="2" class="titleHeading"><img src="{{ asset("images/pixel_trans.gif")}}" border="0" alt="" width="1" height="10"></td>
          </tr>
        </tbody></table></td>
      </tr>
    </tbody></table></td>
  </tr>
  <tr>
    <td align="left" class="main"><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tbody><tr>
        <td style="font-family: Verdana, Arial, sans-serif; font-size: 12px;"><b>Payment Method:</b>  {{(!empty($order->payment_method)) ? $paymenttypes=Finder::getPaymentMethods()[$order->payment_method] : 'N/A'}}</td>
      </tr>
      <tr>
        <td style="font-family: Verdana, Arial, sans-serif; font-size: 12px;"><b>Delivery:</b> {{\Carbon\Carbon::parse($order->shipdate)->format('d.m.Y')}}</td>
      </tr>
      <tr>
        <td style="font-family: Verdana, Arial, sans-serif; font-size: 12px;">Updated on Orders Page</td>
      </tr>
      <tr>
        <td style="font-family: Verdana, Arial, sans-serif; font-size: 12px;"></td>
      </tr>
    </tbody></table></td>
  </tr>
  <tr>
    <td style="font-family: Verdana, Arial, sans-serif; font-size: 12px;"><b>Date Purchased:</b> {{$order->created_at->format('d.m.Y H:i:s')}}</td>
  </tr>
  <tr>
    <td align="center"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="1">
      <tbody><tr>
        <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="1">
          <tbody><tr>
            <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
              <tbody><tr>
                <td style="font-family: Verdana, Arial, sans-serif; font-size: 12px; color: #000000; background-color: #C9C9C9; font-weight: bold;"><b>SOLD TO:</b></td>
              </tr>
              <tr class="dataTableRow">
                <td style="font-size:12px; font-family: Verdana, Arial, sans-serif; color: #000000;">{{ucfirst($order->orderdetail->first_name.' '.$order->orderdetail->last_name)}} <br>&nbsp;{{$order->orderdetail->company_name}}<br>&nbsp;{{$order->orderdetail->street_address}}<br>&nbsp;{{$order->orderdetail->address_line_2}}<br>&nbsp;{{$order->orderdetail->city}}<br>&nbsp;{{$order->orderdetail->country_state}}<br>&nbsp;{{$order->orderdetail->country}}<br>&nbsp;{{$order->orderdetail->post_code}}</td>
              </tr>
            </tbody></table></td>
          </tr>
        </tbody></table></td>
        <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="1">
          <tbody><tr>
            <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
              <tbody><tr class="dataTableHeadingRow">
                <td style="font-family: Verdana, Arial, sans-serif; background-color: #C9C9C9; font-size: 12px; color: #000000; font-weight: bold;"><b>SHIP TO:</b></td>
              </tr>
              <tr class="dataTableRow">
                <td style="font-size:12px; font-family: Verdana, Arial, sans-serif; color: #000000;">{{ucfirst($order->orderdetail->shipping_add_name)}} <br>&nbsp;{{$order->orderdetail->shipping_add_company}}<br>&nbsp;{{$order->orderdetail->shipping_street_address}}<br>&nbsp;{{$order->orderdetail->shipping_address_line2}}<br>&nbsp; {{$order->orderdetail->shipping_city}}<br>&nbsp; {{$order->orderdetail->shipping_state}}<br>&nbsp; {{$order->orderdetail->shipping_country}}<br>&nbsp; {{$order->orderdetail->shipping_post_code}}</td>
              </tr>
            </tbody></table></td>
          </tr>
        </tbody></table></td>
      </tr>
    </tbody></table></td>
  </tr>
  <tr>
    <td><img src="{{ asset("images/pixel_trans.gif")}}" border="0" alt="" width="1" height="10"></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="1">
      <tbody><tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tbody><tr class="dataTableHeadingRow">
            <td align="center" style="font-size:12px; font-family: Verdana, Arial, sans-serif; color: #000000;"><strong>Code</strong></td>
            <td style="font-size:12px; font-family: Verdana, Arial, sans-serif; color: #000000;" align="center" colspan="2"><strong>Product</strong></td>
			<td style="font-size:12px; font-family: Verdana, Arial, sans-serif; color: #000000;" align="right"><strong>Ordered</strong></td>
            <td style="font-size:12px; font-family: Verdana, Arial, sans-serif; color: #000000;" align="right"><strong>Pack Size</strong></td>
            <td style="font-size:12px; font-family: Verdana, Arial, sans-serif; color: #000000;" align="right"><strong>Price</strong></td>
			
			<td style="font-size:12px; font-family: Verdana, Arial, sans-serif; color: #000000;" align="right"><strong>Actual Weight</strong></td>
            <td style="font-size:12px; font-family: Verdana, Arial, sans-serif; color: #000000;" align="center"><strong>Total</strong></td>
            <td style="font-size:12px; font-family: Verdana, Arial, sans-serif; color: #000000;" align="right"><strong>Tick</strong></td>
          </tr>
		@foreach($order->orderproducts as $product)
        <tr class="dataTableRow">
        <td style="font-size:12px; font-family: Verdana, Arial, sans-serif; color: #000000;" valign="top" align="right">{{$product->product_code}}</td>
        <td style="font-size:12px; font-family: Verdana, Arial, sans-serif; color: #000000;" valign="top" align="right">{{$product->quantity}}&nbsp;x</td>
        <td style="font-size:12px; font-family: Verdana, Arial, sans-serif; color: #000000;" valign="top">{{$product->product_name}}<br>        </td>
        <td style="font-size:12px; font-family: Verdana, Arial, sans-serif; color: #000000;" align="right" valign="top">{{$product->quantity}}</td>
        <td style="font-size:12px; font-family: Verdana, Arial, sans-serif; color: #000000;" align="right" valign="top">{{$product->weight}} {{$product->weight_unit}}</td>
        <td style="font-size:12px; font-family: Verdana, Arial, sans-serif; color: #000000;" align="right" valign="top">{!! config('cart.currency_symbol') !!} {{$product->product_price}}</td>
        <td style="font-size:12px; font-family: Verdana, Arial, sans-serif; color: #000000;" align="right" valign="top">{{$product->actual_weight}}</td>
        <td style="font-size:12px; font-family: Verdana, Arial, sans-serif; color: #000000;" align="right" valign="top">{!! config('cart.currency_symbol') !!} {{$product->final_price}}</td>
		<td align="right"><img src="tickbox.gif" height="15" width="15"></td>      
		</tr>
		@endforeach
		
		</tbody></table></td>
      </tr>
    </tbody></table></td>
  </tr>
  <tr>
    <td align="right" colspan="7"><table border="0" cellspacing="0" cellpadding="2">
      <tbody><tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                        <tbody><tr>
            <td align="right" style="font-family: Verdana, Arial, sans-serif; font-size: 11px;">Customer Discount:</td>
            <td align="right" style="font-family: Verdana, Arial, sans-serif; font-size: 11px;">{!! config('cart.currency_symbol') !!} 0</td>
          </tr>
          <tr>
            <td align="right" style="font-family: Verdana, Arial, sans-serif; font-size: 11px;"> (I am within the FREE delivery Zone):</td>
            <td align="right" style="font-family: Verdana, Arial, sans-serif; font-size: 11px;">{!! config('cart.currency_symbol') !!} 0.00</td>
          </tr>
          <tr>
            <td align="right" style="font-family: Verdana, Arial, sans-serif; font-size: 11px;">Sub-Total:</td>
            <td align="right" style="font-family: Verdana, Arial, sans-serif; font-size: 11px;">{!! config('cart.currency_symbol') !!} 24.68</td>
          </tr>
          <tr>
            <td align="right" style="font-family: Verdana, Arial, sans-serif; font-size: 11px;">Total:</td>
            <td align="right" style="font-family: Verdana, Arial, sans-serif; font-size: 11px;"><b>{!! config('cart.currency_symbol') !!} 24.68</b></td>
          </tr>
        </tbody></table></td>
      </tr>
    </tbody></table></td>
  </tr>
  <tr><td colspan="7">&nbsp;</td></tr>
  <tr><td colspan="7" style="font-size:12px; font-family: Verdana, Arial, sans-serif; color: #000000;">
    
  </td></tr>
  <tr><td colspan="7" style="font-size:12px; font-family: Verdana, Arial, sans-serif; color: #000000;">Thank you for your order. Our team appreciate your continued custom.</td></tr>
  <tr><td colspan="7" style="font-size:12px; font-family: Verdana, Arial, sans-serif; color: #000000;">Any product showing as N/A may be out of season or discontinued. Please contact us to arrange an alternative.</td></tr>
</tbody></table></td>
    <td width="180" align="left" valign="top">&nbsp;</td>
  </tr>
</tbody></table>
@endforeach
@else
	<p style="text-align: center;color: red;margin-top: 15em;" class="not_found">No Record Found!</p>
@endif
</td>
  </tr>
</tbody></table>
<!-- body_text_eof //-->


</body></html>