@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.faq.update', $faq->id) }}" method="post" class="form" enctype="multipart/form-data">
                <div class="box-body">
                    <h2>Edit Faq</h2>
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="put">
                    <div class="form-group">
                        <label for="name">Question <span class="text-danger">*</span></label>
                        <input type="text" name="question" id="question" placeholder="Enter question" class="form-control" value="{{ old('question', $faq->question) }}">
                    </div>
					<div class="form-group">
                        <label for="name">
							Answer <span class="text-danger">*</span>
						</label>
                        <textarea name="answer" id="answer" placeholder="Enter answer" class="form-control" rows="10">{!! old('answer', $faq->answer) !!}</textarea>
                    </div>
					<div class="form-group">
						@include('admin.shared.status-select', ['status' => old('status', $faq->status)])
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
