@extends('layouts.admin.app')

@section('content')
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.dashboardSectionsSave') }}" method="post" class="form" enctype="multipart/form-data">
                <div class="box-body">
                    <h2>Create Dashboard Section</h2>
                    {{ csrf_field() }}
                    
                    <div class="form-group">
                        <label for="page_name">Name <span class="text-danger">*</span></label>
                        <input type="text" name="page_name" id="page_name" placeholder="Section Name" required="required" class="form-control" value="{{ old('page_name') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="page_title">Title <span class="text-danger">*</span></label>
                        <input type="text" name="page_title" id="page_title_pages" placeholder="Section Title" required="required" class="form-control" value="{{ old('page_title') }}">
                    </div>
                    
					<div class="form-group">
						<label for="banner_image">Banner Image</label>
                        <input type="file" name="banner_image" accept="image/*">
					</div>
                    
                    <div class="form-group">
                        <label for="banner_text">Banner Text <span class="text-danger">*</span></label>
                        <input type="text" name="banner_text" id="banner_text" placeholder="Banner Text" class="form-control" value="{{ old('banner_text') }}">
                    </div>
                    
                    <div class="form-group">
						@php
							$pages_content = str_replace("[app_url]", url('/') , old('page_content'));
						@endphp
                        <label for="page_content">Section content</label>
                        <textarea class="form-control ckeditor" name="page_content" id="page_content" rows="5" placeholder="Description">{{ $pages_content }}</textarea>
                    </div>
				</div>
                <div class="box-footer">
                    <div class="btn-group">
                        <a onclick="window.history.go(-1);" class="btn btn-default">Back</a>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
