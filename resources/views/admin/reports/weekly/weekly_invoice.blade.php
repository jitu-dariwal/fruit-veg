<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="LTR" lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>Fruit And Veg</title>
    <base href="{{URL::to('/admin')}}">
    <script language="javascript"><!--
      function popupWindow(url) {
        window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=650,height=500,screenX=150,screenY=150,top=150,left=150')
      }
      //-->
	  
    </script>
  </head>
  <body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
    
    <table border="0" width="100%" cellspacing="2" cellpadding="2">
      <tr>
        <td width="BOX_WIDTH" valign="top">
          <table border="0" width="BOX_WIDTH" cellspacing="1" cellpadding="1" class="columnLeft">
          </table>
        </td>
        <!-- body_text //-->
        <td width="100%" valign="top">
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td align="center">
                <table width="650" class="break" cellspacing="0" cellpadding="0" >
                  <tr>
                    <td align="left" valign="middle" class="">
                      <table cellpadding="0" cellspacing="0" border="0" class="no_banner_image">
                        <tr>
                          <td align="left" style="padding-left:50px;">
                            <img src="{{asset('img/logo.gif')}}" alt="" />
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td align="right" valign="top" class="mainmattr row_hight">
                      <table width="47%" border="0" align="right" cellpadding="0" cellspacing="0" class="no_table_data">
                        <tr>
                          <td align="right" valign="top" class="grentxt" style="color:#556e00; font-weight:bold;"> Fruit And Veg<br />
                            UNIT 5D<br />
                            Bates Industrial Estate<br />
                            The Old Brickworks,<br />
                            Church Road,<br />
                            Harold Wood,<br />
                            Essex<br />
                            RM3 0HU<br /><br />			
                            E:info@fruitandveg.co.uk<br />
                            W:www.fruitandveg.co.uk<br /> 
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td height="5">&nbsp;</td>
                  </tr>
                  <!--<tr>
                    <td style="color: #ff0000; font-family: Verdana,Arial,Helvetica,sans-serif;font-size: 12px;font-weight: bold; text-align:center; padding-top:10px; padding-bottom:10px; line-height:14px;">**MOVING TO WEEKLY INVOICING FROM 01.08.17**
                    </td>
                  </tr>-->
                  <tr>
                    <td height="5">&nbsp;</td>
                  </tr>
                  <tr>
                    <td valign="top" style="padding:15px;">
                      <table border="0" cellspacing="0" cellpadding="2">
					  @php
					  $invoiceId=Finder::getInvoiceId($week_no,$year,$customer_id);
					  $getInvoice=Finder::getInvoiceStatus($invoiceId);
					  $total_due=0;
					  @endphp
                        <tr>
                          <td colspan="4"><font size="2"><b><u>Weekly Invoice - #{{$invoiceId}}</u></b></font><br><br></td>
                        </tr>
                        <tr>
						<td colspan="4">
						<div style="float:left;">Company:<br>Contact:<br>Address:<br><br><br><br><br>Period:</div>
						<div style="float:left; padding-left:15px; line-height:16ps;"><b>{{ucfirst($customer->defaultaddress->company_name)}}<br>{{ucfirst($customer->first_name.' '.$customer->last_name)}}<br>{{$customer->defaultaddress->street_address}}<br>{{$customer->defaultaddress->address_line_2}}<br>{{$customer->defaultaddress->city}}<br>{{$customer->defaultaddress->county_state}}<br><br>{{$w_start}} - {{$w_end}}</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Customer No:<b>#{{$customer->id}}</b>
						</div>
						<div style="float:right;">Invoice Date: <b>{{($getInvoice) ? \Carbon\Carbon::parse($getInvoice->created_at)->format('jS M Y')  : date('jS M Y')}}</b>
						</div>
						</td>
						</tr>
						<tr id="defaultSelected">
                          <td class="dataTableContent" width="70"><b>Order</b></td>
                          <td class="dataTableContent" align="center" width="100"><b>Delivery Date</b></td>
                          <td class="dataTableContent" align="left" width="250"><b>Order Summary</b></td>
                          <td class="dataTableContent" align="right" width="182"><b>Amount</b></td>
                        </tr>
						@foreach($orders as $order)
                        <tr id="defaultSelected">
                        <td class="dataTableContent" valign="top"><a href="{{ route('admin.orders.show', $order->id) }}"><img src="{{asset('images/preview.gif')}}" border="0" alt="Preview" title=" Preview "></a>&nbsp;<b>{{$order->id}}</b></td>
                        <td class="dataTableContent" align="center" valign="top"><b>{{\Carbon\Carbon::parse($order->orderDetail->shipdate)->format('d M,Y')}}</b></td>
                        <td class="dataTableContent" align="left">
						@foreach($order->orderproducts as $order_product)
						{{$order_product->quantity}}&nbsp;x&nbsp;{{$order_product->product_name}}({{$order_product->type}})-({{$order_product->final_price}})<br>
                        @endforeach						
                        </td>
                        <td class="dataTableContent" align="right" valign="top"><b>{!! config('cart.currency_symbol') !!} {{$order->total}}</b></td>
                        </tr>
						@php $total_due+=$order->total; @endphp
                        @endforeach
                                                <tr>
                          <td colspan="4">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="3" align="right"><b>Total Due:</b></td>
                          <td align="right"><b>{!! config('cart.currency_symbol') !!} {{$total_due}}</b></td>
                        </tr>
                                                <tr>
                          <td colspan="4">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="4" width="100%">
                            <table width="100%">
                              <tr>
                                <td colspan="4">
																
								
                                  Payment due 7 days from date of Invoice.<BR>
                                  Please make all cheques payable to   '<strong>FRUIT AND VEG </strong>'<BR>
                                  We accept payment by Credit Card. Please call 0808   141 2828.<br>
                                  <br>
                                  If you would like to pay by BACS: acc / 01370193 srt / 30-96-64 IBAN / GB57 LOYD 3096 6401 3701 93 BIC / LOYDGB21085<br>Please include the company name and invoice reference. <br><br><b>IBAN: GB57 LOYD 3096 6401 3701 93 &nbsp;  &nbsp; BIC: LOYDGB21085</b><br><br>
                                  <font size="1"><b>Fruit And Veg- UNIT 5D, Bates Industrial Estate, Harold Wood, RM3 0HU </b></font><br>
                                  <br>
                                  <br>
                                  <font size="3"><i><b>We thank you for your continued custom.</b></i></font> <br />
                                  <br />
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;COMPANY VAT NO. 914804137<br />
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;Company number 6257707
                                </td>
                                <td align="right" valign="top"><img src="{{asset('images/statement_01.jpg')}}" align="right"></td>
                              </tr>
                              <tr>
                                <td align="left"height="15px;"></td>
                              </tr>
                              <tr>
                                <td align="left">
                                  <table width="60%" border="1" cellspacing="0" cellpadding="3">
                                    <tr>
                                      <td style="line-height: 18px;" ></td>
                                      <td align="right">TOTALS</td>
                                    </tr>
                                    <tr>
                                      <td style="line-height: 18px;" align="right">FNV Total</td>
                                      <td align="right">{!! config('cart.currency_symbol') !!} {{$total_due}}</td>
                                    </tr>
                                    <tr>
                                      <td style="line-height: 18px;" align="right">Other Total </td>
                                      <td align="right">{!! config('cart.currency_symbol') !!} 0.00</td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <td align="left"height="15px;"></td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                        <tr>
                          <td colspan="4" >
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td style="line-height: 18px;" ><b>note : this invoice was resent on {{date("d/m/Y")}} and this will change depending on when I send the invoice</b></td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                      <p>
                      <form id="emailinvoice" action="{{route('admin.reports.mail-weekly-invoice')}}" method="post">
					  @csrf
                        <input type="hidden" name="customer_id" value="{{$customer->id}}">                        <p>
                          <label>
                          <textarea name="Notes" id="Notes" cols="45" rows="5"></textarea>
                          Notes</label>
                        </p>
                        <p>
                          <input type="hidden" id="month" name="week_number" value="{{$week_no}}" />
                          <input type="hidden" id="year" name="year" value="{{$year}}" />
                          <input type="hidden" id="cus_add" name="cus_add" value="" />
                          <input type="email" id="emailadd" name="emailadd" value="" />
                          &nbsp;
                          <input type="submit" id="submit" name="submit" value="Email" />
                          &nbsp;Press submit even if empty, will send to customers stored email address. 
                        </p>
                      </form>
                      </p>	
                      <!--<p><a target="_blank" href="email_invoice.php?cID=376&amp;month=&amp;year=2017&amp;cus_add=%3Cb%3EBaskervilles+Tea+Shop%3C%2Fb%3E%3Cbr%3E%3Cb%3EID%3A+%23376%3C%2Fb%3E%3Cbr+%2F%3E%3Cb%3EDiane+Odling-Smee%3Cbr+%2F%3E66+Aldermans+Hill%3Cbr+%2F%3EPalmers+Green%3Cbr+%2F%3ELondon%3Cbr+%2F%3EN13+4PP%3Cbr+%2F%3E%3Cbr+%2F%3EPeriod%3A+18%2F12%2F2017+-+24%2F12%2F2017%3C%2Fb%3E ">Email invoice</a></p>-->
                    </td>
                    	
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
        <!-- body_text_eof //-->
      </tr>
    </table>
        <!-- body_eof //-->
    <!-- footer //-->
        <!-- footer_eof //-->
    <br>
  </body>
	<script>
	  @if(session()->has('message'))
			  alert('{{ session()->get('message') }}');
	  @endif
    </script>
</html>