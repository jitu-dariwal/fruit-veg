@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        
            <div class="box">
                <div class="box-body">
                    <h2>	
There is a customer discount, that does not appear on issue log (match by order number) for the below date range.</h2>
                    <div class="table-responsive">
					<!-- search form -->
					<form class="form-inline">
					
					<table class="table">
					<tr>
					<td>
					<label class="" for="inlineFormInputName2">From:</label>
					</td>
					<td>
					 <input type="text" value="{{$from_date}}" class="datepicker form-control" name="from_date" readonly="">
					</td>
					<td>
					  <label class="" for="inlineFormInputGroupUsername2">To:</label>
					</td>
					<td>
						<input type="text" value="{{$to_date}}" class="datepicker form-control" name="to_date" readonly="">
					</td>
					<td>
					<div style="margin-bottom:5px;">
					  <button type="submit" class="btn btn-primary mb-2">Submit</button>
					  <a href="{{route('admin.reports.discount-not-appear-on-issue-log')}}" class="btn btn-default mb-2">Reset</a>
					</div>
					</td>
                    </tr>
					</table>
						
					</form>
					
					<!-- /.search form -->
				</div>
                    <div class="table-responsive">
					<table class="table table-hover">
                        <thead>
                            <tr class="dataTableHeadingRow">
                                <td class="">Order Id</td>
                                <td class="">Order Ship date</td>
                                <td class="">Company</td>
                                <td class="">Agent History </td>
                                <td class="">Discount</td>
                                <td class="">Financial Implication</td>
                            </tr>
                        </thead>
                        <tbody>
						@foreach($discounts as $discount_not_logged)
						<tr>
						<td><a href="{{ route('admin.orders.show', $discount_not_logged->id) }}">{{$discount_not_logged->id}}</a></td>
						<td>{{\Carbon\Carbon::parse($discount_not_logged->shipdate)->format('d.m.Y')}}</td>
						<td><a href="{{route('admin.customers.show',$discount_not_logged->customer_id)}}">{{ucfirst($discount_not_logged->customer->defaultaddress->company_name)}}</a></td>
						<td></td>
						<td>{!! config('cart.currency_symbol') !!} {{abs($discount_not_logged->customer_discount
						)}}<br><a href="{{ route('admin.orders.show', $discount_not_logged->id) }}">View Order</a></td>
						<td>{!! config('cart.currency_symbol') !!} 0.00</td>
						</tr>
						@endforeach
						@if(count($discounts)<=0)
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
				{{$discounts->appends($_GET)->links()}}
                </div>
            </div>
            <!-- /.box -->
        

    </section>
    <!-- /.content -->
@endsection
