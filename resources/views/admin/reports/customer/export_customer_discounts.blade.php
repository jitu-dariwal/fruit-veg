<table class="table table-bordered table-striped">
<tr><td colspan="12">
<h3>Customer Discounts Report For: {{$from_date}} To {{$to_date}}</h3>
</td>
</tr>
</table>  
					<table class="table table-bordered table-striped">
                        <thead>
                            <tr class="thead-light">
                                <th class="">S. No.</th>
                                <th class="">Customer Name</th>
                                <th class="">Email</th>
                                <th class="">Company</th>
                                <th colspan="3">Action</th>
                                <th class="">Discount</th>
                            </tr>
                        </thead>
                        <tbody>
						@php 
						$i=0;
                        $total_dis=0;						
						@endphp
                        @foreach($customers as $customer)
						@php 
						$i++;
                        $total_dis+=abs($customer->total_discount);						
						@endphp
						<tr>
						<td>{{$i}}</td>
						<td><a href="{{route('admin.customers.show',$customer->id)}}">{{ucfirst($customer->first_name.' '.$customer->last_name)}}</a></td>
						<td>{{'@@'.$customer->email}}</td>
						
						<td>{{$customer->defaultaddress->company_name}}</td>
						<td colspan="3"><a title="View Orders" class="btn btn-xs btn-default" data-toggle="collapse" href="#view_orders_{{$customer->id}}" role="button" aria-expanded="false" aria-controls="view_orders_{{$customer->id}}"><i class="fa fa-eye" aria-hidden="true" ></i></a></td>
						<td>{!! config('cart.currency_symbol') !!} {{ abs($customer->total_discount) }}</td>
						</tr>
						<tr class="collapse" id="view_orders_{{$customer->id}}">
						<td colspan="25">
						<table class="table" >
						<tr class="show_items">
						<th></th>
						<th>Order Id</th>
						<th>Created At</th>
						<th>Total</th>
						<th>Action</th>
						</tr>
						@foreach($customer->ordersWithDiscount as $order)
						@php
						$orderShipdate = $order->orderDetail->shipdate;
						@endphp
						@if(!empty($orderShipdate) && strtotime($orderShipdate)>=strtotime($from_date) && strtotime($orderShipdate)<=strtotime($to_date))
						<tr class="show_items">
						<td></td>
						<td><a href="{{ route('admin.orders.show', $order->id) }}" target="_blank">{{$order->id}}</a></td>
						<td>{{$order->created_at->format('M d, Y h:i a')}}</td>
						<td>{!! config('cart.currency_symbol') !!} {{$order->total}}</td>
						<td colspan="2"><a href="{{ route('admin.orders.show', $order->id) }}" target="_blank">View Order</a></td>
						</tr>
						@endif
						@endforeach
						</table>
						</td>
						</tr>
						@endforeach
						
						@if(count($customers)<=0)
							<tr>
						    <td colspan="10" class="not_found">{{Config::get('constants.NO_RECORD_FOUND')}}</td>
						    </tr>
						@else
							<tr>
						    <td colspan="7" ></td>
						    <td>Total :<b>{!! config('cart.currency_symbol') !!} {{$total_dis}}</b></td>
						    </tr>
						@endif
                        </tbody>
                      </table>