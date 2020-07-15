<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="LTR" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Fruit And Veg - Packing Slip #35553</title>
<base href="{{URL::to('/')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/print.css')}}">
<style type="text/css">
	html {
	      overflow: -moz-scrollbars-vertical;
	      overflow-y: scroll;
	}
	.smalltext_dan{
		font-size: 12px;
		font-family:Arial, Helvetica, sans-serif;
		
		
	}
</style>
</head>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10">

<!-- body_text //-->
<table border="0" width="600" cellspacing="0" cellpadding="2">
  <tr>
    <td align="center" class="main"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td valign="top" align="left" class="main">
		{{--<script language="JavaScript">
  /*if (window.print) {
    document.write('<a href="javascript:;" onClick="javascript:window.print()" onMouseOut=document.imprim.src="images/printimage.gif" onMouseOver=document.imprim.src="images/printimage_over.gif"><img src="images/printimage.gif" width="43" height="28" align="absbottom" border="0" name="imprim">' + 'Print</a></center>');
  }
		else document.write ('<h2>Print</h2>')*/--}}
        </script>
		</td>
        <td align="right" valign="bottom" class="main"><p align="right" class="main"><a href="javascript:window.close();"><img src="{{asset("images/close_window.jpg")}}" border=0></a></p></td>
      </tr>
    </table></td>
  </tr>
  <tr align="left">
    <td class="titleHeading">{{--<!--img src="images/pixel_trans.gif" border="0" alt="" width="1" height="15"-->--}}</td>
  </tr>
  <tr>
    <td><table border="0" align="center" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" align="center" width="75%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">Fruit And Veg.co.uk <br>UNIT 5D <br> Bates Industrial Estate  <br>The Old Brickworks, <br> Church Road,  <br>Harold Wood, <br> Essex. <br> RM3 0HU<br>0808 141 2828</td>
            <td class="pageHeading" align="right"><img src="{{ asset("img/logo.jpg") }}" border="0" alt="Fruit And Veg" title=" Fruit And Veg "></td>
          </tr>
          <tr>
            <td colspan="2" align="center" class="titleHeading"><b>Order#{{$order->id}}</b></td>
          </tr>
          <tr align="left">
            <td colspan="2" class="titleHeading">{{--<!--img src="images/pixel_trans.gif" border="0" alt="" width="1" height="10"-->--}}</td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="dataTableContent"><b>Date Purchased:</b>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i a') }}</td>
  </tr>
  <tr>
    <td align="center"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="1" >
          <tr>
            <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><b>SOLD TO:</b></td>
              </tr>
              <tr class="dataTableRow">
                <td class="dataTableContent">{{ucfirst($order->orderdetail->first_name.' '.$order->orderdetail->last_name)}} <br>&nbsp;{{$order->orderdetail->company_name}}<br>&nbsp;{{$order->orderdetail->street_address}}<br>&nbsp;{{$order->orderdetail->address_line_2}}<br>&nbsp;{{$order->orderdetail->city}}<br>&nbsp;{{$order->orderdetail->country_state}}<br>&nbsp;{{$order->orderdetail->country}}<br>&nbsp;{{$order->orderdetail->post_code}}</td>
              </tr>
              <tr class="dataTableRow">
                <td class="dataTableContent">{{--<!--img src="images/pixel_trans.gif" border="0" alt="" width="1" height="5"-->--}}</td>
              </tr>
              <tr class="dataTableRow">
                <td class="dataTableContent">&nbsp;<b>Telephone#</b><br>&nbsp;{{$order->orderdetail->tel_num}}</td>
              </tr>
              <tr class="dataTableRow">
                <td class="dataTableContent">&nbsp;<b>eMail Address:</b><br>&nbsp;{{$order->orderdetail->email}}</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
        <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="1" >
          <tr>
            <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><b>SHIP TO:</b></td>
              </tr>
              <tr class="dataTableRow">
                <td class="dataTableContent">{{ucfirst($order->orderdetail->shipping_add_name)}} <br>&nbsp;{{$order->orderdetail->shipping_add_company}}<br>&nbsp;{{$order->orderdetail->shipping_street_address}}<br>&nbsp;{{$order->orderdetail->shipping_address_line2}}<br>&nbsp; {{$order->orderdetail->shipping_city}}<br>&nbsp; {{$order->orderdetail->shipping_state}}<br>&nbsp; {{$order->orderdetail->shipping_country}}<br>&nbsp; {{$order->orderdetail->shipping_post_code}}</td>
              </tr>
			  <tr class="dataTableRow">
                <td class="dataTableContent">&nbsp;<b>Telephone#</b><br>&nbsp;{{$order->orderdetail->shipping_tel_num}}</td>
              </tr>
              <tr class="dataTableRow">
                <td class="dataTableContent">&nbsp;<b>eMail Address:</b><br>&nbsp;{{$order->orderdetail->shipping_email}}</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>{{--<!--img src="images/pixel_trans.gif" border="0" alt="" width="1" height="10"-->--}}</td>
  </tr>
  <tr>
    <td><table width="595" border="0" cellpadding="2" cellspacing="0">
      <tr>
        <td width="329" class="smalltext_dan"><b>Payment Method:</b></td>
        <td width="258" class="smalltext_dan">{{Finder::getPaymentMethods()[$order->payment_method]}}</td>
      </tr>
      <!--<tr>
        <td width="329" class="smalltext_dan"><b>Delivery Procedure</b></td>
        <td width="258" class="smalltext_dan"></td>
      </tr>-->
      <tr>
        <td class="smalltext_dan"><b>Delivery:</b></td>
        <td class="smalltext_dan">{{ \Carbon\Carbon::parse($order->orderdetail->shipdate)->format('d.m.Y') }}</td>
      </tr>
      <tr>
        <td class="smalltext_dan"><b>Earliest delivery time:</b></td>
        <td class="smalltext_dan">{{$order->orderdetail->arrival_time}}</td>
      </tr>
      <tr>
        <td class="smalltext_dan"><b>Box Type:</b></td>
        <td class="smalltext_dan" colspan="2"></td>
      </tr>
      <tr>

        <td class="smalltext_dan"><b>Box Info:</b></td>
        <td class="smalltext_dan" colspan="2"></td>
      </tr>
            <tr>
        <td class="smalltext_dan"><b>Access Time:</b></td>
        <td class="smalltext_dan" colspan="2">        </td>
      </tr>
            
      <tr class="smallText">
        <td colspan="2"><p>
                    </p></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>{{--<!--img src="images/pixel_trans.gif" border="0" alt="" width="1" height="10"-->--}}</td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="1" >
      <tr>
        <td>
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent" colspan="2">Products</td>
			<td class="dataTableHeadingContent" colspan="2"></td>
          </tr>
		  @foreach($products as $product)
      <tr class="dataTableRow">
        <td class="dataTableContent" valign="top" align="right">{{$product->quantity}}&nbsp;x</td>
        <td class="dataTableContent" valign="top">{{$product->product_name}} ( {{$product->type}} )        </td>
		</tr>        
		@endforeach
		</table>
		</td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_text_eof //-->
<br>
</body>
</html>
