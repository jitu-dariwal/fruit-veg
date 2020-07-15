@if (session('status'))
	<div class="box no-border">
        <div class="box-tools">
			<div class="alert alert-success alert-dismissible">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong>Success!</strong> {{ session('status') }}
			</div>
		</div>
	</div>
@endif
	
@if (session('message'))
	<div class="box no-border">
        <div class="box-tools">
			<div class="alert alert-warning alert-dismissible">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				{{ session('message') }}
			</div>
		</div>
	</div>
@endif	

@if (session('warning'))
	<div class="box no-border">
        <div class="box-tools">
			<div class="alert alert-warning alert-dismissible">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong>Warning!</strong> {{ session('warning') }}
			</div>
		</div>
	</div>
@endif
	
@if (session('error'))
	<div class="box no-border">
        <div class="box-tools">
			<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong>Error!</strong> {{ session('error') }}
			</div>
		</div>
	</div>
@endif