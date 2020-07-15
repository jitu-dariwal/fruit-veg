<?php

if(isset($pagelist) & $pagelist == "cron_paid_unpaid_total_admin" ){

$rowCount=0;
$invoiceidtmp='';
$rowcount_failed=0;
$batch_of = 40;
$batch = array_chunk($xml_array, $batch_of);
//$document_xml = new DOMDocument();
foreach($batch as $b) {

    foreach ($b as $key => $value) {

        $xml_invoices =$value;		
		libxml_use_internal_errors(true);
		
		try{
			$xml = new SimpleXMLElement($xml_invoices);
			//$xml->Invoice->InvoiceNumber;
			$invoiceidtmp = $xml->Invoice->InvoiceNumber;
		
			$InvoiceArr =  explode('cID',$invoiceidtmp);

			if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/invoices/xml_error_xero_invoice/'.$InvoiceArr[0])) {
				mkdir($_SERVER['DOCUMENT_ROOT'].'/invoices/xml_error_xero_invoice/'.$InvoiceArr[0], 0777, true);
			}


            $response = $XeroOAuth->request('POST', $XeroOAuth->url('Invoices', 'core'), array(), $xml_invoices);
 

				if ($XeroOAuth->response['code'] == 200) {
					$rowCount++;
					$invoice = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
				   
				   if (count($invoice->Invoices[0])>0) {
				   
						$sql_xero = DB::select("select * from xero_invoice_success_report where invoiceid = '".$invoiceidtmp."'");
						
						/*
						Output
						Array
							(
								[0] => stdClass Object
									(
										[id] => 1
										[invoiceid] => 1234
										[week_number] => 2
										[year] => 2018
									)

								[1] => stdClass Object
									(
										[id] => 2
										[invoiceid] => 1234
										[week_number] => 8
										[year] => 2017
									)

							)
							*/
						
						
						if(count($sql_xero) == 0){
						    DB::statement("INSERT into xero_invoice_success_report set invoiceid='".$invoiceidtmp."',week_number ='".$week_number."', year='".$year."'");
						}
					}
					
			   } else {
						//if($XeroOAuth->response['code'] == 400 || $XeroOAuth->response['code']==401 || $XeroOAuth->response['code'] == 404 || $XeroOAuth->response['code'] ==500 || $XeroOAuth->response['code'] ==501){
						$rowcount_failed++;
						echo "<br/>".$rowcount_failed." => This invoice ".$invoiceidtmp." is not imported, need to check it manually by developer and code ".$XeroOAuth->response['code']."."; 
					 
						$doc = new DOMDocument();
						$filename = $_SERVER['DOCUMENT_ROOT'].'/invoices/xml_error_xero_invoice/'.$InvoiceArr[0].'/' .  $invoiceidtmp . ".xml";
						$doc->save($filename);          		  
						//$f = fopen($filename, 'wr');
						$f = fopen($filename, 'w');
						fwrite($f, $xml_invoices);
						fclose($f);			
					  //outputError($XeroOAuth);
						//}
				}
			} catch (Exception $e){
				//echo 'Please try again later...';
				//exit();
			} 

	}
	sleep (60);
}

echo "<br/>Total Invoices: ". count($xml_array);

echo "<br/>Total invoices imported: ". $rowCount;

echo "<br/>Total Invoices Failed: ". $rowcount_failed;


}


if(isset($pagelist) & $pagelist == "cron_update_payment_of_invoice_weekly" ){

foreach($invoiceidtmpArray as $key=>$val){

 $invoiceidtmp =$val;
 
	$explodedata=explode('cID',$invoiceidtmp);								
	$explodedata[0];
	if(strlen($explodedata[0])=='6')
	{
	$week_number = substr($explodedata[0],0,2);	
	}else{
	$week_number = substr($explodedata[0],0,1);	
	}
	if($week_number == 31 || $week_number == 32){
	$invoiceidtmp_custom_ffto = $invoiceidtmp; 
	}else{
	$invoiceidtmp_custom_ffto = $invoiceidtmp;
	}

			
          $response = $XeroOAuth->request('GET', $XeroOAuth->url('Payments', 'core'),  array('Where' => 'Invoice.InvoiceNumber.Contains("'.$invoiceidtmp_custom_ffto.'")'));
		
          if ($XeroOAuth->response['code'] == 200) {
              $payments = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
              //echo "<pre>"; print_r($payments); exit;
		if(isset($payments->Payments[0]) && count($payments->Payments[0])){
		echo "There are " . count($payments->Payments[0]). " payments of the invoiceID ". $invoiceidtmp." in this Xero organisation: </br>";
		$sql = "SELECT * FROM invoices	WHERE `invoiceid` = '{$invoiceidtmp}'";
                  		$result = DB::SELECT($sql);
                  		$check=count($result);
                  		if($check){
                  		        
                  				if($result[0]->is_confirm == 0){
									$sql = "UPDATE invoices  SET `status` = '2', is_confirm = '1' WHERE `invoiceid` = '".$invoiceidtmp."'";
                  				 }
                  		}else{
								$explodedata=explode('cID',$invoiceidtmp);
								$explodedata[0];
								if(strlen($explodedata[0])=='6')
								{
								$week_number = substr($explodedata[0],0,2);										  
								$start=2;
								}else{
								$week_number = substr($explodedata[0],0,1);											
								$start=1;
								}
							   $year=substr($explodedata[0],$start,4);
							   $cID=$explodedata[1];
							   
							   $week_array = Finder::getStartAndEndDate($week_number,$year);
							   
							   $firstDayOfTheMonth_date  = $week_array['week_start'];
                  			   $lastDayOfTheMonth_date   = $week_array['week_end'];
							   
							  $sql = "INSERT INTO invoices (`invoiceid`, `status`, `is_confirm`, `week_no`, `year`, `customer_id`, `type`, `start_date`, `end_date`)  VALUES ($invoiceidtmp, '2','1',$week_number,$year,$cID,'weekly',$firstDayOfTheMonth_date,$lastDayOfTheMonth_date)";
                  		}
                  		
                  		$result = DB::statement($sql);
                  		                
                  /*
                  	 	$sql_1 = "SELECT * FROM tbl_invoice_payment_mode_status  WHERE `invoiceid` = '{$invoiceidtmp}'";  
						$result_1 = DB::select($sql_1) or die('ERROR in SQL : ' . mysql_error().'<br />');
						 $check_1=count($result_1);	
										
                  		if($check_1){
                  		  // if($row['status_confirm']=='no'){
                  				 $sql = DB::statement("UPDATE tbl_invoice_payment_mode_status  SET `status` = 'PAID' WHERE `invoiceid` = '".$invoiceidtmp."'");
                  			//}	
                  		}else{
                  				 		
                  			                        $explodedata=explode('cID',$invoiceidtmp);
                  									$explodedata[0];
                  									if(strlen($explodedata[0])=='6')
                  									{
                  									$week_number = substr($explodedata[0],0,2);										  
                  									$start=2;
                  									}else{
                  									$week_number = substr($explodedata[0],0,1);											
                  									$start=1;
                  									}
                  									$year=substr($explodedata[0],$start,4);
                  									$cID=$explodedata[1];									
                  									$week_array = getStartAndEndDate($week_number,$year);												
                  							
                  							$firstDayOfTheMonth_date  = $week_array['week_start'];
                  							$lastDayOfTheMonth_date   = $week_array['week_end'];	
                  		
                  	

	DB::statement("insert into tbl_invoice_payment_mode_status set 
                  		week_number='".$week_number."',
                  		year=".$year.",
                  		customers_id=".$cID.",
                  		invoiceid='".$invoiceidtmp."',											
                  		status='PAID',
						invoice_type='weekly',
                  		invoice_month_start_date='".$firstDayOfTheMonth_date."',
                  		invoice_month_end_date='".$lastDayOfTheMonth_date."'");
                  		} */
			}
          } else {
		  
		 
              //outputError($XeroOAuth);
          }

  }
}