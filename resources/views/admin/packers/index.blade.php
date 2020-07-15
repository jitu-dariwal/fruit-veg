@extends('layouts.admin.app')

@section('content')

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
      <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-tasks"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Orders</span>
              <span class="info-box-number">{{count($list)}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-tags"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Pending Orders</span>
              <span class="info-box-number">{{$pendingOrders}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-tag"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Processing Orders</span>
              <span class="info-box-number">{{$processingOrders}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-shopping-bag"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Delivered Orders</span>
              <span class="info-box-number">{{$deliveredOrders}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                
            <!-- TABLE: LATEST ORDERS -->
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Latest Orders</h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <div class="table-responsive">
                    <table class="table no-margin">
                      <thead>
                      <tr>
                        <th>Order Date</th>
                        <th>Customer</th>
                        <th>Delivery Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>
					  @foreach($orders as $order)
                      <tr>
                        <td><a href="{{ route('admin.packer.order.show', $order->id) }}">{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i a') }}</a></td>
                        <td>{{ucfirst($order->customer->first_name.' '.$order->customer->last_name)}}</td>
                        <td><span class="label label-success">{{ \Carbon\Carbon::parse($order->orderdetail->shipdate)->format('M d, Y ') }}</span></td>
                        <td>
                          <span class="label @if($order->total != $order->total_paid) label-danger @else label-success @endif">{{ config('cart.currency') }} {{ $order->total }}</span>
                        </td>
						<td>
                          <p class="text-center" style="color: #ffffff; background-color: {{ $order->orderStatus->color }}">{{ $order->orderStatus->name }}</p>
                        </td>
						<td>
								<a title="Show order"  class="btn btn-default" href="{{ route('admin.packer.order.show', $order->id) }}"><b>View/Edit</b></a></td>
                      </tr>
                      @endforeach
                      </tbody>
                    </table>
                  </div>
				  {{$orders->links()}}
                  <!-- /.table-responsive -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
<!--                  <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>-->
                  <!--a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a-->
                </div>
                <!-- /.box-footer -->
              </div>
              <!-- /.box -->
              
              
              
            </div>            
            
            
        </div>


    </section>
    <!-- /.content -->
@endsection
@section('js')
<!-- ChartJS -->
<script src="{{ asset('js/chart.js/Chart.js') }}"></script>
<script>
  jQuery(function ($) {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

   
    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieChart       = new Chart(pieChartCanvas)
    var PieData        = [
      {
        value    : 110,
        color    : '#3c8dbc',
        highlight: '#3c8dbc',
        label    : 'Pending'
      },
      {
        value    : 50,
        color    : '#f39c12',
        highlight: '#f39c12',
        label    : 'Processing'
      },
      {
        value    : 400,
        color    : '#00a65a',
        highlight: '#00a65a',
        label    : 'Delivered'
      },
      {
        value    : 10,
        color    : '#00c0ef',
        highlight: '#00c0ef',
        label    : 'Short'
      },
      {
        value    : 80,
        color    : '#f56954',
        highlight: '#f56954',
        label    : 'Canceled'
      },
    ]
    var pieOptions     = {
      
      //Boolean - Whether we should show a stroke on each segment
      segmentShowStroke    : true,
      //String - The colour of each segment stroke
      segmentStrokeColor   : '#fff',
      //Number - The width of each segment stroke
      segmentStrokeWidth   : 2,
      //Number - The percentage of the chart that we cut out of the middle
      percentageInnerCutout: 50, // This is 0 for Pie charts
      //Number - Amount of animation steps
      animationSteps       : 100,
      //String - Animation easing effect
      animationEasing      : 'easeOutBounce',
      //Boolean - Whether we animate the rotation of the Doughnut
      animateRotate        : true,
      //Boolean - Whether we animate scaling the Doughnut from the centre
      animateScale         : true,
      //Boolean - whether to make the chart responsive to window resizing
      responsive           : true,
      // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio  : true,      
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChart.Doughnut(PieData, pieOptions)

  })
</script>
@endsection