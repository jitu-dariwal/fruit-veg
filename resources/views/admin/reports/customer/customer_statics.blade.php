@extends('layouts.admin.app')

@section('content')

    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        
            <div class="box">
                <div class="box-body">
                    <h2>Customers Statistics</h2>
                    <div class="">
					<!-- search form -->
					<form method="GET" class="">
					<div style="margin-bottom:5px;">
					
					<div class="col-md-3">
					<div class="form-group">
					  <label class="" for="inlineFormInputName2">Display for the month:</label>
					  <select class="form-control" name="month">
						  <option value="0">all</option>
						  @foreach(Config::get('constants.MONTHS') as $key=>$value)
					    <option @if(request()->has('month')) @if(request('month')==$key) {{'selected'}} @else {{''}} @endif @else @if(date('m')==$key) {{'selected' }} @endif   @endif value="{{$key}}">{{$value}}</option>
					  @endforeach
						</select>
						</div>
					</div>

					<div class="col-md-3">
					<div class="form-group">
					  <label class="" for="inlineFormInputName2">Display for the year:</label>
					  <select class="form-control" name="year">
						  <option value="0">all</option>
						  @for($y=date('Y'); $y>=2000;$y--)
						  <option {{(request()->has('year') && request('year')==$y) ? 'selected' : ($y==date('Y')) ? 'selected' : ''}} value="{{$y}}">{{$y}}</option>
						  @endfor
						</select>
					  </div>
					</div>

					<div class="col-md-3">
					<div class="form-group">

					  <label class="" for="inlineFormInputGroupUsername2">Orders minimum:</label>
						<select class="form-control" name="mini_ordered">
						<option {{(request()->has('mini_ordered') && request('mini_ordered')==1) ? 'selected' : ''}} value="1">1</option>
						<option {{(request()->has('mini_ordered') && request('mini_ordered')==2) ? 'selected' : ''}} value="2">2</option>
						<option {{(request()->has('mini_ordered') && request('mini_ordered')==3) ? 'selected' : ''}} value="3">3</option>
						<option {{(request()->has('mini_ordered') && request('mini_ordered')==4) ? 'selected' : ''}} value="4">4</option>
						<option {{(request()->has('mini_ordered') && request('mini_ordered')==5) ? 'selected' : ''}} value="5">5</option>
						<option {{(request()->has('mini_ordered') && request('mini_ordered')==10) ? 'selected' : ''}} value="10">10</option>
						<option {{(request()->has('mini_ordered') && request('mini_ordered')==20) ? 'selected' : ''}} value="20">20</option>
						</select>
						</div>
					</div>

					<div class="col-md-3">
					<div class="form-group">
						
						<span id="no_status_div">
					  <label class="" for="inlineFormInputGroupUsername2">Hide orders status:</label>
						<select class="form-control" id="no_status" name="no_status">
						  <option value="0">all</option>
						  @foreach($statuses as $ostatus)
						  <option {{(request()->has('no_status') && request('no_status')==$ostatus->id) ? 'selected' : ''}} value="{{$ostatus->id}}">{{$ostatus->name}}</option>
						  @endforeach
						</select>
						</span>
						
						</div>
					</div>

					<div class="col-md-3">
					<div class="form-group">
						 
					  <label class="" for="inlineFormInputGroupUsername2">Display only orders status:</label>
						<select class="form-control" id="status" name="status">
						  <option value="0">all</option>
						  @foreach($statuses as $ostatus)
						  <option {{(request()->has('status') && request('status')==$ostatus->id) ? 'selected' : ''}} value="{{$ostatus->id}}">{{$ostatus->name}}</option>
						  @endforeach
						</select>
						</div>
					</div>
					
					
					 <div class="col-md-2" style="margin-top:25px;">
					 <div class="form-group">
				
				   <a href="{{route('admin.reports.customer-statics')}}" style="margin-left:3px;" class="btn btn-default pull-right">Reset</a>
				  
					<button type="submit" class="btn btn-primary pull-right">Submit</button>
					
					</div>
					</div>
						
						</div>
						
					</form>
					
					<!-- /.search form -->
				</div>
                    
					<table class="table">
                        <thead>
						@php
						$newcustomer=0;
						$customershavingbought=0;
						$newcustomershaving_bought=0;
						$newcustomers_having_bought_one_order=0;
						if($new_customers && $new_customers->new_customer>0){
							$newcustomer=$new_customers->new_customer;
						}
						if($customersHavingBough){
							$customershavingbought=count($customersHavingBough);
						}
						if($new_customers_having_bought && $new_customers_having_bought->t_order>0){
							$newcustomershaving_bought=$new_customers_having_bought->t_order;
						}
						if($new_customers_having_bought_one_order){
							$newcustomers_having_bought_one_order=count($new_customers_having_bought_one_order)-$newcustomershaving_bought;
						}
						
						@endphp
                            <thead class="thead-light">
                                <th class="col-md-3">New customers:	</th>
                                <th class="col-md-3">{{$newcustomer}}</th>
                            </thead>
							<tr class="show_items">
                                <td class="col-md-3">Customers having bought:</td>
                                <td class="col-md-3">
								{{ $customershavingbought }}
								

								</td>
                            </tr>
							<tr class="show_items">
                                <td class="col-md-3">New customers having bought:	</td>
                                <td class="col-md-3">{{ $newcustomershaving_bought }} (
								{{ ($newcustomer>0 && $newcustomershaving_bought>0) ? Finder::getFormatted(($newcustomershaving_bought/$customershavingbought)*100) : 0 }} %)</td>
                            </tr>
							<tr class="show_items">
                                <td class="col-md-3">Customers having already made at least {{(request()->has('mini_ordered'))?request('mini_ordered'):1}} purchase(s):	</td>
                                <td class="col-md-3">{{ $newcustomers_having_bought_one_order }} (
								{{ ($newcustomer>0 && $newcustomers_having_bought_one_order>0) ? Finder::getFormatted(($newcustomers_having_bought_one_order/$customershavingbought)*100) : 0 }} %)</td>
                            </tr>
			   <thead class="thead-light">
                                <th class="col-md-3">Number of orders:	</th>
                                <th class="col-md-3">{{($order_static  && !empty($order_static->bought_order)) ? $order_static->bought_order : 0}}</th>
                            </thead>
							<tr class="show_items">
                                <td class="col-md-3">Total sales including all taxes:	</td>
                                <td class="col-md-3">{!! config('cart.currency_symbol') !!} {{($order_static && !empty($order_static->total_sale)) ? $order_static->total_sale : 0.00}}</td>
                            </tr>
							<tr class="show_items">
                                <td class="col-md-3">Total shipping:	</td>
                                <td class="col-md-3">{!! config('cart.currency_symbol') !!} {{($order_static && !empty($order_static->total_shipping)) ? $order_static->total_shipping : 0.00}}</td>
                            </tr>
							<tr class="show_items">
                                <td class="col-md-3">Total taxes:</td>
                                <td class="col-md-3">{!! config('cart.currency_symbol') !!} {{($order_static && !empty($order_static->total_tax)) ? $order_static->total_tax : 0.00}}</td>
                            </tr>
							<tr class="show_items">
                                <td class="col-md-3">Total sales:	</td>
                                <td class="col-md-3">{!! config('cart.currency_symbol') !!} 
								@if($order_static)
								@php
								$total_sales=$order_static->total_sale-$order_static->total_shipping-$order_static->total_tax;
								@endphp
								{{Finder::getFormatted($total_sales)}}
								@else
									0
								@endif
								</td>
                            </tr>
							<tr class="show_items">
                                <td class="col-md-3">Average Sale including all taxes:	</td>
                                <td class="col-md-3">{!! config('cart.currency_symbol') !!} {{($order_static && $order_static->bought_order>0) ? Finder::getFormatted($order_static->total_sale/$order_static->bought_order) : '0.00'}}</td>
                            </tr>
			<thead class="thead-light">
                                <th class="col-md-3">Average Basket:</th>
                                <th class="col-md-3">{!! config('cart.currency_symbol') !!} {{($order_static && $order_static->bought_order>0) ? Finder::getFormatted($total_sales/$order_static->bought_order) : '0.00'}}</th>
                            
                        </thead>
                        <tbody>
						
						<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						</tr>
						
						
                        </tbody>
                    </table>
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
</script>
@endsection