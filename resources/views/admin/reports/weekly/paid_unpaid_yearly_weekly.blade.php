@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        
            <div class="box">
                <div class="box-body">
                    <h2>Customer Statements - Paid and Unpaid Yearly in Weekly Invoices Pattern</h2>
                    <div class="table-responsive">
					<!-- search form -->
					<form class="form-inline">
					
					<table class="table table-striped">
					<tr>
					<td>
					@php $paymenttypes=Finder::getPaymentMethods(); @endphp
					  <label class="" for="inlineFormInputName2">Show Invoices Only:</label></td>
					  <td class="week_numbers">
					  <select class="form-control orders_method_type" name="orders_method_type">
					  @foreach($paymenttypes as $key=>$value)
                                      <option {{($invoice_type===$key) ? 'selected' : ''}} value="{{$key}}" >{{$value}}</option>
					  @endforeach
                                    </select>
					</td>
					<td>
					  <label class="" for="inlineFormInputGroupUsername2">Customer:</label></td>
					  <td>
						<select class="form-control cId" style="width:100%;" name="cId">
                            <option value="">Select</option>
                            @foreach($customers as $cust)
							  <option {{($cId==$cust->id) ? 'selected' : ''}} value="{{$cust->id}}">{{ucfirst($cust->defaultaddress->company_name)}}- {{ucfirst($cust->first_name.' '.$cust->last_name)}} - ({{$cust->id}})</option>
							@endforeach						
                        </select>
						</td>
                    </tr>
                    
					  <tr>
					  <td>
					  <label class="" for="inlineFormInputName2">Display for the year:</label></td>
					  <td>
					  <select class="form-control year" name="year">
						  @for($y=date('Y'); $y>=2000;$y--)
						  <option {{($year==$y) ? 'selected' : ''}} value="{{$y}}">{{$y}}</option>
						  @endfor
						</select>
					  </td>
					  
					  <td>
					  <label class="" for="inlineFormInputGroupUsername2">Show Delivered Orders Only:</label></td>
					  <td>
						<select class="form-control orders_type" name="orders_type">
                                      <option {{($order_status=="3") ? 'selected' : ''}} value="3" selected="">Delivered</option>
                                      <option {{($order_status=="5") ? 'selected' : ''}} value="5">Canceled</option>
                                    </select>
						</td>
						</tr>
						
						</table>
						<div style="margin-bottom:5px;">
					  <button type="submit" class="btn btn-primary mb-2">Submit</button>
					  <a href="{{route('admin.reports.paid-unpaid-customer-weekly')}}" class="btn btn-default mb-2">Reset</a>
					  </div>
					</form>
					
					<!-- /.search form -->
				</div>
                    <div class="table-responsive">
					@if($customer)
					<table class="table" width="600" style="margin-bottom:5px;" border="0" bordercolor="#000000">
                          <tbody><tr>
                            <td bordercolor="#000000" bgcolor="#CCCCCC" class="heading">Company</td>
                            <td bordercolor="#000000" class="row2">{{$customer->defaultaddress->company_name}}</td>
                            <td bordercolor="#000000" bgcolor="#CCCCCC" class="heading">Date</td>
                            <td bordercolor="#000000" class="row2">{{date('jS M Y')}}</td>
                          </tr>
                          <tr>
                            <td bordercolor="#000000" bgcolor="#CCCCCC" class="heading">Contact</td>
                            <td bordercolor="#000000" class="row2">{{ucfirst($customer->first_name.' '.$customer->last_name)}}</td>
                            <td bordercolor="#000000" bgcolor="#CCCCCC" class="heading">Telephone</td>
                            <td bordercolor="#000000" class="row2">{{$customer->tel_num}}</td>
                          </tr>
                          <tr>
                            <td bordercolor="#000000" bgcolor="#CCCCCC" class="heading">Address</td>
                            <td bordercolor="#000000" class="row2">{{$customer->defaultaddress->street_address}}<br>{{$customer->defaultaddress->address_line_2}}<br>{{$customer->defaultaddress->city}}<br>{{$customer->defaultaddress->county_state}}<br></td>
                            <td bordercolor="#000000" bgcolor="#CCCCCC" class="heading">Email</td>
                            <td bordercolor="#000000" class="row2">Customers email : {{$customer->email}} <br>  </td>
                          </tr>
						  <tr>
						  <td colspan="4"><strong>TA = Total Amount, PP = Part Payment, DA = Due Amount</strong></td>
						  </tr>
                    </tbody>
					</table>
					@endif
                                        <div class="table-responsive">
					<table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th class="">Week number / year.</th>
                                <th class="">Statement</th>
                                <th class="">Payment Type</th>
                                <th class="">Total</th>
                                <th class="">Paid/Unpaid</th>
                                <th class="">PO Number</th>
                                <th class="">Payment Method</th>
                                <th class="">REMITTANCE</th>
                                <th class="">InvoiceID</th>
                                <th class="">PaidDateByClient</th>
                                <th class="">Notes</th>
                                <th class="">PART PAYMENT</th>
                            </tr>
                        </thead>
                        <tbody>
						@php 
						$i=0; 
						
						@endphp
						@if(count($getinvoice)>0 && !empty($customer))
                        @foreach($getinvoice as $inv_key=>$inv_val)
						
						@php 
						$i++;
						$week_no=$getinvoice[$inv_key]['week'];
						$invoiceStatus=0;
						$is_locked=0;
						$paid_method=0;
						$is_remittance=0;
						$paid_date=0;
						$po_no=0;
						$po_number_confirm=0;
                        $invoiceId=Finder::getInvoiceId($week_no,$year,$customer->id);
                        $getInvoiceinfo=Finder::getInvoiceStatus($invoiceId);
                        if($getInvoiceinfo){
							$invoiceStatus=$getInvoiceinfo->status;
							$is_locked=$getInvoiceinfo->is_confirm;
							$paid_method=$getInvoiceinfo->payment_method;
						    $is_remittance=$getInvoiceinfo->remittance;
						    $paid_date=$getInvoiceinfo->paid_date;
							$po_no=$getInvoiceinfo->po_number;
							$po_number_confirm=$getInvoiceinfo->po_number_confirm;
						}							
						@endphp
						
						<tr data-id="{{$invoiceId}}">
						<td>{{$week_no}}/{{$year}}</td>
						<td>
						<a href="{{route('admin.reports.weekly-invoice').'?customer='.$customer->id.'&week_number='.$week_no.'&year='.$year}}" target="_blank" title="Invoice Detail"><i class="fa fa-file-text"  aria-hidden="true"></i></a> 
						<a href="{{route('admin.reports.customer-weekly-statement')}}?cId={{$customer->id}}&year={{$year}}" target="_blank" title="Customer Statement"><i  class="fa fa-file-text-o" aria-hidden="true"></i></a>
						</td>
						<td>{{(!empty($getinvoice[$inv_key]['invoice']->payment_method)) ? $paymenttypes[$getinvoice[$inv_key]['invoice']->payment_method] : ''}}</td>
						<td>{!! config('cart.currency_symbol') !!} {{$getinvoice[$inv_key]['invoice']->inovice_total}}</td>
						<td>
						<form action="" method="POST" class="update_payment_{{$invoiceId}}">
						<input type="hidden" name="week" value="{{$week_no}}">
						<input type="hidden" name="month" value="0">
						<input type="hidden" name="year" value="{{$year}}">
						<input type="hidden" name="invoiceid" value="{{$invoiceId}}">
						<input type="hidden" name="customer_id" value="{{$customer->id}}">
						<input type="hidden" name="start_date" value="{{$start_d}}">
						<input type="hidden" name="end_date" value="{{$end_d}}">
						<input type="hidden" name="type" value="weekly">
						<select style="width: 91%;" name="status" @if($is_locked==1) disabled @else  class="payment_status" data-id="{{$invoiceId}}" @endif>
						  <option value="" selected="">Select Status</option>
						  @foreach(Config::get('constants.INVOICE_STATUS') as $key=>$val)
						  <option {{($invoiceStatus==$key) ? 'selected' : ''}} value="{{$key}}">{{$val}}</option>
						  @endforeach
						</select>
						</form>
						@if($invoiceStatus==2 && $is_locked==0)
						<a class="locked_{{$invoiceId}}" href="{{route('admin.invoice.lock',$invoiceId)}}"><i class="fa fa-unlock" aria-hidden="true"></i> Lock Invoice</a>
					    @elseif($invoiceStatus==2 && $is_locked==1)
							<a class="locked_{{$invoiceId}}" href="javascript:void(0);"><i class="fa fa-lock" aria-hidden="true"></i> Invoice Locked</a>
					    @endif
						<a class="locked_{{$invoiceId}}" style="display:none;" href="{{route('admin.invoice.lock',$invoiceId)}}"><i class="fa fa-unlock" aria-hidden="true"></i> Lock Invoice</a>
						</td>
						
						<td>
						@if(!empty($po_no) && $po_number_confirm==0)
						<input type="text" value="{{$po_no}}"  name="po_number" style="width: 60px;">
						<a href="javascript:void(0);" class="add_po_number" title="Add PO Number"><i class="fa fa-plus-circle" style="font-size: 25px;" aria-hidden="true"></i></a>
						<a class="polocked_{{$invoiceId}}" href="{{route('admin.invoice.po_number.lock',$invoiceId)}}"><i class="fa fa-unlock" aria-hidden="true" title="Lock PO Number"></i></a>
					    @elseif(!empty($po_no)  && $po_number_confirm==1)
						<input type="text" value="{{$po_no}}"  readonly style="width: 60px;">
							<a class="polocked_{{$invoiceId}}" href="javascript:void(0);"><i class="fa fa-lock" aria-hidden="true"></i>Locked</a>
						@else
						<input type="text" value="{{$po_no}}"  name="po_number" style="width: 60px;">
						<a href="javascript:void(0);" class="add_po_number" title="Add PO Number"><i class="fa fa-plus-circle" style="font-size: 25px;" aria-hidden="true"></i></a>
						<a class="polocked_{{$invoiceId}}" style="display:none;" href="{{route('admin.invoice.po_number.lock',$invoiceId)}}"><i class="fa fa-unlock" aria-hidden="true"></i></a>
					    @endif
						
						</td>
						
						<td>
						<select name="payment_method" style="width: 86%;" class="payment_method">
						  <option value="0">SELECT</option>
						  @foreach($paymenttypes as $key=>$val)
						  <option @if($paid_method===$key) {{ "selected" }} @endif value="{{$key}}">{{$val}}</option>
						  @endforeach
						</select>
						</td>
						<td>
						<select name="remittance" class="remittance">
						  <option {{($is_remittance===0) ? "selected" : '' }} value="0">SELECT</option>
						  <option {{($is_remittance==="yes") ? "selected" : '' }} value="yes">Yes</option>
						  <option {{($is_remittance==="no") ? "selected" : '' }} value="no">No</option>
						</select>
						</td>
						<td>{{$invoiceId}}</td>
						<td>{{(!empty($paid_date)) ? \Carbon\Carbon::parse($paid_date)->format('jS M Y') : 'N/A'}}
						@if(empty($paid_date))
						<br> <input type="text" style="width: 76px;" class="datepicker paid_date" name="paid_date" placeholder="Select Date">
					    @endif
						</td>
						
						
						<td><a href="{{route('admin.invoice-notes.list', [$customer->id,$invoiceId])}}" target="_blank" title="View Invoice Notes" class="btn btn-xs btn-info"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
						<td class="{{ (empty($invoiceStatus)) ? 'not_found' : '' }}">
						@if(empty($invoiceStatus))
							Not Applicable
						@else
						@php
                        $paid_amt=Finder::getPartPayment($invoiceId);
                        @endphp
                        <p>TA: {!! config('cart.currency_symbol') !!} {{$getinvoice[$inv_key]['invoice']->inovice_total}}</p>
                        <p>PP: {!! config('cart.currency_symbol') !!} {{$paid_amt}}</p>
                        <p>DA: {!! config('cart.currency_symbol') !!} {{($getinvoice[$inv_key]['invoice']->inovice_total)-$paid_amt}}</p> 
                        <a href="{{route('admin.invoice-part-payments.list', [$customer->id,$invoiceId])}}" class="btn btn-xs btn-info" target="_blank" title="Add Part Payment"><i class="fa fa-plus" aria-hidden="true"></i></a>
						<a href="{{route('payment-with-cc.index', $invoiceId)}}" target="_blank" class="btn btn-xs btn-primary" title="Pay with CC">Pay with CC</a>
					    @endif
					    </td>
						</tr>
						@endforeach
						@else
						<tr>
						    <td colspan="18" class="not_found">{{Config::get('constants.NO_RECORD_FOUND')}}</td>
						</tr>	
						@endif
						
                        </tbody>
                                        </table></div>
                </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
				
                </div>
            </div>
            <!-- /.box -->
        

    </section>
    <!-- /.content -->
@endsection
@section('js')
<script>
$(document).on('change','#no_status',function(){
	$('#status').val(0);
	if($(this).val() > 0){
	$('#status_div').hide();
	}else{
	$('#status_div').show();	
	}
});
$(document).on('change','#status',function(){
	$('#no_status').val(0);
	if($(this).val() > 0){
	$('#no_status_div').hide();
	}else{
	$('#no_status_div').show();	
	}
});
//Update PO Number
$(document).on('click','.add_po_number',function(){
  var po_number = $(this).closest("td").find("input[name='po_number']").val();
  var invoice_id  = $(this).closest('tr').attr('data-id');
  var formData = "po_number="+po_number+"&invoice_id="+invoice_id;
    $.ajax({
            url: "{{route('admin.invoice.update-invoice-po-number')}}",        
            type: 'POST',
			data: formData,            
            dataType: "json",
            beforeSend: function () { 
              
            },
            success: function (response) {
				$("html, body").animate({ scrollTop: 0 }, "slow");
				if(response.success==true){
                $('.success_msg_wrapper p.msg').html(response.msg);
				$('.success_msg_wrapper').show();
				$('.success_msg_wrapper').delay(3000).fadeOut('slow');
				}else if(response.success==false){
				$('.error_msg_wrapper p.msg').html(response.msg);
				$('.error_msg_wrapper').show();
				$('.error_msg_wrapper').delay(3000).fadeOut('slow');	
				}
            },
			error: function (reject) {
				$("html, body").animate({ scrollTop: 0 }, "slow");
               var errorString = '<ul>';
                    var errors = reject.responseJSON.errors;
					console.log(errors);
                    $.each(errors, function (key, val) {
                    errorString += '<li>'+ val + '</li>';
                    });
					errorString += '</ul>';
                $('.error_msg_wrapper p.msg').html(errorString);
				$('.error_msg_wrapper').show();
				$('.error_msg_wrapper').delay(3000).fadeOut('slow');
            }
         
         });  
        return false;
  });

  /***************/
  $(document).on('change','.payment_status',function(){
  var invoice = $(this).attr('data-id');
  var status = $(this).val();
  //var xform = document.getElementById('update_payment_'+invoice); 
  var formData = $('.update_payment_'+invoice).serialize();
    $.ajax({
            url: "{{route('admin.invoice.add')}}?"+formData,          
            processData: false,            
            contentType: false,            
            type: 'GET',            
            dataType: "json",
            beforeSend: function () { 
              
            },
            success: function (response) {
				$("html, body").animate({ scrollTop: 0 }, "slow");
				if(response.success==true){
					if(status==2){
						$('.locked_'+invoice).show();
					}else{
						$('.locked_'+invoice).hide();
					}
					
                $('.success_msg_wrapper p.msg').html(response.msg);
				$('.success_msg_wrapper').show();
				$('.success_msg_wrapper').delay(3000).fadeOut('slow');
				setTimeout(function() {
					location.reload();
				}, 2000);
				}
            },
			error: function (reject) {
				$("html, body").animate({ scrollTop: 0 }, "slow");
               var errorString = '<ul>';
                    var errors = reject.responseJSON.errors;
					console.log(errors);
                    $.each(errors, function (key, val) {
                    errorString += '<li>'+ val + '</li>';
                    });
					errorString += '</ul>';
                $('.error_msg_wrapper p.msg').html(errorString);
				$('.error_msg_wrapper').show();
				$('.error_msg_wrapper').delay(3000).fadeOut('slow');
            }
         
         });  
        return false;
  });
  /***************/
  $(document).on('change','.payment_method',function(){
  var $paid = $(this);
  var paid_method = $paid.val();
  var invoice_id  = $(this).closest('tr').attr('data-id');
  var formData = 'paid_method='+paid_method+'&invoice_id='+invoice_id;
    $.ajax({
            url: "{{route('admin.invoice.update-payment-method')}}", 
            type: 'POST',            
            data: formData,            
            dataType: "json",
            beforeSend: function () { 
              
            },
            success: function (response) {
				$("html, body").animate({ scrollTop: 0 }, "slow");
				if(response.success==true){
                $('.success_msg_wrapper p.msg').html(response.msg);
				$('.success_msg_wrapper').show();
				$('.success_msg_wrapper').delay(3000).fadeOut('slow');
				}else if(response.success==false){
				$paid.val(0);
				$('.error_msg_wrapper p.msg').html(response.msg);
				$('.error_msg_wrapper').show();
				$('.error_msg_wrapper').delay(3000).fadeOut('slow');	
				}
            },
			error: function (reject) {
				$paid.val(0);
				$("html, body").animate({ scrollTop: 0 }, "slow");
               var errorString = '<ul>';
                    var errors = reject.responseJSON.errors;
					console.log(errors);
                    $.each(errors, function (key, val) {
                    errorString += '<li>'+ val + '</li>';
                    });
					errorString += '</ul>';
                $('.error_msg_wrapper p.msg').html(errorString);
				$('.error_msg_wrapper').show();
				$('.error_msg_wrapper').delay(3000).fadeOut('slow');
            }
         
         });  
        return false;
  });
  /***************/
  $(document).on('change','.remittance',function(){
  var $remittance = $(this);
  var remittance = $remittance.val();
  var invoice_id  = $(this).closest('tr').attr('data-id');
  var formData = 'remittance='+remittance+'&invoice_id='+invoice_id;
    $.ajax({
            url: "{{route('admin.invoice.update-remittance')}}", 
            type: 'POST',            
            data: formData,            
            dataType: "json",
            beforeSend: function () { 
              
            },
            success: function (response) {
				$("html, body").animate({ scrollTop: 0 }, "slow");
				if(response.success==true){
                $('.success_msg_wrapper p.msg').html(response.msg);
				$('.success_msg_wrapper').show();
				$('.success_msg_wrapper').delay(3000).fadeOut('slow');
				}else if(response.success==false){
				$remittance.val(0);
				$('.error_msg_wrapper p.msg').html(response.msg);
				$('.error_msg_wrapper').show();
				$('.error_msg_wrapper').delay(3000).fadeOut('slow');	
				}
            },
			error: function (reject) {
				$remittance.val(0);
				$("html, body").animate({ scrollTop: 0 }, "slow");
               var errorString = '<ul>';
                    var errors = reject.responseJSON.errors;
					console.log(errors);
                    $.each(errors, function (key, val) {
                    errorString += '<li>'+ val + '</li>';
                    });
					errorString += '</ul>';
                $('.error_msg_wrapper p.msg').html(errorString);
				$('.error_msg_wrapper').show();
				$('.error_msg_wrapper').delay(3000).fadeOut('slow');
            }
         
         });  
        return false;
  });
  
    /***************/
  $(document).on('change','.paid_date',function(){
  var $paid_date = $(this);
  var paid_date = $paid_date.val();
  var invoice_id  = $(this).closest('tr').attr('data-id');
  var formData = 'paid_date='+paid_date+'&invoice_id='+invoice_id;
    $.ajax({
            url: "{{route('admin.invoice.update-paid-date')}}", 
            type: 'POST',            
            data: formData,            
            dataType: "json",
            beforeSend: function () { 
              
            },
            success: function (response) {
				$("html, body").animate({ scrollTop: 0 }, "slow");
				if(response.success==true){
                $('.success_msg_wrapper p.msg').html(response.msg);
				$('.success_msg_wrapper').show();
				$('.success_msg_wrapper').delay(3000).fadeOut('slow');
				}else if(response.success==false){
				$paid_date.val('');
				$('.error_msg_wrapper p.msg').html(response.msg);
				$('.error_msg_wrapper').show();
				$('.error_msg_wrapper').delay(3000).fadeOut('slow');	
				}
            },
			error: function (reject) {
				$paid_date.val('');
				$("html, body").animate({ scrollTop: 0 }, "slow");
               var errorString = '<ul>';
                    var errors = reject.responseJSON.errors;
					console.log(errors);
                    $.each(errors, function (key, val) {
                    errorString += '<li>'+ val + '</li>';
                    });
					errorString += '</ul>';
                $('.error_msg_wrapper p.msg').html(errorString);
				$('.error_msg_wrapper').show();
				$('.error_msg_wrapper').delay(3000).fadeOut('slow');
            }
         
         });  
        return false;
  });

</script>
@endsection