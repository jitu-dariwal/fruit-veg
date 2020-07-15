<option value = "">Select Sub Category</option>
@if(!$subcategories->isEmpty())
    @foreach($subcategories as $subcategory)
        <option value="{{$subcategory->id}}">{{$subcategory->name}}</option>
    @endforeach
@endif