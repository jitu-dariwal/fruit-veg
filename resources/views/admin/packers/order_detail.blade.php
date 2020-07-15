@extends('layouts.admin.app')
@section('css')
<style>

/* width */
::-webkit-scrollbar {
    width: 20px;
}

/* Track */
::-webkit-scrollbar-track {
    box-shadow: inset 0 0 5px grey; 
    border-radius: 10px;
}
 
/* Handle */
::-webkit-scrollbar-thumb {
    background: #7caee4; 
    border-radius: 10px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    background: #5e9fe9; 
}
</style>
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('css/jquery.datetimepicker.min.css') }}">
@endsection
@section('content')
<!-- Main content -->
<section class="content">
    @include('layouts.errors-and-messages')
    <form method="post" id="order_product_status" action="">
        @csrf
		<input type="hidden" name="order_id" value="{{$order->id}}">
		<input type="hidden" name="customer_id" value="{{$order->customer_id}}">
        <div class="box" id="added-product-list">
            @include('admin/packers/order-product-list')  
        </div>
	</form>
    <!-- /.box -->
    <form method="post" id="order_delivery_info" action="">
        @csrf
        <input type="hidden" name="order_id" value="{{$order->id}}">
        <input type="hidden" name="customer_id" value="{{$order->customer_id}}">
       
        <!-- /.box -->
        <!-- /.box -->
        <!---------Payment Method----------->
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12 ">
                        
                        <!------Status Logs--------->
						<div class="table-responsive">
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
                                    <td class="smallText">{{$order_status_his->order_status->name}}</td>
                                    <td class="smallText">{!!$order_status_his->comments!!}</td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
						</div>
						<div class="table-responsive">
                        <!-------Update & Comment----------->
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                   <td><span style="padding-top:5px;">
              <b>Message:</b><br>
              <select name="delivery_message" style="height:40px; font-size:20px;">
                <option value="" style="height:40px;">Select Message</option>
                <option value="Sorry, there was an item unavailable at time of packing." style="height:40px;">
                Sorry, there was an item unavailable at time of packing.</option>
                <option value="Sorry, the items missing were not of suitable quality." style="height:40px;">
                Sorry, the items missing were not of suitable quality.</option>
                <option value="We have substituted an alternative product for one of your products." style="height:40px;">We have substituted an alternative product for one of your products.</option>
                <option value="Sorry, a product was out of season." style="height:40px;">Sorry, a product was out of season.</option>
              </select>
            </span>&nbsp;</td>
			 <td>
									<span style="padding-top:5px;">
									<b>Notify Customer:</b><br>
									<input type="checkbox" value="1" name="notify_user" style="width:50px; height:50px;">
									</span>&nbsp;
									</td>
                                </tr>
                                
                                <tr>
                                    
                                    <td>
									<span style="padding-top:5px;">
              <b>Message:</b><br>
                                        <select name="status" style="width:160px; height:40px; font-size:20px;">
										@foreach($order_status as $o_status)
                                            <option style="height:40px;" {{ ($order->order_status_id==$o_status->id) ? 'selected' : '' }} value="{{$o_status->id}}">{{$o_status->name}}</option>
										@endforeach
                                        </select>
										</span>&nbsp;
										<td>
									<span style="padding-top:5px;">
									<b>Append Comments:</b><br>
									<input type="checkbox" value="1" name="append_comments" style="width:50px; height:50px;">
									</span>&nbsp;
									</td>
                                    </td>
                                </tr>

                                <tr>
                                    
                                   
                                </tr>
                                

                            </tbody>
                        </table>	
                        </div>						
                        <div class="box-footer">
                            <strong>Please Update This Point!:</strong>
                            <button type="button" id="updated_delivery_information" class="btn btn-primary ">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
     
	<div class="box">
        <div class="box-header">
            <div class="row">
                <div class="col-md-6">
                    <h3>Customer:</h3>
                    <dl class="address-display">
                        <dt class=""><small>Name :</small></dt>
                        <dd class="">{{ucwords($order->orderDetail->first_name . ' ' . $order->orderDetail->last_name)}}</dd>                          
                        <dt class=""><small>Company Name :</small> </dt>
                        <dd class="">{{$order->orderDetail->company_name}}</dd>
                        <dt class=""><small>Your Address :</small> </dt>
                        <dd class="">{{$order->orderDetail->street_address}}</dd>
                        <dt class=""><small>Address Line 2 :</small> </dt>
                        <dd class="">{{$order->orderDetail->address_line_2}}</dd>
                        <dt class=""><small>City :</small> </dt>
                        <dd class="">{{$order->orderDetail->city}}</dd>
                        <dt class=""><small>State :</small> </dt>
                        <dd class="">{{$order->orderDetail->country_state}}</dd>
                        <dt class=""><small>PostCode :</small> </dt>
                        <dd class="">{{$order->orderDetail->post_code}}</dd>
                        <dt class=""><small>Country :</small> </dt>
                        <dd class="">{{$order->orderDetail->country}}</dd>                          
                    </dl>
                </div>
                <div class="col-md-6">                        
                    <h3>Billing Address:</h3>
                    <dl class="address-display">
                        <dt class=""><small>Name:</small></dt>
                        <dd class="">{{ucwords($order->orderDetail->billing_name)}}</dd>
                        <dt class=""><small>Company Name :</small> </dt>
                        <dd class="">{{$order->orderDetail->billing_company}}</dd>
                        <dt class=""><small>Your Address : </small></dt>
                        <dd class="">{{$order->orderDetail->billing_street_address}}</dd>
                        <dt class=""><small>Address Line 2 : </small></dt>
                        <dd class="">{{$order->orderDetail->billing_address_line_2}}</dd>
                        <dt class=""><small>City : </small></dt>
                        <dd class="">{{$order->orderDetail->billing_city}}</dd>
                        <dt class=""><small>State : </small></dt>
                        <dd class="">{{$order->orderDetail->billing_state}}</dd>
                        <dt class=""><small>PostCode : </small></dt>
                        <dd class="">{{$order->orderDetail->billing_postcode}}</dd>
                        <dt class=""><small>Country : </small></dt>
                        <dd class="">{{$order->orderDetail->billing_country}}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>


</section>
<!-- /.content -->

@endsection

@section('js')
<script>

$('.actual_wgt_cal').click(function() {
        var newwindow = window.open($(this).prop('href'), '', 'width=540,height=450,top=180,left=280');
        if (window.focus) {
            newwindow.focus();
        }
        return false;
    });
	
$(document).on('change','.product_packed',function(){
	
	//check Product is short or not
	var $id =$(this).attr('data-id');
	var is_packed =$(this).val();
	if(is_packed==1){
	if($('input[name="product_status_'+$id+'"]').is(':checked')){
		alert("This product has already been set to 'NOT AVAILABLE'.");
		$("input[name=packed_"+$id+"]").val([0]);
		return false;
	}
	
	
	if($('input[name="product_short_'+$id+'"]').is(':checked')){
		alert("This product has already been set to 'Short'.");
		$("input[name=packed_"+$id+"]").val([0]);
		return false;
	}
	}
    update_order_product_status();
});
$(document).on('change','.product_available',function(){
	var $id =$(this).attr('data-id');
	var is_packed =$("input[name=packed_"+$id+"]:checked").val();
	if($(this). prop("checked") == true){
		if(is_packed==1){
		alert("This product has already been Packed.");
		$(this).prop('checked',false );
		return false;
		}
	}
	if($(this). prop("checked") == true){
      if($('input[name="product_short_'+$id+'"]').is(':checked')){
		alert("This product has already been set to 'Short'.");
		$(this).prop('checked',false );
		return false;
	}
	}
	
  update_order_product_status();
});

$(document).on('change','.product_short',function(){
  var $id =$(this).attr('data-id');
  var is_packed =$("input[name=packed_"+$id+"]:checked").val();
  var is_avl =$('packed_'+$id).val();
  if($(this).prop("checked") == true){
	    
		if(is_packed==1){
		alert("This product has already been Packed.");
		$(this).prop('checked',false );
		return false;
		}
		
  
  }
  
  update_order_product_status();
});

$(document).on('change','.order_product_actual_weight',function(){
 update_order_product_status();
});

/******Update Order Changes**********/
function update_order_product_status(){
  var xform = document.getElementById('order_product_status'); 
        var formData = new FormData(xform);
    $.ajax({
            url: "/admin/packer/update-products-status",          
            processData: false,            
            contentType: false,            
            type: 'POST',            
            data:formData,
            beforeSend: function () { 
              
            },
            success: function (data) {
                  $('#added-product-list').html(data);
            }
         
         });  
        return false;
}
/******************Update Order Delivery Info***********/
$(document).on('click','#updated_delivery_information',function(){
  var xform = document.getElementById('order_delivery_info'); 
        var formData = new FormData(xform);
    $.ajax({
            url: "/admin/packer/update-order-status",          
            processData: false,            
            contentType: false,            
            type: 'POST',            
            data:formData,
            beforeSend: function () { 
              
            },
            success: function (data) {
                 if(data==1){
           alert("Order Information Updated Successfully.");
           location.reload();
         }
            }
         
         });  
        return false;
  });
  
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=650,height=500,screenX=150,screenY=150,top=50,left=150')
}
</script>
@endsection