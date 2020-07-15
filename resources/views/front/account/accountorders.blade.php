@extends('layouts.front.app')

@section('content')

<section class="myAccount-wrapper">
    <div class="container">
        <h1>Your account </h1>
        <p>Welcome back Darren, how can we help you today?</p> 
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
<!-- /.content -->
@endsection