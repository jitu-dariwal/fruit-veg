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
                    <h2>Best Customer Orders-Total</h2>
                   
					<!-- search form -->
				<div class="best-customer-orders-total" style="margin-bottom:20px;">	
				  <form class="form-inline">
				  
				  		
					 <div class="input-group" style="margin-top:22px;margin-right:20px; ">
					  
					  <input class="form-check-input" {{(request()->has('monthly_invoice')) ? (request('monthly_invoice')==1) ? 'checked' : '' : ''}}  type="checkbox" value="1" name="monthly_invoice"  id="inlineFormmonthly_invoice">&nbsp; <label class="" for="inlineFormmonthly_invoice">Monthly Invoice </label>
						
						
						<br>
						
						<input class="form-check-input" {{(request()->has('delivered')) ? (request('delivered')==1) ? 'checked' : '' : ''}} type="checkbox"  value="1" name="delivered" id="inlineFormDelivered">
						&nbsp;<label class="" for="inlineFormDelivered">Delivered </label>
						
						</div>
						
				 
					 <div class="input-group">
					  <label class="" for="inlineFormInputName2">Month:</label>
					  <select name="month" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2">
					  @foreach(Config::get('constants.MONTHS') as $key=>$value)
					    <option @if(request()->has('month')) @if(request('month')==$key) {{'selected'}} @else {{''}} @endif @else @if(date('m')==$key) {{'selected' }} @endif   @endif value="{{$key}}">{{$value}}</option>
					  @endforeach
					  </select> 					  
						</div>
					  
                     <div class="input-group">
                     <label class="" for="inlineFormInputGroupUsername2">Year:</label>						
                      <select class="form-control" name="year">
						  @for($y=date('Y'); $y>=2000;$y--)
						  <option {{(request()->has('year') && request('year')==$y) ? 'selected' : ''}} value="{{$y}}">{{$y}}</option>
						  @endfor
						</select>
						</div>	
				
						
					    <div class="input-group" style="margin-top:25px;margin-right:20px; ">
						 <label class="form-check-input" for="inlineFormmonthly_invoice"> &nbsp;</label>
					  <button type="submit" class="btn btn-success mb-2">Submit</button>&nbsp;
					  <a href="{{route('admin.reports.customers-order-total')}}" class="btn btn-default mb-2">Reset</a>
						
						</div>
						
						
						 
					</form>
					</div>
					<!-- /.search form -->
				
				<div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>S. No.</th>
                                <th>Customer</th>
                                <th>Statement</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
						@php $i=($customers->currentPage()-1)*$customers->perPage(); @endphp
                        @foreach($customers as $customer)
						
						@php 
						$i++; 
						$companyName = $customer->customer->defaultaddress->company_name;
						@endphp
						<tr>
						<td>{{$i}}</td>
						<td><a href="{{route('admin.customers.show',$customer->customer_id)}}">{{ucfirst($customer->customer->first_name.' '.$customer->customer->last_name)}} {{(!empty($companyName))?'('.$companyName.')':''}}</a></td>
						@if($customer->customer->customers_require_invoice_type==Config::get('constants.REQUIRE_INVOICE_TYPE'))
						<td><a href="{{ route('admin.reports.monthly-invoice') }}?customer={{$customer->customer_id}}&month={{request('month')}}&year={{request('year')}}" target="_blank" class="btn  btn-xs" title="View Statement"><i class="fa fa-file-text-o" style="font-size: large;" aria-hidden="true"></i></a>
						</td>
						@else
						<td><a href="{{ route('admin.reports.customer-weekly-statement') }}?cId={{$customer->customer_id}}&year={{request('year')}}" target="_blank" class="btn  btn-xs" title="View Statement"><i class="fa fa-file-text-o" style="font-size: large;" aria-hidden="true"></i></a>
						</td>
						@endif
						
						<td>{!! config('cart.currency_symbol') !!} {{$customer->total_amt}}</td>
						<td><a href="{{ route('admin.orders.customer_order_list', $customer->customer_id) }}" target="_blank" class="btn btn-info btn-xs" title="View Customer Orders"><i class="fa fa-eye" aria-hidden="true"></i></a>
						</td>
						</tr>
						@endforeach
						@if(count($customers)<=0)
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
				{{$customers->appends($_GET)->links()}}
                </div>
            </div>
            <!-- /.box -->
        

    </section>
    <!-- /.content -->
@endsection