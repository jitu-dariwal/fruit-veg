<h3>Categories</h3>
<ul>
        @if(!empty($categories))
                @foreach ($categories as $category)
                         <li>{{$category['name']}}
                        @if (!empty($category['subcategories']))
                             <ul> 
                                     @foreach ($category['subcategories'] as $subcategory)
                                             <li><a href="{{ route('accounts.productslist') }}?catid={{$subcategory['cat_id']}}">{{$subcategory['name']}}</a></li>
                                     @endforeach
                             </ul>
                        @endif
                        </li>
                @endforeach
        @endif
        
        <li class="search_products"><a href="{{ route('products.searchproduct') }}">Search Products</a></li>
</ul>