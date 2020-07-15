@extends('layouts.admin.app')

@section('content')
<!-- Main content -->
<section class="content">
    @include('layouts.errors-and-messages')
    <div class="box">
        <form action="{{ route('admin.products.store') }}" method="post" class="form" enctype="multipart/form-data">
            <div class="box-body">
                <h2>Create Product</h2>
                {{ csrf_field() }}


                <!-- Product basic details -->			
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Product Basic Information</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">


                        <div class="form-group">
                            <label for="name">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" placeholder="Name" class="form-control" value="{{ old('name') }}">
                        </div>
                        <div class="form-group">
                            <label for="description">Product Description </label>
                            <textarea class="form-control ckeditor" name="description" id="description" rows="5" placeholder="Description">{{ old('description') }}</textarea>
                        </div>
                       
                        <div class="form-group">
                            <label for="cover">Product Image</label>
                            <input type="file" name="cover" id="cover" class="form-control">
                        </div>
                         <!--
						<div class="form-group">
                            <label for="image">Product Images</label>
                            <input type="file" name="image[]" id="image" class="form-control" multiple>
                            <small class="text-warning">You can use ctr (cmd) to select multiple images</small>
                        </div>
                        -->
                        
                        
                        <div class="form-group">
                            <label for="quantity">Product Quantity <span class="text-danger">*</span></label>
                            <input type="text" name="quantity" id="quantity" placeholder="Quantity" class="form-control" value="{{ old('quantity') }}">
                            <input type="hidden" name="price" id="price" value="{{ old('gross_price_bulk') }}">
                        </div>
                        @if(!$brands->isEmpty())
                        <div class="form-group">
                            <label for="brand_id">Product Manufacturer </label>
                            <select name="brand_id" id="brand_id" class="form-control select2">
                                <option value=""></option>
                                @foreach($brands as $brand)
                                <option @if(old('brand_id') == $brand->id) selected="selected" @endif value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="form-group">
                            @include('admin.shared.status-select', ['status' => $product->status])
                        </div>
                        @include('admin.shared.attribute-select', [compact('default_weight')])

                    </div>
                </div>

                <!-- Product other details -->			
                <div class="box box-default collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Product Other Information</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">

                        <fieldset class="fleldset-block">

                            <!--	<div class="form-group">
                                    
        <label for="products_status">Is Split Product?<span class="text-danger"></span></label>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
                                    <input type="checkbox" name="is_split" value="1" checked >						
                                                                     
    </div> -->
                            <div class="row">					
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="products_status">Product Status:Bulk (Backend)<span class="text-danger"></span></label>                                                            
                                        <input name="products_status" value="1" type="radio" checked>&nbsp;In Stock&nbsp;<input name="products_status" value="0" type="radio">&nbsp;Out of Stock                          
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="products_status_split">Product Status:Split (Backend)<span class="text-danger"></span></label>                                                            
                                        <input name="products_status_split" value="1" type="radio" checked>&nbsp;In Stock&nbsp;<input name="products_status_split" value="0" type="radio">&nbsp;Out of Stock                          
                                    </div>
                                </div>
                            </div>

                            <div class="row">					
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="products_status">Product Status:Bulk (Frontend)<span class="text-danger"></span></label>                                                            
                                        <input name="products_status_2" value="1" type="radio" checked>&nbsp;In Stock&nbsp;<input name="products_status_2" value="0" type="radio">&nbsp;Out of Stock                          
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="products_status_split">Product Status:Split (Frontend)<span class="text-danger"></span></label>                                                            
                                        <input name="products_status_split_2" value="1" type="radio" checked>&nbsp;In Stock&nbsp;<input name="products_status_split_2" value="0" type="radio">&nbsp;Out of Stock                          
                                    </div>
                                </div>
                            </div>                        
                            <div class="row">					
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_code">Product Code for Bulk:<span class="text-danger">*</span></label>                                                          
                                        <input name="product_code" value="{{ old('product_code') }}" type="text" class="form-control"  placeholder="Product Code for Bulk">  
                                    </div>						
                                    <div class="form-group">                            
                                        <label for="product_code_split">Product Code for Split:<span class="text-danger"></span></label>                                                        
                                        <input name="product_code_split" value="{{ old('product_code_split') }}" type="text" class="form-control" placeholder="Product Code for Split"> 
                                    </div>
                                    <div class="form-group">
                                        <label for="packet_size">Packet Size for Bulk: (Ex: KG,litre etc...)<span class="text-danger"></span></label>                                                         
                                        <input name="packet_size" value="{{ old('packet_size') }}" type="text" class="form-control" placeholder="Packet Size for Bulk: (Ex: KG,litre etc...)">                     					
                                    </div>						
                                    <div class="form-group">                            
                                        <label for="packet_size_split">Packet Size for Split: (Ex: KG,litre etc...)<span class="text-danger"></span></label>                                                          
                                        <input name="packet_size_split" value="{{ old('packet_size_split') }}" type="text" class="form-control" placeholder="Packet Size for Split: (Ex: KG,litre etc...)">                          
                                    </div>
                                    <div class="form-group">                            
                                        <label for="packet_brand">Packet Brand:<span class="text-danger"></span></label>
                                        <input name="packet_brand" value="{{ old('packet_brand') }}" type="text" class="form-control" placeholder="Packet Brand">   
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bulk_quantity">Pack Value:Bulk<span class="text-danger">*</span></label>
                                        <input name="packvalue_quantity" id="packvalue_quantity" value="{{ old('packvalue_quantity') }}" type="text" class="form-control" placeholder="Pack Value:Bulk"> 
                                    </div>						
                                    <div class="form-group">                            
                                        <label for="split_quantity">Pack Value:Split<span class="text-danger"></span></label>
                                        <input name="split_quantity" value="{{ old('split_quantity') }}" type="text" class="form-control" placeholder="Pack Value:Split">
                                        <input name="split_product_count" id="split_product_count" value="" type="hidden" class="form-control" placeholder="Split product count"> 								
                                    </div>
                                    <div class="form-group">
                                        <label for="split_price">Gross Price (£)<span class="text-danger">*</span></label>
                                        <input name="gross_price_bulk" id="gross_price_bulk" value="{{ old('gross_price_bulk') }}" type="text" class="form-control" placeholder="GrossPrice:(price+VAT)">  
                                    </div>						
                                    <div class="form-group">                            
                                        <label for="real_split_price">Split Price (£)<span class="text-danger"></span></label>                                                          
                                        <input name="real_split_price" value="{{ old('real_split_price') }}" type="text" readonly="readonly" class="form-control" placeholder="Split Price">                             

                                    </div>
                                    <div class="form-group">                            
                                        <label for="type">Type:<span class="text-danger"></span></label><br><br>                                                    
                                        BULK                              
                                        <input name="type"  value="Bulk" type="hidden" class="form-control" placeholder="type">  
                                    </div>	



                                </div>
                            </div>

                        </fieldset>

                    </div>
                </div>

                <!-- Product meta details -->			
                <div class="box box-default collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Product Meta Tag Information</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                @include('layouts.admin.meta-tags')						
                            </div>
                        </div>	
                    </div>
                </div>

                <!-- Product category details -->			
                <div class="box box-default collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Product Category Information</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        @include('admin.shared.categories-list', ['categories' => $categories, 'selectedIds' => old('categories')])
                    </div>
                </div>


            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="btn-group">
                    <a onclick="window.history.go(-1);" class="btn btn-default">Back</a>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </div>
        </form>
    </div>
    <!-- /.box -->

</section>
<!-- /.content -->
@endsection
