@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box daterange-group">
           <form action="{{ route('admin.coupons.store') }}" method="post" class="form" enctype="multipart/form-data">
                <div class="box-body">
                    <h2>Create Coupon</h2>
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">Coupon Name</label>
                        <input type="text" name="name" id="name" placeholder="Coupon Name" class="form-control" value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="name">Coupon Description</label>
                        <textarea name="description" id="description" placeholder="Coupon Description" class="form-control">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="name">Coupon Amount (£) <span class="text-danger">*</span></label>
                        <input type="text" name="coupon_amount" id="coupon_amount" placeholder="Coupon Amount" class="form-control" value="{{ old('coupon_amount') }}" required="required">
                    </div>
                    <div class="form-group">
                        <label for="name">Coupon Amount Type <span class="text-danger">*</span></label>
                        <select name="coupon_amount_type" class="form-control">
                            <option value="fixed" @if(old('coupon_amount_type') == 'fixed') selected="selected" @endif>Fixed</option>
                            <option value="percentage" @if(old('coupon_amount_type') == 'percentage') selected="selected" @endif>Percentage</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Coupon Minimum Order (£)</label>
                        <input type="text" name="coupon_minimum_order" id="coupon_minimum_order" placeholder="Coupon Minimum Order" class="form-control" value="{{ old('coupon_minimum_order') }}">
                    </div>
                   <!-- <div class="form-group">
                        <label for="name">Free Shipping</label>
                        <input type="checkbox" name="free_shipping" id="free_shipping" value="1">
                    </div> -->
                    <div class="form-group">
                        <label for="name">Coupon Code <span class="text-danger">*</span></label>
                        <input type="text" name="coupon_code" id="coupon_code" placeholder="Coupon Code" class="form-control" value="{{ old('coupon_code') }}" required="required">
                    </div>
                    <div class="form-group">
                        <label for="name">Uses per Coupon</label>
                        <input type="text" name="uses_per_coupon" id="uses_per_coupon" placeholder="Uses per Coupon" class="form-control" value="{{ old('uses_per_coupon') }}">
                    </div>
                    <div class="form-group">
                        <label for="name">Uses per Customer</label>
                        <input type="text" name="uses_per_user" id="uses_per_user" placeholder="Uses per Customer" class="form-control" value="{{ old('uses_per_user') }}">
                    </div>
                    <div class="form-group">
                        <label for="name">Valid Product List</label>
                        <input type="text" name="restrict_to_products" id="valid_product_list" placeholder="Valid Product List" class="form-control" value="{{ old('restrict_to_products') }}">
                        <br />
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#v_prd_list">View</button>
                    </div>
                    <div class="form-group">
                        <label for="name">Valid Categories List</label>
                        <input type="text" name="restrict_to_categories" id="valid_category_list" placeholder="Valid Categories List" class="form-control" value="{{ old('restrict_to_categories') }}">
                        <br />
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#v_cat_list">View</button>
                   </div>
                    <div class="form-group">
                        <label for="name">Start Date <span class="text-danger">*</span></label>
                        
                        <input type="text" name="coupon_start_date" id="date" placeholder="Start Date" class="form-control datepicker1 coupondatefrom" value="{{ old('coupon_start_date') }}" required="required">
                    </div>
                    <div class="form-group">
                        <label for="name">End Date <span class="text-danger">*</span></label>
                        <input type="text" name="coupon_expire_date" id="coupon_end_date" placeholder="End Date" class="form-control datepicker1 coupondateto" value="{{ old('coupon_expire_date') }}" required="required">
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
 
<!-- valid product list -->
 <div id="v_prd_list" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Product List</h4>

                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.coupons.selectproduct') }}" id="selectproduct" method="post" class="form-horizontal">
                        {{ csrf_field() }}
                    <ul>
                            @if(!empty($categories))
                                    @foreach ($categories as $category)
                                             <li>{{$category['name']}}
                                            @if (!empty($category['subcategories']))
                                                 <ul> 
                                                         @foreach ($category['subcategories'] as $subcategory)
                                                                @if(!$subcategory['products']->isEmpty()) 
                                                                    <li>
                                                                         {{$subcategory['name']}}
                                                                         <ul>
                                                                           @foreach($subcategory['products'] as $product)
                                                                           <li><input type="checkbox" name="product_ids[]" value="{{$product->id}}"> {{$product->name}}</li>
                                                                           @endforeach

                                                                         </ul>


                                                                     </li>
                                                                 @endif
                                                         @endforeach
                                                 </ul>
                                            @endif
                                            </li>
                                    @endforeach
                            @endif

                    </ul>
                        
                        <button type="submit" class="btn btn-primary">Submit</button>
                </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="close-select-product" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

<!-- valid category list -->
<div id="v_cat_list" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Select Categories</h4>

                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.coupons.selectcategory') }}" id="selectcategory" method="post" class="form-horizontal">
                        {{ csrf_field() }}
                        <ul>
                            @if(!empty($categories))
                                    @foreach ($categories as $category)
                                             <li>{{$category['name']}}
                                            @if (!empty($category['subcategories']))
                                                 <ul> 
                                                         @foreach ($category['subcategories'] as $subcategory)
                                                         <li><input type="checkbox" name="catlist[]" value="{{$subcategory['cat_id']}}"> {{$subcategory['name']}}</li>
                                                         @endforeach
                                                 </ul>
                                            @endif
                                            </li>
                                    @endforeach
                            @endif

                    </ul>
                        <button type="submit" class="btn btn-primary">Submit</button>
                </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="close-select-category" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    </section>
    <!-- /.content -->
@endsection
