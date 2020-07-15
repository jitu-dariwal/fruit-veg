@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.issuelog.store') }}" method="post" class="form">
                <div class="box-body">
                    <h2>Create Issue Log</h2>
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
                        <label for="OrderNumber">Order Number <span class="text-danger">*</span></label>
                        <input type="text" name="OrderNumber" id="OrderNumber" placeholder="Enter order number"  class="form-control" value="{{ old('OrderNumber') }}">
						 <span style="color:red;"> {{ $errors->first('OrderNumber') }}</span>
                    </div>
					
					<div class="form-group">
                        <label for="AdminClerk">Admin Clerk </label>
                        <input type="text" name="AdminClerk" id="AdminClerk" placeholder="Enter admin clerk"  class="form-control" value="{{ old('AdminClerk') }}">
						 <span style="color:red;"> {{ $errors->first('AdminClerk') }}</span>
                    </div>
					
					<div class="form-group">
                        <label for="date1">Date <span class="text-danger">*</span> (calendar format) </label>
                        <input type="text" name="date1" id="date1" placeholder="dd-mm-yyyy"  class="form-control datepicker" readonly value="{{ old('date1') }}">
						 <span style="color:red;"> {{ $errors->first('date1') }}</span>
                    </div>
					
					<div class="form-group">
                      <label for="NatureOfIssue">Nature Of Issue <span style="color:red;">*</span></label>
                      <select name="NatureOfIssue" id="NatureOfIssue" class="form-control">
                            <option value="">---Select Nature Of Issue---</option>
							
							@foreach(Config::get('constants.NatureOfIssue') as $nKey => $NatureOfIssue)
                           <option @if( $nKey == old('NatureOfIssue')) {{'selected'}} @endif value="{{$nKey}}">{{$NatureOfIssue}}</option>
							@endforeach
                    </select>
                    <span style="color:red;"> {{ $errors->first('NatureOfIssue') }}</span>
                    </div>
					
					<div class="form-group">
                        <label for="Responsibility">Responsibility  </label>
                        <input type="text" name="Responsibility" id="Responsibility" placeholder="Enter responsibility"  class="form-control" value="{{ old('Responsibility') }}">
						 <span style="color:red;"> {{ $errors->first('Responsibility') }}</span>
                    </div>
					
					<div class="form-group">
                        <label for="Resolution">Resolution   </label>
                        <input type="text" name="Resolution" id="Resolution" placeholder="Enter resolution"  class="form-control" value="{{ old('Resolution') }}">
						 <span style="color:red;"> {{ $errors->first('Resolution') }}</span>
                    </div>
					
					<div class="form-group">
                        <label for="FinancialImplication">Financial Implication (£)    </label>
                        <input type="text" name="FinancialImplication" id="FinancialImplication" placeholder="Enter financial implication (£)"  class="form-control" value="{{ old('FinancialImplication') }}">
						 <span style="color:red;"> {{ $errors->first('FinancialImplication') }}</span>
                    </div>
					
					<div class="form-group">
                      <label for="LossType">Loss Type <span style="color:red;">*</span></label>
                      <select name="LossType" id="LossType" class="form-control">
                            <option value="">---Select Loss Type---</option>
							@foreach(Config::get('constants.LossType') as $lKey => $LossType)
                           <option @if( $lKey == old('LossType')) {{'selected'}} @endif value="{{$lKey}}">{{$LossType}}</option>
							@endforeach
                    </select>
                    <span style="color:red;"> {{ $errors->first('LossType') }}</span>
                    </div>
					
					
					<div class="form-group">
                        <label for="Details">Details</label>
                        <textarea class="form-control ckeditor" name="Details" id="Details" rows="5" placeholder="Description">{{ old('Details') }}</textarea>
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
