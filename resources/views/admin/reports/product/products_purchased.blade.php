@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        
            <div class="box">
                <div class="box-body">
                    <h2>Best Products Purchased</h2>
					 @include('layouts.search', ['route' => route('admin.reports.productspurchased'), 'search_by' => 'With product name'])
                    <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="thead-light">
                            <tr class="dataTableHeadingRow">
                                <th class="col-md-3">S. No.</th>
                                <th class="col-md-3">Products</th>
                                <th class="col-md-2">Purchased Quantity</th>
                                <th class="col-md-2">
								<form class="form-inline sort_products" action="">
								<select class="form-control sort_by" name="sort_by">
								<option value="">Sort By</option>
								
								<option {{(request()->has('sort_by') && request('sort_by')=='psort_asc') ? 'selected' : ''}} value="psort_asc">Product Name Accending</option>					
								<option {{(request()->has('sort_by') && request('sort_by')=='psort_desc') ? 'selected' : ''}} value="psort_desc">Product Name Decending</option>					
								<option {{(request()->has('sort_by') && request('sort_by')=='osort_asc') ? 'selected' : ''}} value="osort_asc">Purchased Accending</option>
								
								<option {{(request()->has('sort_by') && request('sort_by')=='osort_desc') ? 'selected' : ''}} value="osort_desc">Purchased Decending</option>					
								</select>
								</form>
								</th>
                            </tr>
                        </thead>
                        <tbody>
						@php $i=($products->currentPage()-1)*$products->perPage(); @endphp
                        @foreach($products as $product)
						@php $i++; @endphp
						<tr>
						<td>{{$i}}</td>
						<td><a href="{{route('admin.products.show',$product->product_id)}}">{{$product->product->name}}</a></td>
						<td>{{$product->purchased_count}}</td>
						<td></td>
						</tr>
						@endforeach
						@if(count($products)<=0)
							<tr>
						    <td colspan="10" class="not_found">{{Config::get('constants.NO_RECORD_FOUND')}}</td>
						    </tr>
						@endif
                        </tbody>
                    </table>
                </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
				{{$products->appends($_GET)->links()}}
                </div>
            </div>
            <!-- /.box -->
        

    </section>
    <!-- /.content -->
@endsection
@section('js')
<script>
$(document).on('change','.sort_by',function(){
	$('.sort_products').submit();
	});  
</script>
@endsection