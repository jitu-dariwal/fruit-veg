@extends('layouts.admin.app')

@section('content')
@section('css')
<style>
.dataTableHeadingRow {
    background-color: rgb(201, 201, 201);
}
.product_items {
	display:none;
}
.show_items {
	background-color: rgb(241, 241, 241);
}
</style>
@endsection
    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        
            <div class="box">
                <div class="box-body">
                    <h2>ROUND {{$driver_info->round_name}} ({{$driver_info->driver_name}}) DELIVERY REPORT For: {{ $delivery_date }}</h2>
                    <div class="table-responsive">
					<!-- search form -->
					<form class="form-inline">
					
					<table class="table table-striped">
					  <tr>
					  <td>
					  <label class="" for="inlineFormInputName2">Delivery date:</label></td>
					  <td>
					  <input type="text" value="{{ $delivery_date }}" class="datepicker form-control" name="delivery_date">
					  </td>
					  <td>
					  <div style="margin-bottom:5px;">
					  <button type="submit" class="btn btn-primary mb-2">Submit</button>
					  </div>
					  </td>
					  </tr>
						
					</table>
					  
					</form>
					<div style="margin-bottom:5px;">
					  <a href="{{route('admin.reports.delivery-report-summary-export', $driver)}}?delivery_date={{$delivery_date}}" class="btn btn-primary mb-2">Export to XLS</a>
					  <a href="{{route('admin.reports.delivery-summary-fleetmatics-export', $driver)}}?delivery_date={{$delivery_date}}" class="btn btn-primary mb-2">Export to XLS Fleetmatics POI address</a>
					  </div>
					<!-- /.search form -->
				</div>
                    
					<form method="post" action="">
					<div class="table-responsive">
					
					@csrf
					<input type="hidden" value="{{ $delivery_date }}" name="delivery_date">
					<table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th class="">No.</th>
                                <th class="">Order No.</th>
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
								{{$products->quantity}} x {{$products->product_name}} <br>
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
					
					</form>
					
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                </div>
            </div>
            <!-- /.box -->
        

    </section>
    <!-- /.content -->
@endsection
@section('js')
<script>

</script>
@endsection