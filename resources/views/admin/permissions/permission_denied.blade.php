@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
			<div class="box">
                <div class="box-body">
					<p class="alert alert-warning">You are not allowed to access this page.</p>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
    </section>
    <!-- /.content -->
@endsection
