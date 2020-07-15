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
							<h6>
								@if(empty($card))
									Add card
								@else
									Edit card
								@endif
							</h6>
							<form action="{{ route('accounts.savepaymentsaddedit', $id) }}" method="post">
								@csrf
								<input type="hidden" name="id" value="{{ $id }}"/>
								<div class="form-group">
									<label >Please enter your name</label>
									<input class="form-control" name="name" placeholder="Enter card holder name" value="{{ old('name', (!empty($card)) ? $card->card_holder_name : '') }}" data-validation="required" />
									@if ($errors->has('name'))
										<span class="help-block text-danger"> {{ $errors->first('name') }} </span>
									@endif
								</div>
								<div class="form-group">
									<label >Card number</label>
									<input name="number" class="form-control" placeholder="XXXX XXXX XXXX XXXX" value="{{ old('number', (!empty($card)) ? $card->pay360_token : '') }}"  data-validation="required" />
									@if ($errors->has('number'))
										<span class="help-block text-danger"> {{ $errors->first('number') }} </span>
									@endif
								</div>
								<div class="row card-details">
									<div class="col-8">
										<div class="form-group">
											<label >Expiration date</label>
											<div class="form-row">
												<div class="col">
													<div class="c-select">
														<select name="exp_month" class="form-control" data-validation="required">
															<option value="">Month</option>
															@for($i = 01;$i <= 12; $i++)
																<option {{ (old('exp_month', (!empty($card)) ? $card->expiry_date[0].$card->expiry_date[1] : '') == $i) ? 'selected' : '' }} value="{{ $i }}"> {{ sprintf('%02d', $i) }} </option>
															@endfor
														</select>
														@if ($errors->has('exp_month', (!empty($card)) ? $card->pay360_token : ''))
															<span class="help-block text-danger"> {{ $errors->first('exp_month') }} </span>
														@endif
													</div>	
												</div>
												<div class="col">
													<div class="c-select">
														<select name="exp_year" class="form-control"  data-validation="required">
															<option value="">Year</option>
															@for($i = (int)date('Y');$i <= ((int)date('Y') + 10); $i++)
																<option {{ (old('exp_year', (!empty($card)) ? $current_year[0].$current_year[1].$card->expiry_date[2].$card->expiry_date[3] : '') == $i) ? 'selected' : '' }} value="{{ $i }}"> {{ sprintf('%02d', $i) }} </option>
															@endfor
														</select>
														@if ($errors->has('exp_year'))
															<span class="help-block text-danger"> {{ $errors->first('exp_year') }} </span>
														@endif
													</div>  
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="right-btn">
									<button type="submit" class="btn btn-greem">
										@if(empty($card))
											Add
										@else
											Edit
										@endif
									</button>
								</div>
							</form>
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