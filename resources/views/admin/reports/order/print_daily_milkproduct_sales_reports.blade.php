<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="LTR" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Fruit And Veg - Stock Required For {{$for_date}}</title>
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
            <td class="pageHeading" style="font-family:Arial, sans-serif;  font-size: 13px;  color: #666666;" >Fruit And Veg.co.uk <br>UNIT 5D <br> Bates Industrial Estate  <br>The Old Brickworks, <br> Church Road,  <br>Harold Wood, <br> Essex. <br> RM3 0HU</td>
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
    <!-- Main content -->
    
            <div class="box" style="text-align: center;">
                <div class="box-body">
                    <h2>Lists all of the milk products that has been ordered for {{$for_date}}</h2>
                    
                    <div class="table-responsive">
					    <table width="750" border="0" align="center" cellpadding="2" cellspacing="0" class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th align="center" class="">S. No.</th>
                                <th align="center">Products ID</th>
                                <th align="center" class="">Product</th>
                                <th align="center" class="">Quantity</th>
                                <th align="center" class="">Packet Size</th>
                                <th align="center" class="">Sale Price</th>
                                <th align="center" class="">New Price</th>
                                <th align="center" class="">In/Out</th>
                            </tr>
                        </thead>
                        <tbody>
						@php $i=0; @endphp
						@foreach($order_items as $items)
						@php $i++; @endphp
						<tr>
						<td align="center">{{$i}}</td>
						<td align="center"><a href="{{route('admin.products.show',$items->product_id)}}">{{$items->product_id}}</a></td>
						<td align="center"><a href="{{route('admin.products.show',$items->product_id)}}">{{$items->product_name}}</a></td>
						<td align="center">{{$items->orderProQty}}</td>
						<td align="center">{{$items->packet_size}}</td>
						<td align="center">{!! config('cart.currency_symbol') !!} {{$items->product_price}}</td>
						<td align="center">{!! config('cart.currency_symbol') !!} {{$items->final_price}}</td>
						<td>In</td>
						</tr>
						@endforeach
						@if(count($order_items)<=0)
							<tr>
						    <td colspan="10" align="center" class="not_found">{{Config::get('constants.NO_RECORD_FOUND')}}</td>
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
	</body>
</html>