@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    
	@include('alert/alert-box')
    <!-- Default box -->
        @if($CommunicationLogAdmins)
            <div class="box">
                <div class="box-body">
					
					<div class="communication-date" >	
							<label><b><h5>COMMUNICATION DATE : &nbsp;</h5></b> </label>						
							<form action="{{route('admin.communicationlog.print')}}" target="_new">
								<button  class="btn btn-sm btn-success pull-right" type="submit"><i class="fa fa-print"></i> Print Report</button>

								<div class="input-group input-group-sm pull-right">				
								<input type="text" readonly name="printdate" class="form-control col-md-3 pull-right datepicker" placeholder="dd-mm-yyyy" value="">
								</div>
							</form>
					</div>	
						
					
				
			 
                    <h2>Communication Log	</h2>
					 @include('admin.logs.communication_search', ['route' => route('admin.issuelog.index'), 'search_by' => 'Search by Company/name'])
                  <div class="table-responsive">
                       <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Company Name</th>
                                <th>Company Contact</th>
                                <th>Create Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($CommunicationLogAdmins as $CommunicationLogAdmin)
                            <tr>
							 
                                <td>@if(!empty($CommunicationLogAdmin->companyNameShow->company_name))
								<a href="{{route('admin.customers.show',$CommunicationLogAdmin->CompanyName)}}" target="_blank">{{ $CommunicationLogAdmin->companyNameShow->company_name }}</a> @endif</td>
                               
                                <td>{{ $CommunicationLogAdmin->CompanyContact }}</td>
                               
                                <td>{{ $CommunicationLogAdmin->created_at }}</td>
                               
                                <td>
                                    <div class="btn-group">
                                        <a href="{{route('admin.communicationlog.show',$CommunicationLogAdmin->id)}}" data-toggle="tooltip" title="Show" class="btn btn-default btn-sm"><i class="fa fa-eye"></i> </a>
										
                                        <a href="{{route('admin.communicationlog.edit',$CommunicationLogAdmin->id)}}" data-toggle="tooltip" title="Edit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> </a>
										
										 <a href="{{route('admin.communicationlog.delete',$CommunicationLogAdmin->id)}}" onclick="return confirm('Are you sure you want to delete this?');" data-toggle="tooltip" title="Delete" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> </a>
										 
                                    </div>
                                </td>
                            </tr>
                        @endforeach
						@if(count($CommunicationLogAdmins)<=0)
							<tr>
						    <td colspan="15" class="not_found">{{Config::get('constants.NO_RECORD_FOUND')}}</td>
						    </tr>
						@endif
                        </tbody>
                    </table>
					</div> 
					
                    {{ $CommunicationLogAdmins->links() }}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        @endif

    </section>
    <!-- /.content -->
@endsection
