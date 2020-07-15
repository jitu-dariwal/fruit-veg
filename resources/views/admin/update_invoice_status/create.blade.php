@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        
        <div class="box">
            <form action="{{ route('admin.anomolies.store') }}" method="post" class="form">
                <div class="box-body">
                    <h2>Create Anomolie</h2>
                    {{ csrf_field() }}
					
					 
					
					  
					<div class="form-group">
                        <label for="anomolies_date">Anomolies Date <span class="text-danger">*</span> </label>
                        <input type="text" name="anomolies_date" id="anomolies_date" placeholder="dd-mm-yyyy"  class="form-control datepicker" readonly value="{{ old('anomolies_date',date('d-m-Y')) }}">
						 <span style="color:red;"> {{ $errors->first('anomolies_date') }}</span>
                    </div>
					
					<div class="form-group">
                        <label for="anomolies_points">Anomolies Points</label>
                        <textarea class="form-control " name="anomolies_points" id="anomolies_points" rows="5" placeholder="Enter Anomolies Points...">{{ old('anomolies_points') }}</textarea>
                    </div>
					
					
					<div class="form-group">
                        <label for="anomolies_points_reply">Anomolies Points Reply </label>
                        <textarea class="form-control " name="anomolies_points_reply" id="anomolies_points_reply" rows="5" placeholder="Enter Anomolies Points Reply ...">{{ old('anomolies_points_reply') }}</textarea>
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
