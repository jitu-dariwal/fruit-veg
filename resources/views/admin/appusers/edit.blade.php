@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.appusers.update', $app_users->app_user_id) }}" method="post" class="form" enctype="multipart/form-data">
                <div class="box-body">
                    <h2>Edit App User</h2>
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="put">
					
                    <div class="form-group">
                        <label for="first_name">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" id="last_name" placeholder="First Name" required="required" class="form-control" value="{{ $app_users->first_name }}">
                    </div>
					<div class="form-group">
                        <label for="first_name">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" id="last_name" placeholder="Last Name" required="required" class="form-control" value="{{ $app_users->last_name }}">
                    </div>
					<div class="form-group">
                        <label for="first_name">Email Address <span class="text-danger">*</span></label>
                        <input type="text" name="email" id="email" placeholder="Email Address" required="required" class="form-control" value="{{ $app_users->email_address }}">
                    </div>
					<div class="form-group">
                        <label for="first_name">Password <span class="text-danger">*</span></label>
                        <input type="text" name="password" id="password" placeholder="Password" required="required" class="form-control" value="{{ $app_users->password }}">
                    </div>
					
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <a onclick="window.history.go(-1);" class="btn btn-default btn-sm">Back</a>
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
