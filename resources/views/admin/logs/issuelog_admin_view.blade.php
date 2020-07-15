@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        
        <div class="box">
		<div class="box-body">
		 <h2>View Issue Log</h2>
		<!-- /.panel-body -->	
			<div class="panel-body" >

			<div class="container">

		<div class="table-responsive">
			<table class="table table-striped ">

			<tbody>
			
			<tr>
			<td>Order Number</td>
			<td>   <strong> 
			@if($issuelogAdmin->OrderNumber != null)
			<a href="{{ route('admin.orders.show', $issuelogAdmin->OrderNumber) }}" target="_blank">
			{{$issuelogAdmin->OrderNumber}}
			</a>
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Company Name</td>
			<td>   <strong> 
			@if(!empty($issuelogAdmin->companyNameShow->company_name))
			<a href="{{route('admin.customers.show',$issuelogAdmin->CompanyName)}}" target="_blank">
			{{ $issuelogAdmin->companyNameShow->company_name }} 
			</a>
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Company contact</td>
			<td>   <strong> @if($issuelogAdmin->CompanyContact != null)
			 {{$issuelogAdmin->CompanyContact}}
			</a>@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Admin Clerk</td>
			<td> <strong> @if($issuelogAdmin->AdminClerk != null)
			 {{$issuelogAdmin->AdminClerk}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Date</td>
			<td>  <strong> @if($issuelogAdmin->date1 != null)
			 {{$issuelogAdmin->date1}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Nature Of Issue</td>
			<td> <strong> @if($issuelogAdmin->NatureOfIssue != null)
			 {{ Config::get('constants.NatureOfIssue')[$issuelogAdmin->NatureOfIssue] }}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Responsibility</td>
			<td>  <strong> @if($issuelogAdmin->Responsibility != null)
			 {{$issuelogAdmin->Responsibility}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Resolution</td>
			<td>  <strong> @if($issuelogAdmin->Resolution != null)
			 {{$issuelogAdmin->Resolution}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Financial Implication (£)</td>
			<td>  <strong> @if($issuelogAdmin->FinancialImplication != null)
			 {{$issuelogAdmin->FinancialImplication}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Loss Type</td>
			<td>  <strong> @if($issuelogAdmin->LossType != null)
			 {{ Config::get('constants.LossType')[$issuelogAdmin->LossType] }}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Details</td>
			<td>  <strong> @if($issuelogAdmin->Details != null)
			 {!!$issuelogAdmin->Details!!}
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
