@extends('layouts.admin.app')

@section('content')
<!-- Main content -->
<section class="content">
    @include('layouts.errors-and-messages')
    <div class="box">
        <div class="box-body">
            <h2>Generate PDF of products List</h2>

            <ul class="nav nav-tabs" role="tablist" id="tablist">
                <li role="presentation" @if(!request()->has('pdfmanually')) class="active" @endif><a href="#pdfmanually" aria-controls="home" role="tab" data-toggle="tab">Products List PDF manually</a></li>
                <li role="presentation" @if(request()->has('pdfauto')) class="active" @endif><a href="#pdfauto" aria-controls="profile" role="tab" data-toggle="tab">Products List PDF auto-generate</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content" id="tabcontent">

                <div role="tabpanel" class="tab-pane active" id="pdfmanually">

                    <form name="proList" id="proList" action="{{ route('admin.products.generateprdpdf') }}"  method="post" class="form" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <table id="pList" class="table table-bordered">

                            <tr>
                                <td colspan="8">

                                    <div class="form-group">
                                        <label for="name">Customer Name <span class="text-danger">*</span></label>
                                        <input type="text" name="customer_name" id="customer_name" placeholder="Customer Name" required="required" class="form-control" value="{{ old('customer_name') }}">
                                    </div>  
                                </td>
                            </tr>

                            <tr>
                                <td><input type="checkbox" disabled="disabled"  name="2_chk"/></td>
                                <td>1</td>
                                <td><p class="frmtitle">Product Name</p></td>
                                <td><input type="text" name="2_productName" class="form-control" required="required" size="30"/> </td>
                                <td><p class="frmtitle">Size</p></td>
                                <td><input type="text" class="form-control" name="2_productSize"/> </td>
                                <td><p class="frmtitle">Price(£)</p></td>
                                <td>	
                                    <input type="text" class="form-control" name="2_productPrice" placeholder="0.00" pattern = "^\d{0,8}(\.\d{0,2})?$" />
                                    <input type="hidden" name="total_products" id="total_products" value="2" />
                                </td>
                            </tr>
                        </table>

                        <div class="box-footer">
                            <div class="btn-group">
                                <input type="button" name="remove" value="Remove" class="btn btn-primary" onclick="deleteRowFeature('pList')" />&nbsp;
                                <input type="button" name="addmore" value="Add More" class="btn btn-primary" onclick="addRowFeature('pList')" />&nbsp;
                                <input type="submit" name="submit" value="Generate PDF" class="btn btn-primary" />
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sendpdf">Send Mail</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div role="tabpanel" class="tab-pane"  id="pdfauto">
                    <form name="proautoList" id="proautoList" action=""  method="post" class="form" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <table id="filter_block" class="table table-bordered">

                            <tr><td colspan="3"><h3>Filter Products</h3></td></tr>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label for="name">Select Group <span class="text-danger">*</span></label>
                                        <select name="group" id="group_id" class="form-control" required="required">
                                            <option value="">Select Group</option>
                                            @foreach($groups as $group)
                                            <option value="{{$group->customers_group_id}}">{{$group->customers_group_name}}</option>
                                            @endforeach
                                        </select>
                                    </div> 
                                </td>
                                <td width="20"></td>
                                <td>
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
                                    <input type="submit" id="filterproducts" name="filterproducts" value="Submit" class="btn btn-primary"  />
                                    <div id="searching_please_wait"></div>
                                </td>
                            </tr>

                        </table>
                    </form>

                    <form name="toaddedProduct" id="toaddedProduct" action=""  method="post" class="form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        
                        
                        <div id="filteredproducts"></div>
                        
                        
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
    <!-- /.box -->
    <!-- export order form -->
    <div id="sendpdf" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Send Mail</h4>
                </div>
                <div class="modal-body">
                    <form name="pdfmail" action="{{ route('admin.products.sendpdfemail') }}"  method="post" class="form" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <table id="pList1" class="table table-bordered">

                            <tr>
                                <td colspan="10">

                                    <div class="form-group">
                                        <label for="name">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" name="email_address" id="email_address" placeholder="Email Address" required="required" class="form-control"  value="{{ old('email_address') }}">
                                    </div>  
                                </td>
                                <td colspan="2"></td>
                            </tr>

                            <tr>
                                <td colspan="10">

                                    <div class="form-group">
                                        <label for="name">Select PDF <span class="text-danger">*</span></label>
                                        <input type="file" name="pdf_file" accept="application/pdf" required="required">
                                    </div>  
                                </td>
                                <td colspan="2"></td>
                            </tr>


                        </table>

                        <div class="box-footer">
                            <div class="btn-group">

                                <input type="submit" name="submit" value="Send Mail" class="btn btn-primary" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <!-- export order form -->
    <div id="addproductmodal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Selected Products</h4>
                </div>
                <div class="modal-body">
                    <form name="pdfgenerate" action="{{ route('admin.products.generateprdpdf') }}"  method="post" class="form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                       
                        <div id="addedprdList"></div>
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

</section>
<!-- /.content -->
@endsection
