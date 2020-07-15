@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        
        <div class="box">
		<div class="box-body">
		 <h2>View Communication Log	</h2>
		<!-- /.panel-body -->	
			<div class="panel-body" >

			<div class="container">
			<div class="table-responsive">
			<table class="table table-striped">

			<tbody>
			
			<tr>
			<td>Company Name</td>
			<td>   <strong> 
			@if(!empty($CommunicationLogAdmin->companyNameShow->company_name))<a href="{{route('admin.customers.show',$CommunicationLogAdmin->CompanyName)}}" target="_blank">{{ $CommunicationLogAdmin->companyNameShow->company_name }}</a> 
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Company contact</td>
			<td>   <strong> 
			@if($CommunicationLogAdmin->CompanyContact != null)
			{{$CommunicationLogAdmin->CompanyContact}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Admin Clerk</td>
			<td>   <strong> @if($CommunicationLogAdmin->AdminClerk != null)
			 {{$CommunicationLogAdmin->AdminClerk}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			
			<tr>
			<td>Communication Details</td>
			<td>  <strong> @if($CommunicationLogAdmin->AmendedOrderDetails != null)
			 {!!$CommunicationLogAdmin->AmendedOrderDetails!!}
			@else
			N/A 
			@endif</strong></td>
			</tr>

			</tbody>
			</table>
				</div>
			</div>
			</div>

<div class="box-footer">
                    <div class="btn-group">
                        <a onclick="window.history.go(-1);" class="btn btn-default">Back</a>
                       
                    </div>
                </div>
        </div>
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
