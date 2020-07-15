@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.employees.update', $employee->id) }}" method="post" class="form">
                <div class="box-body">
                    <h2>Edit Admin User</h2>
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="put">
                    <div class="form-group">
                        <label for="first_name">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" id="first_name" placeholder="First Name" class="form-control" value="{!! $employee->first_name ?: old('first_name')  !!}">
                    </div>
					<div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name" id="last_name" placeholder="Last Name" class="form-control" value="{!! $employee->last_name ?: old('last_name')  !!}">
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon">@</span>
                            <input type="text" name="email" id="email" placeholder="Email" class="form-control" value="{!! $employee->email ?: old('email')  !!}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="xxxxx" class="form-control">
                    </div>
					<div class="form-group">
                        <label for="password-confirm">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password-confirm" placeholder="xxxxx" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="roles">Role </label>
                        <select name="role" id="role" class="form-control select2">
                            @foreach($roles as $role)
                                <option @if(in_array($role->id, $selectedIds))selected="selected" @endif value="{{ $role->id }}">{{ $role->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @include('admin.shared.status-select', ['status' => $employee->status])
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
