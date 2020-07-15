
<div class="row" style="margin-bottom:20px;">

<div>
    <!-- search form -->
    <form action="{{ route('admin.prep_produce_report.index') }}" method="get" id="admin-search">
	<div class="row col-12 col-sm-12 col-md-12">
	
		 
		<div class="col-12 col-sm-3 col-md-3"> 
        <div class="input-group">
		
		  <label>Display Report Date : </label>
            <input type="text" name="fr" class="form-control datepicker" readonly placeholder="Search by from date" value="@if(request()->input('fr')){!! request()->input('fr') !!}@else {!! date('d-m-Y') !!} @endif">
           
        </div>
        </div>
		
	
		
	
			<div class="col-12 col-sm-2 col-md-2"> 
        <div class="input-group">
		  
            <span class="input-group-btn">
			 
                <button type="submit" style="margin-top:25px;" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i> Search </button>
            </span>
        </div>
        </div>
		
		 
		
        </div>
    </form>
    <!-- /.search form -->
</div>
</div>

