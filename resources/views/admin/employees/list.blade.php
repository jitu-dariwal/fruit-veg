@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

        @include('layouts.errors-and-messages')
        <!-- Default box -->
        @if($employees)
        <div class="box">
            <div class="box-body">
                <h2>Admin users</h2>
				 @include('layouts.search', ['route' => route('admin.employees.index'), 'search_by' => 'with name or email or role'])
                <div class="table-responsive">
                                 <table class="table table-striped">
                    <thead class="thead-light">
                        <tr>
                          <!--  <td class="col-md-1">S.No</td> -->
                            <th class="col-md-2">Name</th>
                            <th class="col-md-3">Email</th>
                            <th class="col-md-2">Role</th>
                            <th class="col-md-1">Status</th>
                            <th class="col-md-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
					
                    @foreach ($employees as $employee)
                      @if(!$employee->hasRole('superadmin'))
                        <tr>
                          <!--  <td>{{ ++$i }}</td> -->
                            <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ $employee->roles()->get()->implode('display_name', ', ') }}</td>
                            <td>@include('layouts.status', ['status' => $employee->status])</td>
                            <td>
                                <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="post" class="form-horizontal">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="delete">
                                    <div class="btn-group">
                                       <!-- <a href="{{ route('admin.employees.show', $employee->id) }}" class="btn btn-default btn-sm"><i class="fa fa-eye"></i> Show</a> -->
                                        <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                        <button onclick="return confirm('You are about to delete this record?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Delete</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                      @endif
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
