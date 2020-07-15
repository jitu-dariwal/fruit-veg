@extends('layouts.admin.app')
@section('css')
<style>
.checkorderproducts {
    position: relative;
}
.order_porductsList {
    position: absolute;
    transform: translateY(-50%);
    top: 50%;
    width: 330px;
    background: #fff;
    border: 2px solid #3c8dbc;
    border-radius: 10px;
    left: 10%;
    padding: 7px 12px;
    z-index: 1;
}
</style>
@endsection
@section('content')
    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
       @if(isset($customer) && !empty($customer))
        @php  $route = route('admin.orders.customer_order_list',$customer); @endphp
	   @else
        @php  $route = route('admin.orders.index'); @endphp
	   @endif
            <div class="box">
                <div class="box-body">
                    <h2>Orders</h2>
					<div class="pull-right">
						<!-- search form -->
						<form action="{{$route}}" method="get" id="admin-search">
						   <div class="input-group">
								<select class="form-control admin-search-field" name="status">
								<option value="">All Status</option>
								@foreach($orders_status as $orderStatus)
								<option {{(request()->has('status') && request('status')==$orderStatus->id)?'selected':''}} value="{{$orderStatus->id}}">{{$orderStatus->name}}</option>
								@endforeach
								</select>
							
								<input type="text" name="q" class="form-control admin-search-field" placeholder="Search @if(isset($search_by)){{$search_by}}@endif..." value="{!! request()->input('q') !!}">
								<span class="input-group-btn">
									<button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i> Search </button>
									<a href="{{$route}}" class="btn btn-flat btn-default"><i class="fa fa-refresh"></i> Reset </a>
								</span>
							</div>
						</form>
						<!-- /.search form -->
					</div>
					<form action="{{route('admin.orders.multi_order_status_update')}}" method="post" id="update-multiple">
					@csrf
                    <table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
							    <th class="col-md-3">Customer</th>
							    <th class="col-md-2">Internet Order</th>
                                <th class="col-md-3">Purchased Date</th>
                                <th class="col-md-2">Ship Date</th>
                                
<!--                                <td class="col-md-2">Courier</td>-->
                                <th class="col-md-2">Total</th>
                                <th class="col-md-2">Payment Method</th>
                                <th class="col-md-2">Status</th>
                                <th class="col-md-2">Action</th>
                                <th class="col-md-2">Downloads</th>
								<th class="col-md-1"><input type="checkbox" class="checkAll"> Tick</th>
                            </tr>
                        </thead>
                        <tbody>

						
						@php 
						$paymenttypes=Finder::getPaymentMethods();
						@endphp
                        @foreach ($orders as $order)
						
						<?php  //echo "CK<pre>"; print_r($order); echo "</pre>CK"; exit;
 ?>
                            <tr>
							    
                                <td>
								<span class="order-quick-view">
								<a href="javascript:void(0);" title="" class="view-qlist">
								<i style="font-size: 18px;" class="fa fa-commenting-o" aria-hidden="true"></i>
								</a>
								<div style="display:none;" class="checkorderproducts">
								<div class="order_porductsList">
								@if(!$order->orderproducts->isEmpty())
								@foreach($order->orderproducts as $item)
								{{ $item->quantity }} x {{ $item->product_name }} <br>
								@endforeach
								@else
									N/A
								@endif
								
								</div>
								</div>
								</span>
								<a href="{{ route('admin.customers.show', $order->customer_id) }}">{{ucfirst($order->customer->first_name.' '.$order->customer->last_name)}}</a>
								</td>
								<td>{{($order->is_internet_order==1)?'Yes':'No'}}</td>
                                <td><a title="Show order" href="{{ route('admin.orders.show', $order->id) }}">{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i a') }}</a></td>
								
                                <td><a title="Show order" href="{{ route('admin.orders.show', $order->id) }}">{{ \Carbon\Carbon::parse($order->orderDetail->shipdate)->format('M d, Y') }}</a></td>
								
                                <td>
                                    <span class="label @if($order->total != $order->total_paid) label-danger @else label-success @endif">{{ config('cart.currency') }} {{ $order->total }}</span>
                                </td>
								<td>{{(isset($order->payment_method) && !empty($order->payment_method) && array_key_exists($order->payment_method,$paymenttypes))?$paymenttypes[$order->payment_method]:''}}</td>
                                <td><p class="text-center" style="color: #ffffff; background-color: {{ $order->orderStatus->color }}">{{ $order->orderStatus->name }}</p></td>
								<td><a href="{{route('admin.orders.addproducts',$order->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a></td>
								<td><a href="{{route('admin.orders.invoice.generate', $order->id)}}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-file-text-o" aria-hidden="true"></i> Invoice</a>
								<a href="{{route('admin.orders.packing_slip.generate', $order->id)}}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-file-text-o" aria-hidden="true"></i> Packing Slip</a></td>
								<td><input type="checkbox" name="orderids[]" value="{{$order->id}}" class="orderNo"     style="height: 20px;width: 30px;"></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
					<div class="pull-right">
						   <div class="input-group">
								<select class="form-control admin-search-field" name="updatestatus">
								@foreach($orders_status as $orderStatus)
								<option  value="{{$orderStatus->id}}">{{$orderStatus->name}}</option>
								@endforeach
								</select>
							
								
								<span class="input-group-btn">
									<button type="submit" id="" class="btn btn-flat"> Save </button>
								</span>
							</div>
					</div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    {{ $orders->links() }}
                </div>
            </div>
            <!-- /.box -->
         

    </section>
    <!-- /.content -->
@endsection
@section('js')
<script>
$( ".view-qlist" )
  .mouseover(function() {
    $(this).parent().find('.checkorderproducts').show();
  })
  .mouseout(function() {
    $(this).parent().find('.checkorderproducts').hide();
  });

 $(document).on('change','.checkAll',function(){
	  if($(this).prop('checked') == true){
		  $('.orderNo').prop('checked', true);
	  } else {
		  $('.orderNo').prop('checked', false);
	  }
  });
</script>
@endsection