@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        
        <div class="box">
            <form action="{{ route('admin.communicationlog.store') }}" method="post" class="form">
                <div class="box-body">
                    <h2>Create Communication Log</h2>
                    {{ csrf_field() }}
					
					<div class="form-group">
                      <label for="CompanyName">Company Name <span style="color:red;">*</span></label>
                      <select name="CompanyName" id="CompanyName" class="form-control">
                            <option value="">---Select Company Name---</option>
							@foreach($companyNames as $companyName)
							<option @if($companyName->customers_id == old('CompanyName')) {{'selected'}} @endif value="{{$companyName->customers_id}}">{{$companyName->company_name}}</option>
							@endforeach
                    </select>
                    <span style="color:red;"> {{ $errors->first('CompanyName') }}</span>
                    </div>
					
					
					<div class="form-group">
                        <label for="CompanyContact">Company contact </label>
                        <input type="text" name="CompanyContact" id="CompanyContact" placeholder="Enter company contact"  class="form-control" value="{{ old('CompanyContact') }}">
						 <span style="color:red;"> {{ $errors->first('CompanyContact') }}</span>
                    </div>
					
				
					<div class="form-group">
                        <label for="AdminClerk">Admin Clerk </label>
                        <input type="text" name="AdminClerk" id="AdminClerk" placeholder="Enter admin clerk"  class="form-control" value="{{ old('AdminClerk') }}">
						 <span style="color:red;"> {{ $errors->first('AdminClerk') }}</span>
                    </div>
					
					
					
					
					<div class="form-group">
                        <label for="AmendedOrderDetails">Communication Details</label>
                        <textarea class="form-control ckeditor" name="AmendedOrderDetails" id="AmendedOrderDetails" rows="5" placeholder="Description">{{ old('AmendedOrderDetails') }}</textarea>
                    </div>
					 
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <a onclick="window.history.go(-1);" class="btn btn-default">Back</a>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
