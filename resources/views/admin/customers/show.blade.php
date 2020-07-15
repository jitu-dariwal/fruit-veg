@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
            <div class="box-body">
                <h2>Customer</h2>
                <table class="table">
                    <tbody>
                    <tr>
                        <td class="col-md-4"><strong>Company Name</strong></td><td>{{ $customer->defaultaddress->company_name }}</td>
                    </tr>
					<tr>
                       <td class="col-md-4"><strong>First Name</strong></td><td>{{ $customer->first_name }}</td>
					</tr>
					<tr>
                      <td class="col-md-4"><strong>Last Name</strong></td><td>{{ $customer->last_name }}</td>
                    </tr>
					<tr>
                       <td class="col-md-4"><strong>Email</strong></td><td>{{ $customer->email }}</td>
                    </tr>
					<tr>
                       <td class="col-md-4"><strong>Post Code</strong></td><td>{{ $customer->defaultaddress->post_code }}</td>
                    </tr>
					<tr>
                       <td class="col-md-4"><strong>City</strong></td><td>{{ $customer->defaultaddress->city }}</td>
                    </tr>
					<tr>
                       <td class="col-md-4"><strong>County/State</strong></td><td>{{ $customer->defaultaddress->county_state }}</td>
                    </tr>
					<tr>
                       <td class="col-md-4"><strong>Country</strong></td><td>{{ $customer->defaultaddress->country->name }}</td>
                    </tr>
					<tr>
                       <td class="col-md-4"><strong>Telephone Number</strong></td><td>{{ $customer->tel_num }}</td>
                    </tr>
					<tr>
                       <td class="col-md-4"><strong>Fax Number</strong></td><td>{{ $customer->fax_num }}</td>
                    </tr>
					<tr>
                       <td class="col-md-4"><strong>Newsletter</strong></td><td>@if($customer->newsletter == 1) Subscribed @else Unsubscribed @endif</td>
                    </tr>
					<tr>
                       <td class="col-md-4"><strong>Current Spend Per Month (£)</strong></td><td>{{ $customer->current_spend_month }}</td>
                    </tr>
					<tr>
                       <td class="col-md-4"><strong>Credit Checked</strong></td><td>@if($customer->credit_checked == 1) Yes @else No @endif</td>
                    </tr>
					
                    </tbody>
                    <tbody>
                   
                    </tbody>
                </table>
            </div>
            <!--<div class="box-body">
                <h2>Addresses</h2>
                <table class="table">
                    <tbody>
                    <tr>
                        <td class="col-md-2">Alias</td>
                        <td class="col-md-2">Address 1</td>
                        <td class="col-md-2">Country</td>
                        <td class="col-md-2">Status</td>
                        <td class="col-md-4">Actions</td>
                    </tr>
                    </tbody>
                    <tbody>
                    @foreach ($addresses as $address)
                        <tr>
                            <td>{{ $address->alias }}</td>
                            <td>{{ $address->address_1 }}</td>
                            <td>{{ $address->country->name }}</td>
                            <td>@include('layouts.status', ['status' => $address->status])</td>
                            <td>
                                <form action="{{ route('admin.addresses.destroy', $address->id) }}" method="post" class="form-horizontal">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="delete">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.customers.addresses.show', [$customer->id, $address->id]) }}" class="btn btn-default btn-sm"><i class="fa fa-eye"></i> Show</a>
                                        <a href="{{ route('admin.customers.addresses.edit', [$customer->id, $address->id]) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                        <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Delete</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div> -->
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="btn-group">
                    <a onclick="window.history.go(-1);" class="btn btn-default btn-sm">Back</a>
                </div>
            </div>
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->
@endsection
