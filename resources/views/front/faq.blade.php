@extends('layouts.front.account-app')

@section('content')
	
	<header>
		<h2 class="sub-heading text-center mb-0">FAQ</h2>
	</header>
	
	<div class="row">
		<div class="col-md-12 col-sm-12">
			@if (session('status'))
				<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Success!</strong> {{ session('status') }}
				</div>
			@endif
				
			@if (session('warning'))
				<div class="alert alert-warning alert-dismissible">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Warning!</strong> {{ session('warning') }}
				</div>
			@endif
		</div>
		
		<div class="col-md-12">
			<div id="accordion" class="custom-accordion5">
				@if(!empty($faqs) && $faqs->count() > 0)
					@foreach($faqs as $faq)
						<div class="card mb-2">
							<div class="card-header faq" id="heading-2">					  
								<a class="collapsed" role="button" data-toggle="collapse" href="#collapse-{{$faq->id}}" aria-expanded="false" aria-controls="collapse-{{$faq->id}}">
									{{ $faq->question }}
								</a>
							</div>
							<div id="collapse-{{ $faq->id }}" class="collapse" data-parent="#accordion" aria-labelledby="heading-{{ $faq->id }}" style="">
								<div class="card-body">
									{{ $faq->answer }}
								</div>
							</div>
						</div>
					@endforeach
				@else
					<div class="col-12"><p>No data found.</p></div>
				@endif
			</div>
		</div>
	</div>
@endsection
