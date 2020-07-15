@if($print=="yes")
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="LTR" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Fruit And Veg - Monthly Sales/Tax Summary</title>
<base href="{{URL::to('/')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/print.css')}}" media="screen" >
<style>

 .productlist .product_th {
    padding: 0px;
    text-align: center;
}

.productlist .product_th {
    background-color: rgb(82, 86, 89);;
    color: white;
}
 
.productlist .product_td {
    height: 25px;
	text-align: center;
    vertical-align: bottom;
}
.rest_total{
    text-align: right;
}
</style>
</head>
<script>window.onload= function () { window.print();window.close();   }  </script>
<body marginwidth="10"  marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10">
<section class="content">
<!-- body_text //-->
<table width="750" border="0" align="center" cellpadding="2" cellspacing="0">
  
  <tr>
    <td><table border="0" align="center" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" align="center" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" style="font-family:Arial, sans-serif;  font-size: 13px;  color: #666666;" >Fruit And Veg.co.uk <br>Monthly Sales/Tax Summary<br>Reported: {{date('d-m-Y')}}-{{date('h:i A')}}<br> Status:{{$statusName}}</td>
            <td class="pageHeading"><img src="{{ asset("img/logo.jpg") }}" border="0" alt="Fruit And Veg" title=" Fruit And Veg "></td>
          </tr>
          <tr align="left">
            <td colspan="2" class="titleHeading"></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>

@endif
	<div class="table-responsive">
					<table width="750" border="0" align="center" cellpadding="2" cellspacing="0">
                        <thead>
                            <tr class="dataTableHeadingRow">
                                <td class="">Month</td>
                                <td class="">Year</td>
                                <td class="">Gross <br>Income</td>
                                <td class="">Product <br>Sales</td>
                                <td class="">Nontaxed <br>Sales</td>
                                <td class="">Taxed <br>Sales</td>
                                <td class="">Taxes  <br>Collected</td>
                                <td class="">Shipping  <br>& Handling</td>
                                <td class="">Tax on  <br>& Shipping</td>
                                <td class="">Gift  <br>Vouchers</td>
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
						   $non_taxed_sale+=$m_non_taxed_sale;
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
							    <td class="">Year</td>
							    <td class="">{{$key}}</td>
							    <td class="">{!! config('cart.currency_symbol') !!} {{$gross_sale}}</td>
							    <td class="">{!! config('cart.currency_symbol') !!} {{$product_sale}}</td>
							    <td class="">{!! config('cart.currency_symbol') !!} {{$product_sale}}</td>
							    <td class="">{!! config('cart.currency_symbol') !!} {{$taxed_sale}}</td>
							    <td class="">{!! config('cart.currency_symbol') !!} {{$gross_tax}}</td>
							    <td class="">{!! config('cart.currency_symbol') !!} {{$gross_shipping}}</td>
							    <td class="">{!! config('cart.currency_symbol') !!} {{$tax_on_shipping}}</td>
							    <td class="">{!! config('cart.currency_symbol') !!} {{$gross_discount}}</td>
                                
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
@if($print=="yes")
	</section>
    <!-- /.content -->
	</body>
</html>
@endif