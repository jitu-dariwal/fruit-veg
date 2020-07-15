@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if($customers)
            <div class="box">
                <div class="box-body">
                    <h2>Customers</h2>
                    @include('layouts.search', ['route' => route('admin.customers.index'), 'search_by' => 'with email or customer name'])
                   <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
								<!--<td class="col-md-1">S.No</td> -->
                                <th>Company Name</th>
                                <th>Name</th>
                                <th>Post Code</th>
                                <th>Spend(£)</th>
                                <th>Account created</th>
                                <th><input type="checkbox" name="checkAll" id="checkAll" value="1"> Update</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                    <form action="{{ route('admin.customers.updatechase') }}" method="post" class="form-horizontal">
                        @foreach ($customers as $customer)
                            <tr>
								<!--<td>{{ ++$i }}</td> -->
                                <td>{{ $customer['company_name'] }}</td>
								<td>
								@if($customer['chase'] == 't')<img src="{{ asset("images/green.gif") }}" alt="Chased to customer">@endif
								<img src="{{ asset("images/red.gif") }}" alt="Chased to customer"> 
								<img src="{{ asset("images/yellow.gif") }}" alt="Chased to customer">
								{{ $customer['first_name'] }} {{ $customer['last_name'] }}
								</td>
								<td>{{ $customer['post_code'] }}</td>
                                <td>{{ $customer['current_spend_month'] }}</td>
                                <td>{{ date('d/m/Y H:i:s', strtotime($customer['created_at'])) }}</td>
								<td><input type="checkbox" value="{{$customer['id']}}" name="update_chase[]"></td>
                              
                                <td>
                                 
                                        {{ csrf_field() }}
                                       
                                        <div class="btn-group">
                                            <!--<a href="{{ route('admin.customers.show', $customer['id']) }}" class="btn btn-default btn-sm"><i class="fa fa-eye"></i> </a>
											<button onclick="return confirm('You are about to delete this record?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> </button>-->
                                            <a href="{{ route('admin.customers.edit', $customer['id']) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
											<a href="{{ route('admin.orders.customer_order_list', $customer['id']) }}" class="btn btn-default btn-sm"><i class="fa fa-list"></i></a>
											<a href="{{ route('admin.customers.email', $customer['id']) }}" class="btn btn-default btn-sm"><i class="fa fa-envelope"></i> </a>											
                                            
                                        </div>
                                   
                                </td>
                            </tr>
                        @endforeach
						
						<tr>
						 <td>
							<select name="update_order_type" class="form-control">
								<option value="t" selected="">Chase</option>
								<option value="f">Unchase</option>
							</select>
						</td>
						<td><input type="submit" value="Submit" class="btn btn-primary btn-sm"></td>
                                                <td colspan="5"></td>
                                                </tr>
						</form>
                        </tbody>
                    </table>
					</div> 
                    {{ $customers->links() }} Displaying  {{ $customers->firstItem() }} to {{ $customers->lastItem() }} (of {{ $customers->total() }} customers) <!--(for page {{ $customers->currentPage() }} ) -->
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        @endif

    </section>
    <!-- /.content -->
@endsection