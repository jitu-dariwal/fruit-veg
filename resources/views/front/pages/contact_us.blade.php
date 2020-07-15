@extends('layouts.front.account-app')

@section('meta_tags')
	<title>{{ config('app.name') }}</title>
	<meta name="description" content="{!! config('app.name') !!}">
	<meta name="tags" content="{!! config('app.name') !!}">
@endsection

@section('og')
    <meta property="og:type" content="contact"/>
    <meta property="og:title" content="{!! config('app.name') !!}"/>
    <meta property="og:description" content="{!! config('app.name') !!}"/>
@endsection

@section('content')
	
	<header>
		<h2 class="sub-heading text-center mb-0"> Contact Us </h2>
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
		
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-6 col-sm-12 d-border-right">
					<div class="pl-0 pl-lg-3">
						<h6>CORPORATE HEADQUARTERS:</h6>
						{!! config('constants.COMPANY_ADDRESS') !!}
					</div>
				</div>
				
				<div class="col-md-6 col-sm-12">
					<div class="pr-0 pr-lg-3">
						<h6>SEND ENQUIRY</h6>
						
						<form class="account-form" method="POST" action="{{ route('page.saveContactUs') }}" onsubmit="return submitForm();">
							@csrf
							<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
								<input type="text" class="form-control" placeholder="Full name" id="name" name="name" value="{{ old('name') }}" data-validation="required length" data-validation-length="3-255">
								@if ($errors->has('name'))
									<span class="help-block text-danger">
										<strong>{{ $errors->first('name') }}</strong>
									</span>
								@endif
							</div>
							
							<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
								<input type="text" class="form-control" placeholder="E-Mail Address" id="email" name="email" value="{{ old('email') }}" data-validation="required email length" data-validation-length="3-255">
								@if ($errors->has('email'))
									<span class="help-block text-danger">
										<strong>{{ $errors->first('email') }}</strong>
									</span>
								@endif
							</div>
							
							<div class="form-group{{ $errors->has('tel_num') ? ' has-error' : '' }}">
								<input class="form-control" placeholder="Telephone Number" id="tel_num" name="tel_num" value="{{ old('tel_num') }}" data-validation="required number length" data-validation-length="min7">
								@if ($errors->has('tel_num'))
									<span class="help-block text-danger">
										<strong>{{ $errors->first('tel_num') }}</strong>
									</span>
								@endif
							</div>
							
							<div class="form-group{{ $errors->has('subject') ? ' has-error' : '' }}">
								<input type="text" class="form-control" placeholder="Subject" id="subject" name="subject" value="{{ old('subject') }}" data-validation="required length" data-validation-length="3-255">
								@if ($errors->has('subject'))
									<span class="help-block text-danger">
										<strong>{{ $errors->first('subject') }}</strong>
									</span>
								@endif
							</div>
							
							<div class="form-group{{ $errors->has('enquiry') ? ' has-error' : '' }}">
								<textarea style="height:auto;" class="form-control" placeholder="Enquiry:" id="enquiry" name="enquiry" rows="5" data-validation="required"> {{ old('enquiry') }} </textarea>
								@if ($errors->has('enquiry'))
									<span class="help-block text-danger">
										<strong>{{ $errors->first('enquiry') }}</strong>
									</span>
								@endif
							</div>
							
							<div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
								<div class="g-recaptcha" data-sitekey="{{ config('constants.google.captcha.site_key')}}" data-callback="verifyCaptcha">
								</div>
								@if ($errors->has('g-recaptcha-response'))
									<span class="help-block text-danger">
										<strong>{{ $errors->first('g-recaptcha-response') }}</strong>
									</span>
								@endif
							</div>
							
							<div class="mt-3 mt-lg-0">
								<button type="submit" class="btn site-btn mb-2">continue <span class="ds-right-arrow"></span></button>
							</div>
						</form>
					</div>
				</div>
				
				<div class="col-sm-12 mt-4">
					<div class="pl-0 pl-lg-3 pr-0 pr-lg-3">
						<h6>Location map</h6>
						<iframe 
						  width="100%" 
						  height="400" 
						  frameborder="0" 
						  scrolling="yes" 
						  marginheight="0" 
						  marginwidth="0" 
						  src="https://maps.google.com/maps?q={{ config('constants.address.latitude') }},{{ config('constants.address.longitude') }}&hl=es&z=14&amp;output=embed"
						  allowfullscreen="1" >
						 </iframe>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
	<script src='https://www.google.com/recaptcha/api.js'></script>
	
	<script>
	function submitForm() {
		$('.g-recaptcha').nextAll('span').remove();
		var response = grecaptcha.getResponse();
		if(response.length == 0) {
			$('<span class="help-block text-danger">This field is required.</span>').insertAfter($('.g-recaptcha'));
			return false;
		}
		return true;
	}
	 
	function verifyCaptcha() {
		$('.g-recaptcha').nextAll('span').remove();
	}
	</script>
@endsection
