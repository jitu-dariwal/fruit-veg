@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
           
                <div class="box-body">
                    <h2>Edit App Orders</h2>
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="put">
					<div class="form-page-headings">Order's Details: {{$order_details->order_id}}</div>
					<div class="app_manager"><strong>App manager:</strong> {{$app_ordered_products[0]->first_name}} {{$app_ordered_products[0]->last_name}}</div>
					<div class="app_manager"><strong>Order Create Date:</strong> {{date("d/m/Y H:i:s", strtotime($order_details->order_create_date))}}</div>
					
					<div class="table-responsive">
					<table class="table table-bordered mt-3">
                        <thead class="thead-light">
                            <tr>
								<!--<td class="col-md-1">S.No</td> -->
                                <th class="col-md-3">Products Name</th>
                                <th class="col-md-1">Products Code</th>
								<th class="col-md-1">Packet Brand</th>
								 <th class="col-md-1">Packet Size</th>
								 <th class="col-md-1">Type</th>
								 <th class="col-md-1">Quantity</th>
								 <th class="col-md-2">Supplier</th>
								<th class="col-md-1">Product Price(£)</th>
								 <th class="col-md-1">Total Price(£)</th>
                            </tr>
                        </thead>
                        <tbody>
						<form action="{{ route('admin.appusers.updateorder') }}" method="post" class="form-horizontal">
                        @foreach ($app_ordered_products as $app_ordered_product)
                            <tr>
								
                                <td>{{ $app_ordered_product->product_name }}</td>
								<td>{{ $app_ordered_product->product_code }}</td>
								<td>{{ $app_ordered_product->packet_brand }}</td>
								<td>{{ $app_ordered_product->packet_size }}</td>
								<td>{{ $app_ordered_product->type }}</td>
								<td>{{ $app_ordered_product->number_purchase }} </td>
								<td>{{ $app_ordered_product->supplier }}</td>
								<td>{{ number_format($app_ordered_product->product_price, 2) }}</td>
								
                              
                                <td align="right">{{number_format($app_ordered_product->product_price*$app_ordered_product->number_purchase, 2)}}
                                 
                                        {{ csrf_field() }}
                                       
                                       
                                   
                                </td>
                            </tr>
                        @endforeach
						
<tr><td colspan="10" style="text-align:right"><strong>Order Total:</strong> £{{$order_total}}</td></tr>
<tr><td colspan="10" style="text-align:right; ">
	<div class="d-flex align-items-center" ><div class="col-xs-10 pl-0">Order Deduction:(Please use only positive value) <strong>£</strong></div> 
		<div class="col-xs-2 pr-0"><input type="text" name="deduct_price" value="" placeholder="1.00" required="required" pattern="^\d+(\.)\d{2}$" size="4" class="form-control"></div>
	</div>
	</td></tr>
<tr><td colspan="10" style="text-align:right">
<div class="d-flex align-items-center" >
	<div class="col-xs-8   pl-0">Deduction Comment:</div> 
	<div class="col-xs-4 pr-0"><textarea rows="4" cols="30" name="deduct_comment" required class="form-control">{{$order_details->deduct_comment}}</textarea></div>
	</div>
</td></tr>	
<tr><td colspan="10" style="text-align:right"><input type="hidden" name="order_id" value="{{$order_details->order_id}}"><input type="submit" value="submit" name="submit" class="btn btn-success"></td></tr>
<tr><td colspan="10" style="text-align:right">Final Order Total: £{{$final_total}}</td></tr>
				</form>
                        </tbody>
                    </table>
					</div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <a onclick="window.history.go(-1);" class="btn btn-default ">Back</a>
                       
                    </div>
                </div>
            
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
