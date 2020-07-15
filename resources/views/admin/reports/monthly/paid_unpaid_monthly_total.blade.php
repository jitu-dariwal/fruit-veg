@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        
            <div class="box">
                <div class="box-body">
                    <h2>Customer Statements</h2>
                    <div class="table-responsive">
					<!-- search form -->
					<form class="form-inline">
					
					<table class="table table-striped">
					<tr>
					<td>
					  <label class="" for="inlineFormInputName2">Month:</label></td>
					  <td class="week_numbers">
					  <select class="form-control month" name="month">
						  @foreach(Config::get('constants.MONTHS') as $key=>$val)
					    <option {{($month_no==$key) ? 'selected' : ''}} value="{{$key}}">{{$val}}</option>
					       @endforeach
						</select>
					</td>
					<td>
					  <label class="" for="inlineFormInputGroupUsername2">Show Monthly Invoices Only:</label></td>
					  <td>
						<input type="checkbox" name="monthly_invoice" {{(request()->has('monthly_invoice') && request('monthly_invoice')==1) ? 'checked' : ''}} value="1">
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
						<input type="checkbox" name="delivered" {{(request()->has('delivered') && request('delivered')==1) ? 'checked' : ''}} value="1">
						</td>
						</tr>
						<tr>
						<td><label class="" for="inlineFormInputGroupUsername2">Show UNPAID Only:</label></td>
						<td><input type="checkbox" name="check_paid" {{(request()->has('check_paid') && request('check_paid')==1) ? 'checked' : ''}} value="1"></td>
                                                <td colspan="2">
						<button type="submit" class="btn btn-primary mb-2">Submit</button>
					  <a href="{{route('admin.reports.paid-unpaid-monthly-total')}}" class="btn btn-default mb-2">Reset</a>
                                            </td>
                                            
						</tr>
						</table>
						<div style="margin-bottom:5px;">
					  <a href="{{route('admin.cron.invoices_monthly')}}{{ (Request::getQueryString()) ? '?'.Request::getQueryString() : '' }}" class="btn btn-default mb-2">Import Monthly Invoices type of customers into XERO</a>
					  </div>
					</form>
					
					<!-- /.search form -->
				</div>
                    <div class="table-responsive">
					<table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th class="">S. No.</th>
                                <th class="">Customer Name</th>
                                <th class="">Company</th>
                                <th class="">Statement</th>
                                <th class="">Payment Type</th>
                                <th class="">Total</th>
                                <th class="">Payment Method</th>
                                <th class="">REMITTANCE</th>
                                <th class="">Paid/Unpaid</th>
                                <th class="">Paid Date</th>
                                <th class="">Tick</th>
                                <th class="">InvoiceID</th>
                                <th class="">Action</th>
                            </tr>
                        </thead>
                        <tbody>
						@php 
						$i=($getInvoices->currentPage()-1)*$getInvoices->perPage();  
						$paymenttypes=Finder::getPaymentMethods();
						@endphp
						
                        @foreach($getInvoices as $inovice)
						@php 
						$i++;
						$invoiceStatus=0;
						$is_locked=0;
						$paid_method=0;
						$is_remittance=0;
						$paid_date=0;
                        $invoiceId=Finder::getMonthlyInvoiceId($month_no,$year,$inovice->id);
                        $getInvoice=Finder::getInvoiceStatus($invoiceId);
                        if($getInvoice){
							$invoiceStatus=$getInvoice->status;
							$is_locked=$getInvoice->is_confirm;
							$paid_method=$getInvoice->payment_method;
						    $is_remittance=$getInvoice->remittance;
						    $paid_date=$getInvoice->paid_date;
						}							
						@endphp
						
						<tr data-id="{{$invoiceId}}">
						<td>{{$i}}</td>
						<td><a href="{{route('admin.customers.show',$inovice->id)}}">{{ucfirst($inovice->first_name.' '.$inovice->last_name)}}</a></td>
						<td>{{$inovice->defaultaddress->company_name}}</td>
						<td><a href="{{route('admin.reports.monthly-invoice').'?customer='.$inovice->id.'&month='.$month_no.'&year='.$year}}" target="_blank" title="Invoice Detail"><i class="fa fa-file-text"  aria-hidden="true"></i></a> 
						<a href="{{route('admin.reports.customer-monthly-statement')}}?cId={{$inovice->id}}&year={{$year}}" target="_blank" title="Customer Statement"><i  class="fa fa-file-text-o" aria-hidden="true"></i></a></td>
						<td>{{$paymenttypes[$inovice->payment_method]}}</td>
						<td>{!! config('cart.currency_symbol') !!} {{$inovice->inovice_total}}</td>
						<td>
						<select name="payment_method" class="payment_method">
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
						<td>
						<form action="" method="POST" class="update_payment_{{$invoiceId}}">
						<input type="hidden" name="week" value="0">
						<input type="hidden" name="month" value="{{$month_no}}">
						<input type="hidden" name="year" value="{{$year}}">
						<input type="hidden" name="invoiceid" value="{{$invoiceId}}">
						<input type="hidden" name="customer_id" value="{{$inovice->id}}">
						<input type="hidden" name="start_date" value="{{$start_d}}">
						<input type="hidden" name="end_date" value="{{$end_d}}">
						<input type="hidden" name="type" value="monthly">
						<select name="status" @if($is_locked==1) disabled @else  class="payment_status" data-id="{{$invoiceId}}" @endif>
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
						<td>{{(!empty($paid_date)) ? \Carbon\Carbon::parse($paid_date)->format('jS M Y') : 'N/A'}}
						@if(empty($paid_date))
						<br> <input type="text" style="width: 76px;" class="datepicker paid_date" name="paid_date" placeholder="Select Date">
					    @endif
						</td>
						<td><input type="checkbox" name="selected" value="{{$invoiceId}}"></td>
						
						<td>{{$invoiceId}}</td>
                                                <td><a href="{{route('admin.cron.invoices_monthly_per_customer')}}{{ (Request::getQueryString()) ? '?customer_id='.$inovice->id.'&'.Request::getQueryString() : 'customer_id='.$inovice->id }}" class="mb-2">Import to XERO</a></td>
						</tr>
						@endforeach
						@if(count($getInvoices)<=0)
							<tr>
						    <td colspan="10" class="not_found">{{Config::get('constants.NO_RECORD_FOUND')}}</td>
						    </tr>
						@endif
                        </tbody>
                    </table>
                </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
				{{$getInvoices->appends($_GET)->links()}}
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