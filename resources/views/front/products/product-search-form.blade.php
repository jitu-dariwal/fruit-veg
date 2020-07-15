@extends('layouts.front.app')

@section('content')
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="box-body">
<!--            @include('layouts.errors-and-messages')-->
        </div>
        <div class="col-md-12">
            <h2> <i class="fa fa-home"></i> My Account</h2>
            <hr>
            <p> If you have a regular order in place you must contact us to change any details.
                You can however use your account to order additional fruitboxes.
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 my-account-categories">
            
            @include('front.shared.categories')
            
         </div>
        <div class="col-md-8 my-account-information">
            <h3>Search Products</h3>
			
<form action="{{ route('accounts.productslist') }}" class="form-inline" method="get">
   
	<table id="filter_block" class="table table-bordered">

                            
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label for="name">Product Name </label>
                                        <input type="text" name="product_name" id="product_name" required="required" placeholder="Product Name" class="form-control" value="{{ old('product_name') }}">
                                    </div> 
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label for="name">Category</label>
                                        <select name="parentcatid" id="categoriesbox" class="form-control">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                            <option value="{{$category['id']}}">{{$category['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div> 
                                </td>
                                
                           </tr>
						    <tr>
						   <td>
                                    <div class="form-group">
                                        <label for="name">Sub Category</label>
                                        <select name="catid" id="subcategoriesbox" class="form-control">
                                            <option value="">Select Sub Category</option>

                                        </select>
                                    </div> 
                             </td>
							 </tr>
                            <tr>
                                <td colspan="3">
                                    <input type="submit" name="filterproducts" value="Submit" class="btn btn-primary"  />
                                    
                                </td>
                            </tr>

                        </table>
	
	
	 {{ csrf_field() }}
    
</form>
           
       </div>
    </div>
</section>
<!-- /.content -->
@endsection