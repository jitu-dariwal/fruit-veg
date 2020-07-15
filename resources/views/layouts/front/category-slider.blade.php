<section class="my-accordion">
	<div class="container">
		<h2 class="mb-3 sub-heading">{{ __('content.home.cat_head') }}</h2>
		<div class="owl-carousel owl-theme">
			@if(isset($categories) && !empty($categories))
				@foreach($categories as $category)
					<a href="{{ route('page.index', $category->slug).'/' }}">
						<div class="item">
							<div class="card">
							@if(isset($category->cover) && !empty(isset($category->cover)))
								<img class="card-img-top img-fluid img-fluid-cat" src="{{ asset('uploads/'.$category->cover) }}" alt="{{ $category->image_alt_txt }}" />
							@else
								<img class="card-img-top img-fluid img-fluid-cat" src="https://placehold.it/204x159" alt="" />
							@endif
							<div class="card-body">
									<h4 class="card-title"> {{ (strlen($category->name)>20) ? substr($category->name, 0, 18).'..' : $category->name  }} </h4>
								</div>
							</div>
						</div>
					</a>
				@endforeach
			@endif
		</div>
	</div>
</section>