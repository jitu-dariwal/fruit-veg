@extends('layouts.admin.app')

@section('content')

    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        
            <div class="box">
                <div class="box-body">
                    <h2>	
There is an issue logged that does not appear on the customer discount report (match by order number)</h2>
                    <div class="table-responsive" >
					<!-- search form -->
					<form class="form-inline">
					
					<table class="table">
					<tr>
					<td>
					<label class="" for="inlineFormInputName2">From:</label>
					</td>
					<td>
					 <input type="text" value="{{$from_date}}" class="datepicker form-control" name="from_date" readonly>
					</td>
					<td>
					  <label class="" for="inlineFormInputGroupUsername2">To:</label>
					</td>
					<td>
						<input type="text" value="{{$to_date}}" class="datepicker form-control" name="to_date" readonly>
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
                                <td class="">Order Number</td>
                                <td class="">Nature Of Issue</td>
                                <td class="">Client Effected</td>
                                <td class="">Responsibility</td>
                                <td class="">Details</td>
                                <td class="">Resolution</td>
                                <td class="">Financial Implication ({!! config('cart.currency_symbol') !!})</td>
                                <td class="">Loss Type</td>
                                <td class="">Create Date</td>
                                <td class="">Action</td>
                            </tr>
                        </thead>
                        <tbody>
						@foreach($discounts as $discount_logged)
						<tr>
						<td><a href="{{ route('admin.orders.show', $discount_logged->OrderNumber) }}">{{$discount_logged->OrderNumber}}</a></td>
						<td>{{Config::get('constants.NatureOfIssue')[$discount_logged->NatureOfIssue]}}</td>
						<td><a href="{{route('admin.customers.show',$discount_logged->customer_id)}}">{{$discount_logged->ClientEffected}}</a></td>
						<td>{{$discount_logged->Responsibility}}</td>
						<td>{{$discount_logged->Details}}</td>
						<td>{{$discount_logged->Resolution}}</td>
						<td>{!! config('cart.currency_symbol') !!} {{$discount_logged->FinancialImplication}}</td>
						<td>{{(!empty($discount_logged->LossType))?Config::get('constants.LossType')[$discount_logged->LossType]:'N/A'}}</td>
						<td>{{\Carbon\Carbon::parse($discount_logged->created_at)->format('d.m.Y H:i:s')}}</td>
						<td></td>
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
