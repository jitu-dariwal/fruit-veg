@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if(!$manufacturers->isEmpty())
            <div class="box">
                <div class="box-body">
				
				<h2>Manufacturers</h2>
		 @include('layouts.search', ['route' => route('admin.manufacturers.index')])		
                 <div class="table-responsive">   
                 <table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($manufacturers as $manufacturer)
                            <tr>
                                <td>
                                    {{ $manufacturer->name }}
                                </td>
                                <td>
                                    <form action="{{ route('admin.manufacturers.destroy', $manufacturer->id) }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="delete">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.manufacturers.edit', $manufacturer->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                            <button onclick="return confirm('You are about to delete this record?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Delete</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                 </div>
                    {{ $manufacturers->links() }}
					
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            @else
            <p class="alert alert-warning">No manufacturer created yet. <a href="{{ route('admin.manufacturers.create') }}">Create one!</a></p>
        @endif
    </section>
    <!-- /.content -->
@endsection
