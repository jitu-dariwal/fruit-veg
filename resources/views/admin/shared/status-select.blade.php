<label for="status">Status </label>
<select name="status" id="status" class="form-control select2">
	<option value="1" @if(isset($status) && $status == 1) selected="selected" @endif @if(!isset($status)) selected="selected" @endif>Enable</option>
    <option value="0" @if(isset($status) && $status == 0) selected="selected" @endif>Disable</option>
</select>