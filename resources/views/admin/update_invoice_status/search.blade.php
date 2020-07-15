
<div class="row" style="margin-bottom:20px;">

<div>
    <!-- search form -->
    <form action="{{ route('admin.update_invoice_status.login') }}" method="post" id="admin-search">
	<div class="row col-12 col-sm-12 col-md-12">
	@csrf
		 
		<div class="col-12 col-sm-4 col-md-3"> 
        <div class="input-group">
		
		  <label>Password:</label>
            <input type="text" name="password" class="form-control "  placeholder="Enter your password." value="">
			
			
           
        </div>
		<span style="color:red;"> {{ $errors->first('password') }}</span>
        </div>
		
	
		
	
			<div class="col-12 col-sm-3 col-md-3"> 
        <div class="input-group">
		  
            <span class="input-group-btn">
			 
                <button type="submit" style="margin-top:25px;"  class="btn btn-success"><i class="fa fa-sign-in"></i> Click To Unlock Invoice Page</button>
            </span>
        </div>
        </div>
		
		
		
        </div>
    </form>
    <!-- /.search form -->
</div>
</div>

