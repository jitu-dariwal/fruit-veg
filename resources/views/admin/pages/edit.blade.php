@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.pages.update', $pages->id) }}" method="post" class="form" enctype="multipart/form-data">
                <div class="box-body">
                    <h2>Edit Page</h2>
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="put">
					
                    <div class="form-group">
                        <label for="page_name">Page Name <span class="text-danger">*</span></label>
                        <input type="text" name="page_name" id="page_name" placeholder="Page Name" required="required" class="form-control" value="{{ $pages->name }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="page_title">Page Title <span class="text-danger">*</span></label>
                        <input type="text" name="page_title" id="page_title" placeholder="Page Title" required="required" class="form-control" value="{{ $pages->title }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="page_slug">Page Alias <span class="text-danger">*</span></label>
                        <input type="text" name="page_slug" id="page_slug" placeholder="Page Slug" required="required" class="form-control" readonly="readonly" value="{{ $pages->slug }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="page_content">Page content</label>
						@php
							$pages->content = str_replace("[app_url]", url('/') , $pages->content);
						@endphp
                        <textarea class="form-control ckeditor" name="page_content" id="page_content" rows="5" placeholder="Description">{{ $pages->content }}</textarea>
                    </div>
					
					<fieldset class="fleldset-block">
						<legend>Meta Tag Information</legend>
						<div class="form-group">
							<label for="name">Meta Title </label>
							<input type="text" name="meta_title" id="meta_title" placeholder="Meta Title" class="form-control" value="{!! $pages->meta_title ?: old('meta_title') !!}">
						</div>
						<div class="row">

							<div class="col-sm-6">
								<div class="form-group">
									<label for="description">Meta Description </label>
									@php
										$pages->meta_description = str_replace("[app_url]", url('/') , $pages->meta_description);
									@endphp
									<textarea class="form-control" name="meta_description" id="meta_description" rows="5" placeholder="Meta Description">{!! $pages->meta_description ?: old('meta_description') !!}</textarea>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="name">Meta Keyword </label>
									<textarea name="meta_keyword" id="meta_keyword" rows="5" placeholder="Meta Keyword" class="form-control">{!! $pages->meta_keyword ?: old('meta_keyword') !!}</textarea>
								</div>
							</div>
					</fieldset>
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
