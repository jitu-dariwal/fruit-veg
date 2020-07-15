<div class="custom-dd">
	<button data-toggle="collapse" data-target="#demo" class="btn btn-outline-secondary dropdown-toggle">
	@if(empty($category))
		Select Category
	@else
		{{ $category->name }}
	@endif	
	</button>
	<div id="demo" class="collapse">
		<div id="accordion4" class="accordion custom-accordion">
			<div class="card mb-0">
				@if(isset($categories) && count($categories) > 0)
					@foreach($categories as $category)
						<div class="card-header @if($parent_cat != $category->slug) collapsed @endif" data-toggle="collapse" data-parent="#accordion" href="#favcollapse{{$category->id}}"> <a class="card-title"> {{$category->name}} </a> </div>
						@if($category->children->count() >= 1)  
							<div id="favcollapse{{$category->id}}" class="collapse @if($parent_cat == $category->slug) show @endif" data-parent="#accordion" >
								<ul class="nav flex-column fruit-list">
									@foreach($category->children as $subcategory)
										<li class="nav-item">
											<a class="nav-link @if($parent_cat == $category->slug && $cat == $subcategory->slug) active @endif" href="{{ route('front.favproducts').'/'.\Auth::user()->id.'?cat='.$subcategory->id }}">
												{{ $subcategory->name }}
												<span>{{$subcategory->listFavCategoryProducts([$subcategory->id],\Auth::user()->id, true)}} items</span>
											</a>
										</li>
									@endforeach
								</ul>
							</div>
						@endif
					@endforeach
				@else
					<div class="card-header collapsed"> <a class="card-title"> No categories </a> </div>
				@endif
			</div>
		</div>
	</div>
</div>