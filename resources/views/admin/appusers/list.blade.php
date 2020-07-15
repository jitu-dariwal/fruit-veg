@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if($app_users)
            <div class="box">
                <div class="box-body">
                    <h2>App Users Manager</h2>
                   <div class="table-responsive"> 
                    <table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
								<!--<td class="col-md-1">S.No</td> -->
                                <th class="col-md-2">App Usre Name</th>
                                <th class="col-md-3">Email Address</th>
				<th class="col-md-3">Account Created</th>
				<th class="col-md-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
						
                        @foreach ($app_users as $app_user)
                            <tr>
								
                                <td>{{ $app_user->first_name }} {{ $app_user->last_name }}</td>
								<td>{{ $app_user->email_address }}</td>
                                <td>{{ date("d/m/Y H:i:s", strtotime($app_user->create_account_date)) }}</td>
                                <td> 
                                    <form action="{{ route('admin.appusers.destroy', $app_user->app_user_id) }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}
                                       <!-- <input type="hidden" name="_method" value="delete"> -->
                                        <div class="btn-group">
                                            <a href="{{ route('admin.appusers.edit', $app_user->app_user_id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                           <!-- <button onclick="return confirm('You are about to delete this record?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Delete</button> -->
                                        </div>
                                    </form>
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