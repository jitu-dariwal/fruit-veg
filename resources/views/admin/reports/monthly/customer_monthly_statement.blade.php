@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        
            <div class="box">
                <div class="box-body">
                    <h2>Customer Statement for monthly invoices</h2>
                    <div class="table-responsive">
					<!-- search form -->
					<form class="form-inline">
					
					<table class="table">
					                  
					  <tr>
					  <td>
					  <label class="" for="inlineFormInputName2">Customer:</label></td>
					  <td>
					  <select class="form-control cId" name="cId">
						  @foreach($customers as $cust)
						  <option {{($cId==$cust->id) ? 'selected' : ''}} value="{{$cust->id}}">{{ucfirst($cust->defaultaddress->company_name)}}-  {{ucfirst($cust->first_name.' '.$cust->last_name)}} - ({{$cust->id}})</option>
						  @endforeach
						</select>
					  </td>
					  
					  <td>
					  <label class="" for="inlineFormInputGroupUsername2">Display for the year:</label></td>
					  <td>
						<select class="form-control year" name="year">
						  @for($y=date('Y'); $y>=2000;$y--)
						  <option {{($year==$y) ? 'selected' : ''}} value="{{$y}}">{{$y}}</option>
						  @endfor
						</select>
					  </td>
                                          <td>
                                              <div style="margin-bottom:5px;">
                                                <button type="submit" class="btn btn-primary mb-2">Submit</button>
                                                <a href="{{route('admin.reports.customer-monthly-statement')}}" class="btn btn-default mb-2">Reset</a>
                                               </div>
                                          </td>
						</tr>
						
						</table>
						
					</form>
					
					<!-- /.search form -->
				</div>
                    <div class="table-responsive">
					<table class="table" width="600" style="margin-bottom:5px;" border="0" bordercolor="#000000">
                          <tbody><tr>
                            <td bordercolor="#000000" bgcolor="#e9ecef" class="heading">Company</td>
                            <td bordercolor="#000000" class="row2">{{$customer->defaultaddress->company_name}}</td>
                            <td bordercolor="#000000" bgcolor="#e9ecef" class="heading">Date</td>
                            <td bordercolor="#000000" class="row2">{{date('jS M Y')}}</td>
                          </tr>
                          <tr>
                            <td bordercolor="#000000" bgcolor="#e9ecef" class="heading">Contact</td>
                            <td bordercolor="#000000" class="row2">{{ucfirst($customer->first_name.' '.$customer->last_name)}}</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td bordercolor="#000000" bgcolor="#e9ecef" class="heading">Address</td>
                            <td bordercolor="#000000" class="row2">{{$customer->defaultaddress->street_address}}<br>{{$customer->defaultaddress->address_line_2}}<br>{{$customer->defaultaddress->city}}<br>{{$customer->defaultaddress->county_state}}<br></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                    </tbody>
					</table>
					</div>
					<div class="table-responsive">
					<table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th class="">Months</th>
                                <th class="">Invoice No.</th>
                                <th class="">Paid or Unpaid</th>
                                <th class="">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
						@php $due_amt=0; @endphp
						@foreach($data as $key=>$val)
						@php
						$getInvoice=Finder::getInvoiceStatus($data[$key]['invoice_id']);
						$invoiceStatus=0;
                        if($getInvoice){
							$invoiceStatus=$getInvoice->status;
							$is_locked=$getInvoice->is_confirm;							
						}
						@endphp
						    <tr>
							    <td class="">{{$data[$key]['month']}}</td>
                                <td class="">{{$data[$key]['invoice_id']}}</td>
								@if($invoiceStatus==2)
                                <td class="">Paid</td>
							    @else
								@php $due_amt+=$data[$key]['amount']; @endphp
                                <td class="">Unpaid</td>
								@endif
                                <td class="">{!! config('cart.currency_symbol') !!} {{$data[$key]['amount']}}</td>
							</tr>
						@endforeach
						@if(count($data)<=0)
							<tr>
						    <td colspan="10" class="not_found">{{Config::get('constants.NO_RECORD_FOUND')}}</td>
						    </tr>
						@else
						<tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td bgcolor="#999999" class="heading"><strong>Total Due</strong></td>
                          <td bgcolor="#999999" class="heading"><strong>{!! config('cart.currency_symbol') !!} {{$due_amt}}</strong></td>
                        </tr>
						@endif
                        </tbody>
                    </table>
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

</script>
@endsection