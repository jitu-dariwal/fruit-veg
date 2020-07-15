@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if(!$coupons->isEmpty())
            <div class="box">
                <div class="box-body">
				
				<h2>Coupons</h2>
			@include('layouts.search', ['route' => route('admin.coupons.index')])
                        <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Coupon Name</th>
                                <th>Coupon Amount (£)</th>
                                <th>Coupon Code</th>
                                <th>Coupon Active Date</th>
                                <th>Coupon Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($coupons as $coupon)
                            <tr>
                                <td>
                                   {{ $coupon->name }}
                                </td>
                                 <td>
                                  {{ $coupon->coupon_amount }} @if($coupon->coupon_amount_type == 'percentage')%@endif
                                </td>
                                 <td>
                                   {{ $coupon->coupon_code }}
                                </td>
                                <td>{{ date('d/m/Y', strtotime($coupon->coupon_start_date)) }} - {{ date('d/m/Y', strtotime($coupon->coupon_expire_date)) }}</td>
                                <td>
                                    
                                    @php
                                        $currentDate = date('Y-m-d');
                                        $currentDate=date('Y-m-d', strtotime($currentDate));
                                        
                                        $coupon_start_date = date('Y-m-d', strtotime($coupon->coupon_start_date));
                                        $coupon_end_date = date('Y-m-d', strtotime($coupon->coupon_expire_date));

                                        if (($currentDate >= $coupon_start_date) && ($currentDate <= $coupon_end_date)){
                                            $status = 'Y';
                                       }else{
                                            $status = 'N';
                                        }
                                   @endphp
                                   
                                    @include('layouts.status', ['status' => $status])
                                    
                                </td>
                                <td>
                                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="delete">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                            <button onclick="return confirm('You are about to delete this record?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Delete</button>
                                            <a href="{{ route('admin.coupons.email', $coupon->id) }}" title="Email" class="btn btn-default btn-sm"><i class="fa fa-envelope"></i> </a>
                                            <a class="btn btn-default btn-sm" href="{{ route('admin.coupons.reports', $coupon->id) }}" title="Report"><i class="fa fa-file"></i></a>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                        </div>
                    {{ $coupons->links() }}
					
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            @else
            <p class="alert alert-warning">No record found. <a href="{{ route('admin.coupons.create') }}">Create one!</a></p>
        @endif
    </section>
    <!-- /.content -->
@endsection
