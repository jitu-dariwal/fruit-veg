@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.bankholidays.update', $bankholiday->id) }}" method="post" class="form" enctype="multipart/form-data">
                <div class="box-body">
                    <h2>Edit Bankholiday</h2>
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="put">
					<div class="form-group">
                        <label for="name">Holiday Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" placeholder="Holiday Name" class="form-control" value="{{$bankholiday->name}}" required="required">
                    </div>
                    <div class="form-group">
                        <label for="name">Holiday Date <span class="text-danger">*</span></label>
                        
                        <input type="text" name="holiday_date" id="date" placeholder="Holiday Date" class="form-control datepicker" value="{{date("d-m-Y", strtotime($bankholiday->holiday_date))}}" required="required">
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
