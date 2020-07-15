<form action="{{ route('search.product') }}">
	<div id="custom-search-input">
		<div class="input-group">
			<input type="text" name="search" class="search-query form-control" placeholder="Search our website here" value="{{ (isset($search)) ? $search : '' }}" />
			<span class="input-group-btn">
				<button type="submit"> <span class="ds-search"></span> </button>
			</span>
		</div>         
		<a href="#" data-toggle="collapse" data-target="#accordion" class="collapsed cat-btn">&nbsp;</a>
	</div>
</form>
