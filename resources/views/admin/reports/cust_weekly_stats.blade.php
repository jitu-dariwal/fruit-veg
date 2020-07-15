@extends('layouts.admin.app')

@section('content')
@section('css')
<style>
.dataTableHeadingRow {
    background-color: rgb(201, 201, 201);
}
.product_items {
	display:none;
}
.show_items {
	background-color: rgb(241, 241, 241);
}
</style>
@endsection
    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        
            <div class="box">
                <div class="box-body">
                    <h2>Customer Statements</h2>
                    <div class="">
					<!-- search form -->
					<form class="form-inline">
					
					<table class="table">
					<tr>
					<td>
					  <label class="" for="inlineFormInputName2">Display for the month:</label></td>
					  <td>
					  <select class="form-control" name="month">
						  <option value="0">all</option>
						  @foreach(Config::get('constants.MONTHS') as $key=>$value)
					    <option @if(request()->has('month')) @if(request('month')==$key) {{'selected'}} @else {{''}} @endif @else @if(date('m')==$key) {{'selected' }} @endif   @endif value="{{$key}}">{{$value}}</option>
					  @endforeach
						</select>
					</td>
					<td>
					  <label class="" for="inlineFormInputGroupUsername2">Show Monthly Invoices Only:</label></td>
					  <td>
						<input type="checkbox" name="monthly-invoice" {{(request()->has('monthly-invoice') && request('monthly-invoice')==1) ? 'checked' : ''}} value="1">
						</td>
                    </tr>
                    
					  <tr>
					  <td>
					  <label class="" for="inlineFormInputName2">Display for the year:</label></td>
					  <td>
					  <select class="form-control" name="year">
						  <option value="0">all</option>
						  @for($y=date('Y'); $y>=2000;$y--)
						  <option {{(request()->has('year') && request('year')==$y) ? 'selected' : ($y==date('Y')) ? 'selected' : ''}} value="{{$y}}">{{$y}}</option>
						  @endfor
						</select>
					</td>
					  
						<td>
					  <label class="" for="inlineFormInputGroupUsername2">Show Delivered Orders Only:</label></td>
					  <td>
						<input type="checkbox" name="delivered" {{(request()->has('delivered') && request('delivered')==1) ? 'checked' : ''}} value="1">
						</td>
						</tr>
						<tr>
						<td></td>
						<td></td>
						<td>
						
					  <label class="" for="inlineFormInputGroupUsername2">Show UNPAID Only:</label></td>
					  <td>
						<input type="checkbox" name="check_paid" {{(request()->has('check_paid') && request('check_paid')==1) ? 'checked' : ''}} value="1">
						</td>
						</tr>
						</table>
						<div style="margin-bottom:5px;">
					  <button type="submit" class="btn btn-primary mb-2">Submit</button>
					  <a href="{{route('admin.reports.customer-weekly-stats')}}" class="btn btn-default mb-2">Reset</a>
					  </div>
					</form>
					
					<!-- /.search form -->
				</div>
                    
					<table class="table">
                        
						
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                </div>
            </div>
            <!-- /.box -->
        

    </section>
    <!-- /.content -->
@endsection
@section('js')
<script>
$(document).on('change','#no_status',function(){
	$('#status').val(0);
	if($(this).val() > 0){
	$('#status_div').hide();
	}else{
	$('#status_div').show();	
	}
});
$(document).on('change','#status',function(){
	$('#no_status').val(0);
	if($(this).val() > 0){
	$('#no_status_div').hide();
	}else{
	$('#no_status_div').show();	
	}
});
</script>
@endsection