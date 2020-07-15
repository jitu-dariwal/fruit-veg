@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.post-code.update', $postCode->id) }}" method="post" class="form" enctype="multipart/form-data">
                <div class="box-body">
                    <h2>Edit Post Code</h2>
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="put">
                    <div class="form-group">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" placeholder="Enter title" class="form-control" value="{{ old('title', $postCode->title) }}">
                    </div>
					<div class="form-group">
                        <label for="name">
							Enable Week Days <span class="text-danger">*</span>
						</label>
						<div class="row border m-1 p-3">
							@php
								$postCode->week_days = explode(',', $postCode->week_days);
							@endphp
							@foreach(config('constants.week_days') as $k=>$v)
								<div class="col-12 col-sm-8 col-md-6 col-lg-4 col-xl-3">
									@php
										$checked = '';
										if(is_array(old('week_days' ,$postCode->week_days)) && in_array($k,old('week_days' ,$postCode->week_days))){
											$checked = 'checked="checked"';
										}
									@endphp
									<input type="checkbox" name="week_days[]" id="week_days" value="{{ $k }}" {{$checked}}/> {{$v}}
								</div>
							@endforeach
						</div>
                    </div>
					<div class="form-group">
                        <label for="name">
							Post codes <span class="text-danger">*</span>
						</label>
                        <textarea name="post_codes" id="post_codes" placeholder="Enter post codes" class="form-control" rows="10">{!! old('post_codes', $postCode->post_codes) !!}</textarea>
                    </div>
					<div class="form-group">
						@include('admin.shared.status-select', ['status' => old('status', $postCode->status)])
					</div>
                </div>
                
                <div class="box-footer">
                    <div class="btn-group">
                        <a onclick="window.history.go(-1);" class="btn btn-default">Back</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
