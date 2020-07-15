@extends('layouts.admin.app')

@section('content')

    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        
            <div class="box">
                <div class="box-body">
                    <h2>	
Once order numbers match on both reports, check to see that ‘financial implication’ on issue log and discount on order number are the same totals – if different alert us</h2>
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
					  <a href="{{route('admin.reports.financial-implication-report')}}" class="btn btn-default mb-2">Reset</a>
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
                                <td class="">Order Number</td>
                                <td class="">Order Ship date</td>
                                <td class="">Company</td>
                                <td class="">Agent History  </td>
                                <td class="">Discount </td>
                                <td class="">Financial Implication ({!! config('cart.currency_symbol') !!})</td>
                            </tr>
                        </thead>
                        <tbody>
						@foreach($discounts as $discount_logged)
						<tr>
						<td><a href="{{ route('admin.orders.show', $discount_logged->OrderNumber) }}">{{$discount_logged->OrderNumber}}</a></td>
						<td>{{\Carbon\Carbon::parse($discount_logged->shipdate)->format('d.m.Y')}}</td>
						<td><a href="{{route('admin.customers.show',$discount_logged->customer_id)}}">{{$discount_logged->customer->defaultaddress->company_name}}</a></td>
						<td></td>
						<td>{!! config('cart.currency_symbol') !!} {{abs($discount_logged->customer_discount)}}</td>
						<td>{!! config('cart.currency_symbol') !!} {{abs($discount_logged->FinancialImplication)}}</td>
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
