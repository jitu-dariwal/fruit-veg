@extends('layouts.admin.app')

@section('content')

    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        
            <div class="box">
                <div class="box-body">
                    <h2>Customer Discount/Issue Log cross matching report</h2>
                   
                    <div class="table-responsive">
					<table class="table table-bordered table-striped">
                            <tr class="thead-light">
                                <td class="">Report 1.</td>
                                <td class=""><a href="{{route('admin.reports.discount-not-appear-on-issue-log')}}" target="_blank">There is a customer discount, that does not appear on issue log (match by order number)</a></td>
                            </tr>
			   <tr class="thead-light">
                                <td class="">Report 2.</td>
                                <td class=""><a href="{{route('admin.reports.discounts-only-in-issue-logs')}}" target="_blank">There is an issue logged that does not appear on the customer discount report (match by order number)</a></td>
                            </tr>
			    <tr class="thead-light">
                                <td class="">Report 3.</td>
                                <td class=""><a href="{{route('admin.reports.financial-implication-report')}}" target="_blank">Once order numbers match on both reports, check to see that ‘financial implication’ on issue log and discount<br> on order number are the same totals – if different alert us</a></td>
                            </tr>
                        
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