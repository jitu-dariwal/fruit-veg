<div id="accordion" class="accordion custom-accordion">
	<div class="card mb-0">
		<div class="left-heading">Categories</div>
		
		@foreach($categories as $category)
			<div class="card-header @if(Request::segment(1) != $category->slug) collapsed @endif {{ ($category->children->count() >= 1) ? 'hasChild' : 'noChild' }}" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$category->id}}"> <a class="card-title"> {{$category->name}} </a> </div>
			@if($category->children->count() >= 1)  
				<div id="collapse{{$category->id}}" class="card-body collapse @if(Request::segment(1) == $category->slug) show @endif" data-parent="#accordion" >
					<div id="accordion_inner_{{$category->id}}" class="accordion inner-accordion">
						@foreach($category->children as $subCat)
							<div class="card mb-0">
								<div class="card-header @if(Request::segment(2) != $subCat->slug) collapsed @endif {{ ($subCat->children->count() >= 1) ? 'hasChild' : 'noChild' }}" data-toggle="collapse" data-parent="#accordion_inner_{{$category->id}}" href="#collapse{{$subCat->id}}">{{$subCat->name}}</div>
								@if($subCat->children->count() >= 1)
									<div id="collapse{{$subCat->id}}" class="card-body collapse @if(Request::segment(2) == $subCat->slug) show @endif" data-parent="#accordion_inner_{{$category->id}}" >
										<ul class="nav flex-column fruit-list">
											@foreach($subCat->children as $child)
												<li class="nav-item">
													<a class="nav-link @if(Request::segment(1) == $category->slug && Request::segment(2) == $subCat->slug && Request::segment(3) == $child->slug)active @endif" href="{{ route('page.index', [$category->slug, $subCat->slug, $child->slug]) }}">{{ $child->name }}</a>
												</li>
											@endforeach
										</ul>
									</div>
								@endif
							</div>
						@endforeach
					</div>
				</div>
			@endif
		@endforeach
	</div>  
</div>