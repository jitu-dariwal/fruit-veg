@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
        <div class="box">
                <div class="box-body">
				
				<h2>Discount Coupons Report</h2>
                                <div><strong>Redemptions Total -</strong> {{$total_uses}}</div>		
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Customer Id</th>
                                <th>Customer Name</th>
                                <th>Ip Address</th>
                                <th>Date Redeemed</th>
                            </tr>
                        </thead>
                        <tbody>
                    @if(!$coupons_customer->isEmpty())
                        @foreach ($coupons_customer as $coupon)
                            <tr>
                                <td>
                                   {{ $coupon->customer_id }}
                                </td>
                                 <td>
                                   {{ $coupon->first_name }} {{ $coupon->last_name }}
                                </td>
                                 <td>
                                   {{ $coupon->redeem_ip }}
                                </td>
                                <td>
                                    {{ $coupon->redeem_date }}
                                </td>
                            </tr>
                        @endforeach
                       @else
                       <tr><td colspan="4"><p class="alert"><b>No record found.</b></p></td></tr>
                        @endif
                        </tbody>
                    </table>
                    {{ $coupons_customer->links() }}
		 <div class="box-footer">
                    <div class="btn-group">
                        <a onclick="window.history.go(-1);" class="btn btn-default btn-sm">Back</a>
                    </div>
                </div>			
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            
    </section>
    <!-- /.content -->
@endsection
