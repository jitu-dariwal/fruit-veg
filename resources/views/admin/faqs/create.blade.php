@extends('layouts.admin.app')

@section('content')
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
			<form action="{{ route('admin.faq.store') }}" method="post" class="form" enctype="multipart/form-data">
				{{ csrf_field() }}
                <div class="box-body">
                    <h2>Create Faq</h2>
                    <div class="form-group">
                        <label for="name">
							Question <span class="text-danger">*</span>
						</label>
                        <input type="text" name="question" id="question" placeholder="Enter question" class="form-control" value="{{ old('question') }}">
                    </div>
					<div class="form-group">
                        <label for="name">
							Answer <span class="text-danger">*</span>
						</label>
                        <textarea name="answer" id="answer" placeholder="Enter answer" class="form-control" rows="10">{!! old('answer') !!}</textarea>
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
