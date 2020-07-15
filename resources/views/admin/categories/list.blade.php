@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if($categories)
            <div class="box">
                <div class="box-body">
                    <h2>Categories</h2>
                   <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                              <th>Name</th>
                              <th>Total Products</th>
                              <th>Sub Categories</th>
                              <th>Status</th>
                              <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td><a href="{{ route('admin.categories.show', $category->id) }}">{{ $category->name }}</a></td>
								<td>{{$total_products[$category->id]}}</td>
                                <td>{{$category->total_cat}}</td>
								<td>@include('layouts.status', ['status' => $category->status])</td>
                                <td>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="delete">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                            <button onclick="return confirm('You are about to delete this record?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Delete</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
					</div>
                    {{ $categories->links() }}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        @endif

    </section>
    <!-- /.content -->
@endsection
