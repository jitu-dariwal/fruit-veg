@extends('layouts.front.account-app')

@section('content')
	<!-- Main content -->
	
	<header>
		<h2 class="sub-heading text-center mb-0 d-none d-md-block">Reset Password</h2>
		<h2 class="sub-heading text-center mb-0 d-block d-md-none">Reset Password</h2>
	</header>
	
	<div class="row">
		<div class="col-md-12 col-sm-12">
			@if (session('status'))
				<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Success!</strong> {{ session('status') }}
				</div>
			@endif
				
			@if (session('warning'))
				<div class="alert alert-warning alert-dismissible">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Warning!</strong> {{ session('warning') }}
				</div>
			@endif
		</div>
		
		<!-- Registration form -->
		<div class="col-md-12 col-sm-12">
			<div class="pl-0 pl-lg-3">
				<form class="form-horizontal account-form" role="form" method="POST" action="{{ route('password.request') }}">
					@csrf
					<input type="hidden" name="token" value="{{ $token }}">
					<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
						<label for="email" class="col-md-4 control-label">E-Mail Address <span class="text-danger">*</span></label>

						<input id="email" type="email" class="form-control" name="email" value="{{ old('email', $email) }}" placeholder="Enter email address" data-validation="required email length" data-validation-length="1-255" readonly />
						@if ($errors->has('email'))
							<span class="help-block text-danger">{!! $errors->first('email') !!}</span>
						@endif
					</div>
					<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
						<label for="password" class="col-md-4 control-label">New Password<span class="text-danger">*</span></label>

						<input id="password" type="password" class="form-control" name="password" value="" placeholder="*********" data-validation="required length custom1" data-validation-length="min6" data-validation-regexp="^(?=.*[\w])(?=.*[\W])[\w\W]{6,}$" data-validation-error-msg-custom="Password contains <li>At least one lowercase</li><li>At least one uppercase</li><li>At least one digit</li><li>At least one special character</li><li>At least it should have 6 characters long</li>.">

						@if ($errors->has('password'))
							<span class="help-block text-danger">{!! $errors->first('password') !!}</span>
						@endif
					</div>
					<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
						<label for="password_confirmation" class="col-md-4 control-label">Confirm Password <span class="text-danger">*</span></label>

						<input id="password_confirmation" type="password" class="form-control" name="password_confirmation" value="" placeholder="*********" data-validation="required length confirmation" data-validation-length="min6"  data-validation-confirm="password" data-validation-error-msg-confirmation="The Confirmation Password must match your Password">

						@if ($errors->has('password_confirmation'))
							<span class="help-block text-danger">{!! $errors->first('password_confirmation') !!}							</span>
						@endif
					</div>
					<div class="float-lg-right mt-3 mt-lg-0">
						<a href="{{ route('login') }}" class="btn site-btn  mb-2">Back to login</a>
						<button type="submit" class="btn site-btn  mb-2">Submit <span class="ds-right-arrow ml-1"></span></button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection
