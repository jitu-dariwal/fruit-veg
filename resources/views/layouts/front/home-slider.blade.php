@php
	$data->content = str_replace("[app_url]", url('/') , $data->content);
@endphp
<main class="banner" style="background:url('{{url('/uploads/'.$data->banner_image)}}') center center no-repeat;background-size: auto;padding: 37px 0;border-top: solid 1px;#d6d6d6;background-size:cover;">
	<div class="container">
		<div class="banner-inner">
			<h1 class="heading">{{$data->banner_text}}</h1>
			<a href="{{config('shop.url')}}" class="btn site-btn">start shopping <span class="ds-right-arrow"></span> </a> 
		</div>
		<a href="{{config('shop.url')}}" class="btn site-btn d-md-none d-inline-block">start shopping <span class="ds-right-arrow"></span> </a>
	</div>
</main>