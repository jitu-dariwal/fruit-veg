@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    
	@include('alert/alert-box')
    <!-- Default box -->
        @if($customers)
            <div class="box col-md-12">
                <div class="box-body col-md-12">
			<form action="{{route('admin.customers_postcode_list_report.exportreport')}}" method="post" >	
			@csrf
			<button  class="btn btn-sm btn-success pull-right" type="submit"><i class="fa fa-file-excel-o"></i> Export Report</button>
			
				
				
			
            <div class="input-group input-group-sm pull-right hide">	
			
            <input type="text" readonly name="printdate" class="form-control col-md-3 pull-right datepicker" placeholder="dd-mm-yyyy" value="{{date('d-m-Y',strtotime($searchDate))}}">
			</div>
			</form>
			 <label  class="pull-right hide"><b><h5>ISSUE LOG DATE : &nbsp;</h5></b> </label> 
				
                    <h2>Customers Postcode List Report </h2>
					<div class="table-responsive">
					<!-- search form -->
					<form method="GET" class="">
					<table class="table table-striped">
					<tr>
					<td>
					<label class="" for="inlineFormInputName2">Search by postcode:</label></td>
					<td class="week_numbers">
					<input type="text" class="form-control" value="{{(request()->has('postcode'))?request('postcode'):''}}" name="postcode">
					</td>
					  <td><div style="margin-bottom:5px;">
					  <button type="submit" class="btn btn-primary mb-2">Submit</button>
					  <a href="{{route('admin.customers_postcode_list_report.index')}}" class="btn btn-default mb-2">Reset</a>
					  </div></td>
                    </tr>
                    </table>
					</form>
					<!-- /.search form -->
				    </div>
                    <table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th class="col-md-1">Client Id</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Company</th>
                                <th>Postcode</th>
                                <th>Date Registration</th>
                                <th>Placed orders in the last two weeks</th>
                                <th>The activity in the last two weeks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
						@php
						$i = 0;
						@endphp
                        @foreach ($customers as $customer)
						@php
						$i++;
						@endphp
                            <tr>
							
                                <td><a href="{{ route('admin.customers.show', $customer->id) }}" target="_blank">{{ $customer->id }}</a></td>
                                
                                <td>@if(!empty($customer->first_name))
								<a href="{{ route('admin.customers.show', $customer->id) }}" target="_blank">{!! $customer->first_name !!} {!! $customer->last_name !!}</a>
								@else
								N/A	
								@endif
								</td>
                                
                                <td>
								@if(!empty($customer->tel_num))
								{!! $customer->tel_num !!}
								@else
								N/A	
								@endif
								</td>
								
								 <td> 
								 

								@if(!empty($customer->company_name))
								<a href="{{ route('admin.customers.show', $customer->id) }}" target="_blank">{!! $customer->company_name !!}</a>
								@else
								N/A	
								@endif
								</td>
								
								<td>
								@if(!empty($customer->invoice_postcode))
								{!! $customer->invoice_postcode !!}
								@else
								N/A	
								@endif
								</td>
								
								<td>
								@if(!empty($customer->created_at))
								{!! $customer->created_at !!}
								@else
								N/A	
								@endif
								</td>
								
								<td>
								@if(!empty($orderPluck[$customer->id]))
								Yes
								@else
								No
								@endif
								</td>
								
								<td>
								
								@if(date('Y-m-d',strtotime($customer->created_at)) > $date_from)
								Yes
								@else
								No
								@endif
								</td>
								
								<td>
								<a href="{{ route('admin.orders.customer_order_list', $customer['id']) }}" title="View Orders" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-list"></i></a>
								</td>
                               
                            </tr>
							
                        @endforeach
						
						
							@if($customers->count() == 0)
                            <tr><td colspan="18" style="color:red;"><center>No Record Found.</center></td></tr>
							@endif
                        </tbody>
                    </table>
                    
                </div>
                <!-- /.box-body -->
				<div class="box-footer">
				{{$customers->appends($_GET)->links()}}
                </div>
            </div>
            <!-- /.box -->
        @endif

    </section>
    <!-- /.content -->
@endsection
