@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.pages.store') }}" method="post" class="form" enctype="multipart/form-data">
                <div class="box-body">
                    <h2>Create Page</h2>
                    {{ csrf_field() }}
                    
                    <div class="form-group">
                        <label for="page_name">Page Name <span class="text-danger">*</span></label>
                        <input type="text" name="page_name" id="page_name" placeholder="Page Name" required="required" class="form-control" value="{{ old('page_name') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="page_title">Page Title <span class="text-danger">*</span></label>
                        <input type="text" name="page_title" id="page_title_pages" placeholder="Page Title" required="required" class="form-control" value="{{ old('page_title') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="page_slug">Page Alias <span class="text-danger">*</span></label>
                        <input type="text" name="page_slug" id="page_slug" id="page_slug" placeholder="Page Slug" required="required" class="form-control" value="{{ old('page_slug') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="page_content">Page content</label>
						@php
							$pages_content = str_replace("[app_url]", url('/') , old('page_content'));
						@endphp
                        <textarea class="form-control ckeditor" name="page_content" id="page_content" rows="5" placeholder="Description">{{ $pages_content }}</textarea>
                    </div>
                    
					@include('layouts.admin.meta-tags')
					
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
