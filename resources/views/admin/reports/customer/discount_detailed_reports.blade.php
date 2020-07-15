@extends('layouts.admin.app')

@section('content')

    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        
            <div class="box">
                <div class="box-body">
                    <h2>Discounts Detailed Report</h2>
					<div class="table-responsive">
					<!-- search form -->
					<form method="GET" class="">
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
					  <label class="" for="inlineFormInputName2">Display for the year:</label></td>
					  <td>
					  <select class="form-control" name="year">
						  @for($y=date('Y'); $y>=2000;$y--)
						  <option {{($year==$y) ? 'selected' : ''}} value="{{$y}}">{{$y}}</option>
						  @endfor
						</select>
					  </td>
					  <td><div style="margin-bottom:5px;">
					  <button type="submit" class="btn btn-primary mb-2">Submit</button>
					  <a href="{{route('admin.reports.discount-detials')}}" class="btn btn-default mb-2">Reset</a>
					  <a href="{{route('admin.reports.export-discount-detailed-report')}}{{ (Request::getQueryString()) ? '?'.Request::getQueryString() : '' }}" class="btn btn-primary mb-2">Export Customer Discounts</a>
					  </div></td>
                    </tr>
                    </table>
					</form>
					<!-- /.search form -->
				    </div>
					
                    <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>S. No.</th>
                                <th>Order ID</th>
                                <th>Customer</th>
								@foreach(Config::get('constants.DISCOUNT_TYPES') as $dkey=>$dval)
								@php
								${"total_" . $dkey} = 0;
								@endphp
                                <th class="">{{$dval}}</th>
								@endforeach
                            </tr>
                        </thead>
                        <tbody>
						@php
						$i=($discount_details->currentPage()-1)*$discount_details->perPage();
						@endphp
						@foreach($discount_details as $discounts)
						@php
						$i++;
						@endphp
						<tr>
						<td>{{ $i }}</td>
						<td><a href="{{ route('admin.orders.show', $discounts->id) }}" target="_blank">{{$discounts->id}}</a></td>
						<td><a href="{{ route('admin.customers.show', $discounts->customer_id) }}" >{{ucfirst($discounts->customer->first_name.' '.$discounts->customer->last_name)}}</a></td>
						@foreach(Config::get('constants.DISCOUNT_TYPES') as $dkey=>$dval)
						@php
						$discount_val = 0; 
						if($discounts->discount_type==$dkey){
						$discount_val = $discounts->customer_discount;
						${"total_" . $dkey} += abs($discounts->customer_discount);
						}
						@endphp
						<td class="" @if(!empty($discount_val)) style="color:red;" @endif>{!! config('cart.currency_symbol') !!} {{abs($discount_val)}}</td>
						@endforeach
						</tr>
						@endforeach
						<tr>
						<td></td>
						<td></td>
						<td>Total</td>
						@foreach(Config::get('constants.DISCOUNT_TYPES') as $dkey=>$dval)
                                <td class="">{!! config('cart.currency_symbol') !!} {{ ${"total_" . $dkey} }}</td>
						@endforeach
						</tr>
						@if(count($discount_details)<=0)
							<tr>
						    <td colspan="25" class="not_found">{{Config::get('constants.NO_RECORD_FOUND')}}</td>
						    </tr>
						@endif
                        </tbody>
                    </table>
                </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
				{{$discount_details->appends($_GET)->links()}}
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