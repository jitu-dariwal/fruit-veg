@extends('layouts.admin.app')

@section('content')
@section('css')
<style>

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
                    <h2>Sales Report</h2>
					<div class="table-responsive">
					<!-- search form -->
					<form method="GET" class="">
					<table class="table table-active">
					<tr>
					<td>
					  <label class="" for="inlineFormInputName2">From:</label></td>
					  <td class="week_numbers">
					  <input type="text" value="{{$start_date}}" class="datepicker form-control" name="from_date"> 
					  </td>
					  <td>
					  <label class="" for="inlineFormInputName2">To:</label></td>
					  <td>
					  <input type="text" value="{{$to_date}}" class="datepicker form-control" name="to_date">
					  </td>
					  <td>
					  <label class="" for="inlineFormInputName2">Detail:</label></td>
					  <td>
					  <select class="form-control" name="detail">
						<option {{(request()->has('detail') && request('detail')==0) ? 'selected' : ''}} value="0">No details</option>
						<option {{(request()->has('detail') && request('detail')==1) ? 'selected' : ''}} value="1">Show details</option>
						<option {{(request()->has('detail') && request('detail')==2) ? 'selected' : ''}} value="2" >Details with amount</option>
						</select>
					  </td>
					  <td>
					  <label class="" for="inlineFormInputName2">Show Top:</label></td>
					  <td>
					  <select class="form-control" name="max">
						<option value="0">All</option>
						<option {{(request()->has('max') && request('max')==1) ? 'selected' : ''}}>1</option>
						<option {{(request()->has('max') && request('max')==3) ? 'selected' : ''}}>3</option>
						<option {{(request()->has('max') && request('max')==5) ? 'selected' : ''}}>5</option>
						<option {{(request()->has('max') && request('max')==10) ? 'selected' : ''}}>10</option>
						<option {{(request()->has('max') && request('max')==25) ? 'selected' : ''}}>25</option>
						<option {{(request()->has('max') && request('max')==50) ? 'selected' : ''}}>50</option>
					  </select>
					  </td>
					   </tr>
					  <tr>
					  <td>
					  <label class="" for="inlineFormInputName2">Status:</label></td>
					  <td>
					  <select class="form-control" name="status">
						<option value="0">All</option>
						@foreach($statuses as $ostatus)
						<option {{(request()->has('status') && request('status')==$ostatus->id) ? 'selected' : ''}} value="1">{{$ostatus->name}}</option>
						@endforeach
					  </select>
					  </td>
					  
					  <td>
					  <label class="" for="inlineFormInputName2">Export:</label></td>
					  <td>
					  <select class="form-control" name="export">
						<option {{(request()->has('export') && request('export')==0) ? 'selected' : ''}} value="0" selected="">Normal</option>
						<option {{(request()->has('export') && request('export')==1) ? 'selected' : ''}} value="1">HTML only</option>
						<option {{(request()->has('export') && request('export')==2) ? 'selected' : ''}} value="2">CSV</option>
					  </select>
					  </td>
					 
					  <td>
					  <label class="" for="inlineFormInputName2">Sort:</label></td>
					  <td>
					  <select class="form-control" name="sort">
					<option {{(request()->has('sort') && request('sort')==0) ? 'selected' : ''}} value="0">Standard</option>
					<option {{(request()->has('sort') && request('sort')==1) ? 'selected' : ''}} value="1">Description</option>
					<option {{(request()->has('sort') && request('sort')==2) ? 'selected' : ''}} value="2" >Description desc</option>
					<option {{(request()->has('sort') && request('sort')==3) ? 'selected' : ''}} value="3">#Items</option>
					<option {{(request()->has('sort') && request('sort')==4) ? 'selected' : ''}} value="4">#Items desc</option>
					<option {{(request()->has('sort') && request('sort')==5) ? 'selected' : ''}} value="5">Revenue</option>
					<option {{(request()->has('sort') && request('sort')==6) ? 'selected' : ''}} value="6">Revenue desc</option>
					</select>
					  </td>
					  <td colspan="2">
					  <label class="radio-inline" style="margin-left: 0px !important;" for="inlineFormmonthly_invoice">
					<input class="form-check-input" {{(request()->has('report') && request('report')==1) ? 'checked' : '' }}  type="radio" value="1" name="report"  id="inlineFormmonthly_invoice"> Yearly</label>
					<label class="radio-inline" style="margin-left: 0px !important;" for="inlineFormDelivered"><input class="form-check-input" {{(request()->has('report')) ? (request('report')==2) ? 'checked' : '' : 'checked' }} type="radio"  value="2" name="report" id="inlineFormDelivered"> Monthly</label>
					<label class="radio-inline" style="margin-left: 0px !important;" for="inlineFormDelivered2"><input class="form-check-input" {{(request()->has('report') && request('report')==3) ? 'checked' : '' }} type="radio"  value="3" name="report" id="inlineFormDelivered2"> Weekly</label>
					<label class="radio-inline" style="margin-left: 0px !important;" for="inlineFormDelivered3"><input class="form-check-input" {{(request()->has('report') && request('report')==4) ? 'checked' : '' }} type="radio"  value="4" name="report" id="inlineFormDelivered3"> Daily</label>
					  </td>
					  </tr>
					  <tr>
					  <td colspan="4"><div style="margin-bottom:5px;">
					  <button type="submit" class="btn btn-primary mb-2">Submit</button>
					  <a href="{{route('admin.reports.sales-report-per-category')}}" class="btn btn-default mb-2">Reset</a>
					  </div></td>
                    </tr>
                    </table>
					</form>
					<!-- /.search form -->
				    </div>
					
                    <!--div class="">
					
					<form method="GET" class="">
					<div class="" style="margin-bottom:5px;">

					<div class="col-md-2">
					<div class="form-group">
					<label class="" for="inlineFormInputName2">From date:</label>
					<input type="text" value="{{$start_date}}" class="datepicker form-control" name="from_date"> 

					</div>
					</div>

					<div class="col-md-2">
					<div class="form-group">
					<label class="" for="inlineFormInputName2">To date:</label>
					<input type="text" value="{{$to_date}}" class="datepicker form-control" name="to_date">
					</div>
					</div>


					<div class="col-md-2">
					<div class="form-group">

					<label class="" for="inlineFormInputGroupUsername2">Detail:</label>
					<select class="form-control" name="detail">
					<option {{(request()->has('detail') && request('detail')==0) ? 'selected' : ''}} value="0">no details</option>
					<option {{(request()->has('detail') && request('detail')==1) ? 'selected' : ''}} value="1">show details</option>
					<option {{(request()->has('detail') && request('detail')==2) ? 'selected' : ''}} value="2" >details with amount</option>
					</select>

					</div>
					</div>

					<div class="col-md-2">
					<div class="form-group">

					<label class="" for="inlineFormInputGroupUsername2">Show Top:</label>
					<select class="form-control" name="max">
					<option value="0">all</option>
					<option {{(request()->has('max') && request('max')==1) ? 'selected' : ''}}>1</option>
					<option {{(request()->has('max') && request('max')==3) ? 'selected' : ''}}>3</option>
					<option {{(request()->has('max') && request('max')==5) ? 'selected' : ''}}>5</option>
					<option {{(request()->has('max') && request('max')==10) ? 'selected' : ''}}>10</option>
					<option {{(request()->has('max') && request('max')==25) ? 'selected' : ''}}>25</option>
					<option {{(request()->has('max') && request('max')==50) ? 'selected' : ''}}>50</option>
					</select>
					</div>
					</div>


					<div class="col-md-2">
					<div class="form-group">
					<label class="" for="inlineFormInputGroupUsername2">Status:</label>
					<select class="form-control" name="status">
					<option value="0">all</option>
					@foreach($statuses as $ostatus)
					<option {{(request()->has('status') && request('status')==$ostatus->id) ? 'selected' : ''}} value="1">{{$ostatus->name}}</option>
					@endforeach
					</select>
					</div>
					</div>

					<div class="col-md-2">
					<div class="form-group">	
					<label class="" for="inlineFormInputGroupUsername2">Export:</label>
					<select class="form-control" name="export">
					<option {{(request()->has('export') && request('export')==0) ? 'selected' : ''}} value="0" selected="">normal</option>
					<option {{(request()->has('export') && request('export')==1) ? 'selected' : ''}} value="1">HTML only</option>
					<option {{(request()->has('export') && request('export')==2) ? 'selected' : ''}} value="2">CSV</option>
					</select>

					</div>
					</div>

					<div class="col-md-2">
					<div class="form-group">	
					<label class="" for="inlineFormInputGroupUsername2">Sort:</label>
					<select class="form-control" name="sort">
					<option {{(request()->has('sort') && request('sort')==0) ? 'selected' : ''}} value="0">standard</option>
					<option {{(request()->has('sort') && request('sort')==1) ? 'selected' : ''}} value="1">description</option>
					<option {{(request()->has('sort') && request('sort')==2) ? 'selected' : ''}} value="2" >description desc</option>
					<option {{(request()->has('sort') && request('sort')==3) ? 'selected' : ''}} value="3">#Items</option>
					<option {{(request()->has('sort') && request('sort')==4) ? 'selected' : ''}} value="4">#Items desc</option>
					<option {{(request()->has('sort') && request('sort')==5) ? 'selected' : ''}} value="5">Revenue</option>
					<option {{(request()->has('sort') && request('sort')==6) ? 'selected' : ''}} value="6">Revenue desc</option>
					</select>
					</div>
					</div>

					<div class="col-md-2">
					<div class="form-group">	
					<label class="" for="inlineFormInputGroupUsername2">Yearly:</label>
					<label class="form-control" for="inlineFormmonthly_invoice">
					<input class="form-check-input" {{(request()->has('report') && request('report')==1) ? 'checked' : '' }}  type="radio" value="1" name="report"  id="inlineFormmonthly_invoice"> Yearly</label>

					</div>
					</div>

					<div class="col-md-2">
					<div class="form-group">	
					<label class="" for="inlineFormInputGroupUsername2">Monthly:</label>
					<label class="form-control" for="inlineFormDelivered"><input class="form-check-input" {{(request()->has('report')) ? (request('report')==2) ? 'checked' : '' : 'checked' }} type="radio"  value="2" name="report" id="inlineFormDelivered"> Monthly</label>
					</div>
					</div>
					
					<div class="col-md-2">
					<div class="form-group">
					<label class="" for="inlineFormInputGroupUsername2">Weekly:</label>
					<label class="form-control" for="inlineFormDelivered2"><input class="form-check-input" {{(request()->has('report') && request('report')==3) ? 'checked' : '' }} type="radio"  value="3" name="report" id="inlineFormDelivered2"> Weekly</label>
					</div>
					</div>
					
					<div class="col-md-2">
					<div class="form-group">
					<label class="" for="inlineFormInputGroupUsername2">Daily:</label>
					<label class="form-control" for="inlineFormDelivered3"><input class="form-check-input" {{(request()->has('report') && request('report')==4) ? 'checked' : '' }} type="radio"  value="4" name="report" id="inlineFormDelivered3"> Daily</label>
					</div>
					</div>
					
					
					 <div class="col-md-2" style="margin-top:25px;">
					 <div class="form-group">
				
				   <a href="{{route('admin.reports.sales-report')}}" style="margin-left:3px;" class="btn btn-default pull-right">Reset</a>
				  
					<button type="submit" class="btn btn-primary pull-right">Submit</button>
					
					</div>
					</div>
					
					</div>
					</form>
					</div-->
					</div>
					
				</div>
                    
					@if(request()->has('report') && request('report')==3)
                        @include('admin.reports.partials.weekly_sales_reports')
				    @elseif(request()->has('report') && request('report')==1)
					    @include('admin.reports.partials.yearly_sales_reports')
					@elseif(request()->has('report') && request('report')==4)
					    @include('admin.reports.partials.daily_sales_reports')
					@else
						@include('admin.reports.partials.monthly_sales_reports')
					@endif
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                </div>
            </div>
            <!-- /.box -->
        

    </section>
    <!-- /.content -->
@endsection