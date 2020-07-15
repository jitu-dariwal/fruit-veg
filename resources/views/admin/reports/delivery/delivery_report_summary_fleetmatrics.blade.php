<table  width="100%" border="1" cellpadding="2" cellspacing="0">
						<tr>
						<td colspan="10" class="dataTableHeadingContent"><h3>{{(!empty($driver_info)) ? 'Round '.$driver_info->round_name.' ('.$driver_info->driver_name .') ' : '' }} Delivery Report For: {{$delivery_date}}</h3></td>
						</tr>
						<tr>
						<td  colspan="10" class="dataTableHeadingContent"><h3>Free Delivery Area (Total : <span id ="TotalInSideDeliveryRecords">{{count($orders)}}</span>)</h3></td>
						</tr>
                           <thead class="thead-light">                     
                            <tr>
                                <th width="10" class="dataTableHeadingContent">Order ID.</th>
                                <th width="10" class="dataTableHeadingContent">Priority</th>
                                <th width="50" class="dataTableHeadingContent">Address</th>
                                <th width="15" class="dataTableHeadingContent">Latitude</th>
                                <th width="15" class="dataTableHeadingContent">Longitude</th>
                                <th width="15" class="dataTableHeadingContent">Time Window</th>
                                <th width="15" class="dataTableHeadingContent">Duration</th>
                                <th width="25" class="dataTableHeadingContent">Scheduled Start Time</th>
                                <th width="25" class="dataTableHeadingContent">Capacity 1</th>
                                <th width="25" class="dataTableHeadingContent">Capacity 2</th>
                                <th width="25" class="dataTableHeadingContent">Capacity 3</th>
                                <th width="25" class="dataTableHeadingContent">Key</th>
                                <th width="20" class="dataTableHeadingContent">Contact Name</th>
                                <th width="30" class="dataTableHeadingContent">Phone Number</th>
                                <th width="20" class="dataTableHeadingContent">Customer Name</th>
                                <th width="25" class="dataTableHeadingContent">Description</th>
                             </tr>
                           </thead>
@foreach($orders as $order)
        <tr class="dataTableRow">
            <td  class="dataTableContent">{{$order->id}}</td>
			<td  class="dataTableContent"> 
            <td  class="dataTableContent">{{$order->shipping_street_address}}<br>{{$order->shipping_address_line2}}<br>{{$order->shipping_city}}<br>{{$order->shipping_state}}<br>{{$order->shipping_country}}</td>
			<td></td>
			<td></td>
			
             <td  class="dataTableContent">
			 @php $access_time=0; @endphp
                @if(!empty($order->customer->Access_Time))
								@php $access_time= $order->Access_Time; @endphp
							    @endif
								@if(!empty($order->customer->Access_Time_latest	))
								@php $access_time= $order->Access_Time_latest; @endphp
							    @endif
								@if(!empty($order->earliest_delivery))
								@php $access_time= $order->earliest_delivery; @endphp
							    @endif
								@php
								$iHour2 = floor($access_time/3600);
						        $iMinute2 = ($access_time-$iHour2*3600)/60;
						        @endphp
								{{sprintf('%02d:%02d',$iHour2,$iMinute2)}} -
                                @php
								$iHour2 = floor($order->customer->Access_Time_latest/3600);
						        $iMinute2 = ($order->customer->Access_Time_latest-$iHour2*3600)/60;
						        @endphp								{{sprintf('%02d:%02d',$iHour2,$iMinute2)}}    &nbsp;</td>
	        <td></td>
			<td>{{date('d/m/y')}} {{sprintf('%02d:%02d',$iHour2,$iMinute2)}}</td>
	        <td></td>
		    <td></td>
		    <td></td>
		    <td>{{$order->customer->fob_card}}</td>
			<td class="dataTableContent">{{$order->shipping_add_company}}</td>
            <td  class="dataTableContent">{{$order->tel_num}}{{(!empty($order->shipping_tel_num)) ? '/'.$order->shipping_tel_num : '' }}</td>
			<td  class="dataTableContent">{{ucfirst($order->customer->first_name.' '.$order->customer->last_name)}}</td>
            <td  class="dataTableContent">{{$order->delivery_procedure}}</td>
            
         
        </tr>
@endforeach
   </table>