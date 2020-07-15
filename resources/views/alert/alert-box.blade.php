		@if(session('success'))
		<div class="alert alert-hide alert-success alert-dismissible">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong>Success! </strong> {{ Session('success') }}
		</div>

		@endif

		@if(session('error'))
		<div class="alert alert-hide alert-danger alert-dismissible">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong>Error! </strong> {{ Session('error') }}
		</div>

		@endif

		@if(session('warning'))

		<div class="alert alert-hide alert-warning alert-dismissible">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong>Warning! </strong> {{ Session('warning') }}
		</div>

		@endif

		@if(session('info'))

		<div class="alert alert-hide alert-info alert-dismissible">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong>Info! </strong> {{ Session('info') }}
		</div>

		@endif

		@if(session('message'))
		<div class="alert alert-hide alert-success alert-dismissible">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong>Message! </strong> {{ Session('message') }}
		</div>

		@endif
