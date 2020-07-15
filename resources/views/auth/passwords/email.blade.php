@extends('layouts.front.account-app')

@section('content')
	<!-- Main content -->
	
	<header>
		<h2 class="sub-heading text-center mb-0 d-none d-md-block">Reset Password Link</h2>
		<h2 class="sub-heading text-center mb-0 d-block d-md-none">Reset Password Link</h2>
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
                <form class="form-horizontal account-form" role="form" method="POST" action="{{ route('password.email') }}">
					@csrf
					<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
						<label for="email" class="col-md-4 control-label">E-Mail Address <span class="text-danger">*</span></label>

						<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Enter email address" data-validation="required email length" data-validation-length="1-255">

						@if ($errors->has('email'))
							<span class="help-block text-danger">
								<strong>{{ $errors->first('email') }}</strong>
							</span>
						@endif
					</div>
					
					<div class="float-lg-right mt-3 mt-lg-0">
						<a href="{{ route('login') }}" class="btn site-btn  mb-2">Back to login</a>
						<button type="submit" class="btn site-btn  mb-2">Send Password Reset Link <span class="ds-right-arrow ml-1"></span></button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection
