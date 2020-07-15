@extends('layouts.admin.app')

@section('content')

    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        
            <div class="box">
                <div class="box-body">
                    <h2>Monthly Sales/Tax Summary</h2>
					<div class="table-responsive">
					<!-- search form -->
					<form method="GET" class="">
					<input type="hidden" name="invert" value="{{$invert}}">
					<table class="table table-striped">
					<tr>
					<td>
					  <label class="" for="inlineFormInputName2">Status:</label></td>
					  <td class="week_numbers">
					  <select class="form-control status" name="status">
					      <option value="">All</option>
						  @foreach($statusLists as $statusInfo)
						  <option {{($status==$statusInfo->id) ? 'selected' : ''}} value="{{$statusInfo->id}}">{{$statusInfo->name}}</option>
						  @endforeach
						</select>
					  </td>
					  
					  <td><div style="margin-bottom:5px;">
					  <button type="submit" class="btn btn-primary mb-2">Submit</button>
					  <a href="{{route('admin.reports.monthly-sales-tax')}}" class="btn btn-default mb-2">Reset</a>
					  <a href="{{route('admin.reports.export-monthly-sales-tax')}}{{ (Request::getQueryString()) ? '?'.Request::getQueryString() : '' }}" class="btn btn-primary mb-2">Export To Xls</a>
					  <a href="{{route('admin.reports.monthly-sales-tax')}}{{ (Request::getQueryString()) ? '?'.Request::getQueryString().'&print=yes' : '?print=yes' }}" class="btn btn-primary mb-2" target="_blank"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
					  @php
					  if($invert==0){
						  $invert=1;
					  }else{
						  $invert=0;
					  }
					  @endphp
					  <a href="{{route('admin.reports.monthly-sales-tax')}}{{ (request()->has('status')) ? '?status='.request('status').'&invert='.$invert : '?invert='.$invert }}" class="btn btn-primary mb-2">Invert</a>
					  				 
					  </div></td>
                    </tr>
                    </table>
					</form>
					<!-- /.search form -->
				    </div>
					
					
					<div class="table-responsive">
		   <table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th class="">Month</th>
                                <th class="">Year</th>
                                <th class="">Gross <br>Income</th>
                                <th class="">Product <br>Sales</th>
                                <th class="">Nontaxed <br>Sales</th>
                                <th class="">Taxed <br>Sales</th>
                                <th class="">Taxes  <br>Collected</th>
                                <th class="">Shipping  <br>& Handling</th>
                                <th class="">Tax on  <br>& Shipping</th>
                                <th class="">Gift  <br>Vouchers</th>
                            </tr>
                        </thead>
                        <tbody>
						@php
                        						
						@endphp
						@foreach($data as $key=>$val)
						@php
						$gross_sale=0;
						$product_sale=0;
						$gross_tax=0;
						$gross_shipping=0;
						$gross_discount=0;
						$product_sale=0;
						$taxed_sale=0;
						$non_taxed_sale=0;
						$tax_on_shipping=0;
						@endphp
						   @foreach($val as $month=>$v_data)
						   @php
						   $m_gross_sale=$v_data['gross_income'];
						   $m_gross_tax=$v_data['gross_tax'];
						   $m_gross_shipping=$v_data['gross_shipping'];
						   $m_gross_discount=$v_data['gross_discount'];
						   $m_product_sale=$m_gross_sale-$m_gross_tax-$m_gross_shipping-$m_gross_discount;
						   $m_taxed_sale=0;
						   $m_non_taxed_sale=$m_gross_sale-$m_gross_tax;
						   $m_tax_on_shipping=0;
						   
						   $gross_sale+=$m_gross_sale;
						   $product_sale+=$m_product_sale;
						   $gross_tax+=$m_gross_tax;
						   $gross_shipping+=$m_gross_shipping;
						   $gross_discount+=$m_gross_discount;
						   $taxed_sale+=$m_taxed_sale;
						   $non_taxed_sale+=$m_product_sale;
						   $tax_on_shipping+=$m_tax_on_shipping;
						   @endphp
						   @if(!empty($m_gross_sale) || !empty($m_product_sale) || !empty($m_non_taxed_sale) || !empty($m_taxed_sale) || !empty($m_gross_tax) || !empty($m_gross_shipping) || !empty($m_tax_on_shipping) || !empty($m_gross_discount))
						    <tr>
							    <td class="">{{$v_data['month']}}</td>
							    <td class="">{{$key}}</td>
							    <td class="">{!! config('cart.currency_symbol') !!} {{$m_gross_sale}}</td>
							    <td class="">{!! config('cart.currency_symbol') !!} {{$m_product_sale}}</td>
							    <td class="">{!! config('cart.currency_symbol') !!} {{$m_product_sale}}</td>
							    <td class="">{!! config('cart.currency_symbol') !!} {{$m_taxed_sale}}</td>
							    <td class="">{!! config('cart.currency_symbol') !!} {{$m_gross_tax}}</td>
							    <td class="">{!! config('cart.currency_symbol') !!} {{$m_gross_shipping}}</td>
							    <td class="">{!! config('cart.currency_symbol') !!} {{$m_tax_on_shipping}}</td>
							    <td class="">{!! config('cart.currency_symbol') !!} {{$m_gross_discount}}</td>
                                
							</tr>
							@endif
						@endforeach
						@if(!empty($gross_sale) || !empty($product_sale) || !empty($non_taxed_sale) || !empty($taxed_sale) || !empty($gross_tax) || !empty($gross_shipping) || !empty($tax_on_shipping) || !empty($gross_discount))
						<tr class="dataTableHeadingRow">
							    <td class="" bgcolor="gray">Year</td>
							    <td class="" bgcolor="gray">{{$key}}</td>
							    <td class="" bgcolor="gray">{!! config('cart.currency_symbol') !!} {{$gross_sale}}</td>
							    <td class="" bgcolor="gray">{!! config('cart.currency_symbol') !!} {{$product_sale}}</td>
							    <td class="" bgcolor="gray">{!! config('cart.currency_symbol') !!} {{$non_taxed_sale}}</td>
							    <td class="" bgcolor="gray">{!! config('cart.currency_symbol') !!} {{$taxed_sale}}</td>
							    <td class="" bgcolor="gray">{!! config('cart.currency_symbol') !!} {{$gross_tax}}</td>
							    <td class="" bgcolor="gray">{!! config('cart.currency_symbol') !!} {{$gross_shipping}}</td>
							    <td class="" bgcolor="gray">{!! config('cart.currency_symbol') !!} {{$tax_on_shipping}}</td>
							    <td class="" bgcolor="gray">{!! config('cart.currency_symbol') !!} {{$gross_discount}}</td>
                                
							</tr>
							@endif
						@endforeach
						@if(count($data)<=0)
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