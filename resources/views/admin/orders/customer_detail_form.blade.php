<!-- customer personal details -->			
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Customer Details</h3>
    </div>
    <div class="box-body">        
        <div class="row">            
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="first_name">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" id="last_name" placeholder="First Name" class="form-control" required value="{!! $customer->first_name ?: old('first_name')  !!}">
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name <span class="text-danger">*</span></label>
                    <input type="text" name="last_name" id="last_name" placeholder="Last Name" class="form-control" required value="{!! $customer->last_name ?: old('last_name')  !!}">
                </div>
                <div class="form-group">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon">@</span>
                        <input type="email" required name="email" id="email" placeholder="Email" class="form-control" value="{!! $customer->email ?: old('email')  !!}">
                    </div>
                </div>                  
            </div>

            <div class="col-sm-6">                  
                <div class="form-group">
                    <label for="company_name">Company Name</label>
                    <input type="text" name="company_name" id="company_name" placeholder="Company Name" class="form-control" value="{!! $customer->company_name ?: old('company_name')  !!}">
                </div>
                <div class="form-group">
                    <label for="tel_num">Telephone Number <span class="text-danger">*</span></label>
                    <input type="text" name="tel_num" required id="tel_num" placeholder="Telephone Number" class="form-control" value="{!! $customer->tel_num ?: old('tel_num')  !!}">
                </div>
            </div>
        </div>
    </div>

</div>

<!-- customer address details -->	 
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Addresses</h3>
    </div>

    <div class="box-body">
        <div class="row">            
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="street_address">Street Address <span class="text-danger">*</span></label>
                    <input type="text" name="street_address" required id="street_address" placeholder="Street Address" class="form-control" value="{!! $customer->invoice_street_address ?: old('street_address')  !!}">
                </div>
                <div class="form-group">
                    <label for="address_line_2">Address Line 2</label>
                    <input type="text" name="address_line_2" id="address_line_2" placeholder="Address Line 2" class="form-control" value="{!! $customer->invoice_suburb ?: old('address_line_2')  !!}">
                </div>
                <div class="form-group">
                    <label for="post_code">Post Code <span class="text-danger">*</span></label>
                    <input type="text" name="post_code" required id="post_code" placeholder="Post Code" class="form-control" value="{!! $customer->invoice_postcode ?: old('post_code')  !!}{{ old('post_code') }}">
                </div>                          
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="city">City <span class="text-danger">*</span></label>
                    <input type="text" name="city" required id="city" placeholder="City" class="form-control" value="{!! $customer->invoice_city ?: old('city')  !!}">
                </div>
                <div class="form-group">
                    <label for="county_state">County/State <span class="text-danger">*</span></label>
					<input type="text" name="country_state" required id="country_state" placeholder="State" class="form-control" value="{!! $customer->invoice_state ?: old('country_state')  !!}">
					
                    @php /* @endphp<select name="country_state" required id="country_state" class="form-control">
                        <option value="">Please Select</option>
                        @foreach($states as $state)
                        <option @if($state->state == $customer->county_state )selected="selected" @endif value="{{ $state->state }}">{{ $state->state }}</option>
                        @endforeach
                    </select> @php */ @endphp
                </div>
                <div class="form-group">
                    <strong>Country</strong> - United Kingdom
                    <input type="hidden" name="country" id="country" placeholder="Country" value="United Kingdom">
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">         
        <div class=" btn-group pull-right">                      
            <button type="submit" class="btn btn-primary">Confirm</button>
        </div>
    </div>
</div>
