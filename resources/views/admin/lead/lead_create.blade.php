@extends('layouts.admin.app')

@section('content')
	
 

    <!-- Main content -->
    <section class="content">
        
        <div class="box">
            <form action="{{ route('admin.lead.store') }}" method="post" class="form">
                <div class="box-body">
                    <h2>Create Lead	</h2>
                    {{ csrf_field() }}
					
					
					
					<div class="form-group">
                        <label for="SalesClerk_ID">Sales Clerk </label>
                        <input type="text" readonly name="SalesClerk_ID" id="SalesClerk_ID" placeholder="Enter Sales Clerk"  class="form-control" value="{{ Auth::guard('employee')->user()->first_name.' '.Auth::guard('employee')->user()->last_name}}">
						 <span style="color:red;"> {{ $errors->first('SalesClerk_ID') }}</span>
                    </div>
					
					<div class="form-group">
                        <label for="created_at">Date  </label>
                        <input type="text" name="created_at" readonly id="created_at" placeholder="Enter order number"  class="form-control" value="{{ date('Y-m-d H:i:s',time()) }}">
						 <span style="color:red;"> {{ $errors->first('created_at') }}</span>
                    </div>
					
					<div class="form-group">
                        <label for="ClientName">Client Name </label>
                        <input type="text" name="ClientName" id="ClientName" placeholder="Enter Client Name"  class="form-control" value="{{ old('ClientName') }}">
						 <span style="color:red;"> {{ $errors->first('ClientName') }}</span>
                    </div>
					
					<div class="form-group">
                        <label for="Company">Company <span style="color:red;">*</span></label>
                        <input type="text" name="Company" id="Company" placeholder="Enter Client Name"  class="form-control" value="{{ old('Company') }}">
						 <span style="color:red;"> {{ $errors->first('Company') }}</span>
                    </div>
				
					
					<div class="form-group">
                        <label for="Tel_1">Tel 1  <span style="color:red;">*</span></label>
                        <input type="text" name="Tel_1" id="Tel_1" placeholder="Enter Tel 1"  class="form-control" value="{{ old('Tel_1') }}">
						 <span style="color:red;"> {{ $errors->first('Tel_1') }}</span>
                    </div>
					
					
					<div class="form-group">
                        <label for="Tel_2">Tel 2  </label>
                        <input type="text" name="Tel_2" id="Tel_2" placeholder="Enter Tel 1"  class="form-control" value="{{ old('Tel_2') }}">
						 <span style="color:red;"> {{ $errors->first('Tel_2') }}</span>
                    </div>
					
					<div class="form-group">
                        <label for="eMail">E-Mail  </label>
                        <input type="text" name="eMail" id="eMail" placeholder="Enter E-Mail"  class="form-control" value="{{ old('eMail') }}">
						 <span style="color:red;"> {{ $errors->first('eMail') }}</span>
                    </div>
					
					<div class="form-group">
                        <label for="Address1">Address 1  </label>
                        <input type="text" name="Address1" id="Address1" placeholder="Enter Address 1"  class="form-control" value="{{ old('Address1') }}">
						 <span style="color:red;"> {{ $errors->first('Address1') }}</span>
                    </div>
					
					
					<div class="form-group">
                        <label for="Address2">Address 2  </label>
                        <input type="text" name="Address2" id="Address2" placeholder="Enter Address 2"  class="form-control" value="{{ old('Address2') }}">
						 <span style="color:red;"> {{ $errors->first('Address2') }}</span>
                    </div>
					
					<div class="form-group">
                        <label for="Town">Town   </label>
                        <input type="text" name="Town" id="Town" placeholder="Enter Address 2"  class="form-control" value="{{ old('Town') }}">
						 <span style="color:red;"> {{ $errors->first('Town') }}</span>
                    </div>
					
					
					<div class="form-group">
                        <label for="County">County    </label>
                        <input type="text" name="County" id="County" placeholder="Enter Address 2"  class="form-control" value="{{ old('County') }}">
						 <span style="color:red;"> {{ $errors->first('County') }}</span>
                    </div>
					
					<div class="form-group">
                        <label for="Postcode">Postcode    </label>
                        <input type="text" name="Postcode" id="Postcode" placeholder="Enter Address 2"  class="form-control" value="{{ old('Postcode') }}">
						 <span style="color:red;"> {{ $errors->first('Postcode') }}</span>
                    </div>
					
					
					
					<div class="form-group">
                      <label for="status">Status <span style="color:red;">*</span></label>
                      <select name="status" id="status" class="form-control">
						<option value="" >---Select Status---</option>
						@foreach(Config('constants.LeadStatusMost') as $leadKey => $LeadStatus)
						<option @if($leadKey == request()->input('status'))selected @endif  value="{{$leadKey}}"> {{$LeadStatus}}</option>
						@endforeach
                    </select>
                    <span style="color:red;"> {{ $errors->first('status') }}</span>
                    </div>
					
					
					<div class="form-group">
                      <label for="Hear_About_Us">Where did you hear about us </label>
                      <select name="Hear_About_Us" id="Hear_About_Us" class="form-control">
						@foreach(Config('constants.Hear_About_Us') as $hearKey => $Hear_About_U)
						<option @if($hearKey == request()->input('Hear_About_Us'))selected @endif  value="{{$hearKey}}">{{$Hear_About_U}}</option>
						@endforeach
                    </select>
                    <span style="color:red;"> {{ $errors->first('Hear_About_Us') }}</span>
                    </div>
					
					<div class="form-group col-md-6">
                        <label for="ArrangeCallBackAlertDate">Arrange Call Back Alert Date    <span style="color:red;">*</span></label>
                        <input type="text" name="ArrangeCallBackAlertDate" id="ArrangeCallBackAlertDate" readonly placeholder="dd-mm-yyyy"  class="form-control datepicker" value="{{ old('ArrangeCallBackAlertDate',date('d-m-Y')) }}">
						 <span style="color:red;"> {{ $errors->first('ArrangeCallBackAlertDate') }}</span>
                    </div>
					
					<div class="form-group col-md-6">
                        <label for="ArrangeCallBackAlertTime">Arrange Call Back Alert Time    <span style="color:red;">*</span></label>
                        <input type="text" name="ArrangeCallBackAlertTime" id="ArrangeCallBackAlertTime" readonly placeholder="dd-mm-yyyy"  class="form-control timepicker" value="{{ old('ArrangeCallBackAlertTime',date('h:m A')) }}">
						 <span style="color:red;"> {{ $errors->first('ArrangeCallBackAlertTime') }}</span>
                    </div>
					
					
					
					
					
					<div class="form-group">
                        <label for="Enquiry">Enquiry </label>
                        <textarea class="form-control ckeditor" name="Enquiry" id="Enquiry" rows="5" placeholder="Description">{{ old('Enquiry') }}</textarea>
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
@section('js')
<link rel="stylesheet" href="{{asset('css/bootstrap-timepicker.min.css')}}">
<script src="{{asset('js/bootstrap-timepicker.min.js')}}"></script>

<script>
$(document).ready(function(){
$('.timepicker').timepicker({
      showInputs: false
    });
});


</script>
@endsection