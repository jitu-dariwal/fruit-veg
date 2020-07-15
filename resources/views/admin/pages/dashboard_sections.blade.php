@extends('layouts.admin.app')

@section('content')
    <section class="content">
		@include('layouts.errors-and-messages')
		
        @if($pages)
            <div class="box">
                <div class="box-body">
                    <h2>Dashboard Sections</h2>
                    <div class="table-responsive">
						<table class="table table-striped">
							<thead class="thead-light">
								<tr>
									<!--<td class="col-md-1">S.No</td> -->
									<th width="60%">Section Name</th>
									<th width="40%">Actions</th>
								</tr>
							</thead>
							<tbody>
							@if (!empty($pages->count()))
								@foreach ($pages as $page)
									<tr>
										<td>{{ $page->name }}</td>
										<td> <form action="{{ route('admin.pages.destroy', $page->id) }}" method="post" class="form-horizontal">
												{{ csrf_field() }}
												<input type="hidden" name="_method" value="delete"> 
												<div class="btn-group">
													<a href="{{ route('admin.dashboardSectionsEdit', $page->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
													<button onclick="return confirm('You are about to delete this record?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Delete</button>
												</div>
											</form>
										</td>
										
									</tr>
								@endforeach
							@else
								<tr><td colspan="4" class="no_record_found">{{ config('constants.NO_RECORD_FOUND') }}</td></tr>
							@endif
							</tbody>
						</table>
                    </div>
                </div>
            </div>
        @endif
    </section>
@endsection