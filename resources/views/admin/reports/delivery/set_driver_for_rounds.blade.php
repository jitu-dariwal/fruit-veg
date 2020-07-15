@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.reports.update-driver-name') }}" method="post" class="form" enctype="multipart/form-data">
                <div class="box-body">
				<h2>Choose / change rounds driver name for specific delivery report on {{ $delivery_date }}</h2>
                    {{ csrf_field() }}
					<input type="hidden" value="{{ $delivery_date }}" name="delivery_date">
					<table class="table add_more_rounds_wrapper">
					@if(count($driver_rounds)>0)
						@php $i=0; @endphp
					@foreach($driver_rounds as $round)
					    @php $i++; @endphp
					  <tr class="{{ ($i>1) ? 'remove_rounds' : '' }}">
					  <td><label class="" for="inlineFormInputName2">Driver Name For Round <span class="text-danger">*</span>:</label></td>
					  <td><input type="text" value="{{$round->round_name}}" class="form-control" name="round_name[]"></td>
					  <td><input type="text" value="{{$round->driver_name}}" class="form-control" name="driver_name[]"></td>
					  <td>
					  @if($i==1)
					  <a href="javascript:void(0);" class="btn btn-xs btn-info add_more_round_atr"><i class="fa fa-plus" aria-hidden="true"></i></a>
				      @else
					  <button class="btn btn-xs btn-danger remove_round_attr" title="Remove"><i class="fa fa-times"></i></button> 
					  @endif
					  </td>
					  </tr>
					@endforeach
					@else
					<tr>
					  <td><label class="" for="inlineFormInputName2">Driver Name For Round <span class="text-danger">*</span>:</label></td>
					  <td><input type="text" class="form-control" name="round_name[]"></td>
					  <td><input type="text" class="form-control" name="driver_name[]"></td>
					  <td><a href="javascript:void(0);" class="btn btn-xs btn-info add_more_round_atr"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
					  </tr>	
					@endif
					</table>
                    {{--<div class="form-group">
                        <label for="name">Round A Driver Name <span class="text-danger">*</span></label>
                        <input type="text" name="round_a_driver" placeholder="Round A Driver Name" class="form-control" value="{{ $driver_rounds->round_a Or '' }}">
                    </div>
					<div class="form-group">
                        <label for="name">Round B Driver Name <span class="text-danger">*</span></label>
                        <input type="text" name="round_b_driver" placeholder="Round B Driver Name" class="form-control" value="{{ $driver_rounds->round_b Or '' }}">
                    </div>
					<div class="form-group">
                        <label for="name">Round C Driver Name <span class="text-danger">*</span></label>
                        <input type="text" name="round_c_driver" placeholder="Round C Driver Name" class="form-control" value="{{ $driver_rounds->round_c Or '' }}">
                    </div>
					<div class="form-group">
                        <label for="name">Round D Driver Name <span class="text-danger">*</span></label>
                        <input type="text" name="round_d_driver" placeholder="Round D Driver Name" class="form-control" value="{{ $driver_rounds->round_d Or '' }}">
                    </div>
					<div class="form-group">
                        <label for="name">Round E Driver Name <span class="text-danger">*</span></label>
                        <input type="text" name="round_e_driver" placeholder="Round E Driver Name" class="form-control" value="{{ $driver_rounds->round_e Or '' }}">
                    </div>
					<div class="form-group">
                        <label for="name">Round F Driver Name <span class="text-danger">*</span></label>
                        <input type="text" name="round_f_driver" placeholder="Round F Driver Name" class="form-control" value="{{ $driver_rounds->round_f Or '' }}">
                    </div>
					<div class="form-group">
                        <label for="name">Round G Driver Name <span class="text-danger">*</span></label>
                        <input type="text" name="round_g_driver" placeholder="Round G Driver Name" class="form-control" value="{{ $driver_rounds->round_g Or '' }}">
                    </div>
					<div class="form-group">
                        <label for="name">Round H Driver Name <span class="text-danger">*</span></label>
                        <input type="text" name="round_h_driver" placeholder="Round H Driver Name" class="form-control" value="{{ $driver_rounds->round_h Or '' }}">
                    </div>
					<div class="form-group">
                        <label for="name">Round I Driver Name <span class="text-danger">*</span></label>
                        <input type="text" name="round_i_driver" placeholder="Round I Driver Name" class="form-control" value="{{ $driver_rounds->round_i Or '' }}">
                    </div>--}}
					
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <a href="{{route('admin.reports.delivery-report-summary')}}?delivery_date={{$delivery_date}}" class="btn btn-default">Back</a>
                        <button type="submit" class="btn btn-primary">Set Driver Name</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
@section('js')
<script>
$(document).ready(function() {
    var max_fields_rules      = 20; //maximum input boxes allowed
    var wrap_rounds        = $(".add_more_rounds_wrapper"); //Fields wrapper
    var add_rounds      = $(".add_more_round_atr"); //Add button ID
    
	
    var round = 1; //initlal text box count
    $(add_rounds).click(function(e){ //on add input button click
        e.preventDefault();
        if(round < max_fields_rules){ //max input box allowed
		
	   
            round++; //text box increment
            $(wrap_rounds).append('<tr class="remove_rounds"><td><label class="" for="inlineFormInputName2">Driver Name For Round <span class="text-danger">*</span>:</label></td><td><input type="text" value="" class="form-control" name="round_name[]"></td><td><input type="text" value="" class="form-control" name="driver_name[]"></td><td><button class="btn btn-xs btn-danger remove_round_attr" title="Remove"><i class="fa fa-times"></i></button></td></tr>'); //add input box
			
           		
        }
		    
    });
		$(wrap_rounds).on("click",".remove_round_attr", function(e){ //user click on remove text
		e.preventDefault(); $(this).closest('.remove_rounds').remove(); size_number--;
	})
	        

});
</script>
@endsection