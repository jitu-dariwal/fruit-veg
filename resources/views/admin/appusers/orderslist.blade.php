@extends('layouts.admin.app')

@section('content')
<!-- Main content -->
<section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
    @if($app_users_orders)
    <div class="box">
        <div class="box-body">

            <h2>App Orders</h2>

            <!-- Trigger the modal with a button -->
            
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#ordersearch">Search App Orders</button>
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exportorder">Export App Orders</button>

            
            @if((isset($search_user_name) && !empty($search_user_name)) || (isset($search_order_date) && !empty($search_order_date)))
            <table class="table">
                <tbody>
                    <tr><td>Search for App User Name <strong>"{{$search_user_name}}"</strong> and Order Date <strong>"{{$search_order_date}}".</strong></td></tr>
                </tbody>
            </table>
            @endif
            <div class="table-responsive">
            <table class="table table-striped" style="margin-top: 5px;">
                <thead class="thead-light">
                    <tr>
                    <!--<td class="col-md-1">S.No</td> -->
                        <th class="col-md-2">Order Id</th>
                        <th class="col-md-3">App User Name</th>
                        <th class="col-md-2">Total</th>
                        <th class="col-md-2">Created Date</th>
                        <th class="col-md-3">Actions</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($app_users_orders as $app_users_order)
                    <tr>

                        <td>{{ $app_users_order->order_id }}</td>
                        <td>{{ $app_users_order->first_name }} {{ $app_users_order->last_name }}</td>
                        <td>£{{ number_format($app_users_order->orders_total, 2) }}</td>
                        <td>{{ date("d/m/Y H:i:s", strtotime($app_users_order->order_create_date)) }}</td>
                        <td> <form action="{{ route('admin.appusers.destroy', $app_users_order->order_id) }}" method="post" class="form-horizontal">
                                {{ csrf_field() }}
                               <!-- <input type="hidden" name="_method" value="delete"> -->
                                <div class="btn-group">
                                    <a href="{{ route('admin.appusers.editapporder', $app_users_order->order_id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                   <!-- <button onclick="return confirm('You are about to delete this record?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Delete</button> -->
                                </div>
                            </form></td>
                        </tr>
                    @endforeach



                </tbody>
            </table>
            </div>
            @if($app_users_orders)
            {!! $app_users_orders->render() !!}
            @endif
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
    @endif

    <!-- Search order form -->
    <div id="ordersearch" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Search App Orders</h4>

                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.appusers.apporders') }}" method="post" class="form-horizontal">

                        {{ csrf_field() }}
                        <table class="table">
                            <tbody>

                                <tr>
                                    <td>Sort By User:</td>
                                    <td>
                                        <select name="user_id" class="form-control">
                                            <option value="">Select user</option>
                                            @foreach ($app_users as $app_user):
                                            <option value="{{$app_user->app_user_id}}">{{$app_user->first_name}} {{$app_user->last_name}}</option>
                                            @endforeach

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sort By order date:</td>
                                    <td>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input class="form-control pull-right datepicker" name="order_date" placeholder="dd-mm-yyyy" type="text">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input type="submit" class="btn btn-primary" value="Search"></td>
                                </tr>

                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <!-- export order form -->
    <div id="exportorder" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Export App Order Products</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.appusers.exportapporders') }}" method="post" class="form-horizontal">
                        {{ csrf_field() }}
                        <table class="table daterange-group">
                            <tbody>
                                <tr>
                                    <td>From Date:</td>
                                    <td>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input class="form-control pull-right datepicker datefrom" name="from_date" required="required"  placeholder="dd-mm-yyyy" type="text">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>To Date:</td>
                                    <td>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input class="form-control pull-right datepicker dateto" name="to_date" required="required" placeholder="dd-mm-yyyy" type="text">
                                    
                                        
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input type="submit" class="btn btn-primary" value="Submit"></td>
                                </tr>

                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

</section>
<!-- /.content -->
@endsection