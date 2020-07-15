@extends('layouts.admin.app')

@section('content')
<!-- Main content -->
<section class="content">
    @include('layouts.errors-and-messages')
    <div class="box">
        <div class="box-body">
            <h2>Product Update (Bulk)</h2>

            <form name="probulkupdateList" id="probulkupdateList" action=""  method="post" class="form" enctype="multipart/form-data">
                {{ csrf_field() }}
<div class="table-responsive">
                <table id="filter_block" class="table table-bordered table-striped">

                    <tr><td colspan="3"><h3>Filter Products</h3></td></tr>
                    <tr>
                        <td colspan="3">
                            <div class="form-group">
                                <label for="name">Product Name </label>
                                <input type="text" name="product_name" id="product_name" placeholder="Product Name" class="form-control" value="{{ old('product_name') }}">
                            </div> 
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="form-group">
                                <label for="name">Category</label>
                                <select name="category" id="categoriesbox" class="form-control">
                                    <option value="-1">Select Category</option>
                                    @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div> 
                        </td>
                        <td width="20"></td>
                        <td>
                            <div class="form-group">
                                <label for="name">Sub Category</label>
                                <select name="subcategory" id="subcategoriesbox" class="form-control">
                                    <option value="">Select Sub Category</option>

                                </select>
                            </div> 
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <input type="submit" id="filterproducts" name="filterproducts" value="Submit" class="btn btn-success"  />
                            <div id="searching_please_wait"></div>
                        </td>
                    </tr>

                </table>
				</div>    
            </form>

            


                <div id="filteredproducts">
					<div class="table-responsive">

                    <table class="table table-bordered table-striped">
                        <thead  class="thead-light">
                            <tr>
                                <th>Product Name</th>
                                <th>Bulk Size</th>
                                <th>Stock <img src="{{ asset("images/action_check.png") }}">(In Stock) <img src="{{ asset("images/action_delete.png") }}">(Out Stock)</th>
                                <th>Price(£)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                            $i = 0;
                            @endphp
                            @foreach ($products_list as $cat)        
                            <tr>
                                <td colspan='6' class="" style="padding:5px 10px;"><strong>{{strtoupper($cat['name'])}}</strong></td>
                            </tr>

                            @if(isset($cat['products']))
                            
                            @foreach ($cat['products'] as $product)
                            @php 
                            $i++;
                            @endphp
                            <tr>
                                <td>{{$product['productName']}} ({{$product['productType']}})</td>
                                <td>{{$product['productSize']}}</td>
                                <td>Front End 

                                    @if ($product['productStatusFront'] != 1)
                                    <span id="show_image_{{$i}}" class="showimg_u_link"><img src="{{ asset("images/action_delete.png") }}" alt="Add to In Stock" title="Add to In Stock" name="Image1_{{$i}}"  onClick="Stock_frontend('{{$product['productId']}}', '1', '{{$i}}')"></span>
                                    @else
                                    <span id="show_image_{{$i}}" class="showimg_u_link"><img src="{{ asset("images/action_check.png") }}" name="Image1_{{$i}}" alt="Add to Out of Stock" title="Add to Out of Stock"  onClick="Stock_frontend('{{$product['productId']}}', '0', '{{$i}}')"></span>
                                    @endif
                                    &nbsp;Back End 
                                    @if ($product['productStatusBackend'] != 1)
                                    <span id="show_image2_{{$i}}" class="showimg_u_link"><img src="{{ asset("images/action_delete.png") }}" name="Image2_{{$i}}" alt="Add to In Stock" title="Add to In Stock"  onClick="Stock_backend('{{$product['productId']}}', '1', '{{$i}}')"></span>
                                    @else
                                    <span id="show_image2_{{$i}}" class="showimg_u_link"><img src="{{ asset("images/action_check.png") }}" name="Image2_{{$i}}" alt="Add to Out of Stock" title="Add to Out of Stock"  onClick="Stock_backend('{{$product['productId']}}', '0', '{{$i}}')"></span>
                                    @endif


                                </td>
                                <td>
                                    <input name="price_{{$i}}" type="text" id="price_{{$i}}" value="{{$product['productPrice']}}" size="1" class="form-control" onblur="ProductPriceUpdate({{$product['productId']}}, {{$i}})">
                                    <div id="bulkprice_updated_{{$i}}" class="bulk_priceupdate_success"></div>
                                </td>
                                <td> 
                                   <form action="{{ route('admin.products.destroy', $product['productId']) }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="delete">
                                        <input type="hidden" name="deletefrombulk" value="1">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.products.edit', $product['productId']) }}" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                            <button onclick="return confirm('You are about to delete this record?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Delete</button>
                                        </div>
                                    </form> 
                                </td>

                            </tr>

                            @endforeach
                            @endif
                            @endforeach
                        </tbody>
                    </table>
					</div>  

         </div>

    </div>
</div>
<!-- /.box -->

</section>
<!-- /.content -->
@endsection
