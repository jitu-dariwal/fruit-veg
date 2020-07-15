@extends('layouts.admin.app')

@section('content')

    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        
            <div class="box">
                <div class="box-body">
                    <h2>Customer Order Differences</h2>
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
					  <a href="{{route('admin.reports.order-difference')}}" class="btn btn-default mb-2">Reset</a>
					  <a href="{{route('admin.reports.export-order-difference')}}{{ (Request::getQueryString()) ? '?'.Request::getQueryString() : '' }}" class="btn btn-primary mb-2">Export Order Differences</a>
					  </div></td>
                    </tr>
                    </table>
					</form>
					<!-- /.search form -->
				    </div>
                    <div class="table-responsive">
					<table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th class="">S. No.</th>
                                <th class="">Customer Name</th>
                                <th class="">Order No.</th>
                                <th class="">Product N/A:</th>
                                <th class="">Payment method:</th>
                            </tr>
							@php $i=($order_items->currentPage()-1)*$order_items->perPage(); @endphp
							@foreach($order_items as $item)
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
                                <td>
								<a title="View Orders" class="btn btn-xs btn-default" data-toggle="collapse" href="#view_orders_{{$item->id}}" role="button" aria-expanded="false" aria-controls="view_orders_{{$item->id}}">View NA Items</a>
								</td>
                                <td>{{Finder::getPaymentMethods()[$item->payment_method]}}</td>
                            </tr>
							<tr class="collapse" id="view_orders_{{$item->id}}">
							<td colspan="25">
							<table class="table" >
							<tr class="show_items">
							<th>Product Name</th>
							<th>Qty</th>
							<th>Price</th>
							<th>Final Price</th>
							</tr>
							@foreach(Finder::getNAProducts($item->id) as $products)
							<tr class="show_items">
							<td><a href="{{ route('admin.products.show', $products->product_id) }}" target="_blank">{{$products->product_name}}</a></td>
							<td>{{$products->quantity}}</td>
							<td>{!! config('cart.currency_symbol') !!} {{$products->product_price}}</td>
							<td>{!! config('cart.currency_symbol') !!} {{$products->final_price}}</td>
							</tr>
							@endforeach
							</table>
							</td>
							</tr>
							@endforeach
                        </thead>
                        <tbody>
						
                        </tbody>
                    </table>
                </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
				{{$order_items->appends($_GET)->links()}}
                </div>
            </div>
            <!-- /.box -->
        

    </section>
    <!-- /.content -->
@endsection
