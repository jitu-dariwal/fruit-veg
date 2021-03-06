@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        
        <div class="box">
            <form action="{{ route('admin.anomolies.update',$Anomolie->id) }}" method="post" class="form">
                <div class="box-body">
                    <h2>Create Anomolie</h2>
                    {{ csrf_field() }}
					
					 
					
					  
					<div class="form-group">
                        <label for="anomolies_date">Anomolies Date <span class="text-danger">*</span> </label>
                        <input type="text" name="anomolies_date" id="anomolies_date" placeholder="dd-mm-yyyy"  class="form-control datepicker" readonly value="{{ old('anomolies_date',date('d-m-Y',strtotime($Anomolie->anomolies_date))) }}">
						 <span style="color:red;"> {{ $errors->first('anomolies_date') }}</span>
                    </div>
					
					<div class="form-group">
                        <label for="anomolies_points">Anomolies Points <span class="text-danger">*</span></label>
                        <textarea class="form-control " name="anomolies_points" id="anomolies_points" rows="5" placeholder="Enter Anomolies Points...">{{ old('anomolies_points',$Anomolie->anomolies_points) }}</textarea>
						<span style="color:red;"> {{ $errors->first('anomolies_points') }}</span>
                    </div>
					
					
					<div class="form-group">
                        <label for="anomolies_points_reply">Anomolies Points Reply </label>
                        <textarea class="form-control " name="anomolies_points_reply" id="anomolies_points_reply" rows="5" placeholder="Enter Anomolies Points Reply ...">{{ old('anomolies_points_reply',$Anomolie->anomolies_points_reply) }}</textarea>
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
