@extends('layouts.admin.app')
@section('content')
<!-- Main content -->
<section class="content">
    @include('layouts.errors-and-messages')
    <div class="box">
        <form action="{{ route('admin.customers.update', $customer->id) }}" method="post" class="form" enctype="multipart/form-data">
            <div class="box-body">
                <h2>Edit Customer</h2>
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put">
                <!-- customer personal details -->			
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Personal Details</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="first_name">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" id="first_name" placeholder="First Name" class="form-control" value="{!! $customer->first_name ?: old('first_name')  !!}">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" id="last_name" placeholder="Last Name" class="form-control" value="{!! $customer->last_name ?: old('last_name')  !!}">
                        </div>
                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-addon">@</span>
                                <input type="text" name="email" id="email" placeholder="Email" class="form-control" value="{!! $customer->email ?: old('email')  !!}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="receive_invoice_customer_email">Receive Invoices: </label>
                            <input type="checkbox" name="customers_email_address_check_for[]" @if(in_array(1, explode(",", $customer->customers_email_address_check_for )) )checked="checked" @endif  value="1">
                            <label for="receive_orderupdate_customer_email">Receive Order Updates: </label>
                            <input type="checkbox" name="customers_email_address_check_for[]" @if(in_array(2, explode(",", $customer->customers_email_address_check_for )) )checked="checked" @endif value="2">
                            <label for="receive_receivenewsletter_customer_email">Receive Newsletter: </label>
                            <input type="checkbox" name="customers_email_address_check_for[]" @if(in_array(3, explode(",", $customer->customers_email_address_check_for )) )checked="checked" @endif value="3">
                        </div>
                    </div>
                </div>
                <!-- customer company details -->		
                <div class="box box-default collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Company Details</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="company_name">Company Name</label>
                            <input type="text" name="company_name" id="company_name" placeholder="Company Name" class="form-control" value="{!! $customer->company_name ?: old('company_name')  !!}">
                        </div>
                        <div class="form-group">
                            <label for="current_spend_month">Current Spend Per Month (£)</label>
                            <select name="current_spend_month" id="current_spend_month" class="form-control">
                                <option value="">Please Select</option>
                                <option value="below 500" @if("below 500" == $customer->current_spend_month )selected="selected" @endif> &lt; £500 </option>
                                <option value="500 - 1500" @if("500 - 1500" == $customer->current_spend_month )selected="selected" @endif>£500 - £1500</option>
                                <option value="1500 - 3000" @if("1500 - 3000" == $customer->current_spend_month )selected="selected" @endif>£1500 - £3000</option>
                                <option value="3001 - 5000" @if("3001 - 5000" == $customer->current_spend_month )selected="selected" @endif>£3001 - £5000</option>
                                <option value="5000 - 7000" @if("5000 - 7000" == $customer->current_spend_month )selected="selected" @endif>£5000 - £7000</option>
                                <option value="7001 â€“ 10,000" @if("7001 â€“ 10,000" == $customer->current_spend_month )selected="selected" @endif>£7001 - £10,000</option>
                                <option value="10,000 - 15000" @if("10,000 - 15000" == $customer->current_spend_month )selected="selected" @endif>£10,000 - £15000</option>
                                <option value="15000 +" @if("15000 +" == $customer->current_spend_month )selected="selected" @endif>£15000 +</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="minimum_order">Minimum Order</label>
                            <select name="minimum_order" id="minimum_order" class="form-control">
                                <option value="{{config('constants.DEFAULT_MINIMUM_ORDER')}}">Default</option>
                                <option value="10" @if("10" == $customer->minimum_order )selected="selected" @endif>£10</option>
                                <option value="20" @if("20" == $customer->minimum_order )selected="selected" @endif>£20</option>
                                <option value="50" @if("50" == $customer->minimum_order )selected="selected" @endif>£50</option>
                                <option value="100" @if("100" == $customer->minimum_order )selected="selected" @endif>£100</option>
                                <option value="200" @if("200" == $customer->minimum_order )selected="selected" @endif>£200</option>
                                <option value="500" @if("500" == $customer->minimum_order )selected="selected" @endif>£500</option>
                                <option value="1000" @if("1000" == $customer->minimum_order )selected="selected" @endif>£1000</option>
                            </select>
                            ( if not selected Default minimum order £ {{config('constants.DEFAULT_MINIMUM_ORDER')}} )
                        </div>
                        <div class="form-group">
                            <label for="company_tax_id">Company's tax id number:</label>
                            <input type="text" name="company_tax_id" id="company_tax_id" placeholder="Company's tax id number" class="form-control" value="{!! $customer->company_tax_id ?: old('company_tax_id')  !!}">
                        </div>
                        <div class="form-group">
                            <label for="authentication_alert">Switch off alert for authentication:</label>
                            <input type="radio" name="authentication_alert" value="0" @if(0 == $customer->authentication_alert )checked="checked" @endif> Alert off   <input type="radio" name="authentication_alert" @if(1 == $customer->authentication_alert )checked="checked" @endif value="1"> Alert on 
                        </div>
                        <div class="form-group">
                            <label for="customers_company_contact_email_extra">Company Contact Email Address Extra:</label>
                            <input type="text" name="customers_company_contact_email_extra" id="customers_company_contact_email_extra" placeholder="Company extra email" class="form-control" value="{!! $customer->customers_company_contact_email_extra ?: old('customers_company_contact_email_extra')  !!}
                                ">
                        </div>
                        <div class="form-group">
                            <label for="receive_invoice_companyemail">Receive Invoices: </label>
                            <input type="checkbox" name="customers_company_contact_email_extra_check_for[]" @if(in_array(1, explode(",", $customer->customers_company_contact_email_extra_check_for)) )checked="checked" @endif value="1">
                            <label for="order_update__companyemail">Receive Order Updates: </label>
                            <input type="checkbox" name="customers_company_contact_email_extra_check_for[]" @if(in_array(2, explode(",", $customer->customers_company_contact_email_extra_check_for)) )checked="checked" @endif value="2">
                            <label for="receive_newsletter__companyemail">Receive Newsletter: </label>
                            <input type="checkbox" name="customers_company_contact_email_extra_check_for[]" @if(in_array(3, explode(",", $customer->customers_company_contact_email_extra_check_for)) )checked="checked" @endif value="3">
                        </div>
                        <div class="form-group">
                            <label for="company_extra_email">Account Contact Email Address Extra:</label>
                            <input type="text" name="customers_account_contact_email_extra" id="customers_account_contact_email_extra" placeholder="Account extra email" class="form-control" value="{!! $customer->customers_account_contact_email_extra ?: old('customers_account_contact_email_extra')  !!}">
                        </div>
                        <div class="form-group">
                            <label for="receive_invoice_companyextra_email">Receive Invoices: </label>
                            <input type="checkbox" name="customers_account_contact_email_extra_check_for[]" @if(in_array(1, explode(",", $customer->customers_account_contact_email_extra_check_for)) )checked="checked" @endif value="1">
                            <label for="order_update_companyextra_email">Receive Order Updates: </label>
                            <input type="checkbox" name="customers_account_contact_email_extra_check_for[]" @if(in_array(2, explode(",", $customer->customers_account_contact_email_extra_check_for)) )checked="checked" @endif value="2">
                            <label for="receive_newsletter_companyextra_email">Receive Newsletter: </label>
                            <input type="checkbox" name="customers_account_contact_email_extra_check_for[]" @if(in_array(3, explode(",", $customer->customers_account_contact_email_extra_check_for)) )checked="checked" @endif value="3">
                        </div>
                    </div>
                </div>
                <!-- customer address details -->	 
                <div class="box box-default collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Addresses</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="street_address">Street Address <span class="text-danger">*</span></label>
                            <input type="text" name="street_address" id="street_address" placeholder="Street Address" class="form-control" value="{!! $customer->street_address ?: old('street_address')  !!}">
                        </div>
                        <div class="form-group">
                            <label for="address_line_2">Address Line 2</label>
                            <input type="text" name="address_line_2" id="address_line_2" placeholder="Address Line 2" class="form-control" value="{!! $customer->address_line_2 ?: old('address_line_2')  !!}">
                        </div>
                        <div class="form-group">
                            <label for="post_code">Post Code <span class="text-danger">*</span></label>
                            <input type="text" name="post_code" id="post_code" placeholder="Post Code" class="form-control" value="{!! $customer->post_code ?: old('post_code')  !!}{{ old('post_code') }}">
                        </div>
                        <div class="form-group">
                            <label for="city">City <span class="text-danger">*</span></label>
                            <input type="text" name="city" id="city" placeholder="City" class="form-control" value="{!! $customer->city ?: old('city')  !!}">
                        </div>
                        <div class="form-group">
                            <label for="county_state">County/State <span class="text-danger">*</span></label>
                            <select name="county_state" id="county_state" class="form-control">
                                <option value="">Please Select</option>
                                @foreach($states as $state)
                                <option @if($state->state == $customer->county_state )selected="selected" @endif value="{{ $state->state }}">{{ $state->state }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <strong>Country</strong> - United Kingdom
                            <input type="hidden" name="country_id" id="country_id" value="225">
                        </div>
                        <div class="form-page-headings">Invoice Address</div>
                        <div class="form-group">
                            <label for="invoice_street_address">Invoice Street Address <span class="text-danger">*</span></label>
                            <input type="text" name="invoice_street_address" id="invoice_street_address" placeholder="Invoice Street Address" class="form-control" value="{!! $customer->invoice_street_address ?: old('invoice_street_address')  !!}">
                        </div>
                        <div class="form-group">
                            <label for="invoice_address_line_2">Invoice Address Line 2</label>
                            <input type="text" name="invoice_suburb" id="invoice_suburb" placeholder="Invoice Address Line 2" class="form-control" value="{!! $customer->invoice_suburb ?: old('invoice_suburb')  !!}">
                        </div>
                        <div class="form-group">
                            <label for="invoice_post_code">Invoice Post Code <span class="text-danger">*</span></label>
                            <input type="text" name="invoice_postcode" id="invoice_postcode" placeholder="Invoice Post Code" class="form-control" value="{!! $customer->invoice_postcode ?: old('invoice_postcode')  !!}">
                        </div>
                        <div class="form-group">
                            <label for="invoice_city">Invoice City <span class="text-danger">*</span></label>
                            <input type="text" name="invoice_city" id="invoice_city" placeholder="Invoice City" class="form-control" value="{!! $customer->invoice_city ?: old('invoice_city')  !!}">
                        </div>
                        <div class="form-group">
                            <label for="invoice_county_state">Invoice County/State <span class="text-danger">*</span></label>
                            <select name="invoice_state" id="invoice_state" class="form-control">
                                <option value="">Please Select</option>
                                @foreach($states as $state)
                                <option @if($state->state == $customer->invoice_state )selected="selected" @endif value="{{ $state->state }}">{{ $state->state }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-page-headings">Fleetmatics POI address</div>
                        <div class="form-group">
                            <label for="fleetmatics_street_address">Fleetmatics Street Address <span class="text-danger">*</span></label>
                            <input type="text" name="fleetmatics_street_address" id="fleetmatics_street_address" placeholder="Fleetmatics Street Address" class="form-control" value="{!! $customer->fleetmatics_street_address ?: old('fleetmatics_street_address')  !!}">
                        </div>
                        <div class="form-group">
                            <label for="fleetmatics_address_line_2">Fleetmatics Address Line 2</label>
                            <input type="text" name="fleetmatics_suburb" id="fleetmatics_suburb" placeholder="Fleetmatics Address Line 2" class="form-control" value="{!! $customer->fleetmatics_suburb ?: old('fleetmatics_suburb')  !!}">
                        </div>
                        <div class="form-group">
                            <label for="fleetmatics_post_code">Fleetmatics Post Code <span class="text-danger">*</span></label>
                            <input type="text" name="fleetmatics_post_code" id="fleetmatics_post_code" placeholder="Fleetmatics Post Code" class="form-control" value="{!! $customer->fleetmatics_post_code ?: old('fleetmatics_post_code')  !!}">
                        </div>
                        <div class="form-group">
                            <label for="fleetmatics_city">Fleetmatics City <span class="text-danger">*</span></label>
                            <input type="text" name="fleetmatics_city" id="fleetmatics_city" placeholder="Fleetmatics City" class="form-control" value="{!! $customer->fleetmatics_city ?: old('fleetmatics_city')  !!}">
                        </div>
                        <div class="form-group">
                            <label for="fleetmatics_county_state">Fleetmatics County/State <span class="text-danger">*</span></label>
                            <select name="fleetmatics_county_state" id="fleetmatics_county_state" class="form-control">
                                <option value="">Please Select</option>
                                @foreach($states as $state)
                                <option @if($state->state == $customer->fleetmatics_county_state )selected="selected" @endif value="{{ $state->state }}">{{ $state->state }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <!-- contact information -->
                <div class="box box-default collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Customer Contact Information</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="tel_num">Telephone Number <span class="text-danger">*</span></label>
                            <input type="text" name="tel_num" id="tel_num" placeholder="Telephone Number" class="form-control" value="{!! $customer->tel_num ?: old('tel_num')  !!}">
                        </div>
                        <div class="form-group">
                            <label for="fax_num">Fax Number</label>
                            <input type="text" name="fax_num" id="fax_num" placeholder="Fax Number" class="form-control" value="{!! $customer->fax_num ?: old('fax_num')  !!}">
                        </div>
                    </div>
                </div>
                <!-- credit term agreed -->
                <div class="box box-default collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Credit Terms Agreed</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="credit_term">Credit Terms Agreed:</label>
                            <select name="credit_terms_agreed" id="credit_terms_agreed" class="form-control">
                            <option value="7" @if("7" == $customer->credit_terms_agreed )selected="selected" @endif>7 days</option>
                            <option value="15" @if("15" == $customer->credit_terms_agreed )selected="selected" @endif>15 days</option>
                            <option value="30" @if("30" == $customer->credit_terms_agreed )selected="selected" @endif>30 days</option>
                            <option value="45" @if("45" == $customer->credit_terms_agreed )selected="selected" @endif>45 days</option>
                            <option value="60" @if("60" == $customer->credit_terms_agreed )selected="selected" @endif>60 days</option>
                            <option value="75" @if("75" == $customer->credit_terms_agreed )selected="selected" @endif>75 days</option>
                            <option value="100" @if("100" == $customer->credit_terms_agreed )selected="selected" @endif>100 days</option>
                            </select>
                            <label for="credit_term_option">Select terms option from:</label>
                            <select name="credit_terms_agreed_from" id="credit_terms_agreed_from" class="form-control">
                                <option value="">Select Terms option from</option>
                                <option value="from_invoice_end_date" @if("from_invoice_end_date" == $customer->credit_terms_agreed_from )selected="selected" @endif>Date of Invoice</option>				  
                                <option value="from_month_end_date" @if("from_month_end_date" == $customer->credit_terms_agreed_from )selected="selected" @endif>End of month</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- customer require invoice type -->
                <div class="box box-default collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Customer Invoice Type</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            Invoice Type:
                            <select name="customers_require_invoice_type" id="customers_require_invoice_type">
                                <option value="">Select Invoie Type</option>
                                <option value="Monthly" @if("Monthly" == $customer->customers_require_invoice_type )selected="selected" @endif>Monthly Invoice</option>				  
                            </select>
                            If customer set to monthly invoice type then it can not be reverted back into weekly as lot of conditions defined at code complexity.
                        </div>
                    </div>
                </div>
                <!-- options -->
                <div class="box box-default collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Options</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="sage_ref">SAGE REF:</label>
                            <input type="text" name="sage_ref" id="sage_ref" placeholder="SAGE REF" class="form-control" value="{!! $customer->sage_ref ?: old('sage_ref')  !!}">
                        </div>
                        <div class="form-group">
                            <label for="hear_about_us">How Did Customer Hear About Us</label>
                            <select name="Hear_About_Us" id="Hear_About_Us" class="form-control">
                            <option value="Google" @if("Google" == $customer->Hear_About_Us )selected="selected" @endif>Google</option>
                            <option value="Referral" @if("Referral" == $customer->Hear_About_Us )selected="selected" @endif>Referral</option>
                            <option value="Magazine" @if("Magazine" == $customer->Hear_About_Us )selected="selected" @endif>Magazine</option>
                            <option value="Television" @if("Television" == $customer->Hear_About_Us )selected="selected" @endif>Television</option>
                            <option value="Newspaper" @if("Newspaper" == $customer->Hear_About_Us )selected="selected" @endif>Newspaper</option>
                            <option value="OurDeliveryVans" @if("OurDeliveryVans" == $customer->Hear_About_Us )selected="selected" @endif>Our Delivery Vans</option>
                            <option value="Radio" @if("Radio" == $customer->Hear_About_Us )selected="selected" @endif>Radio</option>
                            <option value="Flyer" @if("Flyer" == $customer->Hear_About_Us )selected="selected" @endif>Flyer</option>
                            <option value="Charity_Run" @if("Charity_Run" == $customer->Hear_About_Us )selected="selected" @endif>Charity Run</option>
                            <option value="Other" @if("Other" == $customer->Hear_About_Us )selected="selected" @endif>Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="box_type">Box Type</label>
                            <select name="Box_Type" id="Box_Type" class="form-control">
                            <option value="Standard Box" @if("Standard Box" == $customer->Box_Type )selected="selected" @endif>Standard Box</option>
                            <option value="Replacement Crate" @if("Replacement Crate" == $customer->Box_Type )selected="selected" @endif>Replacement Crate</option>
                            <option value="Recycled Box" @if("Recycled Box" == $customer->Box_Type )selected="selected" @endif>Recycled Box</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="account_email_address">Account E-Mail Address</label>
                            <input type="email" name="customers_accountemail" id="customers_accountemail" placeholder="Account E-Mail Address" class="form-control" value="{!! $customer->customers_accountemail ?: old('customers_accountemail')  !!}">
                        </div>
                        <div class="form-group">
                            <label for="receive_invoice_account_email">Receive Invoices: </label>
                            <input type="checkbox" name="customers_accountemail_check_for[]" @if(in_array(1, explode(",", $customer->customers_accountemail_check_for)) )checked="checked" @endif value="1">
                            <label for="order_update_account_email">Receive Order Updates: </label>
                            <input type="checkbox" name="customers_accountemail_check_for[]" @if(in_array(2, explode(",", $customer->customers_accountemail_check_for)) )checked="checked" @endif value="2">
                            <label for="receive_newsletter_account_email">Receive Newsletter: </label>
                            <input type="checkbox" name="customers_accountemail_check_for[]" @if(in_array(3, explode(",", $customer->customers_accountemail_check_for)) )checked="checked" @endif value="3">
                        </div>
                        <div class="form-group">
                            <label for="customers_notify">Receive Order Status Updates? Tick to turn OFF</label>
                            <input type="checkbox" name="customers_notify" @if(1 == $customer->customers_notify )checked="checked" @endif value="1">
                        </div>
                        <div class="form-group">
                            <label for="Box_Info">Box Info</label>
                            <textarea name="Box_Info" id="Box_Info" class="form-control">{{$customer->Box_Info}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="Delivery_Procedure_Customer">Delivery Procedure for Customer</label>
                            <textarea name="Delivery_Procedure_Customer" id="Delivery_Procedure_Customer" class="form-control">{{$customer->Delivery_Procedure_Customer}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="fob_card">Fob and Card</label>
                            <input type="text" name="fob_card" id="fob_card" placeholder="Fob and Card" class="form-control" value="{!! $customer->fob_card ?: old('fob_card')  !!}">
                        </div>
                        <div class="form-group access_time_fields">
                            <label for="hour">Access Time</label>
                            <div class="earlist_delivery">
                                Earliest delivery time:
                                <select name="hour" class="form-control">
                                @for ($k = 0; $k <11; $k++)
                                <option value="{{ $k }}" @if($k == floor($customer->Access_Time/3600))selected="selected" @endif>{{ sprintf('%02d',$k) }}</option>
                                @endfor
                                </select>
                                <select name="minute" class="form-control">
                                @for ($k=0;$k<60;$k+=10)
                                <option value="{{ $k }}" @if($k == ($customer->Access_Time - (floor($customer->Access_Time/3600)*3600))/60)selected="selected" @endif>{{ sprintf('%02d',$k) }}</option>
                                @endfor
                                </select>*Please specify the very earliest time we can have access for delivery
                            </div>
                            <div class="latest_delivery">
                                <select name="hour_latest" class="form-control">
                                @for ($k = 0; $k <24; $k++)
                                <option value="{{ $k }}" @if($k == floor($customer->Access_Time_latest/3600))selected="selected" @endif>{{ sprintf('%02d',$k) }}</option>
                                @endfor
                                </select>
                                <select name="minute_latest" class="form-control">
                                @for ($k = 0; $k <60; $k+=10)
                                <option value="{{ $k }}" @if($k == ($customer->Access_Time_latest - (floor($customer->Access_Time_latest/3600)*3600))/60)selected="selected" @endif>{{ sprintf('%02d',$k) }}</option>
                                @endfor
                                </select>*Please specify Latest delivery time 
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="delivery_status">Customer Regular Order Status</label>
                            <input type="checkbox" name="delivery_status[]" @if(in_array(1, explode(",", $customer->delivery_status)) )checked="checked" @endif value="1"> Monday
                            <input type="checkbox" name="delivery_status[]" @if(in_array(2, explode(",", $customer->delivery_status)) )checked="checked" @endif value="2"> Tuesday
                            <input type="checkbox" name="delivery_status[]" @if(in_array(3, explode(",", $customer->delivery_status)) )checked="checked" @endif value="3"> WednesDay
                            <input type="checkbox" name="delivery_status[]" @if(in_array(4, explode(",", $customer->delivery_status)) )checked="checked" @endif value="4"> Thrusday
                            <input type="checkbox" name="delivery_status[]" @if(in_array(5, explode(",", $customer->delivery_status)) )checked="checked" @endif value="5"> Friday
                        </div>
                        <div class="form-group">
                            <label for="purchase_order_number">Purchase Order Number</label>
                            <input name="purchase_order_number" class="form-control" type="text" value="{!! $customer->purchase_order_number ?: old('purchase_order_number')  !!}">
                        </div>
                        <div class="form-group">
                            <label for="customers_acc_trade_contract">Account/trade contact:</label>
                            <input type="text" name="customers_acc_trade_contract" value="{!! $customer->customers_acc_trade_contract ?: old('customers_acc_trade_contract')  !!}" class="form-control" maxlength="50">
                        </div>
                        <div class="form-group">
                            <label for="CustomersInvoiceMethod">Invoice Method:</label>
                            <select name="CustomersInvoiceMethod" class="form-control" onchange="Javascript:ShowFaxValueBox(this.value)">
                                <option value="">Select</option>
                                <option value="E-Mail" @if("E-Mail" == $customer->CustomersInvoiceMethod )selected="selected" @endif>E-Mail</option> 
                                <option value="Post" @if("Post" == $customer->CustomersInvoiceMethod )selected="selected" @endif>Post</option>  
                                <option value="Fax" @if("Fax" == $customer->CustomersInvoiceMethod )selected="selected" @endif>Fax</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="customers_invoice_notes">Invoice Notes:</label>
                            <textarea name="customers_invoice_notes" class="form-control" wrap="" cols="50" rows="3">{{$customer->customers_invoice_notes}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="monthly_marker">Monthly Marker:</label>
                            <input type="checkbox" name="monthly_marker" @if(in_array("yes", explode(",", $customer->monthly_marker)) )checked="checked" @endif value="yes">
                        </div>
                        <div class="form-group">
                            <label for="newsletter">Newsletter </label>
                            <select name="newsletter" id="newsletter" class="form-control">
                            <option value="1" @if(1 == $customer->newsletter )selected="selected" @endif>Subscribed</option>
                            <option value="0" @if(0 == $customer->newsletter )selected="selected" @endif>Unsubscribed</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="credit_checked">Credit Checked?  </label>
                            <input type="radio" name="credit_checked" value="1" @if(1 == $customer->credit_checked )checked="checked" @endif>Yes  <input type="radio" name="credit_checked" value="0" @if(0 == $customer->credit_checked )checked="checked" @endif>No
                        </div>
                        <div class="form-group credit-checked-by" @if(0 == $customer->credit_checked )style="display:none" @endif>
                        <input type="text" name="credit_by" value="{!! $customer->credit_by ?: old('credit_by')  !!}" placeholder="By:" class="form-control credit_by_field">
                        <input type="text" name="credit_ref" value="{!! $customer->credit_ref ?: old('credit_ref')  !!}" placeholder="Ref:" class="form-control credit_ref_field"><br />
						</div>
						<div class="form-group">
							<label for="newsletter">Customer Group:</label>
							<select name="customers_group_id" class="form-control">
								<option value="0">&nbsp; PLease select Customer Group&nbsp;</option>
								<option value="1" @if("1" == $customer->customers_group_id )selected="selected" @endif>&nbsp;Group A&nbsp;</option>
								<option value="2" @if("2" == $customer->customers_group_id )selected="selected" @endif>&nbsp;Group B&nbsp;</option>
								<option value="3" @if("3" == $customer->customers_group_id )selected="selected" @endif>&nbsp;Group C&nbsp;</option>
							</select>
						</div>
						<div class="form-group">
							<label for="customers_emailvalidated">Email Validated:</label>
							<select name="customers_emailvalidated" class="form-control">
							<option value="0" @if("0" == $customer->customers_emailvalidated )selected="selected" @endif>Unvalidated (inactive)</option>
							<option value="1" @if("1" == $customer->customers_emailvalidated )selected="selected" @endif>Validated (active)</option>
							</select>
						</div>
						<div class="form-group">
							<label for="Updatechase">Update Chase</label>
							<input name="chase" type="checkbox" @if("t" == $customer->chase )checked="checked" @endif value="t">  
						</div>
					</div>
				</div>
				<!-- payment modules -->                   
				<div class="box box-default collapsed-box">
					<div class="box-header with-border">
						<h3 class="box-title">Payment Modules</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="form-group">
							@foreach($payment_methods as $payment_method)
							<input type="checkbox" name="customers_payment_allowed[]" value="{{$payment_method->value}}" @if(in_array($payment_method->value, explode(",", $customer->customers_payment_allowed)) )checked="checked" @endif> {{$payment_method->name}}<br />
							@endforeach
							If you choose <strong>Set payment modules for the customer</strong> but do not check any of the boxes, default settings (Group settings or Configuration) will still be used.
						</div>
					</div>
				</div>
				<!-- credit application form -->
				<div class="box box-default collapsed-box">
					<div class="box-header with-border">
						<h3 class="box-title">Credit application form</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="form-group">
							<input type="file" name="credit_application_form">
							@if($customer->credit_application_form != '')
							<br /><a href="{{asset("uploads/$customer->credit_application_form")}}" target="_blank">view form</a>
							<br>
							Want to remove, Please tick checkbox <input type="hidden" name="existing_credit_application"  value="{{$customer->credit_application_form}}"> <input name="remove_existing_credit_application" type="checkbox" value="1">
							@endif
						</div>
					</div>
				</div>
				<!-- Disabled dates of shipping -->
				<div class="box box-default collapsed-box">
					<div class="box-header with-border">
						<h3 class="box-title">Disable dates for shipping</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="form-group">
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar datepickerSet"></i>
								</div>
								<input type="text" name="shipping_disabled_dates" class="form-control pull-right" id="inputTags" value="" readonly>
							</div>
						</div>
					</div>
				</div>
				<!-- other options -->					
				<div class="box box-default collapsed-box">
					<div class="box-header with-border">
						<h3 class="box-title">Other Options</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="form-group">
							<label for="status">Status </label>
							<select name="status" id="status" class="form-control">
							<option value="0" @if($customer->status == 0) selected="selected" @endif>Disable</option>
							<option value="1" @if($customer->status == 1) selected="selected" @endif>Enable</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<!-- /.box-body -->
			<div class="box-footer">
				<div class="btn-group">
					<a onclick="window.history.go(-1);" class="btn btn-default btn-sm">Back</a>
					<button type="submit" class="btn btn-primary btn-sm">Update</button>
				</div>
			</div>
		</form>
    </div>
    <!-- /.box -->
</section>
<!-- /.content -->
@endsection

@section('js')
	<script>
		$('#inputTags').tagsinput({
			freeInput: false,
			confirmKeys: [39],
		});
		
		$('#inputTags').closest('div').find('div.bootstrap-tagsinput').find('input').prop('readonly', true);
		
		$('.datepickerSet').datepicker({
			format: "yyyy-mm-dd",
			autoclose: true,
			//dDate: "today",
			startDate: '-0d',
			todayBtn: true,
			todayHighlight: true,
			daysOfWeekDisabled: [0],
			daysOfWeekHighlighted: [1,2,3,4,5,6],
			multidate: true,
			multidateSeparator: ",",
		}).on('changeDate', function(e) {
			//alert(e.format());
			var the_dates = $('.datepickerSet').datepicker('getDates');
			
			var dateStr = ''
			$.each(the_dates, function(k,v){
				var getDate = String(v.getDate()).padStart(2, '0');
				var getMonth = String((v.getMonth() + 1)).padStart(2, '0');
				
				dateStr += v.getFullYear()+'-'+getMonth+'-'+getDate+',';
			})
			
			dateStr = dateStr.replace(/(^[,\s]+)|([,\s]+$)/g, '');
			
			$('#inputTags').tagsinput('add',dateStr)
		});
		
		$("#inputTags").on('itemRemoved', function(event) {
			var the_dates = $('.datepickerSet').datepicker('getDates');
			
			var dateArr = [];
			var dateStr = ''
			$.each(the_dates, function(k,v){
				var getDate = String(v.getDate()).padStart(2, '0');
				var getMonth = String((v.getMonth() + 1)).padStart(2, '0');
				
				dateStr = v.getFullYear()+'-'+getMonth+'-'+getDate;
				
				if(dateStr == event.item){
					the_dates.splice(k,1);
					
					$('.datepickerSet').datepicker('setDates', the_dates);
					return false;
				}
			});
		});
		
		$('.datepickerSet').datepicker('setDates', {!! json_encode(explode(',', $customer->shipping_disabled_dates)) !!});
	</script>
@endsection