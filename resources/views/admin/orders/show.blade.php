@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
        <div class="box">
            <div class="box-header">
                <div class="row">
                    <div class="col-md-6">
                        <h2>
						@php 
						$paymenttypes=Finder::getPaymentMethods();
						@endphp
                            <a href="{{ route('admin.customers.show', $customer->id) }}">{{ucfirst($customer->first_name.' '.$customer->last_name)}}</a> <br />
                            <small>{{$customer->email}}</small> <br />
                            <small>Reference: <strong>{{$order->reference}}</strong></small>
                            <small>Payment Method: <strong>{{(!empty($order->payment_method))?$paymenttypes[$order->payment_method]:''}}</strong></small><br>
							<small>Shipment Arrival Date: <strong>{{(!empty($order->orderDetail->shipdate))? \Carbon\Carbon::parse($order->orderDetail->shipdate)->format('M d, Y'):'N/A'}}</strong></small>
                        </h2>
                    </div>
                    <div class="col-md-3 col-md-offset-3">
                        <h2><a href="{{route('admin.orders.invoice.generate', $order['id'])}}" class="btn btn-primary btn-block">Download Invoice</a></h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="box">
            <div class="box-body">
                <h4> <i class="fa fa-shopping-bag"></i> Order Information</h4>
                <table class="table table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th class="col-md-3">Date</td>
                            <th class="col-md-3">Customer</td>
                            <th class="col-md-3">Payment</td>
                            <th class="col-md-3">Status</td>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i a') }}</td>
                        <td><a href="{{ route('admin.customers.show', $customer->id) }}">{{ ucfirst($customer->first_name.' '.$customer->last_name) }}</a></td>
                        <td><strong>{{-- $order['payment'] --}}</strong></td>
                        <td><button type="button" class="btn btn-info btn-block">{{ $currentStatus->name }}</button></td>
                    </tr>
                    </tbody>
                    <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="bg-warning">Subtotal</td>
                        <td class="bg-warning">{!! config('cart.currency_symbol') !!} {{ $order['total_products'] }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="bg-warning">Tax</td>
                        <td class="bg-warning">{!! config('cart.currency_symbol') !!} {{ $order['tax'] }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="bg-warning">Discount</td>
                        <td class="bg-warning">{!! config('cart.currency_symbol') !!} {{ $order['customer_discount'] }}</td>
                    </tr>
					<tr>
                        <td></td>
                        <td></td>
                        <td class="bg-warning">Delivery Charges</td>
                        <td class="bg-warning">{!! config('cart.currency_symbol') !!} {{ $order['shipping_charges'] }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="bg-success text-bold">Order Total</td>
                        <td class="bg-success text-bold">{!! config('cart.currency_symbol') !!} {{ $order['total'] }}</td>
                    </tr>
                    @if($order['total_paid'] != $order['total'])
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="bg-danger text-bold">Total paid</td>
                            <td class="bg-danger text-bold">{!! config('cart.currency_symbol') !!} {{ $order['total_paid'] }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        @if($order)
            @if($order->total != $order->total_paid)
                <p class="alert alert-danger">
                    Ooops, there is discrepancy in the total amount of the order and the amount paid. <br />
                    Total order amount: <strong>{{ config('cart.currency') }} {{ $order->total }}</strong> <br>
                    Total amount paid <strong>{{ config('cart.currency') }} {{ $order->total_paid }}</strong>
                </p>

            @endif
            <div class="box">
                @if(!$order->orderproducts->isEmpty())
                    <div class="box-body">
                        <h4> <i class="fa fa-gift"></i> Items</h4>
                        <table class="table table-striped">
                            <thead class="thead-light">
                            <th class="col-md-2">Product Code</th>
                            <th class="col-md-2">Name</th>
                            <th class="col-md-2">Description</th>
                            <th class="col-md-2">Quantity</th>
                            <th class="col-md-2">Price</th>
                            </thead>
                            <tbody>
							
                            @foreach($order->orderproducts as $item)
                                <tr>
                                    <td>{{ $item->product_code }}</td>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{!! $item->product_description !!}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{!! config('cart.currency_symbol') !!} {{ $item->product_price }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
				<div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4> <i class="fa fa-calendar" aria-hidden="true"></i> Shipment Arrival Date</h4>
							<p>{{\Carbon\Carbon::parse($order->orderDetail->shipdate)->format('d M, Y')}}</p>
							@if(count($order->order_status_historys)>0)
                            <table class="table " border="1" >
                            <thead>
                                <tr>
                                    <th class="smallText" style="text-align: center;" align="center"><b>Date Added</b></th>
                                    <th class="smallText" style="text-align: center;" align="center"><b>Customer Notified</b></th>
                                    <th class="smallText" style="text-align: center;" align="center"><b>Status</b></th>
                                    <th class="smallText" style="text-align: center;" align="center"><b>Comments</b></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->order_status_historys as $order_status_his)
                                <tr>
                                    <td class="smallText" style="text-align: center;" align="center">{{ \Carbon\Carbon::parse($order_status_his->created_at)->format('d/M/Y h:i A')}}</td>
                                    <td class="smallText" style="text-align: center;" align="center">
                                        @if($order_status_his->customer_notified==1)
                                        <i style="color:green;" class="fa fa-check-circle" aria-hidden="true"></i>
                                        @else
                                        <i style="color:red;" class="fa fa-times-circle" aria-hidden="true"></i>
                                        @endif
                                    </td>
                                    <td class="smallText" style="text-align: center;">@if(isset($order_status_his->order_status->name)) {{$order_status_his->order_status->name}} @endif </td>
                                    <td class="smallText" style="text-align: center;">{!!$order_status_his->comments!!}</td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
						@endif
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4> <i class="fa fa-map-marker"></i> Address</h4>
                            <table class="table table-striped">
                                <thead class="thead-light">
                                    <th>Address 1</th>
                                    <th>Address 2</th>
                                    <th>City</th>
                                    <th>Province</th>
                                    <th>Zip</th>
                                    <th>Country</th>
                                    <th>Phone</th>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{ $order->orderDetail->street_address }}</td>
                                    <td>{{ $order->orderDetail->address_line_2 }}</td>
                                    <td>{{ $order->orderDetail->city }}</td>
                                    <td>
                                        @if(isset($order->orderDetail->country_state))
                                            {{ $order->orderDetail->country_state }}
                                        @endif
                                    </td>
                                    <td>{{ $order->orderDetail->post_code }}</td>
                                    <td>{{ $order->orderDetail->country }}</td>
                                    <td>{{ $order->orderDetail->tel_num }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box -->
            <div class="box-footer">
                <div class="btn-group">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-default">Back</a>
                    @if($user->hasPermission('update-order'))<a href="{{route('admin.orders.addproducts',$order->id)}}" class="btn btn-primary">Edit</a>@endif
                </div>
            </div>
        @endif

    </section>
    <!-- /.content -->
@endsection