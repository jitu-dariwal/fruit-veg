@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.customers.store') }}" method="post" class="form">
                <div class="box-body">
                    <h2>Create Customer</h2>
                    {{ csrf_field() }}
					
					<div class="form-page-headings">Customer Personal Details</div>
                    <div class="form-group">
                        <label for="first_name">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" id="last_name" placeholder="First Name" class="form-control" value="{{ old('first_name') }}">
                    </div>
					 <div class="form-group">
                        <label for="last_name">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" id="last_name" placeholder="Last Name" class="form-control" value="{{ old('last_name') }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon">@</span>
                            <input type="text" name="email" id="email" placeholder="Email" class="form-control" value="{{ old('email') }}">
                        </div>
                    </div>
					<div class="form-page-headings">Company Details</div>
					 <div class="form-group">
                        <label for="company_name">Company Name</label>
                        <input type="text" name="company_name" id="company_name" placeholder="Company Name" class="form-control" value="{{ old('company_name') }}">
                    </div>
					<div class="form-page-headings">Customer Address</div>
					<div class="form-group">
                        <label for="street_address">Street Address <span class="text-danger">*</span></label>
                        <input type="text" name="street_address" id="street_address" placeholder="Street Address" class="form-control" value="{{ old('street_address') }}">
                    </div>
					<div class="form-group">
                        <label for="address_line_2">Address Line 2</label>
                        <input type="text" name="address_line_2" id="address_line_2" placeholder="Address Line 2" class="form-control" value="{{ old('address_line_2') }}">
                    </div>
					<div class="form-group">
                        <label for="post_code">Post Code <span class="text-danger">*</span></label>
                        <input type="text" name="post_code" id="post_code" placeholder="Post Code" class="form-control" value="{{ old('post_code') }}">
                    </div>
					<div class="form-group">
                        <label for="city">City <span class="text-danger">*</span></label>
                        <input type="text" name="city" id="city" placeholder="City" class="form-control" value="{{ old('city') }}">
                    </div>
					<div class="form-group">
                        <label for="county_state">County/State <span class="text-danger">*</span></label>
						<select name="county_state" id="county_state" class="form-control">
							<option value="">Please Select</option>
							@foreach($states as $state)
                                <option value="{{ $state->state }}" @if(old('county_state') == $state->state) selected="selected" @endif>{{ $state->state }}</option>
                            @endforeach
                        </select>
                       
                    </div>
					<div class="form-group">
                        <strong>Country</strong> - United Kingdom
                        <input type="hidden" name="country_id" id="country_id" value="225">
                    </div>
					
					<div class="form-page-headings">Customer Contact Information</div>
					<div class="form-group">
                        <label for="tel_num">Telephone Number <span class="text-danger">*</span></label>
                        <input type="text" name="tel_num" id="tel_num" placeholder="Telephone Number" class="form-control" value="{{ old('tel_num') }}">
                    </div>
					<div class="form-group">
                        <label for="fax_num">Fax Number</label>
                        <input type="text" name="fax_num" id="fax_num" placeholder="Fax Number" class="form-control" value="{{ old('fax_num') }}">
                    </div>
					<div class="form-page-headings">Options</div>
					<div class="form-group">
                        <label for="newsletter">Newsletter </label>
                        <select name="newsletter" id="newsletter" class="form-control">
                            <option value="1" selected="selected">Subscribed</option>
                            <option value="0">Unsubscribed</option>
                        </select>
                    </div>
					<div class="form-group">
                        <label for="current_spend_month">Current Spend Per Month (£)</label>
                        <select name="current_spend_month" id="current_spend_month" class="form-control">
                            <option value="">Please Select</option>
                                <option value="below 500" @if(old('current_spend_month') == "below 500") selected="selected" @endif> &lt; £500 </option>
                                <option value="500 - 1500" @if(old('current_spend_month') == "500 - 1500") selected="selected" @endif>£500 - £1500</option>
                                <option value="1500 - 3000" @if(old('current_spend_month') == "1500 - 3000") selected="selected" @endif>£1500 - £3000</option>
                                <option value="3001 - 5000" @if(old('current_spend_month') == "3001 - 5000") selected="selected" @endif>£3001 - £5000</option>
                                <option value="5000 - 7000" @if(old('current_spend_month') == "5000 - 7000") selected="selected" @endif>£5000 - £7000</option>
                                <option value="7001 - 10,000" @if(old('current_spend_month') == "7001 - 10,000") selected="selected" @endif>£7001 - £10,000</option>
                                <option value="10,000 - 15000" @if(old('current_spend_month') == "10,000 - 15000") selected="selected" @endif>£10,000 - £15000</option>
                                <option value="15000 +" @if(old('current_spend_month') == "15000 +") selected="selected" @endif>£15000 +</option>
                        </select>
                    </div>
					<div class="form-group">
                        <label for="credit_checked">Credit Checked?  </label>
                        <input type="radio" name="credit_checked" value="1">Yes  <input type="radio" name="credit_checked" checked="checked" value="0">No
                    </div>
					<div class="form-group credit-checked-by" style="display:none">
                       <input type="text" name="credit_by" placeholder="By:" class="form-control credit_by_field">
					   <input type="text" name="credit_ref" placeholder="Ref:" class="form-control credit_ref_field"><br />
                    </div>
					
                    <div class="form-group">
                        <label for="status">Status </label>
                        <select name="status" id="status" class="form-control">
                            <option value="1">Enable</option>
                            <option value="0">Disable</option>
                        </select>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <a onclick="window.history.go(-1);" class="btn btn-default">Back</a>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
