@extends('layouts.front.app')

@section('content')

<section class="myAccount-wrapper">
    <div class="container">
        @include('layouts.front.top-account')
		
        <div class="myAccount-block">
			
			@include('layouts.front.left-account')		

			<div class="account-Content-outer">
				<div class="row">
					<div class="col-xl-8">
						@include('layouts.front.top-alert-message')
						
						<div class="yourOrderBlock">
							<h6>About you</h6>
							
							<div class="controlar">
								<form class="profileForm" role="form" method="POST" action="{{ route('accounts.update', $customer->id) }}">
									@csrf
									<input type="hidden" name="form_type" value="profile"/>
									<div class="left-textbox">                             
										<div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
											<input id="first_name" type="text" class="form-control" name="first_name" value="{{old('first_name', $customer->first_name)}}" placeholder="First Name" data-validation="required alphanumeric length" data-validation-length="1-255" {{ (old('form_type') == 'profile')?:'readonly'}}>
											@if ($errors->has('first_name'))
												<span class="help-block text-danger">
													<strong>{{ $errors->first('first_name') }}</strong>
												</span>
											@endif
										</div>
										
										<div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
											<input id="last_name" type="text" class="form-control" name="last_name" value="{{old('last_name', $customer->last_name)}}" placeholder="Last Name" data-validation="alphanumeric length" data-validation-length="1-255" data-validation-optional="true" {{ (old('form_type') == 'profile')?:'readonly'}}>
											@if ($errors->has('last_name'))
												<span class="help-block text-danger">
													<strong>{{ $errors->first('last_name') }}</strong>
												</span>
											@endif
										</div>
										
										<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
											<input id="email" type="text" class="form-control" name="email" value="{{old('email', $customer->email)}}" placeholder="Email Address" data-validation="required email length" data-validation-length="1-255" {{ (old('form_type') == 'profile')?:'readonly'}}>
											@if ($errors->has('email'))
												<span class="help-block text-danger">
													<strong>{{ $errors->first('email') }}</strong>
												</span>
											@endif
										</div>
										
										<div class="form-group{{ $errors->has('tel_num') ? ' has-error' : '' }}">
											<input id="tel_num" class="form-control" name="tel_num" value="{{old('tel_num', $customer->tel_num)}}" placeholder="Telephone Number" data-validation="required number length" data-validation-length="7-12" {{ (old('form_type') == 'profile')?:'readonly'}}>
											@if ($errors->has('tel_num'))
												<span class="help-block text-danger">
													<strong>{{ $errors->first('tel_num') }}</strong>
												</span>
											@endif
										</div>
										
										<div class="form-group{{ $errors->has('fax_num') ? ' has-error' : '' }}">
											<input id="fax_num" type="text" class="form-control" name="fax_num" value="{{old('fax_num',$customer->fax_num)}}" placeholder="Fax Number" {{ (old('form_type') == 'profile')?:'readonly'}}>
											@if ($errors->has('fax_num'))
												<span class="help-block text-danger">
													<strong>{{ $errors->first('fax_num') }}</strong>
												</span>
											@endif
										</div>
									</div>
									<div class="right-btn">
										<button type="submit" class="btn btn-greem editAccount">edit</button>
									</div>
								</form>
							</div>
						</div>
						
						<div class="yourOrderBlock">
							<h6>Set New Password</h6>
							<div class="controlar">
								<form class="" role="form" method="POST" action="{{ route('accounts.update', $customer->id) }}">
									@csrf
									<input type="hidden" name="form_type" value="password"/>
									<div class="left-textbox">                             
										<div class="form-group{{ $errors->has('old_password') ? ' has-error' : '' }}">
											<input id="old_password" type="password" class="form-control" name="old_password" value="" placeholder="Current Password" data-validation="required length" data-validation-length="min6">
											@if ($errors->has('old_password'))
												<span class="help-block text-danger">
													<strong>{!! $errors->first('old_password') !!}</strong>
												</span>
											@endif
										</div>
										<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
											<input id="password" type="password" class="form-control" name="password" value="" placeholder="New Password" data-validation="required length custom" data-validation-length="min6" data-validation-regexp="^(?=.*[\w])(?=.*[\W])[\w\W]{6,}$" data-validation-error-msg-custom="Password contains <li>At least one lowercase</li><li>At least one uppercase</li><li>At least one digit</li><li>At least one special character</li><li>At least it should have 6 characters long</li>.">
											@if ($errors->has('password'))
												<span class="help-block text-danger">
													<strong>{!! $errors->first('password') !!}</strong>
												</span>
											@endif
										</div>
										<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
											<input id="password_confirmation" type="password" class="form-control" name="password_confirmation" value="" placeholder="Confirm Password" data-validation="required length confirmation" data-validation-length="min6"  data-validation-confirm="password" data-validation-error-msg-confirmation="The Confirmation Password must match your Password">
											@if ($errors->has('password_confirmation'))
												<span class="help-block text-danger">
													<strong>{!! $errors->first('password_confirmation') !!}</strong>
												</span>
											@endif
										</div>
									</div>
									<div class="right-btn">
										<button type="submit" class="btn btn-greem">Set</button>
									</div>
								</form>
							</div>
						</div>
					   
						@include('layouts.front.tell-us-friend')
                       
					</div>
					<div class="col-xl-4">
						@include('layouts.front.right-account')
                    </div>
               </div>
            </div>            
        </div>
    </div>    
</section>
<!-- /.content -->
@endsection

@section('js')
	<script>	
		$(document).ready(function(){
			$(document).on('click','.editAccount',function(){
				$('.profileForm').find('input').prop('readonly',false);
				$(this).html('Update').removeClass('editAccount');
				return false;
			});
		});
	</script>
@endsection