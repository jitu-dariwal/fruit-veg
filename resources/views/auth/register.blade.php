@extends('layouts.front.account-app')

@section('content')
	<!-- Main content -->
	
	<header>
		<h2 class="sub-heading text-center mb-0 d-none d-md-block">Create account</h2>
		<h2 class="sub-heading text-center mb-0 d-block d-md-none">Create account</h2>
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
			
			@if($errors->any())
				{!! implode('', $errors->all('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>:message</div>')) !!}
			@endif
		</div>
		
		<div class="col-12">
			<div class="pl-0 pl-lg-3">
                <form class="account-form" method="POST" action="{{ route('registerStep1') }}">
					@csrf
					<h6>New customer?</h6>
					
					<hr/>
					
					<label>Please enter your name <span class="text-danger">*</span></label>
					
					<div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
						<input type="text" class="form-control" placeholder="First name" id="name" name="first_name" value="{{ old('first_name') }}" data-validation="required alphanumeric length" data-validation-length="1-255">
						@if ($errors->has('first_name'))
							<span class="help-block text-danger">
								<strong>{{ $errors->first('first_name') }}</strong>
							</span>
						@endif
					</div>
					
					<div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
						<input type="text" class="form-control" placeholder="Last name" id="name" name="last_name" value="{{ old('last_name') }}" data-validation="alphanumeric length" data-validation-length="1-255" data-validation-optional="true">
						@if ($errors->has('last_name'))
							<span class="help-block text-danger">
								<strong>{{ $errors->first('last_name') }}</strong>
							</span>
						@endif
					</div>
					
					<label>Please provide an email address <span class="text-danger">*</span></label>
					
					<div class="form-group{{ $errors->has('register_email') ? ' has-error' : '' }}">
						<input type="text" class="form-control" placeholder="Email Address" id="register_email" name="register_email" value="{{ old('register_email') }}" data-validation="required email length" data-validation-length="1-255">
						@if ($errors->has('register_email'))
							<span class="help-block text-danger">
								<strong>{{ $errors->first('register_email') }}</strong>
							</span>
						@endif
					</div>
					
					<div class="form-group{{ $errors->has('register_email_confirmation') ? ' has-error' : '' }}">
						<input type="text" class="form-control" placeholder="Confirm Email Address" id="register_email_confirmation" name="register_email_confirmation" value="{{ old('register_email_confirmation') }}" data-validation="required email length confirmation" data-validation-length="1-255" data-validation-confirm="register_email" data-validation-error-msg-confirmation="The Confirmation Email must match your Email Address">
						@if ($errors->has('register_email_confirmation'))
							<span class="help-block text-danger">
								<strong>{{ $errors->first('register_email_confirmation') }}</strong>
							</span>
						@endif
					</div>
					
					<label>Enter your company name <span class="text-danger">*</span></label>
					
					<div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
						<input type="text" class="form-control" placeholder="Company Name" id="company_name" name="company_name" value="{{ old('company_name') }}" data-validation="required length" data-validation-length="1-255">
						@if ($errors->has('company_name'))
							<span class="help-block text-danger">
								<strong>{{ $errors->first('company_name') }}</strong>
							</span>
						@endif
					</div>
					
					<label>Please provide a business telephone number <span class="text-danger">*</span></label>
					
					<div class="form-group{{ $errors->has('tel_num') ? ' has-error' : '' }}">
						<input class="form-control" placeholder="447222555555" id="tel_num" name="tel_num" value="{{ old('tel_num') }}" data-validation="required number length" data-validation-length="7-12">
						@if ($errors->has('tel_num'))
							<span class="help-block text-danger">
								<strong>{{ $errors->first('tel_num') }}</strong>
							</span>
						@endif
					</div>
					
					<div class="float-lg-right mt-3 mt-lg-0">
						<button type="submit" class="btn site-btn mb-2">continue <span class="ds-right-arrow"></span></button>
						<p class="mb-0">To select your billing address</p>
					</div>
                </form>
            </div>
		</div>
	</div>
@endsection
