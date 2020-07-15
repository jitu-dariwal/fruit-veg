@if(count($categoryProducts)>0)
<div class="col-xs-12">
<div class="form-group">
<label for="product">Product:<span class="text-danger">*</span></label><br />
<select class="form-control" required id="product" name="product">
<option value="">Select Product</option>
@foreach($categoryProducts as $productlist)
<option value="{{$productlist->id}}">{{$productlist->name}}</option>
@endforeach
</select>
</div>
</div>
<div class="col-xs-12">
			  <div class="form-group">
				<label for="InputQty">Quantity:<span class="text-danger">*</span></label>
                <input type="number" name="qty" class="form-control" min="1" required id="InputQty" placeholder="Quantity">
			  </div>
			  </div>
			  <div class="col-xs-12">
			  
			  </div>
			  <div class="col-xs-12">
			  <button type="submit" class="btn btn-primary">Submit</button>
			  </div>
@else
<div class="col-xs-12">
<span class="text-danger">No Product Found!</span>
</div>
@endif