<?php
function getStartAndEndDate($week, $year) {
  $dto = new DateTime();
  $dto->setISODate($year, $week);
  $ret['week_start'] = $dto->format('Y-m-d');
  $dto->modify('+6 days');
  $ret['week_end'] = $dto->format('Y-m-d');
  return $ret;
}

if(isset($pagelist) & $pagelist == "cron_attachment_of_invoices_weekly_auto_script" ){


//$response = $XeroOAuth->request('GET', $XeroOAuth->url('Invoices', 'core'), array('If-Modified-Since' => gmdate("M d Y H:i:s",(time() - (7 * 24 * 60 * 60)))));
$date = date("Y-m-d");
$dateArr = explode('-',$date);
$dateArr[0];
$dateArr[1];
$dateArr[2];
//$response = $XeroOAuth->request('GET', $XeroOAuth->url('Invoices', 'core'), array('Where' => 'Date == DateTime('.$dateArr[0].', '.$dateArr[1].','. $dateArr[2].')') );
$response = $XeroOAuth->request('GET', $XeroOAuth->url('Invoices', 'core'), array('Where' => 'Status=="DRAFT"') );
$invoices = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
//echo "There are " . count($invoices->Invoices[0]). " draft invoices in this Xero organisation, the first one is: </br>";

foreach($invoices->Invoices as $invoices => $invoice){
			   foreach($invoice as $key => $value){			   
			   $arr1[] = json_decode( json_encode($value) , 1);
			   
			   
			   
			   
			/* echo  $InvoiceID = $value->InvoiceNumber;
			  echo "<br>";
			   $fileName = 'invoice_'.$InvoiceID.'.pdf';
				//$fileName = 'invoice_0112018cID4552.pdf';
				
				$url =  'http://www.fruitfortheoffice.co.uk/admin/temp/invoices/'.$fileName;				
				if(false!==file($url)){				
					$attachmentFile = file_get_contents('http://www.fruitfortheoffice.co.uk/admin/temp/invoices/'.$fileName);
					//print_r($attachmentFile);
					$response = $XeroOAuth->request('PUT', $XeroOAuth->url('Invoice/'.$value->InvoiceID.'/Attachments/'.$fileName, 'core'), array('IncludeOnline' => 'True'), $attachmentFile, 'file');
					if ($XeroOAuth->response['code'] == 200) {
					echo "Attachment successfully created against this invoice.";
					} else {
					outputError($XeroOAuth);
					}
				}
				*/
}
}
$batch_of = 40;
$batch = array_chunk($arr1, $batch_of);

foreach($batch as $b) {


    foreach ($b as $key => $value) {
	
    $value['InvoiceNumber'];

			  echo  $InvoiceID = $value['InvoiceNumber'];
			  echo "<br>";
			   $fileName = 'invoice_'.$InvoiceID.'.pdf';
				//$fileName = 'invoice_0112018cID4552.pdf';
				
				$url =  'http://www.fruitfortheoffice.co.uk/admin/temp/invoices/'.$fileName;				
				if(false!==file($url)){				
					$attachmentFile = file_get_contents('http://www.fruitfortheoffice.co.uk/admin/temp/invoices/'.$fileName);
					//print_r($attachmentFile);
					$response = $XeroOAuth->request('PUT', $XeroOAuth->url('Invoice/'.$value['InvoiceID'].'/Attachments/'.$fileName, 'core'), array('IncludeOnline' => 'True'), $attachmentFile, 'file');
					if ($XeroOAuth->response['code'] == 200) {
					echo "Attachment successfully created against this invoice.";
					} else {
					outputError($XeroOAuth);
					}
				}
	
	
	
	
	}
	sleep (60);
}

	

}
?>

