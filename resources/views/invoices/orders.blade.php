<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="LTR" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Fruit And Veg - ORDER NUMBER : {{$order->id}}</title>
<base href="{{URL::to('/')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/print.css')}}" media="screen" >
<style>

 .productlist .product_th {
    padding: 0px;
    text-align: center;
}

.productlist .product_th {
    background-color: rgb(82, 86, 89);;
    color: white;
}
 
.productlist .product_td {
    height: 25px;
	text-align: center;
    vertical-align: bottom;
}
.rest_total{
    text-align: right;
	
}
</style>
</head>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10">


<!-- body_text //-->
<table width="750" border="0" align="center" cellpadding="2" cellspacing="0">
  
  <tr>
    <td><table border="0" align="center" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" align="center" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" style="font-family:Arial, sans-serif;  font-size: 13px;  color: #666666;" >Fruit And Veg.co.uk <br>UNIT 5D <br> Bates Industrial Estate  <br>The Old Brickworks, <br> Church Road,  <br>Harold Wood, <br> Essex. <br> RM3 0HU</td>
            <td class="pageHeading"><img src="{{ asset("img/logo.jpg") }}" border="0" alt="Fruit And Veg" title=" Fruit And Veg "></td>
          </tr>
          <tr>
            <td colspan="2" align="center" style="font-family: Verdana, Arial, sans-serif; font-size: 18px; color:#727272; font-weight: bold;"><b>ORDER NUMBER : {{$order->id}}</b></td>
          </tr>
          <tr align="left">
            <td colspan="2" class="titleHeading"></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="left" class="main"><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td style="font-family: Verdana, Arial, sans-serif; font-size: 12px;" ><b>Payment Method:</b> {{$paymenttypes=Finder::getPaymentMethods()[$order->payment_method]}}</td>
      </tr>
      <tr>
        <td style="font-family: Verdana, Arial, sans-serif; font-size: 12px;"><b>Delivery:</b> {{ \Carbon\Carbon::parse($order->orderdetail->shipdate)->format('d.m.Y') }}</td>
      </tr>
      <tr>
        <td style="font-family: Verdana, Arial, sans-serif; font-size: 12px;"></td>
      </tr>
      <tr>
        <td style="font-family: Verdana, Arial, sans-serif; font-size: 12px;"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td style="font-family: Verdana, Arial, sans-serif; font-size: 12px;"><b>Date Purchased:</b> {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i a') }}</td>
  </tr>
  <tr>
    <td align="center"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="1">
      <tr>
        <td align="center" valign="top">
		<table align="center" width="100%" border="0" cellspacing="0" cellpadding="1" >
          <tr>
            <td align="center" valign="top">
			<table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td style="font-family: Verdana, Arial, sans-serif; font-size: 12px; color: #000000; background-color: #C9C9C9; font-weight: bold;"><b>SOLD TO:</b></td>
              </tr>
              <tr class="dataTableRow">
                <td style="font-size:12px; font-family: Verdana, Arial, sans-serif; color: #000000;">{{ucfirst($order->orderdetail->first_name.' '.$order->orderdetail->last_name)}} <br>&nbsp;{{$order->orderdetail->company_name}}<br>&nbsp;{{$order->orderdetail->street_address}}<br>&nbsp;{{$order->orderdetail->address_line_2}}<br>&nbsp;{{$order->orderdetail->city}}<br>&nbsp;{{$order->orderdetail->country_state}}<br>&nbsp;{{$order->orderdetail->country}}<br>&nbsp;{{$order->orderdetail->post_code}}</td>
             
              </tr>
            </table>
			</td>
          </tr>
        </table>
		</td>
        <td align="center" valign="top">
		<table align="center" width="100%" border="0" cellspacing="0" cellpadding="1" >
          <tr>
            <td align="center" valign="top">
			<table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td style="font-family: Verdana, Arial, sans-serif; background-color: #C9C9C9; font-size: 12px; color: #000000; font-weight: bold;"><b>SHIP TO:</b></td>
              </tr>
              <tr class="dataTableRow">
                <td style="font-size:12px; font-family: Verdana, Arial, sans-serif; color: #000000;">{{ucfirst($order->orderdetail->shipping_add_name)}} <br>&nbsp;{{$order->orderdetail->shipping_add_company}}<br>&nbsp;{{$order->orderdetail->shipping_street_address}}<br>&nbsp;{{$order->orderdetail->shipping_address_line2}}<br>&nbsp; {{$order->orderdetail->shipping_city}}<br>&nbsp; {{$order->orderdetail->shipping_state}}<br>&nbsp; {{$order->orderdetail->shipping_country}}<br>&nbsp; {{$order->orderdetail->shipping_post_code}}</td>
              </tr>
            </table>
			</td>
          </tr>
        </table>
		</td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_text_eof //-->


    <section class="row">
        <div class="col-md-12 productlist">
            <h2>Details</h2>
            <table class=""  align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th class="product_th">SKU</th>
                        <th class="product_th">Product</th>
                        <th class="product_th">Ordered</th>
                        <th class="product_th">Pack Size</th>
                        <th class="product_th">Price</th>
                        <th class="product_th">Actual Weight</th>
                        <th class="product_th">Total</th>
                    </tr>
                </thead>
                <tbody>
				@php $sub_total=0; @endphp
                @foreach($products as $product)
                    <tr>
                        <td class="product_td">{{$product->product_code}}</td>
                        <td class="product_td">{{$product->quantity}} x {{$product->product_name}}</td>
                        <td class="product_td">{{$product->quantity}}</td>
                        <td class="product_td">{{$product->weight}} {{$product->weight_unit}}</td>
                        <td class="product_td">{{$product->product_price}}</td>
                        <td class="product_td">{{$product->actual_weight}}</td>
                        <td class="product_td">{{$product->final_price}}</td>
                    </tr>
					@php $sub_total+=$product->final_price; @endphp
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        
                        <td colspan="6" class="rest_total">Subtotal:</td>
                        <td  class="product_td">{{$sub_total}}</td>
                    </tr>
                    <tr>
                        <td colspan="6" class="rest_total">Discounts:</td>
                        <td  class="product_td">{{$order->discounts}}</td>
                    </tr>
					<tr>
                        <td colspan="6" class="rest_total">Delivery Charges:</td>
                        <td  class="product_td">{{$order->shipping_charges}}</td>
                    </tr>
                    <tr>
                        <td colspan="6" class="rest_total">Tax:</td>
                        <td  class="product_td">{{$order->tax}}</td>
                    </tr>
                    <tr>
                        <td colspan="6" class="rest_total"><strong>Total:</strong></td>
                        <td  class="product_td"><strong>{{$order->total}}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </section>
</body>
</html>