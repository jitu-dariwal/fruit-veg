@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    
	@include('alert/alert-box')
    <!-- Default box -->
        @if($Anomolies)
            <div class="box col-md-12">
                <div class="box-body col-md-12">
			<form action="{{route('admin.anomolies.print')}}" target="_new">	
			
			<button  class="btn btn-sm btn-success pull-right" type="submit"><i class="fa fa-print"></i> Print Report</button>
			
				
				
			
            <div class="input-group input-group-sm pull-right hide">	
			
            <input type="text" readonly name="printdate" class="form-control col-md-3 pull-right datepicker" placeholder="dd-mm-yyyy" value="{{date('d-m-Y',strtotime($searchDate))}}">
			</div>
			</form>
			 <label  class="pull-right hide"><b><h5>ISSUE LOG DATE : &nbsp;</h5></b> </label> 
				
                    <h2>ANOMOLIES avaliable on <span class="text-success">{{date('d M Y', strtotime($searchDate))}}</span> 	</h2>
					 @include('admin.anomolies.search', ['route' => route('admin.anomolies.index'), 'search_by' => 'with email or customer name'])
                    <table class="table col-md-12">
                        <thead class="thead-light">
                            <th class="col-md-1">S No</th>
                            <th class="col-md-4">Anomiles Points</th>
                            <th class="col-md-3">Anomiles Points Reply</th>
                            <th class="col-md-1">Add Date</th>
                            <th class="col-md-2 text-center">Actions</th>
                       </thead>
                        <tbody>
						@php
						$i = 0;
						@endphp
                        @foreach ($Anomolies as $Anomolie)
						@php
						$i++;
						@endphp
                            <tr>
							
                                <td>{{ $i }}</td>
                                
                                <td>@if(!empty($Anomolie->anomolies_points))
								{!! $Anomolie->anomolies_points !!}
								@else
								N/A	
								@endif
								</td>
                                <td>
								@if(!empty($Anomolie->anomolies_points_reply))
								{!! $Anomolie->anomolies_points_reply !!}
								@else
								N/A	
								@endif
								 </td>
                                <td>
								@if(!empty($Anomolie->anomolies_date))
								{!! date('d-m-Y',strtotime($Anomolie->anomolies_date))  !!}
								@else
								N/A	
								@endif
								</td>
                               
                                <td class="col-md-2 text-center" >
                                    <div class="btn-group">
                                        <a href="{{route('admin.anomolies.show',$Anomolie->id)}}" data-toggle="tooltip" title="Show" class="btn btn-default btn-sm hide"><i class="fa fa-eye"></i> </a>
										
                                        <a href="{{route('admin.anomolies.edit',$Anomolie->id)}}" data-toggle="tooltip" title="Edit Anomolies" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> </a>
										
										 <a href="{{route('admin.anomolies.delete',$Anomolie->id)}}" onclick="return confirm('Are you sure you want to delete this?');" data-toggle="tooltip" title="Delete" class="btn btn-danger btn-sm hide"><i class="fa fa-trash"></i> </a>
										 
                                    </div>
                                </td>
                            </tr>
							
                        @endforeach
						
						
							@if($Anomolies->count() == 0)
                            <tr><td colspan="8" style="color:red;"><center>No Record Found.</center></td></tr>
							@endif
                        </tbody>
                    </table>
                    {{ $Anomolies->links() }}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        @endif

    </section>
    <!-- /.content -->
@endsection
