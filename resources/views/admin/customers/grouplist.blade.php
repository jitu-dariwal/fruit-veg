@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if($customer_groups)
            <div class="box">
                <div class="box-body">
                    <h2>Customers Group</h2>
                    <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
								<!--<td class="col-md-1">S.No</td> -->
                                <th class="col-md-3">Group Name</th>
                                <th class="col-md-7">Group Description</th>
                                <th class="col-md-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($customer_groups as $customer_group)
                            <tr>
								
                                <td>{{ $customer_group->customers_group_name }}</td>
								<td>{!! $customer_group->customers_group_description !!}</td>
								<td><a href="{{ route('admin.customersgroup.edit', $customer_group->customers_group_id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
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
        @endif

    </section>
    <!-- /.content -->
@endsection