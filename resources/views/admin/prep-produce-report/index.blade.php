@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    
	@include('alert/alert-box')
    <!-- Default box -->
        @if($OrdersTotal)
            <div class="box col-md-12">
                <div class="box-body col-md-12">
			<form action="{{route('admin.prep_produce_report.printreport')}}" target="_new">	
			
			<button  class="btn btn-sm btn-success pull-right" type="submit"><i class="fa fa-print"></i> Print Report</button>
			
				
				
			
            <div class="input-group input-group-sm pull-right hide">	
			
            <input type="text" readonly name="printdate" class="form-control col-md-3 pull-right datepicker" placeholder="dd-mm-yyyy" value="{{date('d-m-Y',strtotime($searchDate))}}">
			</div>
			</form>
			 <label  class="pull-right hide"><b><h5>ISSUE LOG DATE : &nbsp;</h5></b> </label> 
				
                    <h2>Prep Produce Report for <span class="text-success">{{date('d M Y', strtotime($searchDate))}}</span> 	</h2>
					 @include('admin.prep-produce-report.search', ['route' => route('admin.prep_produce_report.index'), 'search_by' => 'with email or customer name'])
                    <table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th class="col-md-1">No	</th>
                                <th class="col-md-4">Product </th>
                                <th class="col-md-3">Quantity	</th>
                                <th class="col-md-1">Packet Size </th>
                                <th class="col-md-1">Order No. </th>
                               
                            </tr>
                        </thead>
                        <tbody>
						@php
						$i = ($OrdersTotal->currentPage()-1)*$OrdersTotal->perPage();  ;
						@endphp
                        @foreach ($OrdersTotal as $Anomolie)
						@php
						$i++;
						@endphp
                            <tr>
							
                                <td>{{ $i }}</td>
                                
                                <td>@if(!empty($Anomolie->product_name))
								<a href="{{route('admin.products.show', $Anomolie->product_id)}}" target="_blank">{!! $Anomolie->product_name !!}</a>
								@else
								N/A	
								@endif
								</td>
                                <td>
								@if(!empty($Anomolie->quantity))
								{!! $Anomolie->quantity !!}
								@else
								N/A	
								@endif
								 </td>
                                <td>
								@if(!empty($Anomolie->packet_size))
								{!! $Anomolie->packet_size !!}
								@else
								N/A	
								@endif
								</td>
                               <td><a href="{{route('admin.orders.show', $Anomolie->order_ids)}}" target="_blank">{{$Anomolie->order_ids}}</a></td>
                            </tr>
							
                        @endforeach
						
						
							@if($OrdersTotal->count() == 0)
                            <tr><td colspan="8" style="color:red;"><center>No Record Found.</center></td></tr>
							@endif
                        </tbody>
                    </table>
                    {{ $OrdersTotal->links() }}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        @endif

    </section>
    <!-- /.content -->
@endsection
