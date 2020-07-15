<div class="table-responsive">		
<table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th class="">Date</th>
                                <th style="text-align: center;" colspan="10" class="">Drivers Rounds</th>
                                <th>Total Rounds</th>
                            </tr>
                        </thead>
                        <tbody>
						
						@foreach($driverRounds as $drivers)
						<tr>
						<td>{{ \Carbon\Carbon::parse($drivers->round_date)->format('d-M-Y') }}</td>
						@php
						$i=0;
						$total_rounds =0;
						$driversinfo = Finder::getDriverRounds($drivers->round_date);
						@endphp
						@foreach($driversinfo as $driver)
						@php 
						$i++;
						$roundCount = Finder::getDriverTotalRounds($drivers->round_date,$driver->round_name);
						$total_rounds+=$roundCount;
						@endphp
						<td>Round {{$driver->round_name}} ({{$driver->driver_name}}) - {{$roundCount}}</td>
						@endforeach
						<td colspan="{{9-$i}}"><td>
						<td><p>{{$total_rounds}}</p></td>
						</tr>
						@endforeach
						@if(count($driverRounds)<=0)
							<tr>
						    <td colspan="13" class="not_found">{{Config::get('constants.NO_RECORD_FOUND')}}</td>
						    </tr>
						@endif
                        </tbody>
                    </table>
</div>