@extends('layouts.admin.app')

@section('css')
<!-- old calendar css   -->
<link rel="stylesheet" href="{{ asset('css/jquery.datetimepicker.min.css') }}">

<!-- jQuery UI css (necessary for calendar)
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet"> -->

<style>
	button, input, optgroup, select, textarea{
		//color : black;
	}
	.ui-datepicker{
		//width : 100%;
	}
	.ui-datepicker table.ui-datepicker-calendar tr th:first-child,.ui-datepicker table.ui-datepicker-calendar tr td:first-child{
		//display : none;
	}
</style>

@endsection

@section('content')
<!-- Main content -->
<section class="content">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
	@if(strtotime(Finder::getOrderLockDate($order->orderDetail->shipdate)) <= strtotime(date("Y-m-d")) && $order->order_status_id==3)
		<div class="box">
        <div class="box-header">
            <div class="row">
			<table border="0" width="100%" cellspacing="0" cellpadding="2">
			<tr>
            <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>                 
                  <td class="pageHeading" align="center" style="padding-top:50px;color:red">This order is locked and cannot view it after delivered 2 working days</td>
                </tr>
              </table></td>
            </tr>
			</table>
			</div>
			</div>
			</div>
	@else
    <div class="box">
        <div class="box-header">
            <div class="row">
			
                <div class="col-md-6">
                    <h3>Customer:</h3>
                    <dl class="address-display">
                        <dt>Name :</dt>
                        <dd>{{ucwords($order->orderDetail->first_name . ' ' . $order->orderDetail->last_name)}}</dd>                          
                        <dt>Company Name :</dt>
                        <dd>{{$order->orderDetail->company_name}}</dd>
                        <dt>Your Address :</dt>
                        <dd>{{$order->orderDetail->street_address}}</dd>
                        <dt>Address Line 2 : </dt>
                        <dd>{{$order->orderDetail->address_line_2}}</dd>
                        <dt>City :</dt>
                        <dd>{{$order->orderDetail->city}}</dd>
                        <dt>State :</dt>
                        <dd>{{$order->orderDetail->country_state}}</dd>
                        <dt>PostCode :</dt>
                        <dd>{{$order->orderDetail->post_code}}</dd>
                        <dt>Country :</dt>
                        <dd>{{$order->orderDetail->country}}</dd>                          
                    </dl>
                </div>
                <div class="col-md-6">                        
                    <h3>Billing Address:</h3>
                    <dl class="address-display">
                        <dt>Name:</dt>
                        <dd>{{ucwords($order->orderDetail->billing_name)}}</dd>
                        <dt>Company Name :</dt>
                        <dd>{{$order->orderDetail->billing_company}}</dd>
                        <dt>Your Address : </dt>
                        <dd>{{$order->orderDetail->billing_street_address}}</dd>
                        <dt>Address Line 2 : </dt>
                        <dd>{{$order->orderDetail->billing_address_line_2}}</dd>
                        <dt>City : </dt>
                        <dd>{{$order->orderDetail->billing_city}}</dd>
                        <dt>State : </dt>
                        <dd>{{$order->orderDetail->billing_state}}</dd>
                        <dt>PostCode : </dt>
                        <dd>{{$order->orderDetail->billing_postcode}}</dd>
                        <dt>Country : </dt>
                        <dd>{{$order->orderDetail->billing_country}}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    <form method="post" id="order_product_info" action="">
        @csrf
        <input type="hidden" name="order_id" value="{{$order->id}}">
        <input type="hidden" name="customer_id" value="{{$order->customer_id}}">
        <div class="box">
            <div class="box-body">
                <h4> <i class="fa fa-map-marker"></i> Shipping Address</h4>
                <div class="row">
                    <div class="col-md-6">
                                                
                        <table class="table table-striped">                           
                            <tbody>
                                <tr>
                                    <td width="30%">Name:</td>
                                    <td width="70%"><input type="text" name="sname" id="shipping_name" placeholder="First Name" class="form-control" value="{{ !empty($order->orderDetail->shipping_add_name) ? $order->orderDetail->shipping_add_name : $order->orderDetail->first_name.' '. $order->orderDetail->last_name}}"></td>
                                </tr>
                                <tr>
                                    <td>Company Name:</td>
                                    <td><input type="text" name="scompany_name" id="scompany_name" placeholder="Company Name" class="form-control" value="{{ !empty($order->orderDetail->shipping_add_company) ? $order->orderDetail->shipping_add_company : $order->orderDetail->company_name}}"></td>
                                </tr>
                                <tr>
                                    <td>Your Address:</td>
                                    <td><input type="text" name="shipping_address" id="shipping_address" placeholder="Your Address" class="form-control" value="{{ !empty($order->orderDetail->shipping_street_address) ? $order->orderDetail->shipping_street_address : $customer->street_address}}"></td>
                                </tr>
                                <tr>
                                    <td>Address Line 2:</td>
                                    <td><input type="text" name="shipping_address_line_2" id="shipping_address_line_2" placeholder="Address Line 2" class="form-control" value="{{ !empty($order->orderDetail->shipping_address_line2) ? $order->orderDetail->shipping_address_line2 : $customer->address_line_2}}"></td>
                                </tr>
                                <tr>
                                    <td>City:</td>
                                    <td><input type="text" name="scity" id="scity" placeholder="City" class="form-control" value="{{ !empty($order->orderDetail->shipping_city) ? $order->orderDetail->shipping_city : $customer->city}}"></td>
                                </tr>
                                <tr>
                                    <td>State:</td>
                                    <td>
										<input type="text" name="sstate" required id="sstate" placeholder="State" class="form-control" value="{{ !empty($order->orderDetail->shipping_state) ? $order->orderDetail->shipping_state : $customer->county_state}}">
										
                                        @php /* @endphp<select name="sstate" id="sstate" class="form-control">
                                            <option value="">Please Select</option>
                                            @foreach($states as $state)
                                            <option @if($state->state == $order->orderDetail->shipping_state )selected="selected" @endif value="{{ $state->state }}">{{ $state->state }}</option>
                                            @endforeach
                                        </select>@php */ @endphp
                                    </td>
                                </tr>


                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <td width="30%">Post Code:</td>
                                    <td width="70%"><input type="text" name="spost_code" id="spost_code" placeholder="Post Code" class="form-control" value="{{ !empty($order->orderDetail->shipping_post_code) ? $order->orderDetail->shipping_post_code : $customer->post_code}}"></td>
                                </tr>

                            </thead>
                            <tbody>
                                <tr>
                                    <td>Country:</td>
                                    <td><strong>Country</strong> - United Kingdom <input type="hidden" name="scountry" id="scountry" placeholder="Country" class="form-control" value="United Kingdom"></td>
                                </tr>
                                <tr>
                                    <td>Telephone Number:</td>
                                    <td><input type="text" name="stel_number" id="stel_number" placeholder="Telephone Number" class="form-control" value="{{ !empty($order->orderDetail->shipping_tel_num) ? $order->orderDetail->shipping_tel_num : $order->orderDetail->tel_num}}"></td>
                                </tr>
                                <tr>
                                    <td>E-Mail Address:</td>
                                    <td><input type="text" name="semail_address" id="semail_address" placeholder="E-Mail Address" class="form-control" value="{{ !empty($order->orderDetail->shipping_email) ? $order->orderDetail->shipping_email : $order->orderDetail->email}}"></td>
                                </tr>
                                <tr>
                                    <td>Agent:</td>
                                    <td><input type="text" name="agent" id="agent" placeholder="Agent" class="form-control" value="{{ !empty($order->orderDetail->agent_name) ? $order->orderDetail->agent_name : ''}}"></td>
                                </tr>
                                <tr>
                                    <td>PO Number:</td>
                                    <td><input type="text" name="po_number" id="po_number" placeholder="PO Number" class="form-control" value="{{ !empty($order->orderDetail->po_number) ? $order->orderDetail->po_number : ''}}"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>




        <div class="box" id="added-product-list">
            @include('admin/orders/order-product-list')  
        </div>
    </form>
    <!-- /.box -->
    <form method="post" id="order_delivery_info" action="">
        @csrf
        <input type="hidden" name="order_id" value="{{$order->id}}">
        <input type="hidden" name="customer_id" value="{{$order->customer_id}}">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4><i class="fa fa-calendar" aria-hidden="true"></i> Select Arrival Date</h4>
                        <div id="demo1-2"></div>
                        <div class="bg-success p-3 mb-3"><strong>Your Order Will Arrive On <span id="date-text1-2">{{ ($order->orderDetail->shipdate) ? \Carbon\Carbon::parse($order->orderDetail->shipdate)->format('d-m-Y') : date('d-m-Y')}}</span></strong></div>
						
                        <input type="hidden" id="delivery_date" value="{{ ($order->orderDetail->shipdate) ? \Carbon\Carbon::parse($order->orderDetail->shipdate)->format('d-m-Y') : date('d-m-Y')}}" name="delivery_date"/>
                    </div>

                    <div class="col-md-6">
                        <!-----------Preferred Delivery Time---------->
                       <h4><i class="fa fa-calendar" aria-hidden="true"></i> Delivery window</h4>
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
									<th colspan="2" ><strong>Preferred delivery window:</strong></th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
									<td colspan="2">
										@php
											$selectOption = old('delivery_window', ( ($order->orderDetail->Access_Time) ? $order->orderDetail->Access_Time : '' ));
										@endphp
										
										<select name="delivery_window" id="delivery_window" class="form-control" required>
											<option value="">Select time</option>
											@if(!empty(config('constants.delivery_window_options')))
												@foreach(config('constants.delivery_window_options') as $key => $option)
													<option value="{{ $key }}" @if($selectOption == $key) selected="selected" @endif>{{ $option }}</option>
												@endforeach
											@endif
										</select>
									</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box -->
        <!-- /.box -->
        <!---------Payment Method----------->
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">                            
                            <tbody>
							@php 
							$paymenttypes=Finder::getPaymentMethods();
							@endphp
                                <tr>
                                    <td width="40%"><b>Payment Method:</b></td>
                                    <td  width="60%">
                                        <select class="form-control update_info_payment_method" name="update_info_payment_method">
										@foreach($paymenttypes as $key=>$value)
                                            <option {{($order->payment_method==$key) ? 'selected' : ''}} value="{{$key}}" >{{$value}}</option>
                                        @endforeach
                                        </select> or View PO Fields
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!------Status Logs--------->
                        <table class="table " border="1" >
                            <thead>
                                <tr>
                                    <th class="smallText" align="center"><b>Date Added</b></th>
                                    <th class="smallText" align="center"><b>Customer Notified</b></th>
                                    <th class="smallText" align="center"><b>Status</b></th>
                                    <th class="smallText" align="center"><b>Comments</b></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->order_status_historys as $order_status_his)
                                <tr>
                                    <td class="smallText" align="center">{{ \Carbon\Carbon::parse($order_status_his->created_at)->format('d/M/Y h:i A')}}</td>
                                    <td class="smallText" align="center">
                                        @if($order_status_his->customer_notified==1)
                                        <i style="color:green;" class="fa fa-check-circle" aria-hidden="true"></i>
                                        @else
                                        <i style="color:red;" class="fa fa-times-circle" aria-hidden="true"></i>
                                        @endif
                                    </td>
                                    <td class="smallText">@if(isset($order_status_his->order_status->name)) {{$order_status_his->order_status->name}} @endif </td>
                                    <td class="smallText">{!!$order_status_his->comments!!}</td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                        <!-------Update & Comment----------->
						 
                          
                           
                           <table class="table table-bordered">                           
                            <tbody>
								<tr>
                                    <td width="50%">Comments: </td>
                                    <td width="50%"><textarea name="comments" class="form-control">{{($order->orderDetail->comment) ? $order->orderDetail->comment : ''}}</textarea></td>
                                </tr>
                                <tr>
                                    <td width="50%">Here is the notes you provided us at sign up. Please feel free to edit as you wish: </td>
                                    <td>
										<textarea class="form-control" name="delivery_notes" placeholder="">{{ old('delivery_notes', $customer->customers_invoice_notes) }}</textarea>
									</td>
                                </tr>
                                <tr>
                                    <td>Status: </td>
                                    <td>
                                        <select name="status" class="form-control">
										@foreach($order_status as $o_status)
                                            <option {{ ($order->order_status_id==$o_status->id) ? 'selected' : '' }} value="{{$o_status->id}}">{{$o_status->name}}</option>
										@endforeach
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Notify Customer: </td>
                                    <td><input type="checkbox" value="1" name="notify_user" ></td>
                                </tr>
                                <!--<tr>
                                    <td>Append Comments: </td>
                                    <td><input type="checkbox" value="1" checked name="append_comments"></td>
                                </tr>-->

                            </tbody>
                        </table>	
                       				
                       						
                        						
                        <div class="box-footer">
						<a href="{{route('admin.orders.paywith-cc', $order->id)}}" style="{{($order->payment_method=='secpay' || $order->payment_method=='credit-card')?'display:block;':'display:none;'}}" class="btn btn-primary paywithcc  pull-left">Pay with CC</a>
                            <div class="d-flex align-items-center justify-content-end">
							
                            <strong class="mr-4">Please Update This Point!:</strong>
                            <button type="button" id="updated_delivery_information" class="btn btn-primary ">Update</button>
							<span></span>
							<a onclick="window.history.go(-1);" class="btn btn-default  pull-right">Back</a>
							</div>
							
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


@endif
</section>
<!-- /.content -->
<!---------Add Product Modal---->
<div class="modal fade" id="addproduct" tabindex="-1" role="dialog" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form method="post" action="{{URL::to('admin/order/'.$order->id.'/add-products')}}">
                        @csrf
                        <input type="hidden" name="order_id" value="{{$order->id}}">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="InputQty">Type:<span class="text-danger">*</span></label><br />
                                <select class="form-control" required id="type" name="type">
                                    <option value="">Select Type</option>
                                    <option value="Bulk">Bulk</option>
                                    <option value="Split">Split</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="InputQty">Category:<span class="text-danger">*</span></label><br />
                                <select class="form-control" required id="category" name="category">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
										<option value="{{$category['id']}}">{{$category['name']}}</option>
										
										@if(isset($category['subcategories']) && !empty($category['subcategories']))
											@foreach($category['subcategories'] as $subCat)
												<option value="{{$subCat['cat_id']}}">----{{$subCat['name']}}</option>
												
												@if(isset($subCat['childcategories']) && !empty($subCat['childcategories']))
													@foreach($subCat['childcategories'] as $childCat)
														<option value="{{$childCat['cat_id']}}">--------{{$childCat['name']}}</option>
													@endforeach
												@endif
											@endforeach
										@endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="product-list"></div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<!----------End Product Modal--->
@endsection

@section('js')

<!-- Old Calendar JS  -->
<script src="{{ asset('js/jquery.datetimepicker.min.js') }}"></script>

<!-- Jquery UI Calendar
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>-->

<script type="text/javascript">
        $(document).ready(function(){
			var today = new Date();
			
			$('#demo1-2').datetimepicker({
				@if(!empty($order->orderDetail->shipdate))
					date: {{\Carbon\Carbon::parse($order->orderDetail->shipdate)->format('Y-m-d')}},
				@else
					date: new Date(),	
				@endif
                viewMode: 'YMD',
				startDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()),
				onDateChange: function(){
                    $('#delivery_date').val(this.getText('DD-MM-YYYY'));
                    $('#date-text1-2').text(this.getText('DD-MM-YYYY'));
                    $('#date-text-ymd1-2').text(this.getText('yyyy-MM-dd'));
                    $('#date-value1-2').text(this.getValue());
                }
            });
			
			
			/* Below commented code for calendar with disableDates */
			
			/* var disableDates = {!! json_encode(explode(',', $customer->shipping_disabled_dates)) !!};
            var disableDay = [1,2,3,4,5,6,7];
			
			@if(isset($bankholidays) && !empty($bankholidays))
				disableDates = disableDates.concat({!! json_encode($bankholidays) !!});
			@endif
			
			@if(isset($postCodesDeliveries[$selectedPostCode]) && !empty($postCodesDeliveries[$selectedPostCode]))
				var postCodesDeliveries = {!! json_encode($postCodesDeliveries[$selectedPostCode]) !!};
				
				var disableDay = postCodesDeliveries.split(',');
				for (a in disableDay ) {
					disableDay[a] = parseInt(disableDay[a]);
				}
			@endif
			
			var default_date = {!! json_encode(explode('-', date('Y-m-d'))) !!};
			
			var defaultDate = new Date(Number(default_date[0]),(Number(default_date[1]) - 1),Number(default_date[2]));
			
			if ($.inArray(defaultDate.getDay(), disableDay) == -1) {
				$('input[name="delivery_date"]').val('');
			}
			
            $('#demo1-2').datepicker({
				@if(!empty($order->orderDetail->shipdate))
					date: {{\Carbon\Carbon::parse($order->orderDetail->shipdate)->format('Y-m-d')}},
				@else
					date: new Date(),	
				@endif
				
				changeMonth: true,
				changeYear: true,
				dateFormat: 'yy-mm-dd',
				minDate: 0,
				defaultDate : defaultDate,
				showMonthAfterYear: true,
				dayNamesMin: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
				
				beforeShowDay: function(date) {
					var getDate = String(date.getDate()).padStart(2, '0');
					var getMonth = String((date.getMonth() + 1)).padStart(2, '0');
					
					dmy = getDate + "-" + getMonth + "-" + date.getFullYear();
					var day = date.getDay();
					
					if ($.inArray(dmy, disableDates) == -1 && $.inArray(day, disableDay) >= 0) {
						return [true, ""];
					}
					else {
						return [false, "", "Unavailable"];
					}
				},
				
				onSelect: function(dateText, inst) {
					var dateObj = new Date(dateText);
					var getDate = String(dateObj.getDate()).padStart(2, '0');
					var getMonth = String((dateObj.getMonth() + 1)).padStart(2, '0');
					
					dmy = getDate + "-" + getMonth + "-" + dateObj.getFullYear();
					
					$('#delivery_date').val(dmy);
                    $('#date-text1-2').text(dmy);
					
					setTimeout(function(){
						addClassWeekHead();
					},2);
					return $(this).trigger('change');
				},
				
				onChangeMonthYear: function(year, month, inst){  
					setTimeout(function(){
						addClassWeekHead();
					},2);
					return $(this).trigger('change');
				},
				
            });
            
			setTimeout(function(){
				addClassWeekHead();
			},100);
			
			function addClassWeekHead(){
				$.each(disableDay, function(k,v){
					$('#demo1-2').find('table.ui-datepicker-calendar thead').find('tr th:nth-child('+(v+1)+')').addClass('enableDays');
				});
			} */
        });
</script>
<script>
$('#type').on('change', function() {
	$(".product-list").html("");
	$("#category").val("");
});
$('#category').on('change', function() {
	var category=this.value;
	var ptype=$("#type").val();
    if(ptype ==''){
		alert("Select type first.");
		$("#category").val("");
		return false;
	}
	
    if(category!=''){
		$.ajax( {
			url: '/admin/orders/category-products/'+ptype+'/'+category,
			type: "GET",
			cache: false,
			async: false,
			success: function ( data ) {
				$(".product-list").html(data);
			}
        });
    }else{
      alert('Select Category.');
    }
});

$(document).ready(function () {
  /*$('#category, #product').select2({
    placeholder: "Select Category"
  });
  $('#category').on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Search by Category name");
  });
  $('#product').on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Search by Product name");
  });
  $('#category, #product').on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });*/
});
$(document).on('change','.product_packed',function(){
  order_amount_cal();
  update_order_details();
});
$(document).on('change','.product_quantity',function(){
  order_amount_cal();
  update_order_details();
});
$(document).on('change','#customer_discount',function(){
  order_amount_cal();
  update_order_details();
});
$(document).on('change','#shipping_charges',function(){
  order_amount_cal();
  update_order_details();
}); 
$(document).on('click','#update-order-product-details',function(){
  order_amount_cal();
  update_order_details();
});
/********Order Calculation***********/
function order_amount_cal(){
  var sub_total=$('#sub_total').val();
  var customer_discount=$('#customer_discount').val();
  var delivery_charges=$('#shipping_charges').val();
  var order_total=parseFloat(sub_total)+parseFloat(delivery_charges); //
  order_total=parseFloat(order_total)-parseFloat(customer_discount);
  $('#order_total').html(order_total);
  $('#total_amount').val(order_total);
}

/******Update Order Changes**********/
function update_order_details(){
  var xform = document.getElementById('order_product_info'); 
        var formData = new FormData(xform);
    $.ajax({
            url: "/admin/order/update-products-info/update",          
            processData: false,            
            contentType: false,            
            type: 'POST',            
            data:formData,
            beforeSend: function () { 
              
            },
            success: function (data) {
                  $('#added-product-list').html(data);
				  $('.success_msg_wrapper .msg').html('Order Information Updated Successfully.');
				  $('.success_msg_wrapper').show();
				  $("html, body").animate({ scrollTop: 0 }, "slow");
				  //alert("Order Information Updated Successfully.");
            }
         
         });  
        return false;
}

/******************Update Order Delivery Info***********/
$(document).on('click','#updated_delivery_information',function(){
  var xform = document.getElementById('order_delivery_info'); 
        var formData = new FormData(xform);
    $.ajax({
            url: "/admin/order/delivery-info/update",          
            processData: false,            
            contentType: false,            
            type: 'POST',            
            data:formData,
            beforeSend: function () { 
              
            },
            success: function (data) {
                 if(data==1){
           //alert("Order Information Updated Successfully.");
		   $('.success_msg_wrapper .msg').html('Order Information Updated Successfully.');
		   $('.success_msg_wrapper').show();
		   $("html, body").animate({ scrollTop: 0 }, "slow");
           window.setTimeout(function(){location.reload()},3000);
         }
            }
         
         });  
        return false;
  });

 /* $(document).on('change','.update_info_payment_method',function(){
  var $paymentMethod = $(this).val();
  if($paymentMethod=='secpay' || $paymentMethod=='credit-card'){
	  $('.paywithcc').show();
  }else{
	  $('.paywithcc').hide();
  }
  });*/
</script>
@endsection