@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <form action="{{ route('admin.employee.profile.update', $employee->id) }}" method="post" class="form">
            <input type="hidden" name="_method" value="put">
            {{ csrf_field() }}
            <!-- Default box -->
            <div class="box">
			<form action="{{ route('admin.employees.store') }}" method="post" class="form">
                <div class="box-body">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="first_name">First Name <span class="text-danger">*</span></label>
                        <input name="first_name" type="text" class="form-control" value="{{ $employee->first_name }}">
                    </div>
					<div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input name="last_name" type="text" class="form-control" value="{{ $employee->last_name }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon">@</span>
                            <input name="email" type="email" class="form-control" value="{{ $employee->email }}">
                        </div>
                    </div>
					<div class="form-group">
                        <label for="password">Password</label>
                        <input name="password" type="password" class="form-control" placeholder="xxxxxx">
                    </div>
					<div class="form-group">
                        <label for="password-confirm">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password-confirm" placeholder="xxxxx" class="form-control">
                    </div>
                  </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <a onclick="window.history.go(-1);" class="btn btn-default btn-sm">Back</a>
                        <button class="btn btn-success btn-sm" type="submit"> <i class="fa fa-save"></i> Save</button>
                    </div>
                </div>
            </form>
			
			</div>
            <!-- /.box -->
        </form>

    </section>
    <!-- /.content -->
@endsection
