@extends('layouts.front.app')

@section('content')
    <div class="product-in-cart-list">
        @if(!$products->isEmpty())
        
      
            <div class="row">
                <div class="col-md-12">
                    <ol class="breadcrumb">
                        <li><a href="{{ route('home') }}"> <i class="fa fa-home"></i> Home</a></li>
                        <li class="active">Checkout</li>
                    </ol>
                </div>
                <div class="col-md-12 content">
                    <div class="box-body">
                        @include('layouts.errors-and-messages')
                    </div>
                    @if(count($addresses) > 0)
                        
    <form class="form-horizontal" role="form" method="POST" action="{{ route('checkout.confirmcheckout', $customer->id) }}">
                                    {{ csrf_field() }} 
                                    <input type="hidden" name="delivery_date_total_days" id="delivery_date_total_days" value="{{$delivery_date_total_days}}">
                       
							<input type="hidden" name="all_holidays" id="all_holidays" value="{{$all_holidays}}" />
						
						
						
						<div class="row">
                                <div class="col-md-12">
                                    <legend><i class="fa fa-truck" aria-hidden="true"></i> Delivery Information</legend>
                                    <table class="table table-striped">
                                       
                                        <tbody>
                                            <tr>
                                                <td>Delivery Date</td><td><input type="text" name="shipdate" id="datetimepicker12" value="{{Session::get('shipdate')}}" class="form-control"></td>
                                            </tr>
                                            <tr>
                                                <td>Earliest delivery time</td>
                                                <td>
                                                    <select name="hour">
                                                        <option value="0" selected="">00</option>
                                                        <option value="1" @if(Session::get('hour') == 1) selected="selected" @endif>01</option>
                                                        <option value="2" @if(Session::get('hour') == 2) selected="selected" @endif>02</option>
                                                        <option value="3" @if(Session::get('hour') == 3) selected="selected" @endif>03</option>
                                                        <option value="4" @if(Session::get('hour') == 4) selected="selected" @endif>04</option>
                                                        <option value="5" @if(Session::get('hour') == 5) selected="selected" @endif>05</option>
                                                        <option value="6" @if(Session::get('hour') == 6) selected="selected" @endif>06</option>
                                                        <option value="7" @if(Session::get('hour') == 7) selected="selected" @endif>07</option>
                                                    </select>
                                                    <select name="minute">
                                                        <option value="0" selected="">00</option>
                                                        <option value="10" @if(Session::get('minute') == 10) selected="selected" @endif>10</option>
                                                        <option value="20" @if(Session::get('minute') == 20) selected="selected" @endif>20</option>
                                                        <option value="30" @if(Session::get('minute') == 30) selected="selected" @endif>30</option>
                                                        <option value="40" @if(Session::get('minute') == 40) selected="selected" @endif>40</option>
                                                        <option value="50" @if(Session::get('minute') == 50) selected="selected" @endif>50</option>        
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Comments</td>
                                                <td><textarea name="comments" class="form-control">{{Session::get('comments')}}</textarea></td>
                                            </tr>
                                            <tr>
                                                <td>Delivery Procedure eg leave with 24hour security / loading bay</td>
                                                <td><textarea name="delivery_procedure" class="form-control">{{Session::get('delivery_procedure')}}</textarea></td>
                                            </tr>
                                        </tbody>
                                        
                                    </table>
                                </div>
                            </div>
                    
                    <!-- address creation successfull message -->
                    @if(Session::has('message4'))
                            <div class="alert alert-success alert-dismissible">
                                {{ Session::get('message4') }}
                            </div>
                    @endif
                      
                    <div class="row">
                            
                          <div class="col-md-12">
                                <legend><i class="fa fa-home"></i> Address Book Entries</legend>
                            @if($total_address < 5)    
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#add_address">Add Address</button>
                            @endif
                          </div>
                          
                    </div>  
                        
                        @if(isset($addresses))
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped">
                                        <thead>
                                            <th>Name</th>
                                            <th>Company Name</th>
                                            <th>Address</th>
                                            <th>Billing Address</th>
                                            <th>Shipping Address</th>
                                        </thead>
                                        <tbody>
                                            @foreach($addresses as $key => $address)
                                                <tr>
                                                    <td>{{ $address->first_name }} {{ $address->last_name }}</td>
                                                    <td>{{ $address->company_name }}</td>
                                                    <td>
                                                        {{$address->street_address}}<br />
                                                        {{$address->address_line_2}}<br />{{$address->city}} {{$address->post_code}}<br />
                                                        {{$address->county_state}}, United Kingdom
                                                    </td>
                                                     <td>
                                                        <label class="col-md-6 col-md-offset-3">
                                                        <input
                                                                    type="radio"
                                                                    value="{{ $address->id }}"
                                                                    name="billing_address"
                                                                    @if($default_address_id == $address->id || Session::get('billing_address') == $address->id) checked="checked"  @endif>
                                                        </label>
                                                    </td>
                                                    
                                                    <td>
                                                        <label class="col-md-6 col-md-offset-3">
                                                        <input
                                                                    type="radio"
                                                                    value="{{ $address->id }}"
                                                                    name="delivery_address"
                                                                    @if($default_address_id == $address->id || Session::get('delivery_address') == $address->id) checked="checked"  @endif>
                                                        </label>
                                                    </td>
                                                    
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        
                                    </table>
                                </div>
                            </div>
                        @endif
                                           
                        <div class="row">
                            <div class="col-md-12">
                                <legend><i class="fa fa-credit-card"></i> Payment</legend>
                                @if(isset($payments) && !empty($payments))
                                    <table class="table table-striped">
                                        <thead>
                                        <th class="col-md-4">Name</th>
                                        <th class="col-md-4">Description</th>
                                        <th class="col-md-4 text-right">Choose payment</th>
                                        </thead>
                                        <tbody>
                                        @foreach($payments as $payment)
                                            @include('layouts.front.payment-options', compact('payment', 'total', 'shipment'))
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="alert alert-danger">No payment method set</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                              
                                <legend><i class="fa fa-coupon"></i> Discount Coupons</legend>
                                Enter Redeem Code&nbsp;&nbsp;<input type="text" name="redeem_code" class="form-control"> and click <br><input type="submit" name="submit_coupon" value="Reedeem" class="btn btn-primary">
                             <br>

                                </div>
                        </div>
                        
                    @else
                        <p class="alert alert-danger"><a href="javascript:void(0)" data-toggle="modal" data-target="#add_address">No address found. You need to create an address first here.</a></p>
                    @endif
                    <button type="submit" class="btn btn-primary" style="margin-top:20px;">
                                            Continue
                                        </button>
                    
                </div>
            </div>
        </form>
        @else
            <div class="row">
                <div class="col-md-12">
                    <p class="alert alert-warning">No products in cart yet. <a href="{{ route('home') }}">Show now!</a></p>
                </div>
            </div>
        @endif
    </div>

<div id="add_address" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Address</h4>

                </div>
                <div class="modal-body">
                    
                    <form action="{{ route('customer.address.store', ['customerId' => $customer->id, 'page' => 'checkout']) }}" method="post" class="form" enctype="multipart/form-data">
                <input type="hidden" name="status" value="1">
                <div class="box-body">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                        <label for="first_name">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" id="first_name" placeholder="First Name" required="required" class="form-control" value="{{ old('first_name') }}">
                        @if ($errors->has('first_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                        <label for="last_name">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" id="last_name" placeholder="Last Name" required="required" class="form-control" value="{{ old('last_name') }}">
                        @if ($errors->has('last_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
                        <label for="company_name">Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="company_name" id="company_name" placeholder="Company Name" required="required" class="form-control" value="{{ old('company_name') }}">
                        @if ($errors->has('company_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('company_name') }}</strong>
                                        </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('street_address') ? ' has-error' : '' }}">
                        <label for="street_address">Street Address <span class="text-danger">*</span></label>
                        <input type="text" name="street_address" id="street_address" placeholder="Street Address" required="required" class="form-control" value="{{ old('street_address') }}">
                        @if ($errors->has('street_address'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('street_address') }}</strong>
                                        </span>
                        @endif
                    </div>
                     <div class="form-group">
                        <label for="address_line_2">Address Line 2 </label>
                        <input type="text" name="address_line_2" id="address_line_2" placeholder="Address Line 2" class="form-control" value="{{ old('address_line_2') }}">
                    </div>
                     <div class="form-group{{ $errors->has('post_code') ? ' has-error' : '' }}">
                        <label for="post_code">Post Code <span class="text-danger">*</span></label>
                        <input type="text" name="post_code" id="post_code" placeholder="UK Post Code" required="required" class="form-control" value="{{ old('post_code') }}">
                        @if ($errors->has('post_code'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('post_code') }}</strong>
                                        </span>
                        @endif
                     </div>
                     <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                        <label for="city">City <span class="text-danger">*</span></label>
                        <input type="text" name="city" id="city" placeholder="City" required="required" class="form-control" value="{{ old('city') }}">
                        @if ($errors->has('city'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('city') }}</strong>
                                        </span>
                        @endif
                     </div>
                     <div class="form-group{{ $errors->has('county_state') ? ' has-error' : '' }}">
                        <label for="county_state">County <span class="text-danger">*</span></label>
                        <select name="county_state" id="county_state" required="required" class="form-control">
                                <option value="">Please Select</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->state }}">{{ $state->state }}</option>
                                @endforeach
                        </select>
                        @if ($errors->has('county_state'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('county_state') }}</strong>
                                        </span>
                        @endif
                     </div>
                    <div class="form-group">
                        <label for="country_id">Country </label>
                        <input type="hidden" name="country_id" id="country_id" value="225">
                        United Kingdom
                    </div>
                   
                  <!--  <div class="form-group">
                        <input type="checkbox" name="primary_address" value="1"> Set as primary address.
                    
                    </div> -->
                   
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>



@endsection