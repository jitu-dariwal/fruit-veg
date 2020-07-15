@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
      <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-top-links" href="{{ route('admin.categories.index') }}">
                <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-tasks"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Categories</span>
              <span class="info-box-number">{{$totalCategory}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
              </a>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-top-links" href="{{ route('admin.products.index') }}">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-tags"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Products</span>
              <span class="info-box-number">{{$totalProducts}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
        </a>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
         <a class="dashboard-top-links" href="{{ route('admin.products.index') }}?active_status=1">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-tag"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Active Products</span>
              <span class="info-box-number">{{$activeProducts}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
         </a>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
         <a class="dashboard-top-links" href="{{ route('admin.orders.index') }}">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-shopping-bag"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Orders</span>
              <span class="info-box-number">{{$totalOrders}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
         </a>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                
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
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Order Date</th>
                      </tr>
                      </thead>
                      <tbody>
					  @foreach($orders as $order)
                      <tr>
                        <td><a href="{{route('admin.orders.addproducts',$order->id)}}">{{$order->id}}</a></td>
                        <td>{{ucfirst($order->customer->first_name.' '.$order->customer->last_name)}}</td>
                        <td><span class="label " style="color: #ffffff; background-color: {{ $order->orderStatus->color }}">{{$order->orderStatus->name}}</span></td>
                        <td>
                          <div class="sparkbar" data-color="#00a65a" data-height="20">{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i a') }}</div>
                        </td>
                      </tr>
					  @endforeach
                      
                      </tbody>
                    </table>
                  </div>
                  <!-- /.table-responsive -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
<!--                  <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>-->
                  <a href="{{route('admin.orders.index')}}" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
                </div>
                <!-- /.box-footer -->
              </div>
              <!-- /.box -->
              
              <!-- USERS LIST -->
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Customers</h3>

                  <div class="box-tools pull-right">
					<span class="label label-danger">{{$totalCustomers}} Total Customers</span>
                    <span class="label label-danger">{{$totalCustomers}} Subscribed</span>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                  <ul class="users-list clearfix">
				  @foreach($customers as $customer)
                    <li>
                      <img src="img/user_icon_2.jpg" alt="User Image">
                      <a class="users-list-name" href="{{route('admin.customers.show',$customer->id)}}">{{ucfirst($customer->first_name.' '.$customer->last_name)}}</a>
                      <span class="users-list-date">{{$customer->created_at->diffForHumans()}}</span>
                    </li>
				  @endforeach
                    
                  </ul>
                  <!-- /.users-list -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer text-center">
                  <a href="{{route('admin.customers.index')}}" class="uppercase">View All Customers</a>
                </div>
                <!-- /.box-footer -->
              </div>
              <!--/.box -->
              
            </div>            
            
            <div class="col-sm-6 col-xs-12">
              
                <div class="box box-default">
                <div class="box-header with-border">
                  <h3 class="box-title">Order Status</h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-8">
                      <div class="chart-responsive">
                        <canvas id="pieChart" height="180"></canvas>
                      </div>
                      <!-- ./chart-responsive -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-4">
                      <ul class="chart-legend clearfix">
                        <li><i class="fa fa-circle text-light-blue"></i> Pending</li>
                        <li><i class="fa fa-circle text-yellow"></i> Processing</li>
                        <li><i class="fa fa-circle text-green"></i> Delivered </li>
                        <li><i class="fa fa-circle text-aqua"></i> Short</li>
                        <li><i class="fa fa-circle text-red"></i> Canceled</li>
                      </ul>
                    </div>
                    <!-- /.col -->
                  </div>
                  <!-- /.row -->
                </div>
                <!-- /.box-body -->
                
              </div>
              <!-- /.box -->
              <!-- PRODUCT LIST -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Reports</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box report-list">
                <li class="item">
				
                 <div class="report-info odd">
                    <a href="{{route('admin.reports.active-client-report')}}" class="report-title">Active Client's</a>
                  </div>
                </li>
				<li class="item">
                 <div class="report-info">
                    <a href="{{route('admin.reports.productspurchased')}}" class="report-title">Products Purchased</a>
                  </div>
                </li>
				<li class="item">
                 <div class="report-info odd">
                    <a href="{{route('admin.reports.customers-order-total')}}" class="report-title">Customer Orders-Total</a>
                  </div>
                </li>
				<li class="item">
                 <div class="report-info">
                    <a href="{{route('admin.reports.sales-report')}}" class="report-title">Sales Report</a>
                  </div>
                </li>
				<li class="item">
                 <div class="report-info odd">
                    <a href="{{route('admin.reports.customer-statics')}}" class="report-title">Daily Products Report</a>
                  </div>
                </li>
				<li class="item">
                 <div class="report-info">
                    <a href="{{route('admin.reports.monthly-sales-tax')}}" class="report-title">Monthly Sales/Tax</a>
                  </div>
                </li>
				
                <!-- /.item -->
              </ul>
            </div>
            <!-- /.box-body -->
          
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
        value    : {{$pendingOrders}},
        color    : '#3c8dbc',
        highlight: '#3c8dbc',
        label    : 'Pending'
      },
      {
        value    : {{$processingOrders}},
        color    : '#f39c12',
        highlight: '#f39c12',
        label    : 'Processing'
      },
      {
        value    : {{$deliveredOrders}},
        color    : '#00a65a',
        highlight: '#00a65a',
        label    : 'Delivered'
      },
      {
        value    : {{$shortOrders}},
        color    : '#00c0ef',
        highlight: '#00c0ef',
        label    : 'Short'
      },
      {
        value    : {{$canceledOrders}},
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