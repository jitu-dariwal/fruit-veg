$(document).ready(function () { 
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	$( '.dropdown-menu .dropdown-toggle' ).on('click', function() {
		
		var $el = $(this);
		var $parent = $el.offsetParent(".dropdown-menu");
		
		if (!$el.next().hasClass("show")) {
			$el.parents('.dropdown-menu').first().find(".show").removeClass("show");
		}
		$el.next(".dropdown-menu").toggleClass("show").parent("li").toggleClass("show");
		
		$el.parents("li.nav-item.dropdown.show").on("hidden.bs.dropdown", function () {
			$(".dropdown-menu .show").removeClass("show");
		});
		
		if (!$parent.parent().hasClass("navbar-nav")) {
			$el.next().css({"top":$el[0].offsetTop,"left":$parent.outerWidth()});
		}
		
		return false;
	});
	
	$(window).on('resize', function(event){
	   $.category_toggle();
	});
	
	($.category_toggle = function(){
		var w = $(window).width();
		setTimeout(function(){
			if(w <= 769){
				$('.custom-accordion').removeClass('show-max');
			}
			else{
				$('.custom-accordion').addClass('show-max');
			}
		}, 100)
	})();
	
	$(document).on('click','openMenu',function(){
		alert(213)
		$(this).closest("li.dropdown").find('div.dropdown-menu2').toggle();
	});
	
	/*** Include all compiled plugins (below), or include individual files as needed ***/
	if(navigator.userAgent.indexOf('Mac') > 0)
		$('body').addClass('mac-os');    
	
	
	if(parseInt($("#total_basket_price").val()) < 1) {
		$("#continue_btn_block").hide();
		$("#sorted_shopping_list").hide();
	}
	
	$.validate({ 
		modules : 'security, logic',
		inlineErrorMessageCallback: function($input, errorMessage, config) {
			if (errorMessage) {
				if($input.closest('div').hasClass('input-group')){
					$input.closest('div').nextAll('span').remove();
					
					$('<span class="help-block text-danger">'+errorMessage+'</span>').insertAfter($input.closest('div'));
				}else if($input.closest('div').hasClass('custom-checkbox') || $input.closest('div').hasClass('custom-radio')){
					$input.closest('div').nextAll('span').remove();
					
					$('<span class="help-block text-danger">'+errorMessage+'</span>').insertAfter($input.closest('div'));
				}else{
					$('<span class="help-block text-danger">'+errorMessage+'</span>').insertAfter($input);
				}
			}else {
				if(!$input.hasClass('search-query')){
					if(!$input.closest('div').hasClass('custom-checkbox') || !$input.closest('div').hasClass('custom-radio')){
						$input.nextAll('span').remove();
					}else{
						$input.closest('div').nextAll('span').remove();
					}
				}else{
					$input.closest('div').nextAll('span').remove();
				}
			}
			return false; // prevent default behaviour
		},
		submitErrorMessageCallback : function($form, errorMessages, config) {
			/* if (errorMessages) {
				customDisplayErrorMessagesInTopOfForm($form, errorMessages);
			} else {
				customRemoveErrorMessagesInTopOfForm($form);
			}
			return false; // prevent default behaviour */
		}
	});
	
	$(document).on('click','.addFavorite',function(){
		var id = $(this).attr('data-id');
		var action = $(this).attr('data-action');
		var _this = $(this);
		
		if(action == 'add' || (action != 'add' && confirm('Are you sure to remove it from favourite list ?'))){
			var i = 0;
			$.ajax({
				url : '/ajax/add/favourite',
				type : 'post',
				data : {'id' : id, 'action' : action},
				dataType : 'json',
				beforeSend: function() {
					$("#cover-spin").show(0);
					i++;
				},
				success : function(data){
					$("#cover-spin").hide(0);
					if(data.status){
						if(_this.hasClass('listFav')){
							_this.closest('li').remove();
						}else{
							if(action == 'add')
								_this.attr('data-action','remove').find('span').removeClass('ds-star-outline').addClass('ds-star');
							else
								_this.attr('data-action','add').find('span').removeClass('ds-star').addClass('ds-star-outline');
						}
						
						setModalPopup(data.msg);
					}else{
						setModalPopup(data.msg);
					}
				},
				error: function(xhr) { // if error occured
					$("#cover-spin").hide(0);
				},
				complete: function() {
					i--;
					if (i <= 0) {
						$("#cover-spin").hide(0);
					}
				}
			});
		}
	});
	
	$(document).on('click','.removeCartProduct',function(){
		var data_from = $(this).attr('data-from');
		var url = $(this).attr('data-url');
		var _this = $(this);
						
		if(confirm('You are about to delete this product?')){
			var i = 0;
			$.ajax({
				url : url,
				type : 'delete',
				dataType : 'json',
				beforeSend: function() {
					$("#cover-spin").show(0);
					i++;
				},
				success : function(data){
					$("#cover-spin").hide(0);
					if(data.status){
						if(data_from == 'rightCart'){
							$('.your-order').replaceWith(data.view)
							
							if(parseInt($("#total_basket_price").val()) < 1) {
								$("#continue_btn_block").hide();
								$("#sorted_shopping_list").hide();
							}
							
							$("#total_cart_val").html($("#total_basket_qty").val());
						}else{
							window.location.reload();
						}
					}else{
						setModalPopup(data.msg);
					}
				},
				error: function(xhr) { // if error occured
					$("#cover-spin").hide(0);
				},
				complete: function() {
					i--;
					if (i <= 0) {
						$("#cover-spin").hide(0);
					}
				}
			});
		}
	});
	
    $("#brand-logo").owlCarousel({
        autoPlay: 3000, //Set AutoPlay to 3 seconds
        items: 6,
        itemsDesktop: [1199, 6],
        itemsDesktopSmall: [979, 6]
    });

    //$('.select2').select2();

    if ($('#thumbnails li img').length > 0) {
        $('#thumbnails li img').on('click', function () {
            $('#main-image')
                .attr('src', $(this).attr('src') +'?w=400')
                .attr('data-zoom', $(this).attr('src') +'?w=1200');
        });
    }
    
    
    //list subcategories by parent cat    
	$('#categoriesbox').on("change", function() {
	   
	/*  js for left categories block on the category page */	
		$(window).on('resize', function(event){
               $.category_toggle();
           });
		   
		   ($.category_toggle = function(){
               var w = $(window).width();
               setTimeout(function(){
                   if(w <= 769){
                       $('.custom-accordion').removeClass('show-max');
                   }
                   else{
                       $('.custom-accordion').addClass('show-max');
                   }
               }, 100)
           })();
	/* end */	

		var parent_cat = $(this).val();
		$.ajax({
			url: 'subcategories/'+parent_cat,
			type: 'GET',
			success: function(response)
			{
				$('#subcategoriesbox').html(response);
			}
		});

		
	});
	
	var datesForDisable = $("input#all_holidays").val();
	var todayDate = new Date().getDate();
	var delivery_date_total_days = $("input#delivery_date_total_days").val();
       
});

// Popup window function
function basicPopup(url) {
	popupWindow = window.open(url,'popUpWindow','height=500,width=500,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
}

$('.owl-carousel').owlCarousel({
    loop:true,
    margin:15,
    nav:true,
    responsive:{
        0:{
            items:1
        },
        375:{
            items:2
        },
        768:{
            items:3
        },
        1000:{
            items:5
        }
    }
});

$('body').delegate( ".update_prd_bluk", "blur", function() {
  var fieldid = $(this).attr('id');
  var customerid = $(this).attr('data-cust-id');
  var productid1 = $(this).attr('data-prd-id');
  var productid = productid1.split('_')[2];
  
  var catid = $(this).attr('data-cat-id');
  var price = $(this).attr('data-price');
  var prdtype = $(this).attr('data-cust-id');

  var curr_val = $("input#"+fieldid).val();
    var nextval = parseInt(curr_val);
    $("input#"+fieldid).val(nextval);
    
    var qty = $("input#"+fieldid).val();
    
    var currprd = "plusqty_"+productid;
    
	//var default_minimum_order = 0;
    
    if($("#default_minimum_order").val()) {
        var default_minimum_order = $("#default_minimum_order").val();
    }
	
	
	var prd_attr = $("input#"+fieldid).attr('data-prd-id');

	// For some browsers, `attr` is undefined; for others,
	// `attr` is false.  Check for both.
	if (typeof prd_attr !== typeof undefined && prd_attr !== false) {
		$("input#"+prd_attr).val(qty);
	}
        
	//alert(default_minimum_order); return false;
    
    $("."+currprd).addClass('disabled');
    
    var shoppinglisturl = $("#shoppinglisturl").val();
   
    purl = shoppinglisturl+'?catid='+catid+'&pid='+productid+'&pvalue='+qty+'&uid='+customerid+'&price='+price+'&prdtype='+prdtype;
	$.ajax({ url: purl, success: function(data){
		 
		$("#no_items_cart").hide();
		
		$("#sorted_shopping_list").html(data);
		$("#sorted_shopping_list").show();
		$("#continue_btn_block").show();
		
		$("#total_cart_val").html($("#total_basket_qty").val());
		
		if(parseFloat($("#total_products_price").val()) < default_minimum_order) {
                     
						$("#close_note").show();
						$("#link_disabled").addClass("disabled");
                     
                      } else {
                       
						 $("#close_note").hide();
						 $("#link_disabled").removeClass("disabled");
						}
		
		
		 
		$("."+currprd).removeClass('disabled');
		  return false;
						   
	}});
	
});

$('body').delegate( ".update_cart_prd_bluk", "blur", function() {
  var fieldid = $(this).attr('id');
  var customerid = $(this).attr('data-cust-id');
  var productid1 = $(this).attr('data-prd-id');
  var productid = productid1.split('_')[2];
  
  var catid = $(this).attr('data-cat-id');
  var price = $(this).attr('data-price');
  var prdtype = $(this).attr('data-cust-id');

  var curr_val = $("input#"+fieldid).val();
    var nextval = parseInt(curr_val);
    $("input#"+fieldid).val(nextval);
    
    var qty = $("input#"+fieldid).val();
    
    var currprd = "plusqty_"+productid;
    
	//var default_minimum_order = 0;
    
    if($("#default_minimum_order").val()) {
        var default_minimum_order = $("#default_minimum_order").val();
    }
	
	
	var prd_attr = $("input#"+fieldid).attr('data-prd-id');

	// For some browsers, `attr` is undefined; for others,
	// `attr` is false.  Check for both.
	if (typeof prd_attr !== typeof undefined && prd_attr !== false) {
		$("input#"+prd_attr).val(qty);
	}
        
	//alert(default_minimum_order); return false;
    
    $("."+currprd).addClass('disabled');
    
    var shoppinglisturl = $("#shoppinglisturl").val();
   
    purl = shoppinglisturl+'?catid='+catid+'&pid='+productid+'&pvalue='+qty+'&uid='+customerid+'&price='+price+'&prdtype='+prdtype;
	$.ajax({ url: purl, success: function(data){
		 
		$("#sorted_shopping_list").html(data);
		$("#total_cart_val").html($("#total_basket_qty").val());
		
		return false;
						   
	}});
	
});

$('.completePayment').click(function(){
	var id = $(this).data('id');
	var url = $(this).data('url');
	var coupon = $(this).data('coupon');
	var discount = $(this).data('discount');
	
	if(coupon != '' && discount != null){
		var i = 0;
		
		$.ajax({
			type: 'POST',
			url: '/ajax/check/order-coupon-status',
			dataType : 'json',
			data: {'type' : 'checkOrderCouponCode', 'order_id' : id},
			beforeSend: function() {
				$("#cover-spin").show(0);
				i++;
			},
			success: function(data) {
				//console.log("data");
				
				if(data.status == true){
					window.location.href = url;
				}else{
					$("#modalPopup").find('.modal-header').find('h4').html('Confirm Order');
					$("#modalPopup").find('.modal-body').html('<p>'+data.msg+'</p>');
					$("#modalPopup").find('.modal-footer').html('<button class="btn btn-primary" id="orderContinueBtn" data-url="'+url+'" data-id="'+id+'"> Continue </button>').removeClass('hide');
					$("#modalPopup").modal('show')
				}
			},
			error: function(xhr) { // if error occured
				//alert(xhr.statusText +" and "+ xhr.responseText);
				$("#cover-spin").hide(0);
			},
			complete: function() {
				i--;
				if (i <= 0) {
					$("#cover-spin").hide(0);
				}
			},
		});
	}else{
		window.location.href = url;
	}
});

$(document).on('click','#orderContinueBtn',function(){
	var id = $(this).data('id');
	var url = $(this).data('url');
	
	$(this).html('<span class="spinner-grow spinner-grow-sm"></span> Loading..').prop('disabled', true);
	
	$.ajax({
		type: 'POST',
		url: '/ajax/check/order-coupon-status',
		dataType : 'json',
		data: {'type' : 'updateOrderWithoutCoupon', 'order_id' : id},
		success: function(data){
			if(data.status == true){
				window.location.href = url;
			}else{
				$("#modalPopup").find('.modal-body').html('<p>'+data.msg+'</p>');
				$("#modalPopup").find('.modal-footer').html('<button class="btn btn-primary" id="orderContinueBtn" data-url="'+url+'" data-id="'+id+'"> Continue </button>').removeClass('hide');
			}
		}
	});
});

function setModalPopup(body, header = null, footer = null){
	$("#modalPopup").find('.modal-header').find('h4').html(header);
	
	$("#modalPopup").find('.modal-body').html(body);
	
	if(footer != null)
		$("#modalPopup").find('.modal-footer').html(footer).removeClass('d-none');
	
	$("#modalPopup").modal('show')
}

$("#modalPopup").on("hidden.bs.modal", function () {
    $("#modalPopup").find('.modal-header').find('h4').html('');
	$("#modalPopup").find('.modal-body').html('');
	$("#modalPopup").find('.modal-footer').html('').addClass('d-none');
});

function plus(fieldid, customerid, productid, catid, price, prdtype) {
    
    var curr_val = $("input#"+fieldid).val();
    var nextval = parseInt(curr_val)+1;
    $("input#"+fieldid).val(nextval);
    
    var qty = $("input#"+fieldid).val();
    
    var currprd = "plusqty_"+productid;
    
	//var default_minimum_order = 0;
    
    if($("#default_minimum_order").val()) {
        var default_minimum_order = $("#default_minimum_order").val();
    }
	
	
	var prd_attr = $("input#"+fieldid).attr('data-prd-id');

	// For some browsers, `attr` is undefined; for others,
	// `attr` is false.  Check for both.
	if (typeof prd_attr !== typeof undefined && prd_attr !== false) {
		$("input#"+prd_attr).val(qty);
	}
        
	//alert(default_minimum_order); return false;
    
    $("."+currprd).addClass('disabled').find('span').removeClass('ds-pluase').addClass('spinner-border spinner-border-sm').css('font-size', '10px');
    
    var shoppinglisturl = $("#shoppinglisturl").val();
   
    purl = shoppinglisturl+'?catid='+catid+'&pid='+productid+'&pvalue='+qty+'&uid='+customerid+'&price='+price+'&prdtype='+prdtype;
	$.ajax({ url: purl, success: function(data){
		 
		$("#no_items_cart").hide();
		
		$("#sorted_shopping_list").html(data);
		$("#sorted_shopping_list").show();
		$("#continue_btn_block").show();
		
		$("#total_cart_val").html($("#total_basket_qty").val());
		
		if(parseFloat($("#total_products_price").val()) < default_minimum_order) {
                     
						$("#close_note").show();
						$("#link_disabled").addClass("disabled");
                     
                      } else {
                       
						 $("#close_note").hide();
						 $("#link_disabled").removeClass("disabled");
						}
		
		
		 
		$("."+currprd).removeClass('disabled').find('span').removeClass('spinner-border spinner-border-sm').addClass('ds-pluase').css('font-size', '');
		  return false;
						   
	}});
   
}

// plus qty on shopping cart page
function plus_shopping_cart(fieldid, customerid, productid, catid, price, prdtype) {
    
    var curr_val = $("input#"+fieldid).val();
    var nextval = parseInt(curr_val)+1;
    $("input#"+fieldid).val(nextval);
    
    var qty = $("input#"+fieldid).val();
    
    var currprd = "plusqty_"+productid;
    
	var prd_attr = $("input#"+fieldid).attr('data-prd-id');

	// For some browsers, `attr` is undefined; for others,
	// `attr` is false.  Check for both.
	if (typeof prd_attr !== typeof undefined && prd_attr !== false) {
		$("input#"+prd_attr).val(qty);
	}
        
	//alert(default_minimum_order); return false;
    
    $("."+currprd).addClass('disabled').find('span').removeClass('ds-pluase').addClass('spinner-border spinner-border-sm').css('font-size', '10px');
    
    var shoppinglisturl = $("#shoppinglisturl").val();
   
    purl = shoppinglisturl+'?catid='+catid+'&pid='+productid+'&pvalue='+qty+'&uid='+customerid+'&price='+price+'&prdtype='+prdtype;
	$.ajax({ url: purl, success: function(data){
		 
		$("#sorted_shopping_list").html(data);
		$("#total_cart_val").html($("#total_basket_qty").val());
		
		return false;
						   
	}});
   
}

function minus(fieldid, customerid, productid, catid, price, prdtype) {
    
    var curr_val = $("input#"+fieldid).val();
	if(curr_val>1) {
		var nextval = parseInt(curr_val)-1;
	} else {
		var nextval = parseInt(curr_val);
	}
    $("input#"+fieldid).val(nextval);
    
    var qty = $("input#"+fieldid).val();
    
    var currprd = "minusqty_"+productid;
    
	// var default_minimum_order = 0;
    
    if($("#default_minimum_order").val()) {
        var default_minimum_order = $("#default_minimum_order").val();
    }
	
	
	var prd_attr = $("input#"+fieldid).attr('data-prd-id');

	// For some browsers, `attr` is undefined; for others,
	// `attr` is false.  Check for both.
	if (typeof prd_attr !== typeof undefined && prd_attr !== false) {
		$("input#"+prd_attr).val(qty);
	}
        
	//alert(default_minimum_order); return false;
    
    $("."+currprd).addClass('disabled').find('span').removeClass('ds-minus').addClass('spinner-border spinner-border-sm').css('font-size', '10px');
    
    var shoppinglisturl = $("#shoppinglisturl").val();
   
    purl = shoppinglisturl+'?catid='+catid+'&pid='+productid+'&pvalue='+qty+'&uid='+customerid+'&price='+price+'&prdtype='+prdtype;
	$.ajax({ url: purl, success: function(data){
		 
		$("#no_items_cart").hide();
		$("#sorted_shopping_list").html(data);
		$("#sorted_shopping_list").show();
		$("#continue_btn_block").show();
		
		$("#total_cart_val").html($("#total_basket_qty").val());
		
		if(parseFloat($("#total_products_price").val()) < default_minimum_order) {
                     
						$("#close_note").show();
						$("#link_disabled").addClass("disabled");
                     
                      } else {
                       
						 $("#close_note").hide();
						 $("#link_disabled").removeClass("disabled");
						 
						}
		 
		$("."+currprd).removeClass('disabled').find('span').removeClass('spinner-border spinner-border-sm').addClass('ds-minus').css('font-size', '');
		  return false;
						   
	}});
   
}

//minus qty on shopping cart page 
function minus_shopping_cart(fieldid, customerid, productid, catid, price, prdtype) {

	var curr_val = $("input#"+fieldid).val();
    if(curr_val>1) {
		var nextval = parseInt(curr_val)-1;
	} else {
		var nextval = parseInt(curr_val);
	}
    $("input#"+fieldid).val(nextval);
    
    var qty = $("input#"+fieldid).val();
    
    var currprd = "minusqty_"+productid;
    
	var prd_attr = $("input#"+fieldid).attr('data-prd-id');

	// For some browsers, `attr` is undefined; for others,
	// `attr` is false.  Check for both.
	if (typeof prd_attr !== typeof undefined && prd_attr !== false) {
		$("input#"+prd_attr).val(qty);
	}
        
	//alert(default_minimum_order); return false;
    
    $("."+currprd).addClass('disabled').find('span').removeClass('ds-minus').addClass('spinner-border spinner-border-sm').css('font-size', '10px');
    
    var shoppinglisturl = $("#shoppinglisturl").val();
   
    purl = shoppinglisturl+'?catid='+catid+'&pid='+productid+'&pvalue='+qty+'&uid='+customerid+'&price='+price+'&prdtype='+prdtype;
	$.ajax({ url: purl, success: function(data){
		 
		$("#sorted_shopping_list").html(data);
		$("#total_cart_val").html($("#total_basket_qty").val());
		
		return false;
						   
	}});
	
	
}

function addbulkqty(fieldid, customerid, productid, catid, price, prdtype) {
    
    var curr_val = $("input#"+fieldid).val();
	var nextval = 1;
	
	if(curr_val>1) {
		nextval = curr_val;
	}
	
	$("input#"+fieldid).val(nextval);
    
    var qty = $("input#"+fieldid).val();
    
    var currprd = "plusqty_"+productid;
	
	
	if($("#default_minimum_order").val()) {
        var default_minimum_order = $("#default_minimum_order").val();
    }
	
	
	var prd_attr = $("input#"+fieldid).attr('data-prd-id');

	// For some browsers, `attr` is undefined; for others,
	// `attr` is false.  Check for both.
	if (typeof prd_attr !== typeof undefined && prd_attr !== false) {
		$("input#"+prd_attr).val(qty);
	}
        
	//alert(default_minimum_order); return false;
    
    $("."+currprd).addClass('disabled');
	$('a.btn.'+fieldid).html("<span class='spinner-border spinner-border-sm'></span> ADDED...");
    
    var shoppinglisturl = $("#shoppinglisturl").val();
   
    purl = shoppinglisturl+'?catid='+catid+'&pid='+productid+'&pvalue='+qty+'&uid='+customerid+'&price='+price+'&prdtype='+prdtype;
	$.ajax({ url: purl, success: function(data){
		 
		$("#no_items_cart").hide();
		
		$("#sorted_shopping_list").html(data);
		$("#sorted_shopping_list").show();
		$("#continue_btn_block").show();
		 
		$('a.btn.'+fieldid).html("ADDED");
		$('a.btn.'+fieldid).removeClass("btn-lightgreen");
		$('a.btn.'+fieldid).addClass("btn-green");
		 
		 
		$("#total_cart_val").html($("#total_basket_qty").val());
		
		if(parseFloat($("#total_products_price").val()) < default_minimum_order) {
                     
						$("#close_note").show();
						$("#link_disabled").addClass("disabled");
                     
                      } else {
                       
						 $("#close_note").hide();
						 $("#link_disabled").removeClass("disabled");
						 
						}
		 
		$("."+currprd).removeClass('disabled');
		  return false;
						   
	}});
	
}


function printDiv(divName) {
	var printContents = document.getElementById(divName).innerHTML;
     
    w=window.open();
    w.document.write(printContents);
    w.print();
    w.close();
     
    /* 
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents; */
}

function nospaces(t){

	if(t.value.match(/\s/g)){

		alert('Sorry, you are not allowed to enter any spaces');

		t.value=t.value.replace(/\s/g,'');

	}
}

