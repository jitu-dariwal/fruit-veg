@extends('layouts.front.app')

@section('content')

<section class="myAccount-wrapper">
    <div class="container">
        <h1>Your account </h1>
        <p>{{ __('content.account.welcome_msg',['user_name' => 'Jitu']) }}</p> 
        <div class="myAccount-block">

@include('layouts.front.left-account')		

            
<div class="account-Content-outer">            
               <div class="row">
                   <div class="col-xl-8">
                     <div class="yourOrderBlock">
                       <h6>Your orders</h6>
                           <p>If you have a regular order in place you must contact us to change any details.</p>
                           <p class="mb-0">You can however use your account to order  additional fruitboxes.</p>                           
                           <hr>
                           <h6>Current order</h6>
                           
                           <ul class="my-grid-block list-unstyled">
                               <li><h6>Date</h6>
                                   <p class="mb-0">29/06/2019</p>
                               </li>
                               <li><h6>Invoice no.</h6>
                                   <p class="mb-0">#1234567</p>
                               </li>
                               <li><h6>Price</h6>
                                   <p class="mb-0">£123.00</p>
                               </li>
                               <li>
                                  <a href="#" class="btn btn-greem">View</a>
                               </li>
                           </ul>
                           
                           <div class="custom-right">
                           <a href="#" class="link">view all &#62;</a>
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



<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="box-body">
<!--            @include('layouts.errors-and-messages')-->
        </div>
        <div class="col-md-12">
            <h2> <i class="fa fa-home"></i> My Account</h2>
            <hr>
            <p> If you have a regular order in place you must contact us to change any details.
                You can however use your account to order additional fruitboxes.
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 my-account-categories">
            
            @include('front.shared.categories')
            
         </div>
        <div class="col-md-8 my-account-information">
            <h3>My Account Information</h3>
            <div>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" @if(request()->input('tab') == 'account') class="active" @endif><a href="#account" aria-controls="account" role="tab" data-toggle="tab">Account</a></li>
                    <li role="presentation" @if(request()->input('tab') == 'orders') class="active" @endif><a href="#orders" aria-controls="orders" role="tab" data-toggle="tab">Orders</a></li>
                    <li role="presentation" @if(request()->input('tab') == 'email_notification') class="active" @endif><a href="#email_notification" aria-controls="email_notification" role="tab" data-toggle="tab">E-Mail Notifications</a></li>
                    <li role="presentation" @if(request()->input('tab') == 'address') class="active" @endif><a href="#address" aria-controls="address" role="tab" data-toggle="tab">Addresses</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content customer-order-list">
                    <div role="tabpanel" class="tab-pane @if(request()->input('tab') == 'account')active @endif" id="account">
                      
                        <div class="view-change-account block1" style="padding: 10px;">
                         
                        <h3 style="text-align: center">Edit Account</h3>
                        <p style="color:red;text-align: center;">* Required information</p>
                       @if(Session::has('message'))
                            <div class="alert alert-success alert-dismissible">
                                {{ Session::get('message') }}
                            </div>
                        @endif
                         <div class="account-edit-form">
                             
                             <form class="form-horizontal" role="form" method="POST" action="{{ route('accounts.update', $customer->id) }}">
                                    {{ csrf_field() }}

                               <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                <label for="First Name" class="col-md-4 control-label">First Name<span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                    <input id="first_name" type="text" class="form-control" name="first_name" value="{{$customer->first_name}}">
                                    @if ($errors->has('first_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('first_name') }}</strong>
                                            </span>
                                    @endif
                                    </div>
                                </div>
                                    
                                    <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                <label for="Last Name" class="col-md-4 control-label">Last Name <span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                    <input id="last_name" type="text" class="form-control" name="last_name" value="{{$customer->last_name}}">
                                    @if ($errors->has('last_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('last_name') }}</strong>
                                            </span>
                                    @endif
                                    </div>
                                </div>
                                    
                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="Email" class="col-md-4 control-label">E-Mail Address <span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{$customer->email}}">
                                    @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                    @endif
                                    </div>
                                </div>
                                    
                                    <div class="form-group{{ $errors->has('tel_num') ? ' has-error' : '' }}">
                                <label for="Telephone Number" class="col-md-4 control-label">Telephone Number <span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                    <input id="Telephone Number" type="text" class="form-control" name="tel_num" value="{{$customer->tel_num}}">
                                    @if ($errors->has('tel_num'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('tel_num') }}</strong>
                                            </span>
                                    @endif
                                    </div>
                                </div>
                                    
                                    <div class="form-group{{ $errors->has('fax_num') ? ' has-error' : '' }}">
                                <label for="Fax Number" class="col-md-4 control-label">Fax Number</label>
                                    <div class="col-md-6">
                                    <input id="fax_num" type="text" class="form-control" name="fax_num" value="{{$customer->fax_num}}">
                                    @if ($errors->has('fax_num'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('fax_num') }}</strong>
                                            </span>
                                    @endif
                                    </div>
                                </div>
                                    
                                <div class="form-group{{ $errors->has('customers_acc_trade_contract') ? ' has-error' : '' }}">
                                <label for="Accounts contact" class="col-md-4 control-label">Accounts contact</label>
                                    <div class="col-md-6">
                                    <input id="customers_acc_trade_contract" type="text" class="form-control" name="customers_acc_trade_contract" value="{{$customer->customers_acc_trade_contract}}">
                                    @if ($errors->has('customers_acc_trade_contract'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('customers_acc_trade_contract') }}</strong>
                                            </span>
                                    @endif
                                    </div>
                                </div>
                               
                              <h3 style="text-align: center">Change Password</h3>
                              
                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="Password" class="col-md-4 control-label">Password </label>
                                    <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" onkeyup="nospaces(this)">
                                    @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                    @endif
                                    </div>
                                </div>
                                    
                                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <label for="confirm password" class="col-md-4 control-label">Confirm Password</label>
                                    <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" onkeyup="nospaces(this)">
                                    @if ($errors->has('password_confirmation'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                                            </span>
                                    @endif
                                    </div>
                                </div>
                                    
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            Submit
                                        </button>
                                    </div>
                                </div>
                             </form>
                             
                         </div>
                         
                     </div>
                            

                    </div>
                    <div role="tabpanel" class="tab-pane @if(request()->input('tab') == 'orders')active @endif" id="orders">
                        @if(!empty($orders))
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Date</th>
                                    <th>Order Total</th>
                                    <th>Order Status</th>
                                    <th>Action</th>
                                </tr>
                            </tbody>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                       <td>{{date('l jS F Y', strtotime($order->created_at))}}</td>
                                       <td>£ {{$order->total}}</td>
                                       <td>{{$order->name}}</td>
                                       <td><a href="{{route('accounts.orderdetail', $order->id)}}">View Order</a></td>
                                   </tr>
                                @endforeach
                            </tbody>
                        </table>
                       
                        @else
                        <p class="alert alert-warning">No orders yet. <a href="{{ route('home') }}">Shop now!</a></p>
                        @endif
                    </div>
                    <div role="tabpanel" class="tab-pane @if(request()->input('tab') == 'email_notification')active @endif" id="email_notification">

                       <div class="view-change-account block1" style="padding: 10px;">
                         
                        <h3 style="text-align: center">E-Mail Notifications</h3>
                        
                        <div class="row">
                            
                            <div class="col-md-12">
                                <div class="col-md-6" style="border-right: 1px solid #ccc;">
                                    
                    <h3 style="text-align: center"> Newsletter Subscriptions</h3>
                       @if(Session::has('message1'))
                            <div class="alert alert-success alert-dismissible">
                                {{ Session::get('message1') }}
                            </div>
                        @endif
                         <div class="account-edit-form">
                             
                             <form class="form-horizontal" role="form" method="POST" action="{{ route('accounts.updatenewsletter', $customer->id) }}">
                                    {{ csrf_field() }}

                               <div class="form-group">
                                <input type="checkbox" name="newsletter" id="newsletter" @if($customer->newsletter == 1) checked="checked" @endif value="1"> General Newsletter
                               </div>
                                    
                            <p>Including store news, new products, special offers, and other promotional announcements.</p>     
                                    
                                <div class="form-group">
                                    
                                        <button type="submit" class="btn btn-primary">
                                            Submit
                                        </button>
                                    
                                </div>
                             </form>
                             
                         </div>
                                    
                                    
                                </div> 
                            <div class="col-md-6" style="padding-left: 60px;">
                               <h3 style="text-align: center">Product Notifications</h3>
                                    
                            @if(Session::has('message2'))
                            <div class="alert alert-success alert-dismissible">
                                {{ Session::get('message2') }}
                            </div>
                            @endif
                         <div class="account-edit-form">
                             <p>The product notification list allows you to stay up to date on products you find of interest.</p>

                             <p> To be up to date on all product changes, select <strong>Global Product Notifications</strong>.</p> 
                             
                             <strong>Global Product Notifications</strong>
                             <form class="form-horizontal" role="form" method="POST" action="{{ route('accounts.updateproductnotification', $customer->id) }}">
                                    {{ csrf_field() }}
                                <div class="form-group">
                               <input type="checkbox" name="global_product_notifications" id="global_product_notifications" @if($customer->global_product_notifications == 1) checked="checked" @endif value="1"> Global Product Notifications
                               <p>Recieve notifications on all available products.</p>
                                </div>
                                    
                                    <strong>Product Notifications</strong>
	
                                    <p>To remove a product notification, clear the products checkbox and click on Continue.</p>
                                    
                                <div class="form-group">
                                    
                                        <button type="submit" class="btn btn-primary">
                                            Submit
                                        </button>
                                    
                                </div>
                             </form>
                             
                         </div>  
                                    
                                    
                                    
                                </div>
                            </div>
                            
                        </div>
                        
                   </div>

                    </div>


                    <div role="tabpanel" class="tab-pane @if(request()->input('tab') == 'address')active @endif" id="address">
                        
                        <div class="row">
                            <div class="col-md-12">
                                <strong>Primary Address</strong>
                                <p>This address is used as the pre-selected shipping and billing address for orders placed on this store. This address is also used as the base for product and service tax calculations.</p>
                            </div>
                        </div>
                        
                         @if(Session::has('message4'))
                            <div class="alert alert-success alert-dismissible">
                                {{ Session::get('message4') }}
                            </div>
                         @endif
                        
                        <div class="row" style="margin-bottom: 20px;">
                            <div class="col-md-8">
                                <strong>Address Book Entries</strong>
                            </div>
                           @if($total_address < 5) 
                            <div class="col-md-4" style="text-align: right;"><a href="{{ route('customer.address.create', auth()->user()->id) }}" class="btn btn-primary">Add Address</a></div>
                           @endif
                        </div>
                        
                       
                        @if(!$addresses->isEmpty())
                        <table class="table">
                            <thead>
                            <th>Name</th>
                            <th>Company Name</th>
                            <th colspan="2">Address</th>
                            <th>Primary Address</th>
                            <th>Action</th>
                            </thead>
                            <tbody>
                                @foreach($addresses as $address)
                                <tr>
                                    <td>{{$address->first_name}} {{$address->last_name}} @if ($address->id == $customer->default_address_id) <br /><small style="color:#286090">(primary address)</small> @endif</td>
                                    <td>{{$address->company_name}}</td>
                                    <td  colspan="2">
                                        {{$address->street_address}}<br />
                                        {{$address->address_line_2}}<br />{{$address->city}} {{$address->post_code}}<br />
                                        {{$address->county_state}}, United Kingdom
                                    </td>
                                    <td>
                                        @if ($address->id != $customer->default_address_id)
                                        <a href="{{ route('customer.address.updateprimary', [auth()->user()->id, $address->id]) }}"><img src="{{ asset("images/action_delete.png") }}" title="Make Primary" alt="Make Primary"></a>
                                        @else
                                        <img src="{{ asset("images/action_check.png") }}" alt="">
                                        @endif
                                       
                                        
                                    </td>
                                   <td>
                                        <form method="post" action="{{ route('customer.address.destroy', [auth()->user()->id, $address->id]) }}" class="form-horizontal">
                                            <div class="btn-group">
                                                <input type="hidden" name="_method" value="delete">
                                                {{ csrf_field() }}
                                                <a href="{{ route('customer.address.edit', [auth()->user()->id, $address->id]) }}" class="btn btn-primary"> <i class="fa fa-pencil"></i> Edit</a>
                                              @if ($address->id != $customer->default_address_id)  <button onclick="return confirm('You are about to delete this record?')" type="submit" class="btn btn-danger"> <i class="fa fa-trash"></i> Delete</button>@endif
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <br /> <p class="alert alert-warning">No address created yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
@endsection