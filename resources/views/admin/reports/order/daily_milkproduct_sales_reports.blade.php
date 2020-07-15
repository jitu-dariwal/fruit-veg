@extends('layouts.admin.app')

@section('content')

    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        
            <div class="box">
                <div class="box-body">
                    <h2>Lists all of the milk products that has been ordered for {{$for_date}}</h2>
                    <div class="">
					<!-- search form -->
					<form class="form-inline">
					<div style="margin-bottom:5px;">
					  <label class="" for="inlineFormInputName2">Display another report for this date:</label>
					  <input type="text" value="{{$for_date}}" class="datepicker form-control" name="for_date"> 
					  
						
					  <button type="submit" class="btn btn-primary mb-2">Submit</button>
					  <a href="{{route('admin.reports.daily-milk-product-sales-report')}}" class="btn btn-default mb-2">Reset</a>
					  <a href="{{route('admin.reports.export-daily-milkproduct-sales-report')}}{{ (Request::getQueryString()) ? '?'.Request::getQueryString() : '' }}" class="btn btn-default mb-2">Export To Xls</a>
					  <a href="{{route('admin.reports.print-daily-milkproduct-sales-report')}}{{ (Request::getQueryString()) ? '?'.Request::getQueryString() : '' }}" class="btn btn-default mb-2" target="_blank" title="Print Product List"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
					  </div>
					</form>
					
					<!-- /.search form -->
				    </div>
                    <div class="table-responsive">
					    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th class="">S. No.</th>
                                <th class="">Products ID</th>
                                <th class="">Product</th>
                                <th class="">Quantity</th>
                                <th class="">Packet Size</th>
                                <th class="">Sale Price</th>
                                <th class="">New Price</th>
                                <th class="">In/Out</th>
                            </tr>
                        </thead>
                        <tbody>
						@php $i=($order_items->currentPage()-1)*$order_items->perPage();   @endphp
						@foreach($order_items as $items)
						@php 
						$i++; 
						@endphp
						
						<tr>
						<td>{{$i}}</td>
						<td><a href="{{route('admin.products.show',$items->product_id)}}">{{$items->product_id}}</a></td>
						<td><a href="{{route('admin.products.show',$items->product_id)}}">{{$items->product_name}}</a></td>
						<td>{{$items->orderProQty}}</td>
						<td>{{$items->packet_size}}</td>
						<td>{!! config('cart.currency_symbol') !!} {{$items->product_price}}</td>
						<td>{!! config('cart.currency_symbol') !!} {{$items->final_price}}</td>
						<td>In</td>
						</tr>
						@endforeach
						@if(count($order_items)<=0)
							<tr>
						    <td colspan="10" class="not_found">{{Config::get('constants.NO_RECORD_FOUND')}}</td>
						    </tr>
						@endif
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