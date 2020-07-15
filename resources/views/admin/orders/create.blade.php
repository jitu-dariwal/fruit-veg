@extends('layouts.admin.app')
@section('css')
<style>
#loading {
	display: block;
    visibility: visible;
    position: absolute;
    z-index: 999;
    top: 0px;
    left: 0px;
    width: 105%;
    height: 105%;
    background-color:white;
    vertical-align:bottom;
    padding-top: 20%; 
    filter: alpha(opacity=75); 
    opacity: 0.75; 
    font-size:large;
    color:blue;
    font-style:italic;
    font-weight:400;
	background-image: url("{{asset('img/loading.gif')}}");
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: center;
	}

</style>
@endsection
@section('content')
<!-- Main content -->
<section class="content">
<div style="display:none;" id="loading">
</div>

    @include('layouts.errors-and-messages')
    <!-- Default box -->
    <div class="box">
        <form action="{{ route('admin.orders.store') }}" method="post">
            <div class="box-body">
                <h2>Create Order</h2>
                {{ csrf_field() }}

                <div class="row">
                    <div class="col-sm-12">
                        <h4>STEP 1 - Choose a customer & check their details</h4>
                    </div>
                </div>
                <div class="box-header">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="customer_key">Please select a customer:</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">   
                                <select class="form-control" id="customer_key" name="customer_id">
                                  <option></option>
								  
                                @foreach($customers as $customer)
                                  <option value="{{$customer->id}}">{{$customer->first_name .' '. $customer->last_name.', '.$customer->company_name.' #'.$customer->id}}</option>
                                @endforeach
                                </select>
                            </div>  
                            <div class="col-sm-4">
                                <button type="button" class="btn btn-warning btn-select-customer">Select</button>
                            </div>
                        </div>
                        <hr />
                        <div id="customer_detail_panel"></div>
                    </div>
                </div>
            </div>
    <!-- /.box-body -->
    
</form>
</div>
<!-- /.box -->
</section>
<!-- /.content -->
@endsection
@section('js')
<script type="text/javascript">
$(document).ready(function ($) {
  $('#customer_key').select2({
    //placeholder: "Select a customer"
  });
  $('#customer_key').on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Search by Customer ID / Customer name / Company name");
});
  $('#customer_key').on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
});
  /*
    $('#customer_key').select2({
        placeholder: "Select a customer",
        minimumInputLength: 1,
        ajax: {
            url: '/user/find',
            dataType: 'json',
            data: function (params) {
                return {
                    q: $.trim(params.term)
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        current: true
    });*/
    
    
    $('.btn-select-customer').click( function (){ 
      var customer_id = $('#customer_key').val();
	  $('#loading').show();
      $.ajax({
        url: '/admin/orders/customer/'+customer_id,
        type: 'GET',
        async: false,
        success: function (data) {
            $('#loading').hide();			
            $('#customer_detail_panel').html(data);
          }
      });
    });
   
});
</script>
@endsection
