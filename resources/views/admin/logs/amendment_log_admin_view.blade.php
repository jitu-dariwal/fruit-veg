@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        
        <div class="box">
		<div class="box-body">
		 <h2>View Amendment Log Report</h2>
		<!-- /.panel-body -->	
			<div class="panel-body" >

			<div class="container">

		<div class="table-responsive">
			<table class="table table-striped ">

			<tbody>
			
			<tr>
			<td>Company Name</td>
			<td>   <strong> 
			@if(!empty($AmendmentLogAdmin->companyNameShow->company_name))<a href="{{route('admin.customers.show',$AmendmentLogAdmin->CompanyName)}}" target="_blank">{{ $AmendmentLogAdmin->companyNameShow->company_name }}</a> 
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Company contact</td>
			<td>   <strong> 
			@if($AmendmentLogAdmin->CompanyContact != null)
			{{$AmendmentLogAdmin->CompanyContact}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Admin Clerk</td>
			<td>   <strong> @if($AmendmentLogAdmin->AdminClerk != null)
			 {{$AmendmentLogAdmin->AdminClerk}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			@if($AmendmentLogAdmin->NewOrderDate != "0000-00-00") 
			<tr>
			<td>New Order Date 1</td>
			<td>   <strong> @if($AmendmentLogAdmin->NewOrderDate != null)
			 {{$AmendmentLogAdmin->NewOrderDate}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			@endif
			
			
			@if($AmendmentLogAdmin->NewOrderDate2 != "0000-00-00") 
			<tr>
			<td>New Order Date 1</td>
			<td>   <strong> @if($AmendmentLogAdmin->NewOrderDate2 != null)
			 {{$AmendmentLogAdmin->NewOrderDate2}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			@endif
			
			@if($AmendmentLogAdmin->NewOrderDate3 != "0000-00-00") 
			<tr>
			<td>New Order Date 1</td>
			<td>   <strong> @if($AmendmentLogAdmin->NewOrderDate3 != null)
			 {{$AmendmentLogAdmin->NewOrderDate3}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			@endif
			
			@if($AmendmentLogAdmin->NewOrderDate4 != "0000-00-00") 
			<tr>
			<td>New Order Date 1</td>
			<td>   <strong> @if($AmendmentLogAdmin->NewOrderDate4 != null)
			 {{$AmendmentLogAdmin->NewOrderDate4}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			@endif
			
			@if($AmendmentLogAdmin->NewOrderDate5 != "0000-00-00") 
			<tr>
			<td>New Order Date 1</td>
			<td>   <strong> @if($AmendmentLogAdmin->NewOrderDate5 != null)
			 {{$AmendmentLogAdmin->NewOrderDate5}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			@endif
			
			
			 
			<tr>
			<td>Cancellation  </td>
			<td>   <strong> @if($AmendmentLogAdmin->Cancellation == 'yes')
			 Yes
			@else
			No 
			@endif</strong></td>
			</tr>
			
			
			<tr>
			<td>Amended Order Details</td>
			<td>  <strong> @if($AmendmentLogAdmin->AmendedOrderDetails != null)
			 {!!$AmendmentLogAdmin->AmendedOrderDetails!!}
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
