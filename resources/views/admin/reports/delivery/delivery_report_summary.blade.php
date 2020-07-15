@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        
            <div class="box">
                <div class="box-body">
                    <h2>Delivery Report For : {{ $delivery_date }}</h2>
                    <div class="row" style="margin-bottom:20px;">
                    <div class="">
					<!-- search form -->
					<form class="form-inline">
					
					<div class="row col-12 col-sm-12 col-md-12">
            <div class="col-12 col-sm-2 col-md-4"> 
               
                    <strong>Delivery date:</strong> <input type="text" value="{{ $delivery_date }}" class="datepicker form-control" name="delivery_date">
               
            </div>
	
	<div class="col-12 col-sm-2 col-md-4"> 
            <div class="input-group">

                  <button type="submit" class="btn btn-primary mb-2">Submit</button>
                            <a href="{{route('admin.reports.delivery-report-summary')}}" class="btn btn-default mb-2">Reset</a>

            </div>
        </div>
		
        </div>
					  
					</form>
                                        
                                        </div>
                    </div>
					<div style="margin-bottom:5px;">
					  <a href="{{route('admin.reports.set-driver')}}?delivery_date={{$delivery_date}}" class="btn btn-primary mb-2">Click Here to Set Round Driver Name</a>
					  <a href="{{route('admin.reports.delivery-report-summary-export')}}?delivery_date={{$delivery_date}}" class="btn btn-primary mb-2">Export to XLS</a>
					  <a href="{{route('admin.reports.delivery-summary-fleetmatics-export')}}?delivery_date={{$delivery_date}}" class="btn btn-primary mb-2">Export to XLS Fleetmatics POI address</a>
					  </div>
					<!-- /.search form -->
				
					<form method="post" action="">
					<div class="table-responsive">
					
					@csrf
					<input type="hidden" value="{{ $delivery_date }}" name="delivery_date">
					<table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th class="">No.</th>
                                <th class="">Order No.</th>
                                <th class="">Customer</th>
                                <th class="">Client</th>
                                <th class="">Delivery Rounds Report</th>
                                <th class="">Delivery address</th>
                                <th class="">Delivery procedure</th>
                                <th class="">Postcode</th>
                                <th class="">Access From</th>
                                <th class="">Product Summary</th>
                                <th class="">Tick</th>
                            </tr>
                        </thead>
                        <tbody>
						@php 
						$i=($orders->currentPage()-1)*$orders->perPage();
						$access_time=0;
						@endphp
						@foreach($orders as $order)
						@php $i++; @endphp
						    <tr>
							    <td class="">{{$i}}</td>
							    <td class="">{{$order->id}}</td>
							    <td class=""><a href="{{route('admin.customers.show',$order->customer_id)}}">{{ucfirst($order->customer->first_name.' '.$order->customer->last_name)}}</a></td>
							    <td class="">{{$order->shipping_add_company}}</td>
							    <td class="">
								<div class="row">
								@php $r=0; @endphp
								@foreach($driver_rounds as $roundsname)
								@php $r++; @endphp
								<div class="col-sm-4">
								<label><input name="driver_{{$order->id}}" class="driver_{{$order->id}}" type="radio" {{($order->driver==$roundsname->round_name) ? 'checked' : ''}} value="{{$roundsname->round_name}}">{{$roundsname->round_name}}</label>
								</div>
								@if($r%3==0)
									<div class="clearfix"></div>
								@endif
								@endforeach
								
								
								</div>
								
								</td>
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
								<td class="">
								<input type="checkbox" checked name="order[]" value="{{$order->id}}">
								</td>
							</tr>
						@endforeach
						@if(count($orders)<=0)
							<tr>
						    <td colspan="11" class="not_found">{{Config::get('constants.NO_RECORD_FOUND')}}</td>
						    </tr>
						@endif
                        </tbody>
                    </table>
					
					</div>
					<div class="pull-right" style="margin-top:5px;">
					  <a href="{{route('admin.reports.free-delivery-packing-slip')}}?delivery_date={{$delivery_date}}" class="btn btn-primary mb-2" target="_blank">Print all for free delivery area packing slip</a>
					  <a href="{{route('admin.reports.free-delivery-invoice')}}?delivery_date={{$delivery_date}}" class="btn btn-primary mb-2" target="_blank">Print all for free delivery area invoices</a>
					  <button type="submit" class="btn btn-primary mb-2">Save Delivery Rounds Report</button>
					</div>
					<div class="pull-right" style="margin-top:5px;">
					@foreach($driver_rounds as $roundsname)
					  <a style="margin-bottom:5px;" href="{{route('admin.reports.free-delivery-packing-slip' , $roundsname->round_name) }}?delivery_date={{$delivery_date}}" class="btn btn-default mb-2" target="_blank">Print Packing Slip Rounds {{$roundsname->round_name}}</a>
					@endforeach
					</div>
					<div class="pull-right" style="margin-top:5px;">
					@foreach($driver_rounds as $roundsname)
					  <a style="margin-bottom:5px;" href="{{route('admin.reports.rounds-report' , $roundsname->round_name) }}?delivery_date={{$delivery_date}}" class="btn btn-default mb-2" target="_blank">{{$roundsname->driver_name}} - ROUND {{$roundsname->round_name}}</a>
					@endforeach
					</div>
					</form>
					{{$orders->appends($_GET)->links()}}
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