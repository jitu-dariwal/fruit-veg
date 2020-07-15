@extends('layouts.front.account-app')

@section('content')
	<!-- Main content -->
	
	<header>
		<h2 class="sub-heading text-center mb-0">Create account</h2>  
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
	</div>
	
	<div class="row">
		<div class="col-lg-6">
			<div class="pl-0 pl-lg-3">
				<form class="account-form" id="step4Form" method="POST" action="{{ route('registerStep4') }}">
					@csrf
					<h6>Set a password</h6>				
					<div class="mb-2">Please set a password for your account <span class="text-danger">*</span></div>

					<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
						<input type="password" class="form-control" placeholder="Set password" id="password" name="password" value="" data-validation="required length custom" data-validation-length="min6" data-validation-regexp="^(?=.*[\w])(?=.*[\W])[\w\W]{6,}$" data-validation-error-msg-custom="Password contains <li>At least one lowercase</li><li>At least one uppercase</li><li>At least one digit</li><li>At least one special character</li><li>At least it should have 6 characters long</li>.">
						@if ($errors->has('password'))
							<span class="help-block text-danger">{!! $errors->first('password') !!}</span>
						@endif
					</div>
					
					<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
						<input type="password" class="form-control" placeholder="Confirm Password " id="password-confirm" name="password_confirmation" value="" data-validation="required length confirmation" data-validation-length="min6"  data-validation-confirm="password" data-validation-error-msg-confirmation="The Confirmation Password must match your Password">
						@if ($errors->has('password_confirmation'))
							<span class="help-block text-danger">{!! $errors->first('password_confirmation') !!}</span>
						@endif
					</div>
					
					<div class="form-group">
						<div class="custom-control-outer">
							<div class="custom-control custom-checkbox">
								@php
									$checked = '';
									if(old('newsletter'))
										$checked = 'checked="checked"';
								@endphp
								<input type="checkbox" class="custom-control-input" name="newsletter" id="newsletter" value="1" {{$checked}} />
								<label class="custom-control-label" for="newsletter">I would like to receive offers/newsletters</label>
							</div>
							@if ($errors->has('newsletter'))
								<span class="help-block text-danger">{{ $errors->first('newsletter') }}</span>
							@endif
						</div>	
					</div>
					
					<div class="form-group">
						<div class="custom-control-outer">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" name="term_cond" id="term_cond" value="1" data-validation="required">
								<label class="custom-control-label" for="term_cond">I have read and agree to the Fruit + Veg <a href="{{route('page.index', 'privacy-policy')}}" target="_blank">Privacy Policy</a></label>
							</div>
							@if ($errors->has('term_cond'))
								<span class="help-block text-danger">{!! $errors->first('term_cond') !!}</span>
							@endif
						</div>	
					</div>
					<div class="float-md-right mt-4 ">
						<button type="submit" class="btn site-btn  mb-2">
							continue
							<span class="ds-right-arrow"></span>
						</button>
						<br> 			
					</div>
					<div class="text-center clear">To create your account</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@section('js')
	<script>
		$(document).ready(function(){
			
		});
	</script>
@endsection
