<?php
if(isset($pagelist) & trim($pagelist) == "import_monthly_invoices_require_monthly_type_customers" ){

$rowCount=0;
$invoiceidtmp='';
$rowcount_failed=0;



foreach($xml_array as $key=>$value){


        $invoiceidtmp =$key;
        $xml_invoices =$value;			
			
			$invoiceidtmp = $invoiceidtmp; // Removed zero (0) from FFTO invoices which is added to indentified FFTO invoices at xero
		
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
						if(count($sql_xero) == 0){
						   // mysql_query("INSERT into tbl_fed_xero_invoice_success_report set invoiceid='".$invoiceidtmp."',month_number ='".$week_number."', year='".$year."'");
						}
					}
					
			   } else {
						//if($XeroOAuth->response['code'] == 400 || $XeroOAuth->response['code']==401 || $XeroOAuth->response['code'] == 404 || $XeroOAuth->response['code'] ==500 || $XeroOAuth->response['code'] ==501){
						$rowcount_failed++;
						echo "<br/>".$rowcount_failed." => This invoice ".$invoiceidtmp." is not imported, need to check it manually by developer and code ".$XeroOAuth->response['code']."."; 
					 
						$doc = new DOMDocument();
						$filename = $_SERVER['DOCUMENT_ROOT'].'/invoices/xml_error_xero_invoice/'.$InvoiceArr[0].'/' .  $invoiceidtmp . ".xml";
						$doc->save($filename);          		  
						$f = fopen($filename, 'w');
						fwrite($f, $xml_invoices);
						fclose($f);			
					  //outputError($XeroOAuth);
						//}
				}
	}		
	

echo "<br/>Total Invoices: ". count($xml_array);

echo "<br/>Total invoices imported: ". $rowCount;

echo "<br/>Total Invoices Failed: ". $rowcount_failed;
}


