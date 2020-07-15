$(document).ready(function () {  
	//set datepicker and date range picker
        $('.datepicker').datepicker({ 
          format: 'dd-mm-yyyy',
          autoclose: true,
          clearBtn: true
        }).prop('readonly', true); 
        
        var todayDate = new Date().getDate();
        
        $('.datepicker1').datepicker({ 
          format: 'dd-mm-yyyy',
          autoclose: true,
          startDate: new Date(new Date().setDate(todayDate)),
          clearBtn: true
        }).prop('readonly', true); 
      
        
        //Date range validation        
        $('.daterange-group .datefrom').on('changeDate', function() {
            var dateto = $(this).parents('.daterange-group').find('.dateto');
            dateto.datepicker('setStartDate', $(this).datepicker('getFormattedDate'));
        });
         
        $('.daterange-group .dateto').on('changeDate', function() {
            var datefrom = $(this).parents('.daterange-group').find('.datefrom');
            datefrom.datepicker('setEndDate', $(this).datepicker('getFormattedDate'));
        });
        
        $('.daterange-group .coupondatefrom').on('changeDate', function() {
            var dateto = $(this).parents('.daterange-group').find('.coupondateto');
            dateto.datepicker('setStartDate', $(this).datepicker('getFormattedDate'));
        });
        
       
        //End datepicker
        
	$("input[type=checkbox][name=checkAll]").click(function() {
        $("input:checkbox").not(this).prop("checked", this.checked);
	});
	
	$('#is_free').on('change', function () {
        console.log($(this).val());
        if ($(this).val() == 0) {
            $('#delivery_cost').fadeIn();
        } else {
            $('#delivery_cost').fadeOut();
        }
    });
    $('.select2').select2({
        placeholder: 'Select'
    });
    
   
  /* $('.table').DataTable({
        'info' : false,
        'paging' : false,
        'searching' : false,
        'columnDefs' : [
            {
                'orderable': false, 'targets' : -1
            }
        ],
        'sorting' : []
    });
    */
	
$("input[type=radio][name=credit_checked]").click(function() {
		if($(this).val() == 1) {
			$(".credit-checked-by").show();
		} else {
			$(".credit-checked-by").hide();
		}
	});



  /* Calculation to get and set Split prices of the product*/
	$('input[type=text][name=packvalue_quantity], input[type=text][name=gross_price_bulk],input[type=text][name=split_quantity]').keypress(function(event) { 
		 if(event.which == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46) 
          return true;

     else if((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57))
          event.preventDefault();
		});
		
	$('input[type=text][name=gross_price_bulk]').blur(function(event) {
		var grossValue =$('input[type=text][name=gross_price_bulk]').val();		
		 $('input[type=hidden][name=price]').val(grossValue);	
		});
		
	/* $('input[type=text][name=price]').blur(function(event) {
		var price =$('input[type=text][name=price]').val();	
		 $('input[type=text][name=gross_price_bulk]').val(price);		
		}); */
		
	
	$("input[type=text][name=packvalue_quantity], input[type=text][name=gross_price_bulk],input[type=text][name=split_quantity]  ").blur(function(){
		var c =$('input[type=hidden][name=split_product_count]').val();
       // var c =1;		
		var grossValue =$('input[type=text][name=gross_price_bulk]').val();
		var bulk_quanty =$('input[type=text][name=packvalue_quantity]').val();
		var split_quantity =$('input[type=text][name=split_quantity]').val();
		var packet_size_split = $('input[type=text][name=packet_size_split]').val();
		var product_code_split = $('input[type=text][name=product_code_split]').val();
		
		if (product_code_split != '' && packet_size_split != '') {
		var is_split = 1;
		}		
		
	
	   /*  if ( bulk_quanty =="" ) {  
			   alert("Please Provide pack value Bulk in Numeric");  
			return false; 
		} */
		
		if(is_split){		
			//if(c==""){
				/* if ( split_quantity =="" ){
					  alert("Please Provide pack value Split in Numeric");
					  return false; 
				 } */
				temp=1; 
			//}		
		}else{
		temp=2;		
		}
			

	/*	if ( grossValue =="" ) {
		alert("Please Provide Price in Numeric");
		return false; 
		} */
		
		if ( (bulk_quanty !="" && parseFloat(bulk_quanty)) && (grossValue !="" && parseFloat(grossValue))) {
		
			if(temp==1){
				grossValue = doRound(grossValue*split_quantity / bulk_quanty,2);
				$('input[type=text][name=real_split_price]').val(grossValue);		
			}
			if(temp==2){
				//grossValue = doRound(grossValue / bulk_quanty,2);
				//$('input[type=text][name=real_split_price]').val(grossValue);
				$('input[type=text][name=real_split_price]').val('0');
				$('input[type=text][name=split_quantity]').val('');
			}
		}else {		
		
		$('input[type=text][name=real_split_price]').val('0');		
		 		
		return false;
		}
		return true;
		
	});
        
    //list subcategories by parent cat    
        $('#categoriesbox').on("change", function() {
           
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
	   
	//list subcategories by parent cat    
        $('form#proautoList').submit(function(e) { 
          
                e.preventDefault();
               // $("#searching_please_wait").html("Searching Please Wait...");
                //$('#filteredproducts').html('<tr><td colspan="4" align="center">Loading records please wait.....</td></tr>');
                    $.ajax({
                          url: 'filterproducts',
                          type: 'POST',
                          data: $(this).serialize(),
                          success: function(response)
                          {
                              $('#filteredproducts').html(response);
                              $("#searching_please_wait").html("");
                              $("form#toaddedProduct").submit(function(event) {
                                   
                                  event.preventDefault();
                                  
                                  $('#addedpList1').html("Listing products please wait....");
                                  
                                   $.ajax({
                                            url: 'sortpdfproducts',
                                            type: 'POST',
                                            data: $(this).serialize(),
                                            success: function(response)
                                            {
                                                   
                                             $('#addedprdList').html(response);

                                            }
                                        });
                              })
                              
                          }
                      });
            
        });
        
 //select product for coupon create page    
        $('form#selectproduct').submit(function(e) { 
           
            e.preventDefault();
               // $("#searching_please_wait").html("Searching Please Wait...");
                //$('#filteredproducts').html('<tr><td colspan="4" align="center">Loading records please wait.....</td></tr>');
                    $.ajax({
                          url: $(this).attr('action'),
                          type: 'POST',
                          data: $(this).serialize(),
                          success: function(response)
                          {
                              $("#close-select-product").trigger("click");
                              $("input#valid_product_list").val(response);
                              
                          }
                      });
        });
        
 //select category for coupon create page    
        $('form#selectcategory').submit(function(e) { 
            
            e.preventDefault();
               // $("#searching_please_wait").html("Searching Please Wait...");
                //$('#filteredproducts').html('<tr><td colspan="4" align="center">Loading records please wait.....</td></tr>');
                    $.ajax({
                          url: $(this).attr('action'),
                          type: 'POST',
                          data: $(this).serialize(),
                          success: function(response)
                          {
                              $("#close-select-category").trigger("click");
                              $("input#valid_category_list").val(response);
                              
                          }
                      });
        });
  
//list subcategories by parent cat    
$('form#probulkupdateList').on("submit", function(e) { 
      
    e.preventDefault();
    //$("#searching_please_wait").html("Searching Please Wait...");
    //$('#filteredproducts').html('<tr><td colspan="4" align="center">Loading records please wait.....</td></tr>');
        $.ajax({
              url: 'filterbulkproducts',
              type: 'POST',
              data: $(this).serialize(),
              success: function(response)
              {
                 
                $('#filteredproducts').html(response);
                $("#searching_please_wait").html("");
              }
          });
            
});

$('#page_title_pages').on('blur', function(e) { 
    
        var curr_str = $(this).val();
        var slug = curr_str.replace(' ', '-').toLowerCase();
        $("#page_slug").val(slug);
    
});


	
	
});

function doRound(x, places) {
  return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
}

function getTaxRate() {
  var selected_value = document.forms["new_product"].products_tax_class_id.selectedIndex;
  var parameterVal = document.forms["new_product"].products_tax_class_id[selected_value].value;

  if ( (parameterVal > 0) && (tax_rates[parameterVal] > 0) ) {
    return tax_rates[parameterVal];
  } else {
    return 0;
  }
}

function addRowFeature(tableID) {
	var table = document.getElementById(tableID);

	var rowCount = table.rows.length;
	var rowCountAppend = rowCount + 1;
	if(rowCount<20){
	var row = table.insertRow(rowCount);
	//alert(rowCountAppend);	
	var cell1 = row.insertCell(0);
	var element1 = document.createElement("input");
	element1.type = "checkbox";
        element1.name = rowCountAppend+"_chk";
	cell1.appendChild(element1);

	var cell2 = row.insertCell(1);
	cell2.innerHTML = rowCount ;
	
	//var cell3 = row.insertCell(2);
	//cell3.innerHTML = '&nbsp;';


	var cell3 = row.insertCell(2);
	cell3.innerHTML = '<p class="frmtitle">Product Name</p>';

	var cell4 = row.insertCell(3);
	var element2 = document.createElement("input");
	element2.type = "text";
        element2.className = "form-control";
        element2.name = rowCountAppend+"_productName";
        element2.required = "required";
	element2.size = "30";
	cell4.appendChild(element2);
        
        var cell5 = row.insertCell(4);
	cell5.innerHTML = '<p class="frmtitle">Size</p>';
        var cell6 = row.insertCell(5);
	var element3 = document.createElement("input");
	element3.type = "text";
	element3.name = rowCountAppend+"_productSize";
        element3.className = "form-control";
	
	cell6.appendChild(element3);
	var cell7 = row.insertCell(6);
	cell7.innerHTML = '<p class="frmtitle">Price(£)</p>';
        
        var cell8 = row.insertCell(7);
	var element12 = document.createElement("input");
	element12.type = "text";
        element12.className = "form-control";
        element12.placeholder = "0.00";
        element12.name = rowCountAppend+"_productPrice";
	//element9.value = "t";
	cell8.appendChild(element12);	
	//alert(rowCountAppend);			
	document.proList.total_products.value=rowCountAppend;
	
	}
}

function deleteRowFeature(tableID) {
	try {
	
	var table = document.getElementById(tableID);
	var rowCount = table.rows.length;
	
	for(var i=1; i<rowCount; i++) {
		var row = table.rows[i];
		var chkbox = row.cells[0].childNodes[0];
		if(null != chkbox && true == chkbox.checked) {
			table.deleteRow(i);
			rowCount--;
			i--;
		}

	}
	}catch(e) {
		alert(e);
	}
}

function Stock_frontend(pid,status,i){ 
            purl='bulkprdstockupdate?pid='+pid+'&status='+status+'&i='+i+'&frontend=yes';
            $.ajax({ url: purl, success: function(data){
                                   document.getElementById('show_image_'+i).innerHTML =data;
            }});
} 

function Stock_backend(pid,status,i){ 
                purl='bulkprdstockupdate?pid='+pid+'&status='+status+'&i='+i+'&backend=yes';
                $.ajax({ url: purl, success: function(data){
                                      document.getElementById('show_image2_'+i).innerHTML =data;
            }});
} 

function ProductPriceUpdate(pid,i){
            var price = document.getElementById('price_'+i).value;
            
           var validatePrice = function(price) {
                return /^(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(price);
            }
           
            if (!validatePrice(price)) {
                alert("Please enter correct price value.");            
                return false;
            }
            
           purl='bulkprdpriceupdate?pid='+pid+'&price='+price+'&product_price_change=yes';
           $.ajax({ url: purl, success: function(data){
                   //alert(data); return false;
                                   //document.getElementById('show_image2_'+i).innerHTML =data;
                    $("#bulkprice_updated_"+i).show();
                    $("#bulkprice_updated_"+i).html('<img src="/images/action_check.png" alt="" />');
                    $("#bulkprice_updated_"+i).delay(2000).fadeOut();
           }});
}

