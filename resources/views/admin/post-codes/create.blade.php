@extends('layouts.admin.app')

@section('content')
	<section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
			<form action="{{ route('admin.post-code.store') }}" method="post" class="form" enctype="multipart/form-data">
				{{ csrf_field() }}
                <div class="box-body">
                    <h2>Create Post Code</h2>
                    <div class="form-group">
                        <label for="name">
							Title <span class="text-danger">*</span>
						</label>
                        <input type="text" name="title" id="title" placeholder="Enter title" class="form-control" value="{{ old('title') }}">
                    </div>
					<div class="form-group">
                        <label for="name">
							Enable Week Days <span class="text-danger">*</span>
						</label>
						<div class="row border m-1 p-3">
							@foreach(config('constants.week_days') as $k=>$v)
								<div class="col-12 col-sm-8 col-md-6 col-lg-4 col-xl-3">
									@php
										$checked = '';
										if(is_array(old('week_days')) && in_array($k,old('week_days'))){
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
							Post Codes <span class="text-danger">*</span>
						</label>
                        <textarea name="post_codes" id="post_codes" placeholder="Enter Post Codes" class="form-control" rows="10">{!! old('post_codes') !!}</textarea>
                    </div>
					<div class="form-group">
						@include('admin.shared.status-select', ['status' => old('status')])
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
