@extends('layouts.admin.app')

@section('content')
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.dashboardSectionsUpdate', $pages->id) }}" method="post" class="form" enctype="multipart/form-data">
                <div class="box-body">
                    <h2>Edit Dashboard Section</h2>
                    {{ csrf_field() }}
                    
                    <div class="form-group">
                        <label for="page_name">Name <span class="text-danger">*</span></label>
                        <input type="text" name="page_name" id="page_name" placeholder="Section Name" required="required" class="form-control" value="{{ $pages->name }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="page_title">Title <span class="text-danger">*</span></label>
                        <input type="text" name="page_title" id="page_title" placeholder="Section Title" required="required" class="form-control" value="{{ $pages->title }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="banner_image">Banner Image</label>
                        <input type="file" name="banner_image" accept="image/*"><br />
						@if(!empty($pages->banner_image))
							<img class="img-responsive" src="{{asset("uploads/$pages->banner_image")}}" />
						@endif
                     </div>
                    
                    <div class="form-group">
                        <label for="banner_text">Banner Text <span class="text-danger">*</span></label>
                        <input type="text" name="banner_text" id="banner_text" placeholder="Banner Text" class="form-control" value="{{ $pages->banner_text }}">
                    </div>
                    
                    <div class="form-group">
						@php
							$pages->content = str_replace("[app_url]", url('/') , $pages->content);
						@endphp
                        <label for="page_content">Section content</label>
                        <textarea class="form-control ckeditor" name="page_content" id="page_content" rows="5" placeholder="Description">{{ $pages->content }}</textarea>
                    </div>
				</div>	
                <div class="box-footer">
                    <div class="btn-group">
                        <a onclick="window.history.go(-1);" class="btn btn-default btn-sm">Back</a>
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
