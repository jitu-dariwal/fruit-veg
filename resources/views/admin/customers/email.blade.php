@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.customers.sendmail') }}" method="post" class="form">
                <div class="box-body">
                    {{ csrf_field() }}
					
					<input name="customer_id" value="{{$customer_id}}" type="hidden">
					
                    <div class="form-group">
                        <label for="customer_email">Customer</label>
						<select name="customer_email" id="customer_email" class="form-control">
							<option value="all_customers">All Customers</option>
							<option value="all_subscriber">To All Newsletter Subscribers</option>
							@foreach($customers as $customer_data)
                                <option value="{{ $customer_data->email }}" @if($customer_id == $customer_data->id) selected="selected" @endif >{{ $customer_data->last_name }}, {{ $customer_data->first_name }} ({{ $customer_data->email }})</option>
                            @endforeach
                        </select>
                    </div>
					 <div class="form-group">
                        <label for="from_email">From</label>
                        <input type="text" name="from_email" id="from_email" class="form-control" value="info@fruitandveg.co.uk">
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <select name="subject" id="subject" class="form-control" required>
							<option value="">Select A Subject</option>
							<option value="Bank Holiday Deliveries">Bank Holiday Deliveries</option>
							<option value="Bank Holiday Deliveries April">Bank Holiday Deliveries April</option>
							<option value="Baskets, Awards and Christmas!">Baskets, Awards and Christmas!</option>
							<option value="Can you be our Star?">Can you be our Star?</option>
							<option value="Change of Contact Details">Change of Contact Details</option>
							<option value="Christmas Deliveries">Christmas Deliveries</option>
							<option value="Contact Us Email Subject">Contact Us Email Subject</option>
							<option value="Easter Update">Easter Update</option>
							<option value="Invoice Number Change">Invoice Number Change</option>
							<option value="Locally Sourced Mineral Water">Locally Sourced Mineral Water</option>
							<option value="Now Delivering Across the Capital">Now Delivering Across the Capital</option>
							<option value="Order Shipment">Order Shipment</option>
							<option value="Please Ignore Last E-Mail">Please Ignore Last E-Mail</option>
							<option value="Product Inquiry">Product Inquiry</option>
							<option value="Thames Food Festival">Thames Food Festival</option>
							<option value="VOTE FOR US!">VOTE FOR US!</option>
                        </select>
                    </div>
					<div class="form-group">
                        <label for="from_email">Message</label>
                        <textarea class="form-control ckeditor" name="message" id="message" rows="5" placeholder="Message">{{ old('message') }}</textarea>
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
