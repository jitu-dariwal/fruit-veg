<fieldset class="fleldset-block">
	<legend>Meta Tag Information</legend>
	<div class="form-group">
		<label for="meta_title">Meta Title</label>
		<input type="text" name="meta_title" id="meta_title" placeholder="Meta Title" class="form-control" value="{{ old('meta_title') }}">
	</div>
	<div class="row">

		<div class="col-md-6">
			<div class="form-group">
				<label for="meta_description">Meta Description </label>
				@php
					$pages_content = str_replace("[app_url]", url('/') , old('page_content'));
				@endphp
				<textarea class="form-control" name="meta_description" id="meta_description" rows="5" placeholder="Meta Description">{{ old('meta_description') }}</textarea>
			</div>	
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label for="meta_keyword">Meta Keyword </label>
				<textarea class="form-control" name="meta_keyword" id="meta_keyword" rows="5" placeholder="Meta Keyword">{{ old('meta_keyword') }}</textarea>
			</div>
		</div>
	</div>
</fieldset>