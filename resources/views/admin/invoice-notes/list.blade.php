@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if(count($invoiceNotes)>0)
            <div class="box">
                <div class="box-body">
				
				<h2>Invoice Notes <a class="btn btn-primary pull-right" href="{{ route('admin.invoice-notes.create', [$customer_id,$invoiceid]) }}" title="Add New Note"><i class="fa fa-plus" aria-hidden="true"></i> Add Note</a></h2>
				
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>InvoiceID</th>
                                <th>Note Add Date</th>
                                <th>Cusomers Invoice Note</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
						@php $i=0; @endphp
                        @foreach ($invoiceNotes as $note)
						@php $i++; @endphp
                            <tr>
                                <td>
                                   {{ $i }}
                                </td>
                                 <td>
                                  {{ $note->invoiceid }} 
                                </td>
                                 <td>
                                   {{ $note->created_at->format('d-m-Y h:i A') }}
                                </td>
								<td>
                                   {{ $note->notes }}
                                </td>
                                <td>
                                    <form action="{{ route('admin.invoice-notes.destroy', $note->id) }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="delete">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.invoice-notes.edit', $note->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                            <button onclick="return confirm('You are about to delete this record?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Delete</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $invoiceNotes->links() }}
					
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            @else
            <p class="alert alert-warning">No record found. <a href="{{ route('admin.invoice-notes.create', [$customer_id,$invoiceid]) }}">Add Note!</a></p>
        @endif
    </section>
    <!-- /.content -->
@endsection
