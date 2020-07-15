@extends('layouts.admin.app')

@section('content')

    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        
            <div class="box">
                <div class="box-body ">
                    <h2>Customers Not Validated</h2>
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
					  <a href="{{route('admin.reports.not-valid-customers')}}" class="btn btn-default mb-2">Reset</a>
					  <a href="{{route('admin.reports.export-not-valid-customers')}}{{ (Request::getQueryString()) ? '?'.Request::getQueryString() : '' }}" class="btn btn-primary mb-2">Export Customer</a>
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
                                <th>S. No.</th>
                                <th>Customer Name</th>
                                <th>Email</th>
                                <th>Created At</th>
                                <th>Last Login</th>
                                <th>Mail Sent</th>
                                <th>Delete</th>
                                <th>Send E-mail</th>
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
						<td>{{ \Carbon\Carbon::parse($customer->created_at)->format('M d, Y h:i a') }}</td>
						<td></td>
						<td>{{ucfirst($customer->activation_mail_send)}}</td>
						<td>
						<form action="{{ route('admin.reports.remove-not-valid-customers') }}" method="post" class="form-horizontal">
                            @csrf
                            <div class="btn-group">
                                 <input type="hidden" value="{{$customer->id}}" name="customer_id">
                                 <button onclick="return confirm('You are about to delete this record?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Delete</button>
                            </div>
                        </form>
						</td>
						
						<td><a href="{{route('admin.reports.send-verification-mail', $customer->id)}}" class="btn">Send Email</a></td>
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