@extends('layouts.admin.app')

@section('content')

    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        
            <div class="box">
                <div class="box-body">
                    <h2>Customers that haven't ordered ever</h2>
					<div class="table-responsive">
					<!-- search form -->
					<form method="GET" class="">
					<table class="table table-striped">
					<tr>
					<td>
					  <label class="" for="inlineFormInputName2">From:</label></td>
					  <td class="week_numbers">
					  <input type="text" value="{{(request()->has('from_date')) ? request('from_date') : ''}}" class="datepicker form-control" name="from_date" readonly="">
					  </td>
					  <td>
					  <label class="" for="inlineFormInputName2">To:</label></td>
					  <td>
					  <input type="text" value="{{(request()->has('to_date')) ? request('to_date') : ''}}" class="datepicker form-control" name="to_date" readonly="">
					  </td>
					  <td><div style="margin-bottom:5px;">
					  <button type="submit" class="btn btn-primary mb-2">Submit</button>
					  <a href="{{route('admin.reports.never-ordered-client-report')}}" class="btn btn-default mb-2">Reset</a>
					  <a href="{{route('admin.reports.export-never-ordered-client-report')}}{{ (Request::getQueryString()) ? '?'.Request::getQueryString() : '' }}" class="btn btn-primary mb-2">Export Never Ordered Customers</a>
					  </div></td>
                    </tr>
                    </table>
					</form>
					
					<!-- /.search form -->
				</div>
					
					<!-- /.search form -->
				</div>
				<div class="table-responsive">
                    
					<table class="table table-bordered table-striped">
                        <thead>
                            <tr class="thead-light">
                                <th class="">S. No.</th>
                                <th class="">Customer Name</th>
                                <th class="">Email</th>
                                <th class="">Company</th>
                                <th class="col-md-2">Address</th>
                                <th class="">City</th>
                                <th class="">Postcode</th>
                                <th class="">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
						@php $i=($customers->currentPage()-1)*$customers->perPage(); @endphp
                        @foreach($customers as $customer)
						@php $i++; @endphp
						<tr>
						<td>{{$i}}</td>
						<td><a href="{{route('admin.customers.show',$customer->id)}}">{{ucfirst($customer->first_name.' '.$customer->last_name)}}</a></td>
						<td>{{'@@'.$customer->email}}</td>
						
						<td>{{$customer->defaultaddress->company_name}}</td>
						<td>{{$customer->defaultaddress->street_address.' '.$customer->defaultaddress->address_line_2}}</td>
						<td>{{$customer->defaultaddress->city}}</td>
						<td>{{$customer->defaultaddress->post_code}}</td>
						<td>{{ \Carbon\Carbon::parse($customer->created_at)->format('M d, Y h:i a') }}</td>
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
