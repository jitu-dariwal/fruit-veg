@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if(count($invoicePayments)>0)
            <div class="box">
                <div class="box-body">
				
				<h2>
View Customer Part Payment Against Invoice <a class="btn btn-primary pull-right" href="{{ route('admin.invoice-part-payments.create', [$customer_id,$invoiceid]) }}" title="Add Part Payment"><i class="fa fa-plus" aria-hidden="true"></i>Add Part Payment</a></h2>
				
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>InvoiceID</th>
                                <th>Payment Add Date</th>
                                <th>Payment</th>
                            </tr>
                        </thead>
                        <tbody>
						@php $i=0; @endphp
                        @foreach ($invoicePayments as $payment)
						@php $i++; @endphp
                            <tr>
                                <td>
                                  {{ $i }}
                                </td>
                                <td>
                                  {{ $payment->invoiceid }} 
                                </td>
                                <td>
                                  {{ $payment->created_at->format('d-m-Y h:i A') }}
                                </td>
								<td>
                                  {{ $payment->partpayment }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $invoicePayments->links() }}
					
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            @else
            <p class="alert alert-warning">No record found. <a href="{{ route('admin.invoice-part-payments.create', [$customer_id,$invoiceid]) }}">Add Part Payment!</a></p>
        @endif
    </section>
    <!-- /.content -->
@endsection
