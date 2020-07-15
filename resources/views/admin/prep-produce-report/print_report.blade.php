<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="LTR" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Fruit And Veg</title>
<style>

.report_01 {
	border-collapse:collapse;
	border: 1px solid black;
}

.report_01 td {
	border: 1px solid black;
}
.font_12 {
	font-size: 12px;
}
</style>
<script>window.onload= function () { window.print();window.close();   }  </script>
</head>
<body marginwidth="0" align="center" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<!-- header_eof //-->

<div id="spiffycalendar" class="text"></div>



<!-- body //-->
<center>
<table border="0" width="100%" cellspacing="5" cellpadding="0">
  <tr>
    <td width="BOX_WIDTH" valign="top"><table border="0" width="BOX_WIDTH" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<!-- left_navigation_eof //-->
        </table></td>
<!-- body_text //-->
    <td valign="top"><table border="0" cellspacing="0" cellpadding="5" style="border:1px solid black;">
      <tr>
        <td style="padding:15px 0;" width="500px"><table border="0" cellspacing="0" cellpadding="0" width="100%">
          <tr>
            <td><img src="{{asset('img/logo.jpg')}}" align="right"><font size="3"><b>PREP PRODUCE REQUIRED FOR {{ Request::query('printdate')}}</b></font></td>

          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" cellspacing="0" cellpadding="2" width="100%" class="report_01">
              <tr>
                <td width="20"><b> No</b></td>
                <td width="194"><b> Product</b></td>
                <td width="76" align="center"><strong>Quantity</strong></td>
				<td width="76" align="center"><strong>Packet Size</strong></td>
                <td width="76" align="center"><strong>Order No.</strong></td>
                </tr>
				
				
             @php
						$i = 0;
						@endphp
                        @foreach ($OrdersTotal as $Anomolie)
						@php
						$i++;
						@endphp
                            <tr>
							
                                <td>{{ $i }}</td>
                                
                                <td>@if(!empty($Anomolie->product_name))
								<a href="{{route('admin.products.show', $Anomolie->product_id)}}" target="_blank">{!! $Anomolie->product_name !!}</a>
								@else
								N/A	
								@endif
								</td>
                                <td>
								@if(!empty($Anomolie->quantity))
								{!! $Anomolie->quantity !!}
								@else
								N/A	
								@endif
								 </td>
                                <td>
								@if(!empty($Anomolie->packet_size))
								{!! $Anomolie->packet_size !!}
								@else
								N/A	
								@endif
								</td>
                                <td><a href="{{route('admin.orders.show', $Anomolie->order_ids)}}" target="_blank">{{$Anomolie->order_ids}}</a></td>
                            </tr>
							
                        @endforeach
						
						
							@if($OrdersTotal->count() == 0)
                            <tr><td colspan="8" style="color:red;"><center>No Record Found.</center></td></tr>
							@endif
             
            </table>
			</td>
          </tr>
		  	
  </tr>
</table>

</body>
</html>
