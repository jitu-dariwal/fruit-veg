@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    
	@include('alert/alert-box')
    <!-- Default box -->
        @if($issuelogAdmins)
            <div class="box">
                <div class="box-body">
			<form action="{{route('admin.issuelog.print')}}" target="_new">	
			
			<button  class="btn btn-sm btn-success pull-right" type="submit"><i class="fa fa-print"></i> Print Report</button>
			
				
				
			
            <div class="input-group input-group-sm pull-right">	
			
            <input type="text" readonly name="printdate" class="form-control col-md-3 pull-right datepicker" placeholder="dd-mm-yyyy" value="">
			</div>
			</form>
			 <label  class="pull-right"><b><h5>ISSUE LOG DATE : &nbsp;</h5></b> </label> 
				
                    <h2>Issue Log Reports	</h2>
					 @include('admin.logs.search', ['route' => route('admin.issuelog.index'), 'search_by' => 'with email or customer name'])
                   <div class="table-responsive">
                        <table class="table table-bordered mt-3 table-striped">
                        <thead class="thead-light">
                            <tr >
                                <th>Order Number</th>
                                <th>Nature Of Issue</th>
                                <th>Client/Company Effected</th>
                                <th>Responsibility</th>
                                <th>Details</th>
                                <th>Resolution</th>
                                <th>Financial Implication (£)</th>
                                <th>Loss Type</th>
                                <th>Create Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($issuelogAdmins as $issuelogAdmin)
                            <tr>
							
                                <td><a href="{{ route('admin.orders.show', $issuelogAdmin->OrderNumber) }}" target="_blank">{{ $issuelogAdmin->OrderNumber }}</a></td>
                                <td>{{ Config::get('constants.NatureOfIssue')[$issuelogAdmin->NatureOfIssue] }}</td>
                                <td><a href="{{route('admin.customers.show',$issuelogAdmin->CompanyName)}}" target="_blank">{{ $issuelogAdmin->ClientEffected }}</a></td>
                                <td>{{ $issuelogAdmin->Responsibility }}</td>
                                <td>{!! $issuelogAdmin->Details !!}</td>
                                <td>{{ $issuelogAdmin->Resolution }}</td>
                                <td>{{ $issuelogAdmin->FinancialImplication }}</td>
                                <td>{{ Config::get('constants.LossType')[$issuelogAdmin->LossType] }}</td>
                                <td>{{ $issuelogAdmin->created_at }}</td>
                               
                                <td>
                                    <div class="btn-group">
                                        <a href="{{route('admin.issuelog.show',$issuelogAdmin->id)}}" data-toggle="tooltip" title="Show" class="btn btn-default btn-sm"><i class="fa fa-eye"></i> </a>
										
                                        <a href="{{route('admin.issuelog.edit',$issuelogAdmin->id)}}" data-toggle="tooltip" title="Edit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> </a>
										
										 <a href="{{route('admin.issuelog.delete',$issuelogAdmin->id)}}" onclick="return confirm('Are you sure you want to delete this?');" data-toggle="tooltip" title="Delete" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> </a>
										 
                                    </div>
                                </td>
                            </tr>
                        @endforeach
						@if(count($issuelogAdmins)<=0)
							<tr>
						    <td colspan="15" class="not_found">{{Config::get('constants.NO_RECORD_FOUND')}}</td>
						    </tr>
						@endif
                        </tbody>
                    </table>
					</div>
                    {{ $issuelogAdmins->links() }}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        @endif

    </section>
    <!-- /.content -->
@endsection
