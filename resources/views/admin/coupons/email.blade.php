@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.coupons.sendmail') }}" method="post" class="form">
                <input type="hidden" name="coupon_id" id="coupon_id" value="{{$coupon_id}}">
                <div class="box-body">
                    {{ csrf_field() }}
					
					
					
                    <div class="form-group">
                        <label for="customer_email">Customer <span class="text-danger">*</span></label>
                        <select name="customer_email" id="customer_email" class="form-control" required="required">
                                                        <option value="">Select Customers</option>
							<option value="all_customers">All Customers</option>
							<option value="all_subscriber">To All Newsletter Subscribers</option>
							@foreach($customers as $customer_data)
                                <option value="{{ $customer_data->email }}" >{{ $customer_data->last_name }}, {{ $customer_data->first_name }} ({{ $customer_data->email }})</option>
                            @endforeach
                        </select>
                    </div>
					 <div class="form-group">
                        <label for="from_email">From</label>
                        <input type="text" name="from_email" id="from_email" class="form-control" value="info@fruitandveg.co.uk">
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" name="subject" class="form-control" />
                    </div>
					<div class="form-group">
                        <label for="from_email">Message</label>
                        <textarea class="form-control ckeditor" name="message" id="message" rows="5" placeholder="Message"><strong>Coupon Code - </strong> {{$coupon_code}}</textarea>
                    </div>
					
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <a onclick="window.history.go(-1);" class="btn btn-default">Back</a>
                        <button type="submit" class="btn btn-primary">Send Mail</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
