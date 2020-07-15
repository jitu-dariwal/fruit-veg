@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.customersgroup.store') }}" method="post" class="form">
                <div class="box-body">
                    <h2>Create Customer Group</h2>
                    {{ csrf_field() }}
					
					<div class="form-group">
                        <label for="group_name">Group Name <span class="text-danger">*</span></label>
                        <input type="text" name="group_name" id="group_name" placeholder="Group Name" required="required" class="form-control" value="{{ old('group_name') }}">
                    </div>
					<div class="form-group">
                        <label for="group_name">Description</label>
                        <textarea class="form-control ckeditor" name="description" id="description" rows="5" placeholder="Description">{{ old('description') }}</textarea>
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
