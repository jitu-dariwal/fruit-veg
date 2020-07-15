@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    
	@include('alert/alert-box')
    <!-- Default box -->
        @if($salesLeads)
            <div class="box">
                <div class="box-body">
			
			
			
				
                    <h2>Sales Lead Reports		</h2>
					 @include('admin.lead.search', ['route' => route('admin.lead.index'), 'search_by' => 'with email or customer name'])
                    <table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Date </th>
                                <th>Clerk </th>
                                <th>Client Name</th>
                                <th>Company </th>
                                <th>Status</th>
                                <th>Chased On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($salesLeads as $salesLead)
						
				@if($salesLead->SalesClerk =='ONLINE' && $salesLead->status == 4 ) 
				@php $setfontColor ="#D9898F"; @endphp
				@elseif($salesLead->SalesClerk =='ONLINE' && $salesLead['status']== 5 )  
				@php $setfontColor="#9AD876"; @endphp
				@else
				@php $setfontColor=""; @endphp
				@endif
				  
                            <tr style="background-color:{{$setfontColor}};">
							
                                <td>{{ $salesLead->created_at }}</td>
                                 <td>{{ $salesLead->SalesClerk }}</td>
                                <td>
								@if(!empty($salesLead->customers_id))
								<a href="{{route('admin.customers.show',$salesLead->customers_id)}}" target="_blank">{{ $salesLead->ClientName }}</a>
							    @else
								{{ $salesLead->ClientName }}
							    @endif
							    </td>
                                <td>
								@if(!empty($salesLead->customers_id))
								<a href="{{route('admin.customers.show',$salesLead->customers_id)}}" target="_blank">{{ $salesLead->Company }}</a>
							    @else
								{{ $salesLead->Company }}
							    @endif</td>
                                <td>{{ Config::get('constants.LeadStatus')[$salesLead->status] }}</td>
                                <td>{{ $salesLead->Company }}</td>
                               
                               
                               
                                <td>
                                    <div class="btn-group">
                                        <a href="{{route('admin.lead.show',$salesLead->id)}}" data-toggle="tooltip" title="Show" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Chase </a>
										
										
										 <a href="{{route('admin.lead.delete',$salesLead->id)}}" onclick="return confirm('Are you sure you want to close this?');" data-toggle="tooltip" title="Close" class="btn btn-warning btn-sm"><i class="fa fa-times"></i> Close Lead </a>
										 
                                    </div>
                                </td>
                            </tr>
                        @endforeach
						@if(count($salesLeads)<=0)
							<tr>
						    <td colspan="15" class="not_found">{{Config::get('constants.NO_RECORD_FOUND')}}</td>
						    </tr>
						@endif
                        </tbody>
                    </table>
                    {{ $salesLeads->links() }}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        @endif

    </section>
    <!-- /.content -->
@endsection
