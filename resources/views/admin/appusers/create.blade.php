@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.appusers.store') }}" method="post" class="form">
                <div class="box-body">
                    <h2>Create App User</h2>
                    {{ csrf_field() }}
		<div class="form-group">
                        <label for="first_name">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" id="last_name" placeholder="First Name" required="required" class="form-control" value="{{ old('first_name') }}">
                    </div>
					<div class="form-group">
                        <label for="first_name">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" id="last_name" placeholder="Last Name" required="required" class="form-control" value="{{ old('last_name') }}">
                    </div>
					<div class="form-group">
                        <label for="first_name">Email Address <span class="text-danger">*</span></label>
                        <input type="text" name="email" id="email" placeholder="Email Address" required="required" class="form-control" value="{{ old('email') }}">
                    </div>
					<div class="form-group">
                        <label for="first_name">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" placeholder="Password" required="required" class="form-control" value="{{ old('password') }}">
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
