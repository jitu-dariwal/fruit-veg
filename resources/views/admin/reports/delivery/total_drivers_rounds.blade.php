@extends('layouts.admin.app')

@section('content')

    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        
            <div class="box">
                <div class="box-body">
                    <h2>Total Driver Rounds</h2>
                    <div class="table-responsive">
					<!-- search form -->
					<form method="GET" class="">
					
					<table class="table table-striped">
					<tr>
					<td>
					  <label class="" for="inlineFormInputName2">Month:</label></td>
					  <td class="week_numbers">
					  <select class="form-control month" name="month">
						  @foreach(Config::get('constants.MONTHS') as $key=>$val)
					    <option {{($month_no==$key) ? 'selected' : ''}} value="{{$key}}">{{$val}}</option>
					       @endforeach
					  </select>
					  </td>
					  <td>
					  <label class="" for="inlineFormInputName2">Display for the year:</label></td>
					  <td>
					  <select class="form-control" name="year">
						  @for($y=date('Y'); $y>=2000;$y--)
						  <option {{($year==$y) ? 'selected' : ''}} value="{{$y}}">{{$y}}</option>
						  @endfor
						</select>
					  </td>
					  <td><div style="margin-bottom:5px;">
					  <button type="submit" class="btn btn-primary mb-2">Submit</button>
					  <a href="{{route('admin.reports.total-driver-rounds')}}" class="btn btn-default mb-2">Reset</a>
					  <a href="{{route('admin.reports.export-total-driver-rounds')}}?month={{$month_no}}&year={{$year}}" class="btn btn-primary mb-2">Export Total Driver Round CSV</a>
					  </div></td>
                    </tr>
                    
						</table>
						
					</form>
					
					<!-- /.search form -->
				</div>
                    <div class="table-responsive">
					<table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Date</th>
                                <th style="text-align: center;" colspan="24">Drivers Rounds</th>
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
						<td>Round {{$driver->round_name}} ({{$driver->driver_name}}): {{$roundCount}}</td>
						@endforeach
						<td colspan="{{24-$i}}"></td>
						<td><p>{{$total_rounds}}</p></td>
						</tr>
						@endforeach
						@if(count($driverRounds)<=0)
							<tr>
						    <td colspan="25" class="not_found">{{Config::get('constants.NO_RECORD_FOUND')}}</td>
						    </tr>
						@endif
                        </tbody>
                    </table>
                </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                </div>
            </div>
            <!-- /.box -->
        

    </section>
    <!-- /.content -->
@endsection
@section('js')
<script>

</script>
@endsection