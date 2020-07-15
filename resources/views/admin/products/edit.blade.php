@extends('layouts.admin.app')

@section('content')
<!-- Main content -->
<section class="content">
    @include('layouts.errors-and-messages')
    <div class="box">
        <form action="{{ route('admin.products.update', $product->id) }}" method="post" class="form" enctype="multipart/form-data">
            <div class="box-body">
                <div class="row">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="put">
                    <div class="col-md-12">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist" id="tablist">
                            <!--<li role="presentation" @if(!request()->has('combination')) class="active" @endif><a href="#info" aria-controls="home" role="tab" data-toggle="tab">Info</a></li> -->
                            <!--<li role="presentation" @if(request()->has('combination')) class="active" @endif><a href="#combinations" aria-controls="profile" role="tab" data-toggle="tab">Combinations</a></li> -->
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content" id="tabcontent">
                            <div role="tabpanel" class="tab-pane @if(!request()->has('combination')) active @endif" id="info">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h2>{{ ucfirst($product->name) }}</h2>


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
                                                    <input type="text" name="name" id="name" placeholder="Name" class="form-control" value="{!! $product->name !!}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="description">Product Description </label>
                                                    <textarea class="form-control ckeditor" name="description" id="description" rows="5" placeholder="Description">{!! $product->description  !!}</textarea>
                                                </div>
												
												@if(!empty($product->cover))
                                                <div class="form-group">
                                                    <div class="col-md-3">
                                                        <div class="row">
                                                            <img src="{{ asset("uploads/$product->cover") }}" alt="" class="img-responsive img-thumbnail"><br><br>
															<a onclick="return confirm('Are you sure?')" href="{{ route('admin.product.remove.image', ['product' => $product->id]) }}" class="btn btn-danger">Remove image?</a>
                                                            <input name="coverimage_alt_text" value="{!! $product->coverimage_alt_text !!}" type="text" class="form-control"  placeholder="Alt Text"> <br>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
												
                                                <div class="row"></div>
                                                <div class="form-group">
                                                    <label for="cover">Product Image </label>
                                                    <input type="file" name="cover" id="cover" class="form-control">
                                                </div>
												<!--
                                                <div class="form-group">											
                                                    @foreach($images as $image)
                                                    <div class="col-md-3" style="margin-right:10px;">
                                                        <div class="row">

                                                            <img src="{{ asset("uploads/$image->src") }}" alt="" class="img-responsive img-thumbnail"> <br /> <br>
                                                            <input name="image_alt_text[{{ " $image->id " }}]" value="{!! $image->alt_text !!}" type="text" class="form-control"  placeholder="Alt Text"> <br>
                                                            <a onclick="return confirm('You are about to delete this record?')" href="{{ route('admin.product.remove.thumb', ['src' => $image->src]) }}" class="btn btn-danger btn-sm btn-block">Remove?</a><br />

                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                <div class="row"></div>
                                                <div class="form-group">
                                                    <label for="image">Product Images </label>
                                                    <input type="file" name="image[]" id="image" class="form-control" multiple>
                                                    <span class="text-warning">You can use ctr (cmd) to select multiple images</span>
                                                </div>
                                                -->
                                                <div class="form-group">
                                                    <label for="quantity">Product Quantity <span class="text-danger">*</span></label>

                                                    <input
                                                        type="text"
                                                        name="quantity"
                                                        id="quantity"
                                                        placeholder="Quantity"
                                                        class="form-control"
                                                        value="{!! $product->quantity  !!}"
                                                        >

                                                    <input type="hidden" name="price" id="price" value="{!! $product->price !!}">
                                                </div>

                                                <!--  <div class="form-group">
                                                      <label for="sale_price">Sale Price</label>
                                                      <div class="input-group">
                                                          <span class="input-group-addon">{{ config('cart.currency') }}</span>
                                                          <input type="text" name="sale_price" id="sale_price" placeholder="Sale Price" class="form-control" value="{{ $product->sale_price }}">
                                                      </div>
                                                  </div> -->
                                                @if(!$brands->isEmpty())
                                                <div class="form-group">
                                                    <label for="brand_id">Product Manufacturer </label>
                                                    <select name="brand_id" id="brand_id" class="form-control select2">
                                                        <option value=""></option>
                                                        @foreach($brands as $brand)
                                                        <option @if($brand->id == $product->brand_id) selected="selected" @endif value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @endif
                                                <div class="form-group">
                                                    @include('admin.shared.status-select', ['status' => $product->status])
                                                </div>
                                                @include('admin.shared.attribute-select', [compact('default_weight')])
                                                <!-- /.box-body -->	
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



                                                <div class="row">					
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="products_status">Product Status:Bulk (Backend)<span class="text-danger"></span></label>                                                        
                                                            <input name="products_status" value="1"  type="radio" @if($product->products_status == '1') checked @endif>&nbsp;In Stock&nbsp;<input name="products_status" value="0" type="radio" @if($product->products_status == '0') checked @endif>&nbsp;Out of Stock                          
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="products_status_2">Product Status:Bulk (Frontend)<span class="text-danger"></span></label>                                                        
                                                            <input name="products_status_2" value="1"  type="radio" @if($product->products_status_2 == '1') checked @endif>&nbsp;In Stock&nbsp;<input name="products_status_2" value="0" type="radio" @if($product->products_status_2 == '0') checked @endif>&nbsp;Out of Stock                          
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="product_code">Product Code for Bulk:<span class="text-danger"></span></label>                                                          
                                                            <input name="product_code" value="{!! $product->product_code !!}" type="text" class="form-control"  placeholder="Product Code for Bulk">  
                                                        </div>						
                                                        <div class="form-group">                            
                                                            <label for="product_code_split">Product Code for Split:<span class="text-danger"></span></label>                                                        
                                                            <input name="product_code_split" @if(isset($split_product->product_code)) value="{!! $split_product->product_code !!}" @endif type="text" class="form-control" placeholder="Product Code for Split"> 
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="packet_size">Packet Size for Bulk: (Ex: KG,litre etc...)<span class="text-danger"></span></label>                                                         
                                                            <input name="packet_size" value="{!! $product->packet_size !!}" type="text" class="form-control" placeholder="Packet Size for Bulk: (Ex: KG,litre etc...)">                     					
                                                        </div>						
                                                        <div class="form-group">                            
                                                            <label for="packet_size_split">Packet Size for Split: (Ex: KG,litre etc...)<span class="text-danger"></span></label>                                                          
                                                            <input name="packet_size_split" @if(isset($split_product->packet_size)) value="{!! $split_product->packet_size !!}" @endif type="text" class="form-control" placeholder="Packet Size for Split: (Ex: KG,litre etc...)">                          
                                                        </div>
                                                        <div class="form-group">                            
                                                            <label for="packet_brand">Packet Brand:<span class="text-danger"></span></label>
                                                            <input name="packet_brand" value="{!! $product->packet_brand !!}" type="text" class="form-control" placeholder="Packet Brand">   
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="products_status_split">Product Status:Split (Backend)<span class="text-danger"></span></label>                                                            
                                                            <input name="products_status_split" value="1" type="radio" @if(isset($split_product->products_status) && $split_product->products_status == '1') checked @endif>&nbsp;In Stock&nbsp;<input name="products_status_split" value="0" type="radio" @if(isset($split_product->products_status) && $split_product->products_status == '0') checked @endif>&nbsp;Out of Stock                          
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="products_status_split">Product Status:Split (Frontend)<span class="text-danger"></span></label>                                                            
                                                            <input name="products_status_split_2" value="1" type="radio" @if(isset($split_product->products_status_2) && $split_product->products_status_2 == '1') checked @endif>&nbsp;In Stock&nbsp;<input name="products_status_split_2" value="0" type="radio" @if(isset($split_product->products_status_2) && $split_product->products_status_2 == '0') checked @endif>&nbsp;Out of Stock                          
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="bulk_quantity">Pack Value:Bulk<span class="text-danger">*</span></label>
                                                            <input name="packvalue_quantity" id="packvalue_quantity" value="{!! $product->packvalue_quantity !!}" type="text" class="form-control" placeholder="Pack Value:Bulk"> 
                                                        </div>						
                                                        <div class="form-group">                            
                                                            <label for="split_quantity">Pack Value:Split<span class="text-danger"></span></label>
                                                            <input name="split_quantity" @if(isset($split_product->packvalue_quantity)) value="{!! $split_product->packvalue_quantity !!}" @endif type="text" class="form-control" placeholder="Pack Value:Split">
                                                            <!--<input name="split_product_count" id="split_product_count" value="" type="hidden" class="form-control" placeholder="Split product count">--> 								
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="split_price">Gross Price (£)<span class="text-danger">*</span></label>
                                                            <input name="gross_price_bulk" id="gross_price_bulk" value="{!! $product->price !!}" type="text" class="form-control" placeholder="GrossPrice:(price+VAT)">  
                                                        </div>						
                                                        <div class="form-group">                            
                                                            <label for="real_split_price">Split Price (£)<span class="text-danger"></span></label>                                                          
                                                            <input name="real_split_price" @if(isset($split_product->price)) value="{!! $split_product->price !!}" @endif type="text" class="form-control" placeholder="Split Price">                             

                                                        </div>
                                                        <div class="form-group">                            
                                                            <label for="type">Type:<span class="text-danger"></span></label><br><br>                                                    
                                                            BULK                              
                                                            <input name="type"  value="Bulk" type="hidden" class="form-control" placeholder="type">  
                                                        </div>
                                                    </div>

                                                </div>

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
                                                <div class="form-group">
                                                    <label for="meta_title">Meta Title</label>
                                                    <input type="text" name="meta_title" id="meta_title" placeholder="Meta Title" class="form-control" value="{!! $product->meta_title !!}">
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="meta_description">Meta Description </label>
                                                            <textarea class="form-control" name="meta_description" id="meta_description" rows="5" placeholder="Meta Description">{!! $product->meta_description !!}</textarea>
                                                        </div>	
                                                    </div>
                                                    <div class="col-md-6"><div class="form-group"><label for="meta_keyword">Meta Keyword </label>
                                                            <textarea class="form-control" name="meta_keyword" id="meta_keyword" rows="5" placeholder="Meta Keyword">{!! $product->meta_keyword !!}</textarea></div>						 
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
                                                @include('admin.shared.categories-list', ['categories' => $categories, 'ids' => $product])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="box-footer">
                                        <div class="btn-group">
                                            <a onclick="window.history.go(-1);" class="btn btn-default btn-sm">Back</a>
                                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- /.box -->
</section>
<!-- /.content -->
@endsection