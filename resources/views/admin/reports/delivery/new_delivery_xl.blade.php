					<div class="table-responsive">
					<table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th class="">No.</th>
                                <th class="">Order No.</th>
                                <th class="">Customer</th>
                                <th class="">Client</th>
                                <th class="">Delivery address</th>
                                <th class="">Delivery procedure</th>
                                <th class="">Postcode</th>
                                <th class="">Access From</th>
                                <th class="">Product Summary</th>
                            </tr>
                        </thead>
                        <tbody>
						@php 
						$i=0; 
						$access_time=0;
						@endphp
						@foreach($orders as $order)
						@php $i++; @endphp
						    <tr>
							    <td class="">{{$i}}</td>
							    <td class="">{{$order->id}}</td>
							    <td class=""><a href="{{route('admin.customers.show',$order->customer_id)}}">{{ucfirst($order->customer->first_name.' '.$order->customer->last_name)}}</a></td>
							    <td class="">{{$order->shipping_add_company}}</td>
							    <td class="">{{$order->shipping_street_address}}<br>{{$order->shipping_address_line2}}<br>{{$order->shipping_city}}<br>{{$order->shipping_state}}<br>{{$order->shipping_country}}</td>
							    <td class="">{{$order->delivery_procedure}}</td>
							    <td class="">{{$order->shipping_post_code}}</td>
							    <td class="">
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
								{{sprintf('%02d:%02d',$iHour2,$iMinute2)}}
								</td>
							    <td class="">
								@foreach($order->orderproducts as $products)
								{{$products->quantity}} x {{$products->product_name}} , 
								@endforeach
								</td>
							</tr>
						@endforeach
						@if(count($orders)<=0)
							<tr>
						    <td colspan="10" class="not_found">{{Config::get('constants.NO_RECORD_FOUND')}}</td>
						    </tr>
						@endif
                        </tbody>
                    </table>
					</div>