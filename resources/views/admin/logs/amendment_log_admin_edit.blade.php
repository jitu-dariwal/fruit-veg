@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        
        <div class="box">
            <form action="{{ route('admin.amendmentlogreport.update',$AmendmentLogAdmin->id) }}" method="post" class="form">
                <div class="box-body">
                    <h2>Update Amendment Log Report</h2>
                    {{ csrf_field() }}
					
					<div class="form-group">
                      <label for="CompanyName">Company Name <span style="color:red;">*</span></label>
                      <select name="CompanyName" id="CompanyName" class="form-control">
                            <option value="">---Select Company Name---</option>
							@foreach($companyNames as $companyName)
							<option @if($companyName->customers_id == old('CompanyName',$AmendmentLogAdmin->CompanyName)) {{'selected'}} @endif value="{{$companyName->customers_id}}">{{$companyName->company_name}}</option>
							@endforeach
                    </select>
                    <span style="color:red;"> {{ $errors->first('CompanyName') }}</span>
                    </div>
					
					
					<div class="form-group">
                        <label for="CompanyContact">Company contact </label>
                        <input type="text" name="CompanyContact" id="CompanyContact" placeholder="Enter company contact"  class="form-control" value="{{ old('CompanyContact',$AmendmentLogAdmin->CompanyContact) }}">
						 <span style="color:red;"> {{ $errors->first('CompanyContact') }}</span>
                    </div>
					
					
					<div class="form-group">
                        <label for="AdminClerk">Admin Clerk </label>
                        <input type="text" name="AdminClerk" id="AdminClerk" placeholder="Enter admin clerk"  class="form-control" value="{{ old('AdminClerk',$AmendmentLogAdmin->AdminClerk) }}">
						 <span style="color:red;"> {{ $errors->first('AdminClerk') }}</span>
                    </div>
					
					<div class="form-group">
                        <label for="NewOrderDate">New Order Date 1 </label>
                        <input type="text" name="NewOrderDate" id="NewOrderDate" placeholder="dd-mm-yyyy"  class="form-control datepicker" readonly value="{{ old('NewOrderDate',$AmendmentLogAdmin->NewOrderDate) }}">
						 <span style="color:red;"> {{ $errors->first('NewOrderDate') }}</span>
                    </div>
					
					<div class="form-group">
                        <label for="NewOrderDate2">New Order Date 2 </label>
                        <input type="text" name="NewOrderDate2" id="NewOrderDate2" placeholder="dd-mm-yyyy"  class="form-control datepicker" readonly value="{{ old('NewOrderDate2',$AmendmentLogAdmin->NewOrderDate2) }}">
						 <span style="color:red;"> {{ $errors->first('NewOrderDate2') }}</span>
                    </div>
					
					<div class="form-group">
                        <label for="NewOrderDate3">New Order Date 3 </label>
                        <input type="text" name="NewOrderDate3" id="NewOrderDate3" placeholder="dd-mm-yyyy"  class="form-control datepicker" readonly value="{{ old('NewOrderDate3',$AmendmentLogAdmin->NewOrderDate3) }}">
						 <span style="color:red;"> {{ $errors->first('NewOrderDate3') }}</span>
                    </div>
					
					<div class="form-group">
                        <label for="NewOrderDate4">New Order Date 4 </label>
                        <input type="text" name="NewOrderDate4" id="NewOrderDate4" placeholder="dd-mm-yyyy"  class="form-control datepicker" readonly value="{{ old('NewOrderDate4',$AmendmentLogAdmin->NewOrderDate4) }}">
						 <span style="color:red;"> {{ $errors->first('NewOrderDate4') }}</span>
                    </div>
					
					<div class="form-group">
                        <label for="NewOrderDate5">New Order Date 5 </label>
                        <input type="text" name="NewOrderDate5" id="NewOrderDate5" placeholder="dd-mm-yyyy"  class="form-control datepicker" readonly value="{{ old('NewOrderDate5',$AmendmentLogAdmin->NewOrderDate5) }}">
						 <span style="color:red;"> {{ $errors->first('NewOrderDate5') }}</span>
                    </div>
					
					<div class="form-group">
                        <label for="CompanyContact">Cancellation  </label>
                       <br>
						<label class="radio-inline">
						<input type="radio" name="Cancellation"  @if(old('Cancellation',$AmendmentLogAdmin->Cancellation) == 'yes') {{'checked'}} @endif  value="yes" >Yes
						</label>

						<label class="radio-inline">
						<input type="radio" name="Cancellation" @if(old('Cancellation',$AmendmentLogAdmin->Cancellation) != 'yes') {{'checked'}} @endif value="no" >No
						</label>
                    </div>
					
					
					<div class="form-group">
                        <label for="AmendedOrderDetails">Amended Order Details</label>
                        <textarea class="form-control ckeditor" name="AmendedOrderDetails" id="AmendedOrderDetails" rows="5" placeholder="Description">{{ old('AmendedOrderDetails',$AmendmentLogAdmin->AmendedOrderDetails) }}</textarea>
                    </div>
					 
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <a onclick="window.history.go(-1);" class="btn btn-default">Back</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
