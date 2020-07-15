<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  	<html xmlns="http://www.w3.org/1999/xhtml">
  	<head>
  	<meta http-equiv="Content-Type" content="text/html;  charset=utf-8" />
  	<title>Monthly Invoice - #{{$data['invoice_id']}}</title>
  	<style>
  	body{font-family:Arial, Helvetica, sans-serif; margin:0px; padding:0px; }
  	.style3 {
  	font-family: Arial, Helvetica, sans-serif;
  	font-size: 12px;
  	}
  	.style4 {font-size: 12px}
  	.row1 {
  	background-color: #CCCCCC;
  	}
  	.row2 {
  	background-color: #CBD2D3;
  	}
  	.heading {
  	background-color: #BFDEBA;
  	font-weight: bold;
  	}
  	.style7 {color: #000000; font-weight: bold; }
  	body {
  	font-family: Arial, Helvetica, sans-serif;
  	font-size: 12px;
  	}
  	.style10 {font-family: Arial, Helvetica, sans-serif; font-size: 10px; }
  	.style13 {
  	font-size: 12px;
  	font-weight: bold;
  	}
  	</style>
	
  	</head>
  	<body><div style="text-align:center;  padding-top:18px;">
  	<img src="{{asset('img/logo.gif')}}" alt="" /></div>
  	<div style="text-align:right; padding-top:18px; width:800px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold; color:#556e00;"> Fruit And Veg<br />
  	Unit 5D<br />
  	Bates Industrial Estate<br />
  	The Old Brickworks,<br />
  	Church Road,<br />
  	Harold Wood,<br />
  	Essex<br />
  	RM3 0HU<br />
  	<br />
  	T: 0800 019 4037<br />
  	E:info@fruitandveg.co.uk<br />
  	W:www.fruitandveg.co.uk<br /></div>
	@php
					  $invoiceId=Finder::getMonthlyInvoiceId($data['month_no'],$data['year'],$data['customer']->id);
					  $getInvoice=Finder::getInvoiceStatus($invoiceId);
					  $total_due=0;
					  @endphp
	<div style="text-align:center; width:800px; padding-left:0px;"><table width="100%" border="0"><tr>
            	<td height="5" style="color: #556e00;font-family:Verdana,Arial,Helvetica,sans-serif;font-size: 11px;font-weight: bold; text-align:center; line-height:14px;">Click on the below link to make payment online (Card payment)</td>
            </tr>
			 <tr>
            	<td height="5" style="color: #556e00;font-family:Verdana,Arial,Helvetica,sans-serif;font-size: 11px;font-weight: bold; text-align:center; line-height:14px;"><a href="{{route('payment-with-cc.index', $invoiceId)}}">Pay Invoice online </a>
				</td>
            </tr>
			<tr>
            	<td height="5">&nbsp;</td>
            </tr></table></div>
  	<div style="text-align:center; width:800px; padding-left:0px;">
	<!--------------MonthLy Invoice------------------->
	<!--------------MonthLy Invoice------------------->
	<!--------------MonthLy Invoice------------------->
	                  
	<table width="100%"  border="0" cellspacing="2" cellpadding="2" >
  			<tr>
			<td    width="19%" align="left" valign="top" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; "  class="heading">Company:</td>
			<td   width="41%" align="left" valign="top" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; "  class="row2" ><b>{{ucfirst($data['customer']->defaultaddress->company_name)}}<b></td>
			<td  width="40%" align="left" valign="top" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; "  class="heading" >Invoice Date: <b>{{($getInvoice) ? \Carbon\Carbon::parse($getInvoice->created_at)->format('jS M Y')  : date('jS M Y')}}</b></td>
			</tr>
  			<tr>
			<td  class="heading" align="left" valign="top" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; " >Contact:</td>
  			<td   class="row2" align="left" valign="top"  style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; " ><b>{{ucfirst($data['customer']->first_name.' '.$data['customer']->last_name)}}<b></td>
  			<td   class="heading"  align="left" valign="top"  style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; " >&nbsp;</td>
  			</tr>      
  			<tr>
  			<td  class="heading"  align="left" valign="top" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; ">Invoice address:</td>
  			<td bordercolor="#000000" class="row2"  align="left" valign="top" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold; "><strong>
  			{{$data['customer']->defaultaddress->street_address}}<br>{{$data['customer']->defaultaddress->address_line_2}}<br>{{$data['customer']->defaultaddress->city}}<br>{{$data['customer']->defaultaddress->county_state}}<br>{{$data['customer']->defaultaddress->post_code}}<br>
  			</strong>
			</td>
  			<td class="heading" align="left" valign="top" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; ">Delivery address: <strong><br>
  			{{$data['customer']->defaultaddress->street_address}}<br>{{$data['customer']->defaultaddress->address_line_2}}<br>{{$data['customer']->defaultaddress->city}}<br>{{$data['customer']->defaultaddress->county_state}}<br>{{$data['customer']->defaultaddress->post_code}}<br>
			</strong>
			</td>
  			</tr>
  			<tr>
  			<td width="19%"   class="heading"  align="left" valign="top" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; ">Period:</td>
  			<td width="61%"  class="row2" align="left" valign="top" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold; "><b>{{\Carbon\Carbon::parse($data['m_start'])->format('d/m/Y')}} - {{\Carbon\Carbon::parse($data['m_end'])->format('d/m/Y')}}</b></td>
  			<td width="20%"  class="heading"  align="left" valign="top" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; ">Customer No: <b>#{{$data['customer']->id}}</b></td>        
  			</tr>
			</table>
	<!--------------End MonthLy Invoice------------------->
	<!--------------End MonthLy Invoice------------------->
	<!--------------End MonthLy Invoice------------------->
	</div>			  
  	<div style="text-align:center; width:800px; padding-left:0px;">
	<!--------------Order customers details Invoice------------------->
	<!--------------Order customers details Invoice------------------->
	<!--------------Order customers details Invoice------------------->
	<table  width="100%" border="0">
  	<tr>
  	<td width="19%" align="left" valign="top" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold; "  bordercolor="#000000" bgcolor="#999999">Order ID</td>
  	<td width="23%"  align="left" valign="top" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold; "  bordercolor="#000000" bgcolor="#999999">Delivery Date</td>
  	<td width="38%"  align="left" valign="top" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold; "  bordercolor="#000000" bgcolor="#999999">Order Summary</td>
  	<td width="20%"  align="left" valign="top" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold; "  bordercolor="#000000" bgcolor="#999999">Amount</td>
  	</tr>
	@foreach($data['orders'] as $order)
	<tr>
  		<td valign="top"  align="left"  style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; font-weight:normal; " bordercolor="#000000" bgcolor="#CCCCCC" class="row1">#{{$order->id}}&nbsp;</td>
  		<td valign="top"  align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; font-weight:normal; " bordercolor="#000000" bgcolor="#CCCCCC" class="row1" >{{\Carbon\Carbon::parse($order->orderDetail->shipdate)->format('d.m.Y')}}</td>
  		<td valign="top"  align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; font-weight:normal; " bordercolor="#000000" bgcolor="#CCCCCC" class="row1" >
		@foreach($order->orderproducts as $order_product)
			{{$order_product->quantity}}&nbsp;x&nbsp;{{$order_product->product_name}}({{$order_product->type}})-({{$order_product->final_price}})<br>
        @endforeach	
		</td><td valign="top" align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; font-weight:normal; " bordercolor="#000000" bgcolor="#CCCCCC" class="row1" >{{$order->total}}&nbsp;</td>
  	</tr>
	@php $total_due+=$order->total; @endphp
    @endforeach
	
	</table>
	<!--------------End Order customers details Invoice------------------->
	<!--------------End Order customers details Invoice------------------->
	<!--------------End Order customers details Invoice------------------->
	</div>
  	<div style="text-align:center; width:800px; padding-left:0px;">
	<!--------------order customers purchase num Invoice------------------->
	<!--------------order customers purchase num Invoice------------------->
	<!--------------order customers purchase num Invoice------------------->
	<table  width=100% border="0" >
  	<tr>
  	<td align="left" valign="top" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold; ">Monthly Invoice - #{{$data['invoice_id']}}</td>
  	</tr>
  	<tr>
  		<td align="left" >&nbsp;</td>
  		</tr>
		@if(!empty($data['notes']))
  		<tr>
  		<td align="left" valign="top" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold; ">Notes: {{$data['notes']}}</td>
  		</tr>
        @endif
  	</table>
	<!--------------End order customers purchase num Invoice------------------->
	<!--------------End order customers purchase num Invoice------------------->
	<!--------------End order customers purchase num Invoice------------------->
	</div>
  	<div style="text-align:center; width:800px; padding-left:0px;">
	<!--------------order details------------------->
	<!--------------order details------------------->
	<!--------------order details------------------->
	
	<!--------------End order details------------------->
	<!--------------End order details------------------->
	<!--------------End order details------------------->
	</div>
  	<div style="text-align:center; width:800px; padding-left:0px;">
	<!--------------Total Dues------------------->
	<!--------------Total Dues------------------->
	<!--------------Total Dues------------------->
	<table  width=100% border="0">
  	<tr>
  	<td width=200>&nbsp;</td>
  	<td width=200>&nbsp;</td>
  	<td class="heading"><strong>Total Due</strong></td>
  	<td class="heading"><strong>&pound;{{number_format($total_due,2,'.',',')}}</strong></td>
  	</tr></table>
	<!--------------End Total Dues------------------->
	<!--------------End Total Dues------------------->
	<!--------------End Total Dues------------------->
	</div>
  	<div style="text-align:center; width:800px; padding-left:0px;">
	<!--------------New Table 2------------------->
	<!--------------New Table 2------------------->
	<!--------------New Table 2------------------->
	<table width="100%" border="0">
  	<tr>
  	<td width="19%" align="left" valign="top"><img src="{{asset('images/statement_01.jpg')}}" />	</td>
  	<td width="81%" align="left" valign="top"  style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; font-weight:normal; " >
  	<p>Payment due 7 days from .<br />
  	Please make all cheques payable to "<b>FRUIT AND VEG</b>"<br/>
  	We accept payment by Credit Card. Please call 0808 141 2828.<br/><br/>
  	If you would like to pay by BACS: acc / 01370193 srt / 30-96-64 IBAN / GB57 LOYD 3096 6401 3701 93 BIC / LOYDGB21085<br>Please include the company name and invoice reference. <br><br><b>IBAN: GB57 LOYD 3096 6401 3701 93 &nbsp;  &nbsp; BIC: LOYDGB21085</b><br><br>
  	<font size="1"><b>Fruit And Veg - UNIT 5D  - Bates Industrial Estate  - Essex - RM3 0HU</b></font><br /><br /><br />
  	<font size="3"><i><b>We thank you for your continued custom. Be fruitful.</b></i></font>
  	<br /><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;COMPANY VAT NO. 914804137<br />
  	Fruit for the office Ltd. Company number 6257707</br><b>note : this invoice was resent on {{date("d/m/Y")}} and this will change depending on when I send the invoice</b>
  	</p>&nbsp;</td>
  	</tr>
  	</table>
	<!--------------End New Table 2------------------->
	<!--------------End New Table 2------------------->
	<!--------------End New Table 2------------------->
	</div>
  	</body>
  	</html>
  
  	
