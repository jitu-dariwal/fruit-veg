@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    
	@include('alert/alert-box')
    <!-- Default box -->
	
	  @if(Session::get('invoiceStatus') == 'ffto_victoria')
		  
	  
          <div class="box col-md-12">
                <div class="box-body col-md-12">
				
			<form action="{{route('admin.update_invoice_status.lock')}}"  method="post">
				@csrf
			<button  class="btn btn-sm btn-warning pull-right" type="submit"><i class="fa fa-lock" aria-hidden="true"></i> Lock Page</button>
			<div class="input-group input-group-sm pull-right hide">	
			<input type="text" readonly name="lock" class="form-control col-md-3 pull-right"  value="lock">
			</div>
			</form>
			
                    <h2>Update Status 	</h2>
					
					<div class="row col-md-6">
					 <form action="{{ route('admin.update_invoice_status.update_payment_status') }}" method="post" class="form">
                <div class="box-body">
                    <h4 class="text-info">INVOICE PAYMENT STATUS </h4>
                    {{ csrf_field() }}
					
					<div class="form-group">
                        <label for="status">Paid / Unpaid Status <span class="text-danger">*</span> </label>
                       <select name="status" id="status" class="form-control ">
                      <option @if(old('status') == 2) selected @endif value="2">PAID</option>
                      <option @if(old('status') == 1) selected @endif value="1">UNPAID</option>
                      <option @if(old('status') == 3) selected @endif value="3">BAD DEBT</option>
                    </select>
						 <span style="color:red;"> {{ $errors->first('status') }} &nbsp;</span>
                    </div>
					
					
					<div class="form-group">
                        <label for="payinvoiceid">Invoice ID <span class="text-danger">*</span> </label>
                        <input type="text" name="payinvoiceid" id="payinvoiceid" placeholder="Enter Invoice ID"  class="form-control "   value="{{ old('payinvoiceid') }}">
						 <span style="color:red;"> {{ $errors->first('payinvoiceid') }} &nbsp;</span>
                    </div>
					
					
					 
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                      
                        <button type="submit" class="btn btn-success"> <i class="fa fa-save"></i> UPDATE INVOICE PAYMENT STATUS</button>
                    </div>
                </div>
            </form>
					
                </div>
				
				
				
					<div class="row col-md-6">
					 <form action="{{ route('admin.update_invoice_status.update_po_unmber') }}" method="post" class="form">
                <div class="box-body">
                    <h4 class="text-info">INVOICE PO NUMBER </h4>
                    {{ csrf_field() }}
					
					<div class="form-group">
                        <label for="po_number">Current PO Number <span class="text-danger">*</span> </label>
                        <input type="text" name="po_number" id="po_number" placeholder="Enter Current PO Number"  class="form-control "   value="{{ old('po_number') }}">
						 <span style="color:red;"> {{ $errors->first('po_number') }} &nbsp;</span>
                    </div>
					
					<div class="form-group">
                        <label for="invoiceid">Invoice ID <span class="text-danger">*</span> </label>
                        <input type="text" name="invoiceid" id="invoiceid" placeholder="Enter Invoice ID"  class="form-control "   value="{{ old('invoiceid') }}">
						 <span style="color:red;"> {{ $errors->first('invoiceid') }} &nbsp;</span>
                    </div>
					
					
					
					 
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                      
                        <button type="submit" class="btn btn-success"> <i class="fa fa-save"></i> UPDATE INVOICE PO NUMBER</button>
                    </div>
                </div>
            </form>
					
                </div>
				
                </div>
                <!-- /.box-body -->
            </div>
			
			
			
            <!-- /.box -->   
			
	  @else
		 <div class="box col-md-12">
                <div class="box-body col-md-12">
		
			 <label  class="pull-right hide"><b><h5>ISSUE LOG DATE : &nbsp;</h5></b> </label> 
				
                    <h2>Please enter Password to Update Invoice Payment Status	</h2>
					 @include('admin.update_invoice_status.search', ['route' => route('admin.anomolies.index'), 'search_by' => 'with email or customer name'])
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->  
	  
      @endif

    </section>
    <!-- /.content -->
@endsection
