
<div class="row" style="margin-bottom:20px;">

<div class="">
    <!-- search form -->
    <form action="{{ route('admin.issuelog.index') }}" method="get" id="admin-search">
	<div class="row col-12 col-sm-12 col-md-12">
	
		 
		<div class="col-12 col-sm-2 col-md-2"> 
        <div class="input-group">
		
		  <label>From Date:</label>
            <input type="text" name="fr" class="form-control datepicker" readonly placeholder="Search by from date" value="@if(request()->input('fr')){!! request()->input('fr') !!}@else {!! date('d-m-Y') !!} @endif">
           
        </div>
        </div>
		
		<div class="col-12 col-sm-2 col-md-2"> 
        <div class="input-group">
		
		  <label>To Date:</label>
            <input type="text" name="to" class="form-control datepicker" readonly placeholder="Search by to date" value="@if(request()->input('to')){!! request()->input('to') !!}@else {!! date('d-m-Y') !!} @endif">
           
        </div>
        </div>
		
		<div class="col-12 col-sm-4 col-md-4"> 
        <div class="input-group">
		
		  <label>Search by Company/name:</label>
            <input type="text" name="com" class="form-control" placeholder="Search by Company/name" value="{!! request()->input('com') !!}">
           
        </div>
        </div>
		
	
			<div class="col-12 col-sm-2 col-md-2"> 
        <div class="input-group">
		  
            <span class="input-group-btn">
			 
                <button type="submit" style="margin-top:25px;" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i> Search </button>
            </span>
        </div>
        </div>
		<div class="col-12 col-sm-2 col-md-2"> 
        <div class="input-group">
            <span class="input-group-btn">
			<a href="{{route('admin.issuelog.index')}}" style="margin-top:25px;margin-left: -90px;" class="btn btn-default mb-2">Reset</a>
			</span>
        </div>
        </div>
        </div>
    </form>
    <!-- /.search form -->
</div>
</div>

