<select class="form-control week_number" name="week_number">
						  @for($x=1; $x<=$weeks; $x++)
							  @php
						      $dates = Finder::getStartAndEndDate($x, $year);
						      @endphp
					    <option {{($week_no==$x) ? 'selected' : ''}} value="{{$x}}">Week {{$x . " - " . $dates['week_start'] . ' - ' . $dates['week_end']}}</option>
					       @endfor
						</select>