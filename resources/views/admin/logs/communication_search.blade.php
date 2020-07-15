<div class="row" style="margin-bottom:20px;">
<div class="col-xs-12">
    <!-- search form -->
    <form action="{{ route('admin.communicationlog.index') }}" method="get" id="admin-search">
	<!--
	<div class="row col-12 col-sm-12 col-md-12">		
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
		
        </div>
        -->
        
		<table cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse; width: 100%;">
			<tr>
				<td colspan="2"><label>Search by Company/name:</label></td>
			</tr>
			<tr>
				<td style="width:339px;"><input type="text" name="com" class="form-control" placeholder="Search by Company/name" value="{!! request()->input('com') !!}">  </td>
				<td> <button type="submit"  id="search-btn" class="btn btn btn-flat"><i class="fa fa-search"></i> Search </button></td>
				<td> <a href="{{route('admin.communicationlog.index')}}" style="margin-right: 568px;" class="btn btn-flat btn-default">Reset</a></td>
                                <td></td>
			</tr>
		</table>
    </form>
    <!-- /.search form -->
</div>
</div>

