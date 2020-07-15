@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if($orders)
            <div class="box">
                <div class="box-body">
                    <h2>Orders</h2>
                    @include('layouts.search', ['route' => route('admin.orders.index')])
                    <table class="table">
                        <thead>
                            <tr>
                                <td class="col-md-3">Date Purchased</td>
                                <td class="col-md-3">Customer</td>
                                <td class="col-md-2">Delivery Date</td>
                                <td class="col-md-2">Total</td>
                                <td class="col-md-2">Status</td>
                                <td class="col-md-2">Action</td>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $orderdetail)
                            <tr>
                                <td><a title="Show order" href="{{ route('admin.packer.order.show', $orderdetail->order->id) }}">{{ \Carbon\Carbon::parse($orderdetail->order->created_at)->format('M d, Y h:i a') }}</a></td>
                                <td>{{ucfirst($orderdetail->order->customer->first_name.' '.$orderdetail->order->customer->last_name)}}</td>
								<td>{{ \Carbon\Carbon::parse($orderdetail->shipdate)->format('M d, Y ') }}</td>
                                <td>
                                    <span class="label @if($orderdetail->order->total != $orderdetail->order->total_paid) label-danger @else label-success @endif">{{ config('cart.currency') }} {{ $orderdetail->order->total }}</span>
                                </td>
                                <td><span class="label btn" style="color: #ffffff; background-color: {{ $orderdetail->order->orderStatus->color }}">{{ $orderdetail->order->orderStatus->name }}</span></td>
								<td>
								<a title="Show order"  class="btn btn-default" href="{{ route('admin.packer.order.show', $orderdetail->order->id) }}"><b>View/Edit</b></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    {{ $orders->links() }}
                </div>
            </div>
            <!-- /.box -->
        @endif

    </section>
    <!-- /.content -->
@endsection