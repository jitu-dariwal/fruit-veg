<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Shop\Customers\Customer;
use App\Helper\Finder;
use DB;

class CronController extends Controller
{
    /**
     * update settings form.
     *
     * @return \Illuminate\Http\Response
     */
       
        
    public function __construct() {
		
	}
        
    public function invoices_weekly(Request $request) {
        
        
             $week_no      = date("W");
	     $year         = date("Y");
	     $order_status = 5;
	     $invoice_type = '';
	     $check_paid   = 0;
		 if($request->week_number){
			$week_no= $request->week_number;
		 }
		 if($request->year){
			$year= $request->year;
		 }
		 if($request->delivered){
			$order_status= 3;
		 }
		 if($request->monthly_invoice){
			$invoice_type= 'monthly-invoice';
		 }
		 if($request->check_paid){
			$check_paid= $request->check_paid;
		 }
		 $paymenttypes=Finder::getPaymentMethods();
		 $dates   = Finder::getStartAndEndDate($week_no, $year);
		 $w_start =$dates['week_start'];
		 $w_end   =$dates['week_end'];
		 $start_d=date('Y-m-d', strtotime($w_start));
		 $end_d = date('Y-m-d', strtotime($w_end));
		 $customers_invoice_type =\Config::get('constants.REQUIRE_INVOICE_TYPE');
		 $getInvoices=Customer::join('orders','customers.id','=','orders.customer_id')
		                        ->join('order_details','orders.id','=','order_details.order_id')
                                        ->join('addresses','addresses.id','=','customers.default_address_id')
								->selectRaw('orders.id as order_id, customers.id, customers.invoice_street_address, customers.invoice_suburb, customers.invoice_city, customers.invoice_state, customers.invoice_postcode, customers.purchase_order_number, addresses.company_name, addresses.street_address, addresses.address_line_2, addresses.city, addresses.county_state, addresses.post_code, customers.company_tax_id, customers.customers_accountemail, customers.first_name, customers.last_name, customers.tel_num, customers.fax_num, customers.default_address_id, sum(orders.total) as inovice_total, orders.payment_method')
								->where(function($query) use($customers_invoice_type){
									$query->whereNull('customers.customers_require_invoice_type')
										  ->orWhere('customers.customers_require_invoice_type', '!=' ,$customers_invoice_type);
                                })
								->whereDate('order_details.shipdate','>=',$start_d)
								->whereDate('order_details.shipdate','<=',$end_d);
								if($order_status==3){
								$getInvoices=$getInvoices->where('orders.order_status_id','=',$order_status);
								}else{
								$getInvoices=$getInvoices->where('orders.order_status_id','!=',$order_status);	
								}
								if(!empty($invoice_type)){
								$getInvoices=$getInvoices->where('orders.payment_method','=',$invoice_type);
								}
								$getInvoices=$getInvoices->groupBy('customers.id')
		                        ->get();
        
            $xml_array = array();
            
           //print xml
        foreach($getInvoices as $invoice)  {

            $invoiceId=Finder::getInvoiceId($week_no,$year,$invoice->id);
           // echo $invoice->id; exit;


          $invoiceidtmp_custom_ffto = $invoiceId; // to avoid same invoice id on FFTO and FNV, we added ZERO (0) before invoice ID in FFTO.
//echo "<pre>"; print_r($invoice); exit;
                $xml_array[$invoiceId]="<Invoices>
          <Invoice>
            <Type>ACCREC</Type>
                <Contact>
              <ContactNumber>".$invoice->tel_num."</ContactNumber>
              <Name>".$invoice->company_name."</Name>
              <ContactStatus>ACTIVE</ContactStatus>
                  <TaxNumber>".$invoice->company_tax_id."</TaxNumber>
              <EmailAddress>".$invoice->customers_accountemail."</EmailAddress> 
              <FirstName>".$invoice->first_name."</FirstName>
              <LastName>".$invoice->last_name."</LastName>
              <DefaultCurrency>GBP</DefaultCurrency>
              <Addresses>
                <Address>
                  <AddressType>STREET</AddressType>
                  <AttentionTo>".$invoice->street_address."</AttentionTo>
                  <AddressLine1>".$invoice->street_address."</AddressLine1>
                  <AddressLine2>".$invoice->address_line_2."</AddressLine2>
                  <AddressLine3></AddressLine3>
                  <AddressLine4></AddressLine4>
                  <City>".$invoice->city."</City>
                  <Region>".$invoice->county_state."</Region>
                  <PostalCode>".$invoice->post_code."</PostalCode>
                  <Country>United Kingdom</Country>
                </Address>  
                <Address>
                  <AddressType>POBOX</AddressType>
                  <AttentionTo>".$invoice->invoice_street_address."</AttentionTo>
                  <AddressLine1>".$invoice->invoice_street_address."</AddressLine1>
                  <AddressLine2>".$invoice->invoice_suburb."</AddressLine2>
                  <AddressLine3></AddressLine3>
                  <AddressLine4></AddressLine4>
                  <City>".$invoice->invoice_city."</City>
                  <Region>".$invoice->invoice_state."</Region>
                  <PostalCode>".$invoice->invoice_postcode."</PostalCode>
                  <Country>United Kingdom</Country>
                </Address>		
              </Addresses>
              <Phones>
                <Phone>
                  <PhoneType>DEFAULT</PhoneType>
                  <PhoneNumber>".$invoice->tel_num."</PhoneNumber>          
                </Phone>        
                <Phone>
                  <PhoneType>FAX</PhoneType>
                  <PhoneNumber>".$invoice->fax_num."</PhoneNumber>         
                </Phone>        
              </Phones>
              <ContactPersons>
                  <ContactPerson>
                      <FirstName>".$invoice->first_name."</FirstName>
                      <LastName></LastName>
                      <EmailAddress>".$invoice->email."</EmailAddress>
                      <IncludeInEmails>true</IncludeInEmails>
                  </ContactPerson>
              </ContactPersons>
            </Contact>    
            <Date>".$invoice->paid_date."</Date>
            <DueDate>".$invoice->paid_date."</DueDate>
            <InvoiceNumber>".$invoiceidtmp_custom_ffto."</InvoiceNumber>
            <Reference>Po Number-".$invoice->purchase_order_number." fruitandveg-".$invoiceidtmp_custom_ffto."</Reference>
            <CurrencyCode>GBP</CurrencyCode>
            <Status>DRAFT</Status>   
            <SubTotal><![CDATA[".$invoice->inovice_total."]]></SubTotal>
            <TotalTax>0</TotalTax>
            <Total><![CDATA[".$invoice->inovice_total."]]></Total>    
            <LineItems>
              <LineItem>
                <Description>Payment-<![CDATA[".$invoice->payment_method."]]></Description>
                <Quantity>1</Quantity>
                <UnitAmount><![CDATA[".$invoice->inovice_total."]]></UnitAmount>
                <TaxType>OUTPUT</TaxType>
                <TaxAmount>0</TaxAmount>
                <LineAmount><![CDATA[".$invoice->inovice_total."]]></LineAmount>
                <AccountCode>200</AccountCode>        
              </LineItem>      
            </LineItems>
          </Invoice>
        </Invoices>";                                                   

        }                                                          

      //echo "<pre>";
     // print_r($xml_array);
    // die;

      $pagelist= 'cron_paid_unpaid_total_admin';
      
      require 'XeroOAuth-PHP-master/private.php';
      include 'XeroOAuth-PHP-master/tests/testRunner.php';
      $initialCheck = $XeroOAuth->diagnostics();
      $checkErrors = count( $initialCheck );
      if ($checkErrors > 0) {
          // you could handle any config errors here, or keep on truckin if you like to live dangerously
          foreach ( $initialCheck as $check ) {
              echo 'Error: ' . $check . PHP_EOL;
          }
      } else {

          $session = persistSession ( array (
          'oauth_token' => $XeroOAuth->config['consumer_key'],
          'oauth_token_secret' => $XeroOAuth->config['shared_secret'],
          'oauth_session_handle' => '' 
          ) );
          $oauthSession = retrieveSession();

          if (isset ( $oauthSession['oauth_token'] )) {

          $XeroOAuth->config['access_token'] = $oauthSession['oauth_token'];
          $XeroOAuth->config['access_token_secret'] = $oauthSession['oauth_token_secret'];		
          include 'XeroOAuth-PHP-master/tests/tests2.php';
          }

      }

}

 public function invoices_weekly_per_customer(Request $request) {
            
             $week_no      = date("W");
	     $year         = date("Y");
	     $order_status = 5;
	     $invoice_type = '';
             $check_paid   = 0;
             $customer_id = $request->customer_id;
             
		 if($request->week_number){
			$week_no= $request->week_number;
		 }
		 if($request->year){
			$year= $request->year;
		 }
		 if($request->delivered){
			$order_status= 3;
		 }
		 if($request->monthly_invoice){
			$invoice_type= 'monthly-invoice';
		 }
		 if($request->check_paid){
			$check_paid= $request->check_paid;
		 }
		 $paymenttypes=Finder::getPaymentMethods();
		 $dates   = Finder::getStartAndEndDate($week_no, $year);
		 $w_start =$dates['week_start'];
		 $w_end   =$dates['week_end'];
		 $start_d=date('Y-m-d', strtotime($w_start));
		 $end_d = date('Y-m-d', strtotime($w_end));
		 $customers_invoice_type =\Config::get('constants.REQUIRE_INVOICE_TYPE');
                 
                 $invoice_id=$week_no.''.$year.'cID'.$customer_id.'';
                 
                 
		 $getInvoices=Customer::join('orders','customers.id','=','orders.customer_id')
		                        ->join('order_details','orders.id','=','order_details.order_id')
                                        ->join('addresses','addresses.id','=','customers.default_address_id')
								->selectRaw('orders.id as order_id, customers.id, customers.invoice_street_address, customers.invoice_suburb, customers.invoice_city, customers.invoice_state, customers.invoice_postcode, customers.purchase_order_number, addresses.company_name, addresses.street_address, addresses.address_line_2, addresses.city, addresses.county_state, addresses.post_code, customers.company_tax_id, customers.customers_accountemail, customers.first_name, customers.last_name, customers.tel_num, customers.fax_num, customers.default_address_id, sum(orders.total) as inovice_total, orders.payment_method')
								->where(function($query) use($customers_invoice_type){
									$query->whereNull('customers.customers_require_invoice_type')
										  ->orWhere('customers.customers_require_invoice_type', '!=' ,$customers_invoice_type);
                                })
								->whereDate('order_details.shipdate','>=',$start_d)
								->whereDate('order_details.shipdate','<=',$end_d);
								if($order_status==3){
								$getInvoices=$getInvoices->where('orders.order_status_id','=',$order_status);
								}else{
								$getInvoices=$getInvoices->where('orders.order_status_id','!=',$order_status);	
								}
								if(!empty($invoice_type)){
								$getInvoices=$getInvoices->where('orders.payment_method','=',$invoice_type);
								}
                                                                
                                                                if(!empty($customer_id)) {
                                                                    
                                                                    $getInvoices=$getInvoices->where('customers.id','=',$customer_id);
                                                                }
                                                                
								$getInvoices=$getInvoices->groupBy('customers.id')
		                        ->get();
                                                                
           //print xml
        foreach($getInvoices as $invoice)  {

            $invoiceId=Finder::getInvoiceId($week_no,$year,$invoice->id);
           // echo $invoice->id; exit;


          $invoiceidtmp_custom_ffto = $invoiceId;

                $xml_array[$invoiceId]="<Invoices>
          <Invoice>
            <Type>ACCREC</Type>
                <Contact>
              <ContactNumber>".$invoice->tel_num."</ContactNumber>
              <Name>".$invoice->company_name."</Name>
              <ContactStatus>ACTIVE</ContactStatus>
                  <TaxNumber>".$invoice->company_tax_id."</TaxNumber>
              <EmailAddress>".$invoice->customers_accountemail."</EmailAddress> 
              <FirstName>".$invoice->first_name."</FirstName>
              <LastName>".$invoice->last_name."</LastName>
              <DefaultCurrency>GBP</DefaultCurrency>
              <Addresses>
                <Address>
                  <AddressType>STREET</AddressType>
                  <AttentionTo>".$invoice->street_address."</AttentionTo>
                  <AddressLine1>".$invoice->street_address."</AddressLine1>
                  <AddressLine2>".$invoice->address_line_2."</AddressLine2>
                  <AddressLine3></AddressLine3>
                  <AddressLine4></AddressLine4>
                  <City>".$invoice->city."</City>
                  <Region>".$invoice->county_state."</Region>
                  <PostalCode>".$invoice->post_code."</PostalCode>
                  <Country>United Kingdom</Country>
                </Address>  
                <Address>
                  <AddressType>POBOX</AddressType>
                  <AttentionTo>".$invoice->invoice_street_address."</AttentionTo>
                  <AddressLine1>".$invoice->invoice_street_address."</AddressLine1>
                  <AddressLine2>".$invoice->invoice_suburb."</AddressLine2>
                  <AddressLine3></AddressLine3>
                  <AddressLine4></AddressLine4>
                  <City>".$invoice->invoice_city."</City>
                  <Region>".$invoice->invoice_state."</Region>
                  <PostalCode>".$invoice->invoice_postcode."</PostalCode>
                  <Country>United Kingdom</Country>
                </Address>		
              </Addresses>
              <Phones>
                <Phone>
                  <PhoneType>DEFAULT</PhoneType>
                  <PhoneNumber>".$invoice->tel_num."</PhoneNumber>          
                </Phone>        
                <Phone>
                  <PhoneType>FAX</PhoneType>
                  <PhoneNumber>".$invoice->fax_num."</PhoneNumber>         
                </Phone>        
              </Phones>
              <ContactPersons>
                  <ContactPerson>
                      <FirstName>".$invoice->first_name."</FirstName>
                      <LastName></LastName>
                      <EmailAddress>".$invoice->email."</EmailAddress>
                      <IncludeInEmails>true</IncludeInEmails>
                  </ContactPerson>
              </ContactPersons>
            </Contact>    
            <Date>".$invoice->paid_date."</Date>
            <DueDate>".$invoice->paid_date."</DueDate>
            <InvoiceNumber>".$invoiceidtmp_custom_ffto."</InvoiceNumber>
            <Reference>Po Number-".$invoice->purchase_order_number." fruitandveg-".$invoiceidtmp_custom_ffto."</Reference>
            <CurrencyCode>GBP</CurrencyCode>
            <Status>DRAFT</Status>   
            <SubTotal><![CDATA[".$invoice->inovice_total."]]></SubTotal>
            <TotalTax>0</TotalTax>
            <Total><![CDATA[".$invoice->inovice_total."]]></Total>    
            <LineItems>
              <LineItem>
                <Description>Payment-<![CDATA[".$invoice->payment_method."]]></Description>
                <Quantity>1</Quantity>
                <UnitAmount><![CDATA[".$invoice->inovice_total."]]></UnitAmount>
                <TaxType>OUTPUT</TaxType>
                <TaxAmount>0</TaxAmount>
                <LineAmount><![CDATA[".$invoice->inovice_total."]]></LineAmount>
                <AccountCode>200</AccountCode>        
              </LineItem>      
            </LineItems>
          </Invoice>
        </Invoices>";                                                   

        }                                                          

     // echo "<pre>";
     // print_r($xml_array);
     // die;

      $pagelist= 'cron_paid_unpaid_total_admin';
      
      require 'XeroOAuth-PHP-master/private.php';
      include 'XeroOAuth-PHP-master/tests/testRunner.php';
      $initialCheck = $XeroOAuth->diagnostics();
      $checkErrors = count( $initialCheck );
      if ($checkErrors > 0) {
          // you could handle any config errors here, or keep on truckin if you like to live dangerously
          foreach ( $initialCheck as $check ) {
              echo 'Error: ' . $check . PHP_EOL;
          }
      } else {

          $session = persistSession ( array (
          'oauth_token' => $XeroOAuth->config['consumer_key'],
          'oauth_token_secret' => $XeroOAuth->config['shared_secret'],
          'oauth_session_handle' => '' 
          ) );
          $oauthSession = retrieveSession();

          if (isset ( $oauthSession['oauth_token'] )) {

          $XeroOAuth->config['access_token'] = $oauthSession['oauth_token'];
          $XeroOAuth->config['access_token_secret'] = $oauthSession['oauth_token_secret'];		
          include 'XeroOAuth-PHP-master/tests/tests2.php';
          }

      }
   }
	
    public function invoices_weekly_paid_status(Request $request) {
            
        $week_no      = date("W");
	     $year         = date("Y");
	     $order_status = 5;
	     $invoice_type = '';
		 $check_paid   = 0;
		 if($request->week_number){
			$week_no= $request->week_number;
		 }
		 if($request->year){
			$year= $request->year;
		 }
		 if($request->delivered){
			$order_status= 3;
		 }
		 if($request->monthly_invoice){
			$invoice_type= 'monthly-invoice';
		 }
		 if($request->check_paid){
			$check_paid= $request->check_paid;
		 }
		 $paymenttypes=Finder::getPaymentMethods();
		 $dates   = Finder::getStartAndEndDate($week_no, $year);
		 $w_start =$dates['week_start'];
		 $w_end   =$dates['week_end'];
		 $start_d=date('Y-m-d', strtotime($w_start));
		 $end_d = date('Y-m-d', strtotime($w_end));
		 $customers_invoice_type =\Config::get('constants.REQUIRE_INVOICE_TYPE');
		 $getInvoices=Customer::join('orders','customers.id','=','orders.customer_id')
		                        ->join('order_details','orders.id','=','order_details.order_id')
                                        ->join('addresses','addresses.id','=','customers.default_address_id')
								->selectRaw('orders.id as order_id, customers.id, customers.invoice_street_address, customers.invoice_suburb, customers.invoice_city, customers.invoice_state, customers.invoice_postcode, customers.purchase_order_number, addresses.company_name, addresses.street_address, addresses.address_line_2, addresses.city, addresses.county_state, addresses.post_code, customers.company_tax_id, customers.customers_accountemail, customers.first_name, customers.last_name, customers.tel_num, customers.fax_num, customers.default_address_id, sum(orders.total) as inovice_total, orders.payment_method')
								->where(function($query) use($customers_invoice_type){
									$query->whereNull('customers.customers_require_invoice_type')
										  ->orWhere('customers.customers_require_invoice_type', '!=' ,$customers_invoice_type);
                                })
								->whereDate('order_details.shipdate','>=',$start_d)
								->whereDate('order_details.shipdate','<=',$end_d);
								if($order_status==3){
								$getInvoices=$getInvoices->where('orders.order_status_id','=',$order_status);
								}else{
								$getInvoices=$getInvoices->where('orders.order_status_id','!=',$order_status);	
								}
								if(!empty($invoice_type)){
								$getInvoices=$getInvoices->where('orders.payment_method','=',$invoice_type);
								}
                                                                
                                                               $getInvoices=$getInvoices->groupBy('customers.id')
		                        ->get();
         $invoiceidtmpArray = array();                                                       
           //print xml
        foreach($getInvoices as $invoice)  {

            $invoiceId=Finder::getInvoiceId($week_no,$year,$invoice->id);
          
            $invoiceidtmpArray[] = $invoiceId;

        }                                                          

      //echo "<pre>";
      ///print_r($invoiceidtmpArray);
      //die;
      
      $pagelist= 'cron_update_payment_of_invoice_weekly';
      
      require 'XeroOAuth-PHP-master/private.php';
		include 'XeroOAuth-PHP-master/tests/testRunner.php';
		$initialCheck = $XeroOAuth->diagnostics();
		$checkErrors = count($initialCheck);
		if ($checkErrors > 0) {
                    // you could handle any config errors here, or keep on truckin if you like to live dangerously
                    foreach ($initialCheck as $check) {
                    echo 'Error: ' . $check . PHP_EOL;
                    }
		} else {

                    $session = persistSession(array (
                    'oauth_token' => $XeroOAuth->config['consumer_key'],
                    'oauth_token_secret' => $XeroOAuth->config['shared_secret'],
                    'oauth_session_handle' => '' 
                    ) );
                    $oauthSession = retrieveSession();

                    if (isset( $oauthSession ['oauth_token'] )) {

                    $XeroOAuth->config['access_token'] = $oauthSession ['oauth_token'];
                    $XeroOAuth->config['access_token_secret'] = $oauthSession ['oauth_token_secret'];		
                    include 'XeroOAuth-PHP-master/tests/tests2.php';
                    }

                    //testLinks ();
		}

     
      
    }
	
    public function invoices_weekly_per_customer_paid_status(Request $request) {
            
            
        $week_no      = date("W");
	     $year         = date("Y");
	     $order_status = 5;
	     $invoice_type = '';
		 $check_paid   = 0;
		 if($request->week_number){
			$week_no= $request->week_number;
		 }
		 if($request->year){
			$year= $request->year;
		 }
		 if($request->delivered){
			$order_status= 3;
		 }
		 if($request->monthly_invoice){
			$invoice_type= 'monthly-invoice';
		 }
		 if($request->check_paid){
			$check_paid= $request->check_paid;
		 }
		 $paymenttypes=Finder::getPaymentMethods();
		 $dates   = Finder::getStartAndEndDate($week_no, $year);
		 $w_start =$dates['week_start'];
		 $w_end   =$dates['week_end'];
		 $start_d=date('Y-m-d', strtotime($w_start));
		 $end_d = date('Y-m-d', strtotime($w_end));
		 $customers_invoice_type =\Config::get('constants.REQUIRE_INVOICE_TYPE');
                 
                 $customer_id = $request->customer_id;
                $invoice_id=$week_no.''.$year.'cID'.$customer_id.'';
                 
		 $getInvoices=Customer::join('orders','customers.id','=','orders.customer_id')
		                        ->join('order_details','orders.id','=','order_details.order_id')
                                        ->join('addresses','addresses.id','=','customers.default_address_id')
								->selectRaw('orders.id as order_id, customers.id, customers.invoice_street_address, customers.invoice_suburb, customers.invoice_city, customers.invoice_state, customers.invoice_postcode, customers.purchase_order_number, addresses.company_name, addresses.street_address, addresses.address_line_2, addresses.city, addresses.county_state, addresses.post_code, customers.company_tax_id, customers.customers_accountemail, customers.first_name, customers.last_name, customers.tel_num, customers.fax_num, customers.default_address_id, sum(orders.total) as inovice_total, orders.payment_method')
								->where(function($query) use($customers_invoice_type){
									$query->whereNull('customers.customers_require_invoice_type')
										  ->orWhere('customers.customers_require_invoice_type', '!=' ,$customers_invoice_type);
                                })
								->whereDate('order_details.shipdate','>=',$start_d)
								->whereDate('order_details.shipdate','<=',$end_d);
								if($order_status==3){
								$getInvoices=$getInvoices->where('orders.order_status_id','=',$order_status);
								}else{
								$getInvoices=$getInvoices->where('orders.order_status_id','!=',$order_status);	
								}
								if(!empty($invoice_type)){
								$getInvoices=$getInvoices->where('orders.payment_method','=',$invoice_type);
								}
                                                                
                                                                if(!empty($customer_id)) {
                                                                    
                                                                    $getInvoices=$getInvoices->where('customers.id','=',$customer_id);
                                                                }
                                                                
								$getInvoices=$getInvoices->groupBy('customers.id')
		                        ->get();
         $invoiceidtmpArray = array();                                                       
           //print xml
        foreach($getInvoices as $invoice)  {

            $invoiceId=Finder::getInvoiceId($week_no,$year,$invoice->id);
          
            $invoiceidtmpArray[] = $invoiceId;

        }                                                          

     // echo "<pre>";
      //print_r($invoiceidtmpArray);
     // die;
      
      $pagelist= 'cron_update_payment_of_invoice_weekly';
      
      require 'XeroOAuth-PHP-master/private.php';
		include 'XeroOAuth-PHP-master/tests/testRunner.php';
		$initialCheck = $XeroOAuth->diagnostics();
		$checkErrors = count($initialCheck);
		if ($checkErrors > 0) {
                    // you could handle any config errors here, or keep on truckin if you like to live dangerously
                    foreach ($initialCheck as $check) {
                    echo 'Error: ' . $check . PHP_EOL;
                    }
		} else {

                    $session = persistSession(array (
                    'oauth_token' => $XeroOAuth->config['consumer_key'],
                    'oauth_token_secret' => $XeroOAuth->config['shared_secret'],
                    'oauth_session_handle' => '' 
                    ) );
                    $oauthSession = retrieveSession();

                    if (isset( $oauthSession ['oauth_token'] )) {

                    $XeroOAuth->config['access_token'] = $oauthSession ['oauth_token'];
                    $XeroOAuth->config['access_token_secret'] = $oauthSession ['oauth_token_secret'];		
                    include 'XeroOAuth-PHP-master/tests/tests2.php';
                    }

                    //testLinks ();
		}
    }
	
    public function invoices_monthly(Request $request) {
               
	     $month_no      = date("m");
	     $year         = date("Y");
	     $order_status = 5;
	     $invoice_type = '';
		 $check_paid   = 0;
		 if($request->month){
			$month_no= $request->month;
		 }
		 if($request->year){
			$year= $request->year;
		 }
		 if($request->delivered){
			$order_status= 3;
		 }
		 if($request->monthly_invoice){
			$invoice_type= 'monthly-invoice';
		 }
		 if($request->check_paid){
			$check_paid= $request->check_paid;
		 }
		 
		 $m_start = date('01-'.$month_no.'-'.$year);
		 $m_end   = date('t-'.$month_no.'-'.$year, strtotime($m_start));
		 $start_d = date('Y-m-d', strtotime($m_start));
		 $end_d   = date('Y-m-d', strtotime($m_end));
		 $customers_invoice_type =\Config::get('constants.REQUIRE_INVOICE_TYPE');
                 
                 $getInvoices=Customer::join('orders','customers.id','=','orders.customer_id')
		                        ->join('order_details','orders.id','=','order_details.order_id')
								->selectRaw('orders.id as order_id, customers.id, customers.first_name, customers.last_name, customers.default_address_id, sum(orders.total) as inovice_total, orders.payment_method')
								->where('customers.customers_require_invoice_type', '=' ,$customers_invoice_type)
								->whereDate('order_details.shipdate','>=',$start_d)
								->whereDate('order_details.shipdate','<=',$end_d);
								if($order_status==3){
								$getInvoices=$getInvoices->where('orders.order_status_id','=',$order_status);
								}else{
								$getInvoices=$getInvoices->where('orders.order_status_id','!=',$order_status);	
								}
								if(!empty($invoice_type)){
								$getInvoices=$getInvoices->where('orders.payment_method','=',$invoice_type);
								}
								$getInvoices=$getInvoices->groupBy('customers.id')
		                        ->get();
                                                                
                                                                
         //print xml
        foreach($getInvoices as $invoice)  {

            
            $invoiceId=Finder::getMonthlyInvoiceId($month_no,$year,$inovice->id);
           // echo $invoice->id; exit;


          $invoiceidtmp_custom_ffto = $invoiceId; // to avoid same invoice id on FFTO and FNV, we added ZERO (0) before invoice ID in FFTO.

                $xml_array[$invoiceId]="<Invoices>
          <Invoice>
            <Type>ACCREC</Type>
                <Contact>
              <ContactNumber>".$invoice->tel_num."</ContactNumber>
              <Name>".$invoice->company_name."</Name>
              <ContactStatus>ACTIVE</ContactStatus>
                  <TaxNumber>".$invoice->company_tax_id."</TaxNumber>
              <EmailAddress>".$invoice->customers_accountemail."</EmailAddress> 
              <FirstName>".$invoice->first_name."</FirstName>
              <LastName>".$invoice->last_name."</LastName>
              <DefaultCurrency>GBP</DefaultCurrency>
              <Addresses>
                <Address>
                  <AddressType>STREET</AddressType>
                  <AttentionTo>".$invoice->street_address."</AttentionTo>
                  <AddressLine1>".$invoice->street_address."</AddressLine1>
                  <AddressLine2>".$invoice->address_line_2."</AddressLine2>
                  <AddressLine3></AddressLine3>
                  <AddressLine4></AddressLine4>
                  <City>".$invoice->city."</City>
                  <Region>".$invoice->county_state."</Region>
                  <PostalCode>".$invoice->post_code."</PostalCode>
                  <Country>United Kingdom</Country>
                </Address>  
                <Address>
                  <AddressType>POBOX</AddressType>
                  <AttentionTo>".$invoice->invoice_street_address."</AttentionTo>
                  <AddressLine1>".$invoice->invoice_street_address."</AddressLine1>
                  <AddressLine2>".$invoice->invoice_suburb."</AddressLine2>
                  <AddressLine3></AddressLine3>
                  <AddressLine4></AddressLine4>
                  <City>".$invoice->invoice_city."</City>
                  <Region>".$invoice->invoice_state."</Region>
                  <PostalCode>".$invoice->invoice_postcode."</PostalCode>
                  <Country>United Kingdom</Country>
                </Address>		
              </Addresses>
              <Phones>
                <Phone>
                  <PhoneType>DEFAULT</PhoneType>
                  <PhoneNumber>".$invoice->tel_num."</PhoneNumber>          
                </Phone>        
                <Phone>
                  <PhoneType>FAX</PhoneType>
                  <PhoneNumber>".$invoice->fax_num."</PhoneNumber>         
                </Phone>        
              </Phones>
              <ContactPersons>
                  <ContactPerson>
                      <FirstName>".$invoice->first_name."</FirstName>
                      <LastName></LastName>
                      <EmailAddress>".$invoice->email."</EmailAddress>
                      <IncludeInEmails>true</IncludeInEmails>
                  </ContactPerson>
              </ContactPersons>
            </Contact>    
            <Date>".$invoice->paid_date."</Date>
            <DueDate>".$invoice->paid_date."</DueDate>
            <InvoiceNumber>".$invoiceidtmp_custom_ffto."</InvoiceNumber>
            <Reference>Po Number-".$invoice->purchase_order_number." fruitandveg-".$invoiceidtmp_custom_ffto."</Reference>
            <CurrencyCode>GBP</CurrencyCode>
            <Status>DRAFT</Status>   
            <SubTotal><![CDATA[".$invoice->inovice_total."]]></SubTotal>
            <TotalTax>0</TotalTax>
            <Total><![CDATA[".$invoice->inovice_total."]]></Total>    
            <LineItems>
              <LineItem>
                <Description>Payment - <![CDATA[".$invoice->payment_method."]]></Description>
                <Quantity>1</Quantity>
                <UnitAmount><![CDATA[".$invoice->inovice_total."]]></UnitAmount>
                <TaxType>OUTPUT</TaxType>
                <TaxAmount>0</TaxAmount>
                <LineAmount><![CDATA[".$invoice->inovice_total."]]></LineAmount>
                <AccountCode>200</AccountCode>        
              </LineItem>      
            </LineItems>
          </Invoice>
        </Invoices>";                                                   

        }                                                          

      //echo "<pre>";
      //print_r($xml_array);
      //die;
      
      $pagelist= 'import_monthly_invoices_require_monthly_type_customers';
        require 'XeroOAuth-PHP-master/private.php';
        include 'XeroOAuth-PHP-master/tests/testRunner.php';
        $initialCheck = $XeroOAuth->diagnostics ();
        $checkErrors = count ( $initialCheck );
        if ($checkErrors > 0) {
            // you could handle any config errors here, or keep on truckin if you like to live dangerously
            foreach ( $initialCheck as $check ) {
            echo 'Error: ' . $check . PHP_EOL;
            }
        } else {

            $session = persistSession ( array (
            'oauth_token' => $XeroOAuth->config ['consumer_key'],
            'oauth_token_secret' => $XeroOAuth->config ['shared_secret'],
            'oauth_session_handle' => '' 
            ) );
            $oauthSession = retrieveSession ();

            if (isset ( $oauthSession ['oauth_token'] )) {

            $XeroOAuth->config ['access_token'] = $oauthSession ['oauth_token'];
            $XeroOAuth->config ['access_token_secret'] = $oauthSession ['oauth_token_secret'];		
            include 'XeroOAuth-PHP-master/tests/import_monthly_invoices_require_monthly_type_customers.php';
            }

            //testLinks ();
        }   
                                                                
                                                                
                                                                
                                                                
    }
	
    public function invoices_monthly_per_customer(Request $request) {
            
         $month_no      = date("m");
	     $year         = date("Y");
		 $week_no      = date("W");
	     $order_status = 5;
	     $invoice_type = '';
		 $check_paid   = 0;
		 if($request->month){
			$month_no= $request->month;
		 }
		 if($request->year){
			$year= $request->year;
		 }
		 if($request->delivered){
			$order_status= 3;
		 }
		 if($request->monthly_invoice){
			$invoice_type= 'monthly-invoice';
		 }
		 if($request->check_paid){
			$check_paid= $request->check_paid;
		 }
		 
		 $m_start = date('01-'.$month_no.'-'.$year);
		 $m_end   = date('t-'.$month_no.'-'.$year, strtotime($m_start));
		 $start_d = date('Y-m-d', strtotime($m_start));
		 $end_d   = date('Y-m-d', strtotime($m_end));
		 $customers_invoice_type =\Config::get('constants.REQUIRE_INVOICE_TYPE');
                 
                 $customer_id = $request->customer_id;
                 $invoice_id=$week_no.''.$year.'cID'.$customer_id.'';
                 
		 $getInvoices=Customer::join('orders','customers.id','=','orders.customer_id')
		                        ->join('order_details','orders.id','=','order_details.order_id')
								->selectRaw('orders.id as order_id, customers.id, customers.first_name, customers.last_name, customers.default_address_id, sum(orders.total) as inovice_total, orders.payment_method')
								->where('customers.customers_require_invoice_type', '=' ,$customers_invoice_type)
								->whereDate('order_details.shipdate','>=',$start_d)
								->whereDate('order_details.shipdate','<=',$end_d);
								if($order_status==3){
								$getInvoices=$getInvoices->where('orders.order_status_id','=',$order_status);
								}else{
								$getInvoices=$getInvoices->where('orders.order_status_id','!=',$order_status);	
								}
								if(!empty($invoice_type)){
								$getInvoices=$getInvoices->where('orders.payment_method','=',$invoice_type);
								}
                                                                
                                                                if(!empty($customer_id)) {
                                                                    
                                                                    $getInvoices=$getInvoices->where('customers.id','=',$customer_id);
                                                                }
                                                                
								$getInvoices=$getInvoices->groupBy('customers.id')
		                        ->get();
                                                                
                                                                
               //print xml
        foreach($getInvoices as $invoice)  {

            
            $invoiceId=Finder::getMonthlyInvoiceId($month_no,$year,$invoice->id);
           // echo $invoice->id; exit;


          $invoiceidtmp_custom_ffto = $invoiceId; // to avoid same invoice id on FFTO and FNV, we added ZERO (0) before invoice ID in FFTO.

                $xml_array[$invoiceId]="<Invoices>
          <Invoice>
            <Type>ACCREC</Type>
                <Contact>
              <ContactNumber>".$invoice->tel_num."</ContactNumber>
              <Name>".$invoice->company_name."</Name>
              <ContactStatus>ACTIVE</ContactStatus>
                  <TaxNumber>".$invoice->company_tax_id."</TaxNumber>
              <EmailAddress>".$invoice->customers_accountemail."</EmailAddress> 
              <FirstName>".$invoice->first_name."</FirstName>
              <LastName>".$invoice->last_name."</LastName>
              <DefaultCurrency>GBP</DefaultCurrency>
              <Addresses>
                <Address>
                  <AddressType>STREET</AddressType>
                  <AttentionTo>".$invoice->street_address."</AttentionTo>
                  <AddressLine1>".$invoice->street_address."</AddressLine1>
                  <AddressLine2>".$invoice->address_line_2."</AddressLine2>
                  <AddressLine3></AddressLine3>
                  <AddressLine4></AddressLine4>
                  <City>".$invoice->city."</City>
                  <Region>".$invoice->county_state."</Region>
                  <PostalCode>".$invoice->post_code."</PostalCode>
                  <Country>United Kingdom</Country>
                </Address>  
                <Address>
                  <AddressType>POBOX</AddressType>
                  <AttentionTo>".$invoice->invoice_street_address."</AttentionTo>
                  <AddressLine1>".$invoice->invoice_street_address."</AddressLine1>
                  <AddressLine2>".$invoice->invoice_suburb."</AddressLine2>
                  <AddressLine3></AddressLine3>
                  <AddressLine4></AddressLine4>
                  <City>".$invoice->invoice_city."</City>
                  <Region>".$invoice->invoice_state."</Region>
                  <PostalCode>".$invoice->invoice_postcode."</PostalCode>
                  <Country>United Kingdom</Country>
                </Address>		
              </Addresses>
              <Phones>
                <Phone>
                  <PhoneType>DEFAULT</PhoneType>
                  <PhoneNumber>".$invoice->tel_num."</PhoneNumber>          
                </Phone>        
                <Phone>
                  <PhoneType>FAX</PhoneType>
                  <PhoneNumber>".$invoice->fax_num."</PhoneNumber>         
                </Phone>        
              </Phones>
              <ContactPersons>
                  <ContactPerson>
                      <FirstName>".$invoice->first_name."</FirstName>
                      <LastName></LastName>
                      <EmailAddress>".$invoice->email."</EmailAddress>
                      <IncludeInEmails>true</IncludeInEmails>
                  </ContactPerson>
              </ContactPersons>
            </Contact>    
            <Date>".$invoice->paid_date."</Date>
            <DueDate>".$invoice->paid_date."</DueDate>
            <InvoiceNumber>".$invoiceidtmp_custom_ffto."</InvoiceNumber>
            <Reference>Po Number-".$invoice->purchase_order_number." fruitandveg-".$invoiceidtmp_custom_ffto."</Reference>
            <CurrencyCode>GBP</CurrencyCode>
            <Status>DRAFT</Status>   
            <SubTotal><![CDATA[".$invoice->inovice_total."]]></SubTotal>
            <TotalTax>0</TotalTax>
            <Total><![CDATA[".$invoice->inovice_total."]]></Total>    
            <LineItems>
              <LineItem>
                <Description>Payment - <![CDATA[".$invoice->payment_method."]]></Description>
                <Quantity>1</Quantity>
                <UnitAmount><![CDATA[".$invoice->inovice_total."]]></UnitAmount>
                <TaxType>OUTPUT</TaxType>
                <TaxAmount>0</TaxAmount>
                <LineAmount><![CDATA[".$invoice->inovice_total."]]></LineAmount>
                <AccountCode>200</AccountCode>        
              </LineItem>      
            </LineItems>
          </Invoice>
        </Invoices>";                                                   

        }                                                          

      //echo "<pre>";
      //print_r($xml_array);
      //die;
      
      $pagelist= 'import_monthly_invoices_require_monthly_type_customers';
      
        require 'XeroOAuth-PHP-master/private.php';
        include 'XeroOAuth-PHP-master/tests/testRunner.php';
        $initialCheck = $XeroOAuth->diagnostics ();
        $checkErrors = count ( $initialCheck );
        if ($checkErrors > 0) {
            // you could handle any config errors here, or keep on truckin if you like to live dangerously
            foreach ( $initialCheck as $check ) {
            echo 'Error: ' . $check . PHP_EOL;
            }
        } else {

            $session = persistSession ( array (
            'oauth_token' => $XeroOAuth->config ['consumer_key'],
            'oauth_token_secret' => $XeroOAuth->config ['shared_secret'],
            'oauth_session_handle' => '' 
            ) );
            $oauthSession = retrieveSession ();

            if (isset ( $oauthSession ['oauth_token'] )) {

            $XeroOAuth->config ['access_token'] = $oauthSession ['oauth_token'];
            $XeroOAuth->config ['access_token_secret'] = $oauthSession ['oauth_token_secret'];		
            include 'XeroOAuth-PHP-master/tests/import_monthly_invoices_require_monthly_type_customers.php';
            }

            //testLinks ();
        }                                                 
                                                                
                                                                
                                                                
                                                                
    }
    

}
