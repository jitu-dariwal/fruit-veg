@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
	
    <section class="content">
        
        <div class="box">
		<div class="box-body">
		 <h2>View Lead Record	</h2>
		<!-- /.panel-body -->	
			<div class="panel-body" >

			<div class="container col-md-12">
	<h3>LEAD DETAIL</h3>
			<table class="table table-bordered">

			<tbody>
			
			<tr>
			<td>Sales Clerk	</td>
			<td>   <strong> 
			@if($SalesLead->SalesClerk != null)
			{{$SalesLead->SalesClerk}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Date</td>
			<td>   <strong> @if($SalesLead->created_at != null)
			 {{$SalesLead->created_at}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Client Name	</td>
			<td>   <strong> @if($SalesLead->ClientName != null)
				@if(!empty($SalesLead->customers_id))
				<a href="{{route('admin.customers.show',$SalesLead->customers_id)}}" target="_blank">{{ $SalesLead->ClientName }}</a>
				@else
				{{ $SalesLead->ClientName }}
				@endif
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Company</td>
			<td> <strong> @if($SalesLead->Company != null)
				@if(!empty($SalesLead->customers_id))
				<a href="{{route('admin.customers.show',$SalesLead->customers_id)}}" target="_blank">{{ $SalesLead->Company }}</a>
				@else
				{{ $SalesLead->Company }}
				@endif
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Tel 1</td>
			<td>  <strong> @if($SalesLead->Tel_1 != null)
			 {{$SalesLead->Tel_1}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Tel 2</td>
			<td>  <strong> @if($SalesLead->Tel_2 != null)
			 {{$SalesLead->Tel_2}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>E-Mail</td>
			<td>  <strong> @if($SalesLead->eMail != null)
			 {{$SalesLead->eMail}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Address 1</td>
			<td>  <strong> @if($SalesLead->Address1 != null)
			 {{$SalesLead->Address1}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Address 2</td>
			<td>  <strong> @if($SalesLead->Address2 != null)
			 {{$SalesLead->Address2}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Town</td>
			<td>  <strong> @if($SalesLead->Town != null)
			 {{$SalesLead->Town}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>County</td>
			<td>  <strong> @if($SalesLead->County != null)
			 {{$SalesLead->County}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Postcode</td>
			<td>  <strong> @if($SalesLead->Postcode != null)
			 {{$SalesLead->Postcode}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Status</td>
			<td>  <strong> @if(!empty(Config('constants.LeadStatus')[$SalesLead->status]))
			 {{Config('constants.LeadStatus')[$SalesLead->status]}}
			@else
			N/A 
			@endif</strong></td>
			</tr>
			
			<tr>
			<td>Last time client logged in	</td>
			<td>  <strong> </strong></td>
			</tr>
			@if(!empty($SalesLead->customers_id))
			<tr class="hide">
			<td >CHASE CLIENT</td>
			<td > <a target="_new" href="{{ route('admin.orders.customer_order_list', $SalesLead->customers_id) }}" data-toggle="tooltip" title="View order list" class=""><i class="fa fa-user"></i> Click Here </a></td>
			</tr>
			
			<tr>
			<td>View Order History</td>
			<td> <a target="_new" href="{{ route('admin.orders.customer_order_list', $SalesLead->customers_id) }}" data-toggle="tooltip" title="View order list" class=""><i class="fa fa-eye"></i> Click Here </a></td>
			</tr>
			@endif
			<tr>
			<td>Enquiry</td>
			<td>  <strong> @if($SalesLead->Enquiry != null)
			 {!!$SalesLead->Enquiry!!}
			@else
			N/A 
			@endif</strong></td>
			</tr>

			</tbody>
			</table>
			
			
			
			<h3>CHASED DETAIL</h3>
			
			
			 <table class="table">
                        <thead>
                            <tr class="dataTableHeadingRow">
                                <td >ID </td>
                                <td >DATE CHASED </td>
                                <td >Arrange Call Back Alert</td>
                                <td >Cusomers chase Note </td>
                               
                            </tr>
                        </thead>
                        <tbody>
						
						 
                        @foreach ($CustomerNotesAgainstLeadReports as $CustomerNotesAgainstLeadReport)
				
				  
                            <tr style="background-color:{{$setfontColor}};">
							
                                <td>{{ $CustomerNotesAgainstLeadReport->id }}</td>
                                 <td>{{ $CustomerNotesAgainstLeadReport->created_at }}</td>
                                <td>{{ $CustomerNotesAgainstLeadReport->ArrangeCallBackAlertDate }}</td>
                                <td>{{ $CustomerNotesAgainstLeadReport->notes }}</td>
                                
                            </tr>
                        @endforeach
						
								@if($CustomerNotesAgainstLeadReports->count() == 0)
								<tr > <td colspan="8" style="color:red;"><center>No Records Found</center> </td> </tr>
								@endif
                        </tbody>
                    </table>
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
