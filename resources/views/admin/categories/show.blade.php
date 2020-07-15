@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if($category)
            <div class="box">
                <div class="box-body">
                    <h2>Category</h2>
                  <div class="cat_name_details">Name - {{ $category->name }}</div>
                </div>
                @if(!$categories->isEmpty())
                <hr>
                    <div class="box-body">
                        <h2>Sub Categories</h2>
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="col-md-3">Name</th>
                                <th class="col-md-3">Total Products</th>
                                <th class="col-md-3">Status</th>
                                <th class="col-md-3">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $cat)
                                    <tr>
                                        <td><a href="{{route('admin.categories.show', $cat->id)}}">{{ $cat->name }}</a></td>
										<td>{{$total_products[$cat->id]}}</td>
										<td>@include('layouts.status', ['status' => $cat->status])</td>
                                       <td>
										   <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="post" class="form-horizontal">
											{{ csrf_field() }}
											<input type="hidden" name="_method" value="delete">
											<div class="btn-group">
												<a href="{{ route('admin.categories.edit', $cat->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
												<button onclick="return confirm('You are about to delete this record?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Delete</button>
											</div>
										   </form>
									</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                @if(!$products->isEmpty())
                    <div class="box-body">
                        <h2>Products</h2>
                        @include('admin.shared.products', ['products' => $products])
                    </div>
                @endif
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <a onclick="window.history.go(-1);" class="btn btn-default btn-sm">Back</a>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        @endif

    </section>
    <!-- /.content -->
@endsection
