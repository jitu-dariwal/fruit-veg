<table class="table table-bordered table-striped">
<tr><td colspan="12">
<h3>Discount Details For: {{$month_no}}, {{$year}}</h3>  
</td>
</tr>
</table>

				   <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>S. No.</th>
                                <th>Order ID</th>
                                <th>Customer</th>
								@foreach(Config::get('constants.DISCOUNT_TYPES') as $dkey=>$dval)
								@php
								${"total_" . $dkey} = 0;
								@endphp
                                <th class="">{{$dval}}</th>
								@endforeach
                            </tr>
                        </thead>
                        <tbody>
						@php
						$i=0;
						@endphp
						@foreach($discount_details as $discounts)
						@php
						$i++;
						@endphp
						<tr>
						<td>{{ $i }}</td>
						<td><a href="{{ route('admin.orders.show', $discounts->id) }}" target="_blank">{{$discounts->id}}</a></td>
						<td><a href="{{ route('admin.customers.show', $discounts->customer_id) }}" target="_blank">{{ucfirst($discounts->customer->first_name.' '.$discounts->customer->last_name)}}</a></td>
						@foreach(Config::get('constants.DISCOUNT_TYPES') as $dkey=>$dval)
						@php
						$discount_val = 0; 
						if($discounts->discount_type==$dkey){
						$discount_val = $discounts->customer_discount;
						${"total_" . $dkey} += abs($discounts->customer_discount);
						}
						@endphp
						<td class="">{!! config('cart.currency_symbol') !!} {{abs($discount_val)}}</td>
						@endforeach
						</tr>
						@endforeach
						<tr>
						<td></td>
						<td></td>
						<td>Total</td>
						@foreach(Config::get('constants.DISCOUNT_TYPES') as $dkey=>$dval)
                                <td class="">{!! config('cart.currency_symbol') !!} {{ ${"total_" . $dkey} }}</td>
						@endforeach
						</tr>
						@if(count($discount_details)<=0)
							<tr>
						    <td colspan="25" class="not_found">{{Config::get('constants.NO_RECORD_FOUND')}}</td>
						    </tr>
						@endif
                        </tbody>
                    </table>