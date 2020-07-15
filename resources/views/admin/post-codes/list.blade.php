@extends('layouts.admin.app')

@section('content')
    <section class="content">
		@include('layouts.errors-and-messages')
        <div class="box">
			<div class="box-body">				
				<h2>Post Codes</h2>
				@include('layouts.search', ['route' => route('admin.post-code.index')])		
				<div class="table-responsive">   
					<table class="table table-striped">
						<thead class="thead-light">
							<tr>
								<th>Title</th>
								<th>Status</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
						@if(!$postCodes->isEmpty())
							@foreach ($postCodes as $postCode)
								<tr>
									<td>
										{{ $postCode->title }}
									</td>
									<td>@include('layouts.status', ['status' => $postCode->status])</td>
									<td>
										<form action="{{ route('admin.post-code.destroy', $postCode->id) }}" method="post" class="form-horizontal">
											{{ csrf_field() }}
											<input type="hidden" name="_method" value="delete">
											<div class="btn-group">
												<a href="{{ route('admin.post-code.edit', $postCode->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
												<button onclick="return confirm('You are about to delete this record?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Delete</button>
											</div>
										</form>
									</td>
								</tr>
							@endforeach
						@else
							<tr>
								<td colspan="2">
									<p class="alert alert-warning">No post-code found yet. <a href="{{ route('admin.post-code.create') }}">Create one!</a></p>
								</td>
							</tr>
						@endif
						</tbody>
					</table>
				</div>
				{{ $postCodes->links() }}
			</div>
		</div>
    </section>
@endsection
