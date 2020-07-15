@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.categories.store') }}" method="post" class="form" enctype="multipart/form-data">
                <div class="box-body">
                    <h2>Create Category</h2>
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="parent">Parent Category</label>
                        <select name="parent" id="parent" class="form-control select2">
							<option value="0">Parent</option>
                            @foreach($categories as $category)
								<option value="{{ $category->id }}">{{ $category->name }}</option>
							@endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" placeholder="Name" class="form-control" value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="description">Description </label>
                        <textarea class="form-control ckeditor" name="description" id="description" rows="5" placeholder="Description">{{ old('description') }}</textarea>
                    </div>
                    
					 <div class="form-group">
                        <label for="cover">Category Image </label>
                        <input type="file" name="cover" id="cover" class="form-control">
					</div>
                   
		    <div class="form-group">
                        <label for="name">Meta Title </label>
                        <input type="text" name="meta_title" id="meta_title" placeholder="Meta Title" class="form-control" value="{{ old('meta_title') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Meta Description </label>
                        <textarea class="form-control ckeditor" name="meta_description" id="meta_description" rows="5" placeholder="Meta Description">{{ old('meta_description') }}</textarea>
                    </div>
					<div class="form-group">
                        <label for="name">Meta Keyword </label>
                        <input type="text" name="meta_keyword" id="meta_keyword" placeholder="Meta Keyword" class="form-control" value="{{ old('meta_keyword') }}">
                    </div>
		    <div class="form-group">
                        <label for="status">Status </label>
                        <select name="status" id="status" class="form-control">
                            <option value="1">Enable</option>
                            <option value="0">Disable</option>
                        </select>
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
