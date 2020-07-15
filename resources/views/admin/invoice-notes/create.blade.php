@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box daterange-group">
           <form action="{{ route('admin.invoice-notes.store') }}" method="post" class="form" enctype="multipart/form-data">
                <div class="box-body">
                    <h2>Add Invoice Note</h2>
                    {{ csrf_field() }}
					<input type="hidden" name="invoice_id" value="{{$invoiceid}}">
					<input type="hidden" name="customer_id" value="{{$customer_id}}">
                    <div class="form-group">
                        <label for="name">Notes</label>
                        <textarea class="form-control" placeholder="Add Note"  name="notes">{{old('notes')}}</textarea>
                    </div>
                    
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <a onclick="window.history.go(-1);" class="btn btn-default">Back</a>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box -->
     </section>
    <!-- /.content -->
@endsection
