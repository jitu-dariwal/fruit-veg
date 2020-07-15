<div class="dropdown">
    <a @if(request()->segment(2) == $category->slug) class="active" @endif href="{{route('page.index', $category->slug)}}" class="dropdown-toggle" id="{{$category->slug}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">{{$category->name}} <span class="caret"></span></a>
    <ul class="dropdown-menu" aria-labelledby="{{$category->slug}}">
        @foreach($subs as $sub)
            <li><a href="{{route('page.index', $sub->slug)}}">{{$sub->name}}</a></li>
        @endforeach
    </ul>
</div>