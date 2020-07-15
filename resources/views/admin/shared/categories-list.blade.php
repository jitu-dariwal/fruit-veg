<ul class="list-unstyled">
    @foreach($categories as $category)
		<li>
			<div class="checkbox">
				<label> <strong> {{ $category['name'] }} </strong> </label>
				
				@if(isset($category['subcategories']) && !empty($category['subcategories']))
					<ul class="noListStyle">
					@foreach($category['subcategories'] as $subCat)
						@if(isset($subCat['childcategories']) && !empty($subCat['childcategories']))
							<li>
								<div class="checkbox">
									<label> <b> {{ $subCat['name'] }} </b> </label>
									<ul class="noListStyle">
									@foreach($subCat['childcategories'] as $childCat)
										<li>
											<div class="checkbox">
												<label>
													<input type="checkbox" @if(isset($selectedIds) && in_array($childCat['cat_id'], $selectedIds)) checked="checked" @endif name="categories[]" value="{{ $childCat['cat_id'] }}"> {{ $childCat['name'] }}
												</label>
											</div>
										</li>
									@endforeach
									</ul>
								</div>
							</li>
						@else
							<li>
								<div class="checkbox">
									<label>
										<input type="checkbox" @if(isset($selectedIds) && in_array($subCat['cat_id'], $selectedIds))checked="checked" @endif name="categories[]" value="{{ $subCat['cat_id'] }}"> {{ $subCat['name'] }}
									</label>
								</div>
							</li>
						@endif
					@endforeach
					</ul>
				@endif
			</div>
		</li>
    @endforeach
</ul>