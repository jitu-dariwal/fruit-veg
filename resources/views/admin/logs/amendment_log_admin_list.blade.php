@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    
	@include('alert/alert-box')
    <!-- Default box -->
        @if($AmendmentLogAdmins)
            <div class="box">
                <div class="box-body">
				
				<form action="{{route('admin.amendmentlogreport.print')}}" target="_new">	
			
			<button  class="btn btn-sm btn-success pull-right" type="submit"><i class="fa fa-print"></i> Print Report</button>
			
				
				
			
            <div class="input-group input-group-sm pull-right">	
			
            <input type="text" readonly name="printdate" class="form-control col-md-3 pull-right datepicker" placeholder="dd-mm-yyyy" value="">
			</div>
			</form>
			 <label  class="pull-right"><b><h5>AMENDMENTLOG DATE : &nbsp;</h5></b> </label> 
			 
                    <h2>Amendment Log Reports	</h2>
					<div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Company Name</th>
                                <th>Company Contact</th>
                                <th>Admin Clerk</th>
                                <th>New Order Date</th>
                                <th>Cancellation</th>
                                <th>Create Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($AmendmentLogAdmins as $AmendmentLogAdmin)
                            <tr>
							<?php //echo "CK<pre>"; print_r($AmendmentLogAdmin->companyNameShow->toArray()); echo "</pre>CK"; exit;
 ?>
                                <td>@if(!empty($AmendmentLogAdmin->companyNameShow->company_name))
								<a href="{{route('admin.customers.show',$AmendmentLogAdmin->CompanyName)}}" target="_blank">{{ $AmendmentLogAdmin->companyNameShow->company_name }}</a> @endif</td>
                               
                                <td>{{ $AmendmentLogAdmin->CompanyContact }}</td>
                                <td>{{ $AmendmentLogAdmin->AdminClerk }}</td>
                                <td>
								@if($AmendmentLogAdmin->NewOrderDate != "0000-00-00") 
								{{ $AmendmentLogAdmin->NewOrderDate }} 
								@endif
								
								@if($AmendmentLogAdmin->NewOrderDate2 != "0000-00-00") 
									<br>
								{{ $AmendmentLogAdmin->NewOrderDate2 }}
							    @endif
								
								@if($AmendmentLogAdmin->NewOrderDate3 != "0000-00-00") 
									<br>
								{{ $AmendmentLogAdmin->NewOrderDate3 }} 
								@endif
								
								@if($AmendmentLogAdmin->NewOrderDate4 != "0000-00-00") 
									<br>
								{{ $AmendmentLogAdmin->NewOrderDate4 }} 
								@endif
								
								@if($AmendmentLogAdmin->NewOrderDate5 != "0000-00-00") 
									<br>
								{{ $AmendmentLogAdmin->NewOrderDate5 }} 
								@endif
								</td>
                                <td>{!! $AmendmentLogAdmin->Cancellation !!}</td>
                                
                                <td>{{ $AmendmentLogAdmin->created_at }}</td>
                               
                                <td>
                                    <div class="btn-group">
                                        <a href="{{route('admin.amendmentlogreport.show',$AmendmentLogAdmin->id)}}" data-toggle="tooltip" title="Show" class="btn btn-default btn-sm"><i class="fa fa-eye"></i> </a>
										
                                        <a href="{{route('admin.amendmentlogreport.edit',$AmendmentLogAdmin->id)}}" data-toggle="tooltip" title="Edit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> </a>
										
										 <a href="{{route('admin.amendmentlogreport.delete',$AmendmentLogAdmin->id)}}" onclick="return confirm('Are you sure you want to delete this?');" data-toggle="tooltip" title="Delete" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> </a>
										 
                                    </div>
                                </td>
                            </tr>
                        @endforeach
						@if(count($AmendmentLogAdmins)<=0)
							<tr>
						    <td colspan="15" class="not_found">{{Config::get('constants.NO_RECORD_FOUND')}}</td>
						    </tr>
						@endif
                        </tbody>
                    </table>
					
                    {{ $AmendmentLogAdmins->links() }}
					</div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        @endif

    </section>
    <!-- /.content -->
@endsection
