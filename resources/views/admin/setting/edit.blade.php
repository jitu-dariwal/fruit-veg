@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.setting.update', $setting_data->id) }}" method="post" class="form">
                <div class="box-body">
                    <h2>Site Settings</h2>
                    {{ csrf_field() }}
                    <input type="hidden" value="put" name="_method">
                    <div class="form-group">
                        <label for="display_name">Record Per Page <span class="text text-danger">*</span></label>
                        <input type="number" name="records_per_page" id="records_per_page" placeholder="Record Per Page" class="form-control" value="{{ old('records_per_page') ?: $setting_data->records_per_page }}" required="required">
                    </div>
                    <div class="form-group">
                        <label for="description">Minimum Order <span class="text text-danger">*</span></label>
                        <input type="number" name="minimum_order" id="minimum_order" placeholder="Minimum Order" class="form-control" value="{{ old('minimum_order') ?: $setting_data->minimum_order }}" required="required">
                    </div>
		    <div class="form-group">
                        <label for="description">Total Delivery Days <span class="text text-danger">*</span></label>
                        <input type="number" name="total_delivery_days" id="total_delivery_days" placeholder="Total Delivery Days" class="form-control" value="{{ old('total_delivery_days') ?: $setting_data->total_delivery_days }}" required="required">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Admin Email <span class="text text-danger">*</span></label>
                        <input type="email" name="admin_email" id="admin_email" placeholder="Admin Email" class="form-control" value="{{ old('admin_email') ?: $setting_data->admin_email }}" required="required">
                    </div>
					
					<div class="form-group">
                        <label for="description">Facebook URL <span class="text text-danger">*</span></label>
                        <input type="text" name="fb_url" id="fb_url" placeholder="Facebook URL" class="form-control" value="{{ old('fb_url') ?: $setting_data->fb_url }}" required="required">
                    </div>
					
					<div class="form-group">
                        <label for="description">Twitter URL <span class="text text-danger">*</span></label>
                        <input type="text" name="twitter_url" id="twitter_url" placeholder="Twitter URL" class="form-control" value="{{ old('twitter_url') ?: $setting_data->twitter_url }}" required="required">
                    </div>
					
					<div class="form-group">
                        <label for="description">Youtube URL <span class="text text-danger">*</span></label>
                        <input type="text" name="youtube_url" id="youtube_url" placeholder="Youtube URL" class="form-control" value="{{ old('youtube_url') ?: $setting_data->youtube_url }}" required="required">
                    </div>
					
					<div class="form-group">
                        <label for="description">Company Address <span class="text text-danger">*</span></label>
						
                        <textarea name="company_address" id="company_address" placeholder="Enter company address" class="form-control ckeditor" required="required">{{ old('company_address', $setting_data->company_address) }}</textarea>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
