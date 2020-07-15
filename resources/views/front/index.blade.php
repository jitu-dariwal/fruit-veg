@extends('layouts.front.app')

@section('og')
    <meta property="og:type" content="home"/>
    <meta property="og:title" content="{{ config('app.name') }}"/>
    <meta property="og:description" content="{{ config('app.name') }}"/>
@endsection

@section('content')
    @include('layouts.front.top-alert-message')
	
	@include('layouts.front.home-slider',['data' => $dashboardSections[config('constants.dashboard_sections.top_section')]])
	
	<!-- quality section -->
	<section class="quality">
		<div class="container">	
			@php
				$dashboardSections[config('constants.dashboard_sections.quality')]->content = str_replace("[app_url]", url('/') , $dashboardSections[config('constants.dashboard_sections.quality')]->content);
			@endphp
			{!! $dashboardSections[config('constants.dashboard_sections.quality')]->content !!}
		</div>
	</section>
	<!-- end quality section -->


	<!-- category section -->
	@include('layouts.front.category-slider',['categories' => $cat])
	<!-- end category section -->

	<!-- easy ordering mobile section -->
	<section class="mobile-responsive">
		<div class="container">
			@php
				$dashboardSections[config('constants.dashboard_sections.easy_order')]->content = str_replace("[app_url]", url('/') , $dashboardSections[config('constants.dashboard_sections.easy_order')]->content);
			@endphp
			{!! $dashboardSections[config('constants.dashboard_sections.easy_order')]->content !!}
		</div>
	</section>
	<!-- end easy ordering mobile section -->

	<!-- who we deliver section -->
	<section class="we-deliver">
		<div class="container">
			<h2 class="sub-heading mb-4 text-center">{{$dashboardSections[config('constants.dashboard_sections.deliver_to')]->title}}</h2>
			@php
				$dashboardSections[config('constants.dashboard_sections.deliver_to')]->content = str_replace("[app_url]", url('/') , $dashboardSections[config('constants.dashboard_sections.deliver_to')]->content);
			@endphp
			{!! $dashboardSections[config('constants.dashboard_sections.deliver_to')]->content !!}
		</div>
	</section>
	<!-- end who we deliver section -->

	<!-- direct to you section -->
	<div class="dirct">
		<div class="container">
			<h2 class="heading mb-5"> {{__('content.home.direct_to')}}</h2>
			<div class="map-outer-block mb-5"> <script>
mapboxgl.accessToken = 'pk.eyJ1IjoidGltbTY3OTgiLCJhIjoiY2p1bXRqaGluMmNqYTQ0cGd1dXUzMzdpdiJ9.XpE5XvNvVG8ft9N6C6EgSQ';
var map = new mapboxgl.Map({
container: 'map',
style: 'mapbox://styles/timm6798/cjutydcl00egz1fqslrr4ixwp',
 center: [-0.0038, 51.4855],

  zoom: 9
});
// Add zoom and rotation controls to the map.
map.addControl(new mapboxgl.NavigationControl());
</script> </div>
			<div class="text-center"> <a href="{{config('shop.url')}}" class="btn site-btn">start shopping <span class="ds-right-arrow"></span> </a> </div>
		</div>
	</div>
	<!-- end direct to you section -->

	<!-- our happy customer section -->
	<section class="trustpilot-ratings">
		<div class="container">
			<h2 class="text-center heading mb-4">{{$dashboardSections[config('constants.dashboard_sections.happy_customer')]->title}}</h2>
			@php
				$dashboardSections[config('constants.dashboard_sections.happy_customer')]->content = str_replace("[app_url]", url('/') , $dashboardSections[config('constants.dashboard_sections.happy_customer')]->content);
			@endphp
			{!! $dashboardSections[config('constants.dashboard_sections.happy_customer')]->content !!}
		</div>
	</section>
	<!-- end happy customer section --->

@endsection