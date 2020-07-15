<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=text/html; charset=iso-8859-1">
<title>{{env('APP_NAME')}} - Packing Slip </title>
<base href="{{URL::to('/')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/print.css')}}" media="screen" >
<link rel="stylesheet" type="text/css" href="{{asset('css/print.css')}}" media="print" >
<style type="text/css">
	html {
	      overflow: -moz-scrollbars-vertical;
	      overflow-y: scroll;
	}
	
	@media all {
	.break	{ display: none; }
	}

	@media print {
	.break	{ display: block; page-break-before: always; }
	}

</style>
</head>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10">
<!-- body_text //-->
<table width="100%" border="0">
  <tbody><tr>
    <td align="left" valign="top">
	@if(count($orders)>0)
	@foreach($orders as $order)	
	<table width="100%" border="0">
      <tbody><tr>
        <td align="left" valign="top" width="200">&nbsp;</td>
        <td width="601" align="left" valign="top"><table border="0" width="600" cellspacing="0" cellpadding="2">
            <tbody><tr>
              <td align="center" class="main"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
                  <tbody><tr>
                    <td valign="top" align="left" class="main"><script language="JavaScript">
 /* if (window.print) {
    document.write('<a href="javascript:;" onClick="javascript:window.print()" onMouseOut=document.imprim.src="images/printimage.gif" onMouseOver=document.imprim.src="images/printimage_over.gif"><img src="images/printimage.gif" width="43" height="28" align="absbottom" border="0" name="imprim">' + 'Print</a></center>');
  }
  else document.write ('<h2>Print</h2>')*/
        </script></td>
                    <td align="right" valign="bottom" class="main"><p align="right" class="main">
                      <!--<a href="javascript:window.close();"><img src='images/close_window.jpg' border=0></a>-->
                    </p></td>
                  </tr>
              </tbody></table></td>
            </tr>
            <tr align="left">
              <td class="titleHeading"><img src="{{asset("images/pixel_trans.gif")}}" border="0" alt="" width="1" height="15"></td>
            </tr>
           <tr>
              <td><table border="0" align="center" width="100%" cellspacing="0" cellpadding="0">
                  <tbody><tr>
                    <td><table border="0" align="center" width="100%" cellspacing="0" cellpadding="0">
                        <tbody><tr>
                          <td class="pageHeading"><table width="100%" border="0">
                            <tbody><tr>
                              <td align="left" valign="top">Fruit And Veg.co.uk <br>UNIT 5D <br> Bates Industrial Estate  <br>The Old Brickworks, <br> Church Road,  <br>Harold Wood, <br> Essex. <br> RM3 0HU</td>
                            </tr>
                          </tbody></table></td>
                          <td class="pageHeading" align="right"><img src="{{ asset("img/logo.jpg") }}" border="0" alt="Fruit And Veg" title=" Fruit And Veg "></td>
                        </tr>
                        <tr>
                          <td colspan="2" align="center" class="titleHeading"><b>Order#{{$order->id}}</b></td>
                        </tr>
                        <tr align="left">
                          <td colspan="2" class="titleHeading"><img src="{{asset("images/pixel_trans.gif")}}" border="0" alt="" width="1" height="10"></td>
                        </tr>
                    </tbody></table></td>
                  </tr>
              </tbody></table></td>
            </tr>
            <tr>
              <td class="dataTableContent"><table width="100%" border="0">
  <tbody><tr>
    <td align="left" valign="top"><b> Date Purchased </b> {{$order->created_at->format('d.m.Y H:i:s')}}&nbsp;</td>
  </tr>
</tbody></table>
</td>
            </tr>
            <tr>
              <td align="center"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
                  <tbody><tr>
                    <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="1">
                        <tbody><tr>
                          <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
                              <tbody><tr class="dataTableHeadingRow">
                                <td class="dataTableHeadingContent"><b>Sold To</b></td>
                              </tr>
                              <tr class="dataTableRow">
                                <td class="dataTableContent">{{ucfirst($order->orderdetail->first_name.' '.$order->orderdetail->last_name)}} <br>&nbsp;{{$order->orderdetail->company_name}}<br>&nbsp;{{$order->orderdetail->street_address}}<br>&nbsp;{{$order->orderdetail->address_line_2}}<br>&nbsp;{{$order->orderdetail->city}}<br>&nbsp;{{$order->orderdetail->country_state}}<br>&nbsp;{{$order->orderdetail->country}}<br>&nbsp;{{$order->orderdetail->post_code}}</td>
                              </tr>
                              <tr class="dataTableRow">
                                <td class="dataTableContent"><img src="images/pixel_trans.gif" border="0" alt="" width="1" height="5"></td>
                              </tr>
                              <tr class="dataTableRow">
                                <td class="dataTableContent"><b>Telephone#</b><br>&nbsp;{{$order->orderdetail->tel_num}}</td>
                              </tr>
                              <tr class="dataTableRow">
                                <td class="dataTableContent"><b>eMail Address:</b><br>&nbsp;{{$order->orderdetail->email}}</td>
                              </tr>
                          </tbody></table></td>
                       </tr>
                    </tbody></table></td>
                    <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="1">
                        <tbody><tr>
                          <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
                              <tbody><tr class="dataTableHeadingRow">
                                <td class="dataTableHeadingContent"><b>Ship To</b></td>
                              </tr>
                              <tr class="dataTableRow">
                                <td class="dataTableContent">{{ucfirst($order->orderdetail->shipping_add_name)}} <br>&nbsp;{{$order->orderdetail->shipping_add_company}}<br>&nbsp;{{$order->orderdetail->shipping_street_address}}<br>&nbsp;{{$order->orderdetail->shipping_address_line2}}<br>&nbsp; {{$order->orderdetail->shipping_city}}<br>&nbsp; {{$order->orderdetail->shipping_state}}<br>&nbsp; {{$order->orderdetail->shipping_country}}<br>&nbsp; {{$order->orderdetail->shipping_post_code}}</td>
                              </tr>
                          </tbody></table></td>
                        </tr>
                    </tbody></table></td>
                  </tr>
              </tbody></table></td>
            </tr>
            <tr>
              <td><img src="images/pixel_trans.gif" border="0" alt="" width="1" height="10"></td>
            </tr>
            <tr>
              <td><table width="595" border="0" cellpadding="2" cellspacing="0">
                  <tbody><tr>
                    <td width="329" class="main"><b>Payment Method</b></td>
                    <td width="258" class="main"> {{(!empty($order->payment_method)) ? $paymenttypes=Finder::getPaymentMethods()[$order->payment_method] : 'N/A'}}</td>
                  </tr>
                  <!--<tr>
        <td width="329" class="smalltext_dan"><b>Delivery Procedure</b></td>
        <td width="258" class="smalltext_dan"></td>
      </tr>-->
                  <tr>
                    <td class="main"><b>Delivery:</b></td>
                    <td class="main">{{\Carbon\Carbon::parse($order->shipdate)->format('d.m.Y')}}</td>
                  </tr>
                  <tr>
                    <td class="main"><b>Earliest delivery time:</b></td>
                    <td class="main">
					@if(!empty($order->earliest_delivery))
							@php 
							$access_time= $order->earliest_delivery; 
							@endphp
							    
								@php
								$iHour2 = floor($access_time/3600);
						        $iMinute2 = ($access_time-$iHour2*3600)/60;
						        @endphp
								{{sprintf('%02d:%02d',$iHour2,$iMinute2)}}
					@endif
					</td>
                  </tr>
                  <tr>
                    <td class="main"><b>Box Type:</b></td>
                    <td class="main" colspan="2">Standard Box</td>
                  </tr>
                  <tr>
                    <td class="main"><b>Box Info:</b></td>
                    <td class="main" colspan="2"></td>
                  </tr>
                                    <tr class="smallText">
                    <td colspan="2"><p>
                        Updated on Orders Page                    </p></td>
                  </tr>
              </tbody></table></td>
            </tr>
            <tr>
              <td><img src="images/pixel_trans.gif" border="0" alt="" width="1" height="10"></td>
            </tr>
            <tr>
              <td>
			  <table border="0" width="100%" cellspacing="0" cellpadding="1">
                  <tbody><tr>
                    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                        <tbody><tr class="dataTableHeadingRow">
                          <td class="dataTableHeadingContent" colspan="2"><strong>Products</strong></td>
                          <td class="dataTableHeadingContent" colspan="2"></td>
                        </tr>
						@foreach($order->orderproducts as $product)
                        <tr class="dataTableRow">
						<td class="dataTableContent" valign="top" align="right">{{$product->quantity}}&nbsp;x</td>
						<td class="dataTableContent" valign="top">{{$product->product_name}}</td>        
						<td class="dataTableContent" valign="top"><img src="tickbox.gif" height="15" width="15"></td>
						</tr>
						@endforeach
					  </tbody>
					  </table>
					  </td>
                  </tr>
              </tbody></table></td>
            </tr>
        </tbody></table></td>
        <td width="180" align="left" valign="top">&nbsp;</td>
      </tr>
    </tbody></table>
	<p class="break">&nbsp;</p>
	@endforeach
	@else
		<p style="text-align: center;color: red;margin-top: 15em;" class="not_found">No Record Found!</p>
	@endif
	</td>
  </tr>
</tbody></table>
<!-- body_text_eof //-->
<br>

</body>
</html>