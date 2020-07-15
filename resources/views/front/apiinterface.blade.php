<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Bootstrap Example</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
		
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="bg-light text-center">
			<h1>Fruit and Veg API</h1>
			<p>Here you can check all api and get its details with response!</p> 
		</div>
		<div class="container">
			<div class="row">
				<div class="col-12">
					<!-- Nav tabs -->
					<ul class="nav nav-tabs" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" data-toggle="tab" href="#home">Check API</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#menu1">API Details</a>
						</li>
					</ul>

					<!-- Tab panes -->
					<div class="tab-content">
						<div id="home" class="container tab-pane active"><br>
							<div class="row">
								<div class="col-sm-12 col-md-5 border">
									<form action="" method="post">
										<label style="font-weight: bold;font-size: larger;">Input Json Data</label><br/>
										<textarea id="json_request" name="json_request" rows="15" style="width:100%"></textarea><br/><br/>
									</form>
								</div>
								
								<div class="col-sm-12 col-md-2 text-center mt-5">
									<button type="button" id="checkAPI" class="btn btn-primary">Check Api</button>
								</div>
								
								<div class="col-sm-12 col-md-5 border">
									<label style="font-weight: bold;font-size: larger;">Response Json Data</label><br/>
									<pre id="json_response" class="bg-info p-3" style= "font-size: 20px; font-weight: bold;width:100%;max-height:400px;display:none;">
									</pre>
								</div>
							</div>
						</div>
						<div id="menu1" class="container tab-pane fade"><br>
							<div class="accordion md-accordion" id="accordionEx" role="tablist" aria-multiselectable="true">

								<!-- userLogin  -->
								<div class="card">
									<div class="card-header" role="tab" id="heading1">
										<a data-toggle="collapse" data-parent="#accordionEx" href="#collapse1" aria-expanded="true"
										aria-controls="collapse1">
											<h5 class="mb-0">
												#1 userLogin <i class="fa fa-angle-down rotate-icon"></i>
											</h5>
										</a>
									</div>
									
									<div id="collapse1" class="collapse" role="tabpanel" aria-labelledby="heading1" data-parent="#accordionEx">
										<div class="card-body">
											<div class="row">
												<div class="col-5">
													<b>Request Data</b>
													<pre class="bg-warning p-3">
{
	"method_name": "userLogin",
	"data": {
		"email_id": "rakesh.gupta@dotsquares.com",
		"password": "123456"
	}
}
													</pre>
												</div>
												
												<div class="col-2"></div>
												
												<div class="col-5">
													<b>Error Response Data</b>
													<pre class="bg-danger p-3">
{
	"data": {
		"message": "Invalid user name or password"
	},
	"method_name": "userLogin",
	"status": "error"
}
													</pre>
													
													<b>Response Data</b>
													<pre class="bg-success p-3">
{
	"data": {
		"app_user_id": 1,
		"first_name": "rakesh",
		"last_name": "gupta",
		"email_address": "rakesh.gupta@dotsquares.com",
		"create_account_date": "2014-11-12 00:00:00"
	},
	"method_name": "userLogin",
	"status": "success"
}
													</pre>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- End userLogin -->
								
								<!-- paswordRecovery  -->
								<div class="card">
									<div class="card-header" role="tab" id="heading2">
										<a data-toggle="collapse" data-parent="#accordionEx" href="#collapse2" aria-expanded="true"
										aria-controls="collapse2">
											<h5 class="mb-0">
												#2 paswordRecovery <i class="fa fa-angle-down rotate-icon"></i>
											</h5>
										</a>
									</div>
									
									<div id="collapse2" class="collapse" role="tabpanel" aria-labelledby="heading2" data-parent="#accordionEx">
										<div class="card-body">
											<div class="row">
												<div class="col-5">
													<b>Request Data</b>
													<pre class="bg-warning p-3">
{
	"method_name": "paswordRecovery",
	"data": {
		"email_id": "jitendratest@mailinator.com"
	}
}
													</pre>
												</div>
												
												<div class="col-2"></div>
												
												<div class="col-5">
													<b>Error Response Data</b>
													<pre class="bg-danger p-3">
{
	"method_name": "paswordRecovery",
	"status": "error",
	"data": {
		"message": "Email : The email id must be a valid email address."
	}
}
													</pre>
													
													<b>Response Data</b>
													<pre class="bg-success p-3">
{
	"data": {
		"message": "Your password has been sent on your email address.Please check email."
	},
	"method_name": "paswordRecovery",
	"status": "success"
}
													</pre>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- End paswordRecovery -->
								
								<!-- getCategorieslist  -->
								<div class="card">
									<div class="card-header" role="tab" id="heading3">
										<a data-toggle="collapse" data-parent="#accordionEx" href="#collapse3" aria-expanded="true"
										aria-controls="collapse3">
											<h5 class="mb-0">
												#3 getCategorieslist <i class="fa fa-angle-down rotate-icon"></i>
											</h5>
										</a>
									</div>
									
									<div id="collapse3" class="collapse" role="tabpanel" aria-labelledby="heading3" data-parent="#accordionEx">
										<div class="card-body">
											<div class="row">
												<div class="col-5">
													<b>Request Data</b>
													<pre class="bg-warning p-3">
{
	"method_name": "getCategorieslist"
}
													</pre>
												</div>
												
												<div class="col-2"></div>
												
												<div class="col-5">
													<b>Error Response Data</b>
													<pre class="bg-danger p-3">
{
    "data": {
        "message": "categories does not exist."
    },
    "method_name": "getCategorieslist",
    "status": "error"
}
													</pre>
													
													<b>Response Data</b>
													<pre class="bg-success p-3">
{
    "data": [
        {
            "categories_id": 1,
            "categories_name": "VEGETABLES",
            "parent_id": 0,
            "sort_order": "",
            "date_added": "2010-02-11 12:01:19",
            "last_modified": "2010-02-11 12:01:19"
        },
        {
            "categories_id": 6,
            "categories_name": "FRUIT",
            "parent_id": 0,
            "sort_order": "",
            "date_added": "2010-02-11 12:01:19",
            "last_modified": "2010-02-11 12:01:19"
        },
	..........
	..........
	..........
    ],
    "method_name": "getCategorieslist",
    "status": "success"
}
													</pre>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- End getCategorieslist -->
								
								<!-- getSubCategorieslist  -->
								<div class="card">
									<div class="card-header" role="tab" id="heading4">
										<a data-toggle="collapse" data-parent="#accordionEx" href="#collapse4" aria-expanded="true"
										aria-controls="collapse4">
											<h5 class="mb-0">
												#4 getSubCategorieslist <i class="fa fa-angle-down rotate-icon"></i>
											</h5>
										</a>
									</div>
									
									<div id="collapse4" class="collapse" role="tabpanel" aria-labelledby="heading3" data-parent="#accordionEx">
										<div class="card-body">
											<div class="row">
												<div class="col-5">
													<b>Request Data</b>
													<pre class="bg-warning p-3">
{
	"method_name": "getSubCategorieslist"
}
													</pre>
												</div>
												
												<div class="col-2"></div>
												
												<div class="col-5">
													<b>Error Response Data</b>
													<pre class="bg-danger p-3">
{
    "data": {
        "message": "Sub category does not exist."
    },
    "method_name": "getSubCategorieslist",
    "status": "error"
}
													</pre>
													
													<b>Response Data</b>
													<pre class="bg-success p-3">
{
    "data": [
        {
            "categories_id": 2,
            "categories_name": "Main Line",
            "parent_id": 1,
            "sort_order": "",
            "date_added": "2010-02-11 12:01:19",
            "last_modified": "2010-02-11 12:01:19"
        },
        {
            "categories_id": 3,
            "categories_name": "Prepared",
            "parent_id": 1,
            "sort_order": "",
            "date_added": "2010-02-11 12:01:19",
            "last_modified": "2010-02-11 12:01:19"
        },
	..........
	..........
	..........
    ],
    "method_name": "getSubCategorieslist",
    "status": "success"
}
													</pre>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- End getSubCategorieslist -->
								
								<!-- getProductsList  -->
								<div class="card">
									<div class="card-header" role="tab" id="heading5">
										<a data-toggle="collapse" data-parent="#accordionEx" href="#collapse5" aria-expanded="true"
										aria-controls="collapse5">
											<h5 class="mb-0">
												#5 getProductsList <i class="fa fa-angle-down rotate-icon"></i>
											</h5>
										</a>
									</div>
									
									<div id="collapse5" class="collapse" role="tabpanel" aria-labelledby="heading5" data-parent="#accordionEx">
										<div class="card-body">
											<div class="row">
												<div class="col-5">
													<b>Request Data</b>
													<pre class="bg-warning p-3">
{
	"method_name": "getProductsList"
}
													</pre>
												</div>
												
												<div class="col-2"></div>
												
												<div class="col-5">
													<b>Error Response Data</b>
													<pre class="bg-danger p-3">
{
    "data": {
        "message": "Products does not exist."
    },
    "method_name": "getProductsList",
    "status": "error"
}
													</pre>
													
													<b>Response Data</b>
													<pre class="bg-success p-3">
{
    "data": [
        {
            "products_id": 1,
            "categories_id": 2,
            "products_name": "ASPARAGUS MEDIUM",
            "products_status": 1,
            "products_price": "33.20",
            "products_date_added": "2010-02-15 16:57:53",
            "products_last_modified": "2020-02-27 06:44:15",
            "product_code": "ASMB",
            "packet_size": "BOX 11  bunches",
            "type": "Bulk",
            "bulk_quantity": "11.000",
            "bulk_price": "ASPARAGUS MEDIUM",
            "split_price": "33.20",
            "parent_id": 0,
            "vat_include": "ASPARAGUS MEDIUM",
            "packet_brand": "VEGETABLES"
        },
        {
            "products_id": 2,
            "categories_id": 2,
            "products_name": "ASPARAGUS MEDIUM",
            "products_status": 1,
            "products_price": "3.02",
            "products_date_added": "2010-02-15 16:57:53",
            "products_last_modified": "2017-06-16 01:26:03",
            "product_code": "ASME",
            "packet_size": "bunch",
            "type": "Split",
            "bulk_quantity": "1.000",
            "bulk_price": "ASPARAGUS MEDIUM",
            "split_price": "3.02",
            "parent_id": 1,
            "vat_include": "ASPARAGUS MEDIUM",
            "packet_brand": "VEGETABLES"
        },
	..........
	..........
	..........
    ],
    "method_name": "getProductsList",
    "status": "success"
}
													</pre>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- End getProductsList -->
								
								<!-- getSupplayersList  -->
								<div class="card">
									<div class="card-header" role="tab" id="heading6">
										<a data-toggle="collapse" data-parent="#accordionEx" href="#collapse6" aria-expanded="true"
										aria-controls="collapse6">
											<h5 class="mb-0">
												#6 getSupplayersList <i class="fa fa-angle-down rotate-icon"></i>
											</h5>
										</a>
									</div>
									
									<div id="collapse6" class="collapse" role="tabpanel" aria-labelledby="heading6" data-parent="#accordionEx">
										<div class="card-body">
											<div class="row">
												<div class="col-5">
													<b>Request Data</b>
													<pre class="bg-warning p-3">
{
	"method_name": "getSupplayersList"
}
													</pre>
												</div>
												
												<div class="col-2"></div>
												
												<div class="col-5">
													<b>Error Response Data</b>
													<pre class="bg-danger p-3">
{
    "data": {
        "message": "Manufacturers does not exist."
    },
    "method_name": "getSupplayersList",
    "status": "error"
}
													</pre>
													
													<b>Response Data</b>
													<pre class="bg-success p-3">
{
    "data": [
        {
            "manufacturers_id": 1,
            "manufacturers_name": "A E Booth",
            "date_added": "2015-04-30 18:52:19",
            "last_modified": "2018-09-12 10:43:26"
        },
        {
            "manufacturers_id": 2,
            "manufacturers_name": "Apollo Nuts",
            "date_added": "2015-04-30 18:52:19",
            "last_modified": null
        },
	..........
	..........
	..........
    ],
    "method_name": "getSupplayersList",
    "status": "success"
}
													</pre>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- End getSupplayersList -->
								
								<!-- getCountryList  -->
								<div class="card">
									<div class="card-header" role="tab" id="heading7">
										<a data-toggle="collapse" data-parent="#accordionEx" href="#collapse7" aria-expanded="true"
										aria-controls="collapse7">
											<h5 class="mb-0">
												#7 getCountryList <i class="fa fa-angle-down rotate-icon"></i>
											</h5>
										</a>
									</div>
									
									<div id="collapse7" class="collapse" role="tabpanel" aria-labelledby="heading7" data-parent="#accordionEx">
										<div class="card-body">
											<div class="row">
												<div class="col-5">
													<b>Request Data</b>
													<pre class="bg-warning p-3">
{
	"method_name": "getCountryList"
}
													</pre>
												</div>
												
												<div class="col-2"></div>
												
												<div class="col-5">
													<b>Error Response Data</b>
													<pre class="bg-danger p-3">
{
    "data": {
        "message": "Country does not exist."
    },
    "method_name": "getCountryList",
    "status": "error"
}
													</pre>
													
													<b>Response Data</b>
													<pre class="bg-success p-3">
{
    "data": [
        {
            "countries_id": 1,
            "countries_name": "AFGHANISTAN"
        },
        {
            "countries_id": 2,
            "countries_name": "ALBANIA"
        },
	..........
	..........
	..........
    ],
    "method_name": "getCountryList",
    "status": "success"
}
													</pre>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- End getCountryList -->
								
								<!-- getPurchasedProductsLists  -->
								<div class="card">
									<div class="card-header" role="tab" id="heading8">
										<a data-toggle="collapse" data-parent="#accordionEx" href="#collapse8" aria-expanded="true"
										aria-controls="collapse8">
											<h5 class="mb-0">
												#8 getPurchasedProductsLists <i class="fa fa-angle-down rotate-icon"></i>
											</h5>
										</a>
									</div>
									
									<div id="collapse8" class="collapse" role="tabpanel" aria-labelledby="heading8" data-parent="#accordionEx">
										<div class="card-body">
											<div class="row">
												<div class="col-5">
													<b>Request Data</b>
													<pre class="bg-warning p-3">
{
  "method_name": "getPurchasedProductsLists",
  "userID": 5,
  "cartID": 123,
  "payment_mode": "Credit",
  "orders_total": 48.6000,
  "data": [
    {
      "IsMakeProductOutOfStock": 0,
      "product_id": 1485,
      "product_name": "ARTICHOKES GLOBE LARGE",
      "product_code": "ARLB",
      "category_id": 2,
      "number_purchase": 1,
      "product_price": 16.20,
      "split_price": 16.20,
      "bulk_quantity": 12.000,
      "vat_include": "no",
      "packet_brand": "queen",
      "packet_size": "BOX 13",
      "product_status": 1,
      "split_product_id": 0,
      "split_product_status": 0,
      "split_quantity": 1,
      "split_packet_size": "EACH",
      "supplier": "Lloyds Commerical Finance",
      "new_supplier": null,
      "total_basket_price": 13.150,
      "type": "Bulk",
      "product_date_added": "2020-03-02 11:07:53",
      "product_modified_date": "2020-03-02 11:07:57",
      "bulk_price": 0,
      "parent_id": 0,
      "origin": "Fiji"
    },
	{
      "IsMakeProductOutOfStock": 0,
      "product_id": 1487,
      "product_name": "ASPARAGUS JUMBO",
      "product_code": "ASJB",
      "category_id": 2,
      "number_purchase": 1,
      "product_price": 35.40,
      "split_price": 35.40,
      "bulk_quantity": 11.000,
      "vat_include": "no",
      "packet_brand": "USA",
      "packet_size": "BOX X 11 BUNCHES",
      "product_status": 1,
      "split_product_id": 0,
      "split_product_status": 0,
      "split_quantity": 1,
      "split_packet_size": "BUNCH 500g",
      "supplier": "ALD Automotive",
      "new_supplier": null,
      "total_basket_price": 35.450,
      "type": "Bulk",
      "product_date_added": "2020-03-02 11:07:53",
      "product_modified_date": "2020-03-02 11:07:57",
      "bulk_price": 0,
      "parent_id": 0,
      "origin": "Angola"
    }
  ]
}
													</pre>
												</div>
												
												<div class="col-2"></div>
												
												<div class="col-5">
													<b>Error Response Data</b>
													<pre class="bg-danger p-3">
{
    "data": {
        "message": "Please give at least one product details."
    },
    "method_name": "getPurchasedProductsLists",
    "status": "error"
}
													</pre>
													
													<b>Response Data</b>
													<pre class="bg-success p-3">
{
    "data": [
        {
            "products_id": 1485,
            "categories_id": 2,
            "products_name": "ARTICHOKES GLOBE LARGE",
            "products_status": 1,
            "products_price": "16.20",
            "bulk_quantity": "12.000",
            "packet_size": "BOX 13",
            "vat_include": "",
            "packet_brand": "queen",
            "parent_id": 0
        },
        {
            "products_id": 1487,
            "categories_id": 2,
            "products_name": "ASPARAGUS JUMBO",
            "products_status": 1,
            "products_price": "35.40",
            "bulk_quantity": "11.000",
            "packet_size": "BOX X 11 BUNCHES",
            "vat_include": "",
            "packet_brand": "USA",
            "parent_id": 0
        }
    ],
    "method_name": "getPurchasedProductsLists",
    "status": "success",
    "order_id": 16752
}
													</pre>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- End getPurchasedProductsLists -->
								
								<!-- makeprodcutoutofstock  -->
								<div class="card">
									<div class="card-header" role="tab" id="heading10">
										<a data-toggle="collapse" data-parent="#accordionEx" href="#collapse10" aria-expanded="true"
										aria-controls="collapse10">
											<h5 class="mb-0">
												#10 makeprodcutoutofstock <i class="fa fa-angle-down rotate-icon"></i>
											</h5>
										</a>
									</div>
									
									<div id="collapse10" class="collapse" role="tabpanel" aria-labelledby="heading10" data-parent="#accordionEx">
										<div class="card-body">
											<div class="row">
												<div class="col-5">
													<b>Request Data</b>
													<pre class="bg-warning p-3">
{
	"method_name": "makeprodcutoutofstock",
	"data" : {
		"productID" : 1485,
		"split_product_id" : 0,
		"product_status" : 1,
		"split_product_status" : null
	}
}
													</pre>
												</div>
												
												<div class="col-2"></div>
												
												<div class="col-5">
													<b>Error Response Data</b>
													<pre class="bg-danger p-3">
{
    "method_name": "makeprodcutoutofstock",
    "status": "error",
    "data": {
        "message": "Please give product id."
    }
}
													</pre>
													
													<b>Response Data</b>
													<pre class="bg-success p-3">
{
    "data": [
        {
            "products_id": 1485,
            "categories_id": 2,
            "products_name": "ARTICHOKES GLOBE LARGE",
            "products_status": 1,
            "products_price": "16.20",
            "bulk_quantity": "12.000",
            "packet_size": "BOX 13",
            "vat_include": "",
            "packet_brand": "queen",
            "parent_id": 0
        }
    ],
    "method_name": "makeprodcutoutofstock",
    "status": "success"
}
													</pre>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- End makeprodcutoutofstock -->
								
								<!-- addNewProduct  -->
								<div class="card">
									<div class="card-header" role="tab" id="heading11">
										<a data-toggle="collapse" data-parent="#accordionEx" href="#collapse11" aria-expanded="true"
										aria-controls="collapse11">
											<h5 class="mb-0">
												#11 addNewProduct <i class="fa fa-angle-down rotate-icon"></i>
											</h5>
										</a>
									</div>
									
									<div id="collapse11" class="collapse" role="tabpanel" aria-labelledby="heading11" data-parent="#accordionEx">
										<div class="card-body">
											<div class="row">
												<div class="col-5">
													<b>Request Data</b>
													<pre class="bg-warning p-3">
{
	"method_name": "addNewProduct",
	"userID": 5,
	"data": [{
			"product_name": "Test Product 1",
			"bulk_product_code": "TEST1",
			"subcategory_id": 2,
			"bulk_price": 16.20,
			"split_price": 16.20,
			"bulk_quantity": 12.000,
			"vat_include": "no",
			"bulk_packet_size": "BOX 13",
			"bulk_product_status": 1,
			"split_product_status": 0,
			"split_quantity": 1,
			"split_product_code": "TEST1_SPLIT",
			"split_packet_size": "SINGLE"
		},
		{
			"product_name": "Test Product 2",
			"bulk_product_code": "TEST2",
			"subcategory_id": 2,
			"bulk_price": 16.20,
			"split_price": 16.20,
			"bulk_quantity": 12.000,
			"vat_include": "no",
			"bulk_packet_size": "BOX 14",
			"bulk_product_status": 1,
			"split_product_status": 0,
			"split_quantity": 1,
			"split_product_code": "TEST2_SPLIT",
			"split_packet_size": "SINGLE"
		}
	]
}
													</pre>
												</div>
												
												<div class="col-2"></div>
												
												<div class="col-5">
													<b>Error Response Data</b>
													<pre class="bg-danger p-3">
{
    "data": {
        "message": "Please give products details for add."
    },
    "method_name": "addNewProduct",
    "status": "error"
}
													</pre>
													
													<b>Response Data</b>
													<pre class="bg-success p-3">
{
    "data": [
        {
            "products_id": 2359,
            "categories_id": 2,
            "products_name": "Test Product 1",
            "products_status": 1,
            "products_price": "16.20",
            "products_date_added": "2020-03-03 11:33:27",
            "products_last_modified": "2020-03-03 11:33:27",
            "product_code": "TEST1",
            "packet_size": "BOX 13",
            "type": "Bulk",
            "bulk_quantity": "12.000",
            "bulk_price": "",
            "split_price": "0.00",
            "parent_id": 0,
            "vat_include": "",
            "packet_brand": null
        },
        {
            "products_id": 2360,
            "categories_id": 2,
            "products_name": "Test Product 1",
            "products_status": 0,
            "products_price": "16.20",
            "products_date_added": "2020-03-03 11:33:27",
            "products_last_modified": "2020-03-03 11:33:27",
            "product_code": "TEST1_SPLIT",
            "packet_size": "SINGLE",
            "type": "Split",
            "bulk_quantity": "1.000",
            "bulk_price": "",
            "split_price": "0.00",
            "parent_id": 2359,
            "vat_include": "",
            "packet_brand": null
        },
        {
            "products_id": 2361,
            "categories_id": 2,
            "products_name": "Test Product 2",
            "products_status": 1,
            "products_price": "16.20",
            "products_date_added": "2020-03-03 11:33:27",
            "products_last_modified": "2020-03-03 11:33:27",
            "product_code": "TEST2",
            "packet_size": "BOX 14",
            "type": "Bulk",
            "bulk_quantity": "12.000",
            "bulk_price": "",
            "split_price": "0.00",
            "parent_id": 0,
            "vat_include": "",
            "packet_brand": null
        },
		......
		......
		......
		......
    ],
    "method_name": "addNewProduct",
    "status": "success"
}
													</pre>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- End addNewProduct -->
								
								<!-- update_ordered_products  -->
								<div class="card">
									<div class="card-header" role="tab" id="heading12">
										<a data-toggle="collapse" data-parent="#accordionEx" href="#collapse12" aria-expanded="true"
										aria-controls="collapse12">
											<h5 class="mb-0">
												#12 update_ordered_products <i class="fa fa-angle-down rotate-icon"></i>
											</h5>
										</a>
									</div>
									
									<div id="collapse12" class="collapse" role="tabpanel" aria-labelledby="heading12" data-parent="#accordionEx">
										<div class="card-body">
											<div class="row">
												<div class="col-5">
													<b>Request Data</b>
													<pre class="bg-warning p-3">
{
	"method_name": "update_ordered_products",
	"userID": 5,
	"ordersID": 16752,
	"data": {
		"product_id" : 1485,
		"products_Quantity" : 1,
		"products_price" : 16.2000
	}
}
													</pre>
												</div>
												
												<div class="col-2"></div>
												
												<div class="col-5">
													<b>Error Response Data</b>
													<pre class="bg-danger p-3">
{
    "message": "Error updating record:",
    "method_name": "update_ordered_products",
    "status": "error"
}
													</pre>
													
													<b>Response Data</b>
													<pre class="bg-success p-3">
{
    "message": "Record updated successfully",
    "method_name": "update_ordered_products",
    "status": "success"
}
													</pre>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- End addNewProduct -->
								
							</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<script>
			$(document).ready(function(){
				$('#checkAPI').click(function(){
					var req = $('#json_request').val();
					
					if(req != ''){
						if (/^[\],:{}\s]*$/.test(req.replace(/\\["\\\/bfnrtu]/g, '@').
replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
							req = JSON.parse(req);
							
							$.ajax({
								url  : "{{ url('api/v1/ipadwebservices') }}",
								type : 'post',
								beforeSend: function(){
									$("#checkAPI").html('<span class="spinner-border spinner-border-sm"></span> Loading..').attr('disabled', true);
									$('#json_response').html('').slideUp();
								},
								complete: function(){
									$("#checkAPI").html('Check Api').attr('disabled', false);
								},
								data : {'jsonRequest' : JSON.stringify(req)},
								dataType : 'json',
								success : function(data){
									$('#json_response').html(JSON.stringify(data, undefined, 4)).slideDown();
								}
							});
						}
						else{
							alert("Please enter correct json array.")
						}
					}else{
						alert("Please enter request data for api response.")
					}
				})
			});
		</script>
	</body>
</html>