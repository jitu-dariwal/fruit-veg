@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
        <div class="box">
                <div class="box-body">
				
				<h2>Vouchers/Coupons Details</h2>
				
                    <table class="table">
                        <tbody>
                       @if(!empty($customer_coupon_user_data))     
                        <tr>
                            <td>Coupon sent to(Name):- </td><td>{{$customer_coupon_user_data->sent_firstname}}</td>
                        </tr>
                        <tr>
                            <td>Coupon sent to(Email):- </td><td>{{$customer_coupon_user_data->emailed_to}}</td>
                        </tr>
                        <tr>
                            <td>Voucher Value:- </td><td>{{$customer_coupon_user_data->coupon_amount}}</td>
                         </tr>
                         <tr>
                            <td>Voucher Code:- </td><td>{{$customer_coupon_user_data->coupon_code}}</td>
                        </tr>
                        <tr>
                            <td>Date Sent:- </td><td>{{date('d-m-Y', strtotime($customer_coupon_user_data->date_sent))}}</td>
                        </tr>
                        @if(isset($customer_coupon_user_data->redeem_date) && !empty($customer_coupon_user_data->redeem_date))
                            <tr>
                                <td>Date Redeemed:- </td><td>{{date('d-m-Y', strtotime($customer_coupon_user_data->redeem_date))}}</td>
                            </tr>
                            <tr>
                                <td>IP Address:- </td><td>{{$customer_coupon_user_data->redeem_ip}}</td>
                            </tr>
                        @else
                        <tr><td><b>Not Redeemed</b></td></tr>
                        @endif
                           
                         @else
                         <tr><td colspan="2"><p class="alert alert-warning">No record found.</p></td></tr>
                        @endif   
                        
                        <tr><td colspan="2"><a onclick="window.history.go(-1);" class="btn btn-default">Back</a></td></tr>
                        </tbody>
                    </table>
                    
					
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            
    </section>
    <!-- /.content -->
@endsection
