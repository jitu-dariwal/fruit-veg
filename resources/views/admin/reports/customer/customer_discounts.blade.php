@extends('layouts.admin.app')

@section('content')
@section('css')
<style>
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
                    <h2>Customer Discounts Report</h2>
					<div class="table-responsive">
					<!-- search form -->
					<form method="GET" class="">
					<table class="table table-striped">
					<tr>
					<td>
					  <label class="" for="inlineFormInputName2">From:</label></td>
					  <td class="week_numbers">
					  <input type="text" value="{{$from_date}}" class="datepicker form-control" name="from_date" readonly="">
					  </td>
					  <td>
					  <label class="" for="inlineFormInputName2">To:</label></td>
					  <td>
					  <input type="text" value="{{$to_date}}" class="datepicker form-control" name="to_date" readonly="">
					  </td>
					  <td><div style="margin-bottom:5px;">
					  <button type="submit" class="btn btn-primary mb-2">Submit</button>
					  <a href="{{route('admin.reports.customer-discount-report')}}" class="btn btn-default mb-2">Reset</a>
					  <a href="{{route('admin.reports.export-customer-discount-report')}}{{ (Request::getQueryString()) ? '?'.Request::getQueryString() : '' }}" class="btn btn-primary mb-2">Export Customer Discounts</a>
					  </div></td>
                    </tr>
                    </table>
					</form>
					<!-- /.search form -->
				    </div>
                    <div class="table-responsive">
					<table class="table table-bordered table-striped">
                        <thead>
                            <tr class="thead-light">
                                <th class="">S. No.</th>
                                <th class="">Customer Name</th>
                                <th class="">Email</th>
                                <th class="">Company</th>
                                <th colspan="3">Discount</th>
                                <th class="">Action</th>
                            </tr>
                        </thead>
                        <tbody>
						@php 
						$i=($customers->currentPage()-1)*$customers->perPage();
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
						
						<td colspan="3">{!! config('cart.currency_symbol') !!} {{ abs($customer->total_discount) }}</td>
						<td><a title="View Orders" class="btn btn-xs btn-default" data-toggle="collapse" href="#view_orders_{{$customer->id}}" role="button" aria-expanded="false" aria-controls="view_orders_{{$customer->id}}"><i class="fa fa-eye" aria-hidden="true" ></i></a></td>
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
                                        </table></div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
				{{$customers->appends($_GET)->links()}}
                </div>
            </div>
            <!-- /.box -->
        

    </section>
    <!-- /.content -->
@endsection
