@extends('layouts.admin.app')

@section('content')

    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        
            <div class="box">
                <div class="box-body">
                    <h2>Customers Invoice Notes</h2>
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
					  <a href="{{route('admin.reports.customer-invoice-notes')}}" class="btn btn-default mb-2">Reset</a>
					  <a href="{{route('admin.reports.export-customer-invoice-notes')}}{{ (Request::getQueryString()) ? '?'.Request::getQueryString() : '' }}" class="btn btn-primary mb-2">Export Invoice Notes</a>
					  </div></td>
                    </tr>
                    </table>
					</form>
					<!-- /.search form -->
				    </div>
                    
                    <div class="table-responsive">
			<table class="table table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th class="">S. No.</th>
                                    <th class="">Customers Name</th>
                                    <th class="">Company Name</th>
                                    <th class="">Company Address</th>
                                    <th class="">Notes</th>
                                </tr>
                            </thead>
                        <tbody>
						@php
						$i=($notes->currentPage()-1)*$notes->perPage();
						@endphp
						@foreach($notes as $note)
						@php
						$i++;
						@endphp
						<tr>
						<td>{{ $i }}</td>
						<td><a href="{{ route('admin.customers.show', $note->id) }}">{{ucfirst($note->first_name.' '.$note->last_name)}}</a></td>
						<td><a href="{{ route('admin.customers.show', $note->id) }}">{{$note->defaultaddress->company_name}}</a></td>
						<td>{{$note->defaultaddress->street_address}}<br>{{$note->defaultaddress->address_line_2}}<br>&nbsp;{{$note->defaultaddress->city}}<br>&nbsp;{{$note->defaultaddress->country_state}}<br>&nbsp;{{$note->defaultaddress->country->name}}<br>&nbsp;{{$note->defaultaddress->post_code}}</td>
						<td><p>{{$note->customers_invoice_notes}}</p></td>
						</tr>
						@endforeach
						@if(count($notes)<=0)
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
				{{$notes->appends($_GET)->links()}}
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