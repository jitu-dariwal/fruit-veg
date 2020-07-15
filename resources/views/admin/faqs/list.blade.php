@extends('layouts.admin.app')

@section('content')
    <section class="content">
		@include('layouts.errors-and-messages')
        <div class="box">
			<div class="box-body">				
				<h2>Faqs</h2>
				@include('layouts.search', ['route' => route('admin.faq.index')])		
				<div class="table-responsive">   
					<table class="table table-striped">
						<thead class="thead-light">
							<tr>
								<th>Question</th>
								<th>Status</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
						@if(!$faqs->isEmpty())
							@foreach ($faqs as $faq)
								<tr>
									<td>
										{{ $faq->question }}
									</td>
									<td>@include('layouts.status', ['status' => $faq->status])</td>
									<td>
										<form action="{{ route('admin.faq.destroy', $faq->id) }}" method="post" class="form-horizontal">
											{{ csrf_field() }}
											<input type="hidden" name="_method" value="delete">
											<div class="btn-group">
												<a href="{{ route('admin.faq.edit', $faq->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
												<button onclick="return confirm('You are about to delete this record?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Delete</button>
											</div>
										</form>
									</td>
								</tr>
							@endforeach
						@else
							<tr>
								<td colspan="2">
									<p class="alert alert-warning">No faq found yet. <a href="{{ route('admin.faq.create') }}">Create one!</a></p>
								</td>
							</tr>
						@endif
						</tbody>
					</table>
				</div>
				{{ $faqs->links() }}
			</div>
		</div>
    </section>
@endsection
