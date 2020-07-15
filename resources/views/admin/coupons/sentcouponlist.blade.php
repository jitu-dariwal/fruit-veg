@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if(!empty($sentcoupons))
            <div class="box">
                <div class="box-body">
				
				<h2>Vouchers/Coupons Sent</h2>
		<div class="table-responsive">		
                    <table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Sender's Name</th>
                                <th>Voucher Value (£)</th>
                                <th>Voucher Code</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($sentcoupons as $sentcoupon)
                            <tr>
                                <td>
                                   {{ $sentcoupon->first_name }}
                                </td>
                                 <td>
                                  {{ $sentcoupon->coupon_amount }} @if($sentcoupon->coupon_amount_type == 'percentage')%@endif
                                </td>
                                 <td>
                                   {{ $sentcoupon->coupon_code }}
                                </td>
                                <td>
                                    
                                 <a class="btn btn-default btn-sm" href="{{route('admin.coupons.show', $sentcoupon->coupon_id)}}?customerid={{$sentcoupon->customer_id_sent}}" title="View"><i class="fa fa fa-eye"></i></a>
                                                                           
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
					
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            @else
            <p class="alert alert-warning">No record found.</p>
        @endif
    </section>
    <!-- /.content -->
@endsection
