@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if(!$bankholidays->isEmpty())
            <div class="box">
                <div class="box-body">
				
				<h2>Bank Holidays</h2>
		<div class="table-responsive">		
                    <table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Holiday Name</th>
                                <th>Holiday Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($bankholidays as $bankholiday)
                            <tr>
                                <td>
                                   {{ $bankholiday->name }}
                                </td>
                                 <td>
                                  {{ date('d-m-Y', strtotime($bankholiday->holiday_date)) }}
                                </td>
                                <td>
                                    <form action="{{ route('admin.bankholidays.destroy', $bankholiday->id) }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="delete">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.bankholidays.edit', $bankholiday->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                            <button onclick="return confirm('You are about to delete this record?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Delete</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                    {{ $bankholidays->links() }}
					
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            @else
            <p class="alert alert-warning">No record found. <a href="{{ route('admin.bankholidays.create') }}">Create one!</a></p>
        @endif
    </section>
    <!-- /.content -->
@endsection
