@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.employees.store') }}" method="post" class="form">
                <div class="box-body">
                    <h2>Create Admin User</h2>
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="first_name">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" id="first_name" placeholder="First Name" class="form-control" value="{{ old('first_name') }}">
                    </div>
					<div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name" id="last_name" placeholder="Last Name" class="form-control" value="{{ old('last_name') }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon">@</span>
                            <input type="text" name="email" id="email" placeholder="Email" class="form-control" value="{{ old('email') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" placeholder="xxxxx" class="form-control">
                    </div>
					<div class="form-group">
                        <label for="password-confirm">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" id="password-confirm" placeholder="xxxxx" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="role">Role </label>
                        <select name="role" id="role" class="form-control select2">
                            <option></option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ ucfirst($role->display_name) }}</option>
                            @endforeach
                        </select>
                    </div>
					
                    @include('admin.shared.status-select', ['status' => 0])
					
					<div class="form-group send_mail_check">
                        <label for="password-confirm">Do you want to send welcome email to user?</label>
                        <input type="checkbox" name="send_mail" id="send_mail" value="1">
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <div class="btn-group">
                            <a onclick="window.history.go(-1);" class="btn btn-default">Back</a>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
