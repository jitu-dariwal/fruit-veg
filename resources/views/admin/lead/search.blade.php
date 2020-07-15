
<div class="row" style="margin-bottom:20px;">

<div class="">
    <!-- search form -->
    <form action="{{ route('admin.lead.index') }}" method="get" id="admin-search">
	<div class="row col-12 col-sm-12 col-md-12">
            <div class="col-12 col-sm-2 col-md-2"> 
                <div class="input-group">
                    <label>Search By Lead:</label>
                        <select name="lead" onChange="this.form.submit();" class="form-control">
                            <option value="" >Select Sales Lead </option>
                             @foreach($employees as $employee)
                            <option @if($employee->id == request()->input('lead'))selected @endif value="{{$employee->id}}"> {{$employee->first_name.' '.$employee->last_name}}</option>
                            @endforeach
                       </select>
                </div>
            </div>
	<div class="col-12 col-sm-2 col-md-2"> 
            <div class="input-group">
                   <label>Search By Status :</label>
                   <select name="status" onChange="this.form.submit();" class="form-control">
                            <option value="" >Select Status </option>
                           @foreach(Config('constants.LeadStatus') as $leadKey => $LeadStatus)
                           <option @if($leadKey == request()->input('status'))selected @endif  value="{{$leadKey}}"> {{$LeadStatus}}</option>
                           @endforeach
                    </select>
            </div>
        </div>
		
	<div class="col-12 col-sm-4 col-md-4"> 
            <div class="input-group">
                <label>Search By Customers:</label>
                <input type="text" name="com" class="form-control" placeholder="Search By Customers" value="{!! request()->input('com') !!}">
            </div>
        </div>
		
	
			<div class="col-12 col-sm-2 col-md-2"> 
        <div class="input-group">
		  
            <span class="input-group-btn">
			 
                <button type="submit" style="margin-top:25px; " id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i> Search </button>
				
            </span>
        </div>
        </div>
		<div class="col-12 col-sm-2 col-md-2"> 
        <div class="input-group">
            <span class="input-group-btn">
			<a href="{{route('admin.lead.index')}}" style="margin-top:25px;margin-left: -90px;" class="btn btn-default mb-2">Reset</a>
			</span>
        </div>
        </div>
        </div>
    </form>
    <!-- /.search form -->
</div>
</div>

