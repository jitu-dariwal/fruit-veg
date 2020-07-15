@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.customersgroup.update', $customer_groups->customers_group_id) }}" method="post" class="form" enctype="multipart/form-data">
                <div class="box-body">
                    <h2>Edit Customers Groups Markup charges</h2>
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="put">
					<table class="table">
					 <tr>
                        <td class="col-md-4"><strong>Customers Group Name</strong></td>
 <td class="col-md-4">{{$customer_groups->customers_group_name}}</td>
						<td class="col-md-4"></td></tr>
					@foreach ($categories as $category)
                    <tr>
                        <td class="col-md-4">Mark-Up for <strong>{{strtoupper($category->name)}} BULK</strong> <span class="text-danger">*</span></td>
 <td class="col-md-4"><input type="text" name="bulk_{{$category->id}}" id="bulk_{{$category->id}}" class="form-control" placeholder="50.00" required="required" pattern="^\d+(\.)\d{2}$" @if(isset($grp_cat_charges[$category->id]['bulk_value'])) value="{{ number_format($grp_cat_charges[$category->id]['bulk_value'], 2) }}" @endif></td>
						<td class="col-md-4">%</td>
                    </tr>
					 <tr>
                        <td class="col-md-4">Mark-Up for <strong>{{strtoupper($category->name)}} SPLIT</strong> <span class="text-danger">*</span></td>
                        <td class="col-md-4"><input type="text" name="split_{{$category->id}}" id="split_{{$category->id}}" placeholder="60.00" required="required" pattern="^\d+(\.)\d{2}$" class="form-control" @if(isset($grp_cat_charges[$category->id]['split_value'])) value="{{ number_format($grp_cat_charges[$category->id]['split_value'], 2) }}" @endif></td>
						<td class="col-md-4">%</td>
                    </tr>
                    @endforeach
					
					<tr>
                        <td class="col-md-4">Group Description</td>
						<td class="col-md-8" colspan="2"><textarea class="form-control ckeditor" name="description" id="description" rows="5" placeholder="Description">{{$customer_groups->customers_group_description}}</textarea></td>
						
					</tr>
					
					</table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <a onclick="window.history.go(-1);" class="btn btn-default btn-sm">Back</a>
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
