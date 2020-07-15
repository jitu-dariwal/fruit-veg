@extends('layouts.front.account-app')

@section('meta_tags')
	@if(!empty($page_details->meta_title))
		<title>{{ $page_details->meta_title }}</title>
	@else
		<title>{{ config('app.name') }}</title>
	@endif
	
	<meta name="description" content="{!! $page_details->meta_description !!}">
	<meta name="tags" content="{!! $page_details->meta_keyword !!}">
@endsection

@section('og')
    <meta property="og:type" content="categories"/>
    @if(!empty($page_details->meta_title))
		<meta property="og:title" content="{!! $page_details->meta_title !!}"/>
	@else
		<meta property="og:title" content="{!! config('app.name') !!}"/>
	@endif
	
    <meta property="og:description" content="{!! $page_details->meta_description !!}"/>
@endsection

@section('content')
	
	<header>
		<h2 class="sub-heading text-center mb-0">@if(isset($page_details->title) && !empty($page_details->title)){{$page_details->title}}@endif</h2>
	</header>
	
	<div class="row">
		@php
			if(isset($page_details->content) && !empty($page_details->content)) {
				$page_details->content = str_replace("[app_url]", url('/') , $page_details->content);
			}
		@endphp
		<div class="col-md-12">
			@if(isset($page_details->content) && !empty($page_details->content)){!! $page_details->content !!}@endif
		</div>
	</div>
@endsection
