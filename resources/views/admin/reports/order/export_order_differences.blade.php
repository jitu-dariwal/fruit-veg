<table class="table table-bordered table-striped">
<tr><td colspan="12">
<h3>Order Difference Report For: {{$from_date}} To {{$to_date}}</h3>
</td>
</tr>
</table>  				
				<table class="table table-bordered table-striped">
				
                        <thead class="thead-light">
                            <tr>
                                <th class="">S. No.</th>
                                <th class="">Customer Name</th>
                                <th class="">Order No.</th>
                                <th class="">Product N/A:</th>
                                <th class="">Product Order Qty:</th>
                                <th class="">Payment method:</th>
                            </tr>
							</thead>
							<tbody>
							@php $i=0; @endphp
							@foreach($order_items as $item)
							@foreach(Finder::getNAProducts($item->id) as $products)
							@php $i++; @endphp
							
							<tr>
                                <td>{{$i}}</td>
                                <td><a href="{{route('admin.customers.show',$item->customer_id)}}">
								@if($item->customer->defaultaddress && !empty($item->customer->defaultaddress->company_name))
								{{$item->customer->defaultaddress->company_name}}
							    @else
								{{ucfirst($item->customer->first_name.' '.$item->customer->last_name)}}
							    @endif
								</a></td>
                                <td><a href="{{ route('admin.orders.show', $item->id) }}" target="_blank">{{$item->id}}</a></td>
                                <td><a href="{{ route('admin.products.show', $products->product_id) }}" target="_blank">{{$products->product_name}}</a></td>
								<td>{{$products->quantity}}</td>
                                <td>{{Finder::getPaymentMethods()[$item->payment_method]}}</td>
                            </tr>
							@endforeach
							@endforeach
                        
                        
						
                        </tbody>
                    </table>