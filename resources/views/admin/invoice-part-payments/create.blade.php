@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box daterange-group">
           <form action="{{ route('admin.invoice-part-payments.store') }}" method="post" class="form" enctype="multipart/form-data">
                <div class="box-body">
                    <h2>Add Invoice Part Payment</h2>
                    {{ csrf_field() }}
					<input type="hidden" name="invoice_id" value="{{$invoiceid}}">
					<input type="hidden" name="customer_id" value="{{$customer_id}}">
                    <div class="form-group">
                        <label for="name">Amount</label>
                        <input type="number" min="0" value="{{old('amount')}}" class="form-control" placeholder="Amount"  name="amount">
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
