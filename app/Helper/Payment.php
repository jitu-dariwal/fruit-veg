<?php

namespace App\Helper;

use Auth;
use DateTime;
use DB;
use App\Shop\Customers\CustomerFavouriteProduct;

/**
 *------------------------------------------------------------------
 *  Class Finder
 *------------------------------------------------------------------
 *  Description: This class is used for defining some common functions
 *  used in the project.
 *
 *  @author <>
 */
class Payment
{
	/**
     * Make mask of card number
     *
     * @param  string $card_number
     * @return string of mask card_number
     */
	public static function cc_mask($card_number){
		$len = strlen($card_number);
		return $number_filtered 	= substr_replace($card_number, str_repeat("*", $len-10), 6, $len-10);
	}
	
	/**
     * Before process payment set configration variables and other payment variables
     *
     * @param  decimal $number
     * @return boolean true/false
     */
    public static function pay360($details, $order, $cardId = null){
		
		/* $res['status'] = false;
		$res['message'] = 'Payment is not processed successful, Something went wrong, please try again or check card details.<br/>Error : asdasdfj auhsduash ';
		return $res;die; */
		
		$secret_key = config('constants.pay360.username').':'.config('constants.pay360.password');
		$installation_key = config('constants.pay360.installation_key');
		$publishable_key = config('constants.pay360.publishable_key');
		$transation_server = config('constants.pay360.transaction_env');
		
		if($transation_server == 'Live'){	
			$payment_url = "https://api.pay360.com/acceptor/rest/transactions/".$installation_key."/payment";
			
			$Pay360Tokan_url 	= "https://api.pay360.com/cardlock/createToken";
		}else{
			$payment_url = "https://api.mite.pay360.com/acceptor/rest/transactions/".$installation_key."/payment"; 
			
			$Pay360Tokan_url 	= "https://api.mite.pay360.com/cardlock/createToken";
		}
		
		if ( $cardId == null ) {
			$name 			= $details['name'];
			$card_number 	= $details['number'];
			$exp_month 		= sprintf('%02d', $details['exp_month']);
			$exp_year 		= substr($details['exp_year'],2,2);
			$expiryDate = $exp_month.$exp_year;
			$cvc 			= $details['cvv'];
		} else {
			$card = DB::table('customers_pay360_tokens')->where(['id' => $cardId])->first();
			
			$name 			= $card->card_holder_name;
			$card_number 	= $card->pay360_token;
			$expiryDate     = $card->expiry_date;
			$cvc 			= $details['cvv_'.$details['payBySaveCard']];
		}
		
		$data = array(		
			"publishableId"=> $publishable_key,
			"pan"=>  $card_number,
			"cvv"=> $cvc		
		);
		
		$payload = json_encode($data);
		$pay360_token_result = json_decode( (new self)->createPay360Tokan($Pay360Tokan_url, $payload), true );
		
		if(array_key_exists("token",$pay360_token_result)){
			$pay360Token = $pay360_token_result["token"];			
		}else{
			$res['status'] = false;
			$res['message'] = 'Payment is not processed successful, Something went wrong, please try again or check card details.<br/>Error : '.$pay360_token_result['message'];
			return $res;
		}
		
		$cardarr = [
			"cardLockToken"=> $pay360Token,
			"expiryDate"=> $expiryDate,
			"cardHolderName"=> $name,
			"defaultCard"=> "false",
		];
		
		$totalOrderValue = $order->total;
		
		$data = [
			"transaction"=> [
				"currency"=> config('cart.currency'),
				"amount"=> $totalOrderValue,
				"description"=> "Order Type - ".$order->orderDetail->comment,
				"merchantRef"=> $order->customer->id,
				"commerceType"=> "MOTO",
				"channel"=> "MOTO",
			],
			"paymentMethod"=> [
				"card"=> $cardarr,
				"registered" => true,
				"billingAddress"=> [
					"line1"=> $order->orderDetail->billing_street_address,
					"line2"=> $order->orderDetail->billing_address_line_2,
					"line3"=> "",
					"line4"=> "",
					"city"=> $order->orderDetail->billing_city,
					"region"=> $order->orderDetail->billing_state,
					"postcode"=> $order->orderDetail->billing_postcode,
					"countryCode"=> "UK"
				]
			],
			"customer"=> [
				"merchantRef"=> $order->customer->id,
				"displayName"=> $order->orderDetail->billing_name,
				"billingAddress"=> [
					"line1"=> $order->orderDetail->billing_street_address,
					"line2"=> $order->orderDetail->billing_address_line_2,
					"line3"=> "",
					"line4"=> "",
					"city"=> $order->orderDetail->billing_city,
					"region"=> $order->orderDetail->billing_state,
					"postcode"=> $order->orderDetail->billing_postcode,
					"countryCode"=> "UK"
				],
				"email"=> $order->customer->email,
				"telephone"=> $order->customer->tel_num,
				"defaultCurrency"=> "GBP"
			]
		];
		
		$payload = json_encode($data);
		$pay360_result = json_decode( (new self)->sendTransactionToGateway($payment_url, $payload), true);
		
		$res = [];
		
		if($pay360_result['outcome']['status'] != 'SUCCESS'){
			$res['status'] = false;
			$res['message'] = 'Payment is not processed successful, Something went wrong, please try again or check card details.<br/>Error : '.$pay360_result['outcome']['reasonMessage'];
		}else{
			$res['status'] = true;
			$res['transactionId'] = $pay360_result['transaction']['transactionId'];
			$res['message'] = 'Payment is processed successful.';
			
			if(array_key_exists('save_card', $details) && $details['save_card'] == 'on'){
				$card = DB::table('customers_pay360_tokens')->where(['customers_id' => Auth()->User()->id, 'pay360_token' => $card_number])->first();
				
				if(empty($card)){
					$dataArr = [];
					$dataArr['customers_id'] = $order->customer->id;
					$dataArr['card_holder_name'] = $name;
					$dataArr['pay360_token'] = $card_number;
					$dataArr['number_filtered'] = $pay360_result['paymentMethod']['card']['maskedPan'];
					$dataArr['card_type'] = $pay360_result['paymentMethod']['card']['cardType'];
					$dataArr['expiry_date'] = $pay360_result['paymentMethod']['card']['expiryDate'];
					$dataArr['date_added'] = date('Y-m-d H:i:s');
				
					$res['payment_card_id'] = DB::table('customers_pay360_tokens')->insert($dataArr);
				}else{
					DB::table('customers_pay360_tokens')->where(['id' => $card->id])->update([
						'card_holder_name' => $name,
						'number_filtered' => $pay360_result['paymentMethod']['card']['maskedPan'],
						'card_type' => $pay360_result['paymentMethod']['card']['cardType'],
						'expiry_date' => $pay360_result['paymentMethod']['card']['expiryDate'],
					]);
					
					$res['payment_card_id'] = $card->id;
				}
			}else if ( $cardId != null ) {
				$res['payment_card_id'] = $cardId;
			}
		}
		
		return $res;
    }
	
	private function createPay360Tokan($tokanUrl, $payload){
		
		$secret_key = config('constants.pay360.username').':'.config('constants.pay360.password');
		
		// Prepare new cURL resource
		$ch = curl_init($tokanUrl);
		curl_setopt($ch, CURLOPT_USERPWD,$secret_key);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

		// Set HTTP Header for POST request 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($payload))
		);

		// Submit the POST request
		$result = curl_exec($ch);		
		// Close cURL session handle
		curl_close($ch);
		return $result;	
	}
	
	private function sendTransactionToGateway($url, $payload = null, $curl_opts = []) {
		$secret_key = config('constants.pay360.username').':'.config('constants.pay360.password');
		// Prepare new cURL resource
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERPWD, $secret_key);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

		// Set HTTP Header for POST request 
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json',
			'Content-Length: ' . strlen($payload)
		]);

		// Submit the POST request
		$result = curl_exec($ch);	
		// Close cURL session handle
		curl_close($ch); 

		return $result;
    }
	
	public static function getCardDetails($cardNumber = null){
		$secret_key = config('constants.pay360.username').':'.config('constants.pay360.password');
		
		if(config('constants.pay360.transaction_env') == 'Live'){	
			$url 	= "https://api.pay360.com/acceptor/rest/cardinfo/".config('constants.pay360.installation_key');
			
			$Pay360Tokan_url 	= "https://api.pay360.com/cardlock/createToken";
		}else{
			$url 	= "https://api.mite.pay360.com/acceptor/rest/cardinfo/".config('constants.pay360.installation_key');
			
			$Pay360Tokan_url 	= "https://api.mite.pay360.com/acceptor/rest/authorisation/". config('constants.pay360.installation_key') ."/authoriseClient";
		}
		
		$data = array("scopes" => ["CARDINFO"]);
		
		$payload = json_encode($data);
		
		$pay360_token_result = json_decode( (new self)->createPay360Tokan($Pay360Tokan_url, $payload), true );
		//pr($pay360_token_result);die;
		
		$payload = json_encode(array('pan' => $cardNumber));
		
		// Prepare new cURL resource
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERPWD,$secret_key);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

		// Set HTTP Header for POST request 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Bearer ',
			'Content-Type: application/json',
			'Content-Length: ' . strlen($payload))
		);

		// Submit the POST request
		$result = curl_exec($ch);		
		// Close cURL session handle
		curl_close($ch);
		
		//echo "jitu";
		//pr($result);die;
		pr(json_decode($result, true));die;
		
		return $result;
	}
	
	public static function getCardDetailsFromServer($cardNumber = null){
		$secret_key = config('constants.pay360.username').':'.config('constants.pay360.password');
		
		if(config('constants.pay360.transaction_env') == 'Live'){	
			$url 	= "https://api.pay360.com/acceptor/rest/cardinfo/".config('constants.pay360.installation_key');
		}else{
			$url 	= "https://api.mite.pay360.com/acceptor/rest/cardinfo/".config('constants.pay360.installation_key');
		}
		
		$payload = json_encode(array('pan' => $cardNumber));
		
		/* echo $payload;
		echo $url;
		exit; */
		
		// Prepare new cURL resource
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERPWD,$secret_key);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

		// Set HTTP Header for POST request 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				//'Authorization: Bearer ',
				'Content-Type: application/json',
				//'Content-Length: ' . strlen($payload)
			)
		);

		// Submit the POST request
		$result = curl_exec($ch);		
		// Close cURL session handle
		curl_close($ch);
		
		//echo "jitu";
		//pr($result);die;
		//pr(json_decode($result, true));die;
		
		return $result;
	}
	
	public static function getPayPalAccessToken(){
		$clientId = config('constants.paypal.clientId');
		$secret = config('constants.paypal.secret');
		$transation_server = config('constants.paypal.transaction_env');
		
		if($transation_server == 'live'){	
			$url 	= config('constants.paypal.live_url')."v1/oauth2/token";
		}else{
			$url 	= config('constants.paypal.sandbox_url')."v1/oauth2/token";
		}
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_USERPWD, $clientId.':'.$secret);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
		
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		
		// Set HTTP Header for POST request 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Accept: application/json',
				'content-type: application/x-www-form-urlencoded',
			)
		);
		
		$authAccess=curl_exec ($ch);
		//$information = curl_getinfo($ch, CURLINFO_HEADER_OUT);
		//return $information;
		curl_close ($ch);
		
		$authAccess = json_decode($authAccess, true);
		
		if(array_key_exists('access_token',$authAccess))
			return ['status' => true, 'access_token' => $authAccess['access_token']];
		else
			return ['status' => false, 'name' => $authAccess['name'], 'message' => $authAccess['message']];
	}
	
	public static function getPayPalTransaction($transaction_id = null){
		if($transaction_id != null){
			$authAccess = (new self)->getPayPalAccessToken();
			
			if($authAccess['status']){
				//pr($authAccess);die;
				$transation_server = config('constants.paypal.transaction_env');
		
				if($transation_server == 'live'){	
					$url = 'https://api.paypal.com/v1/reporting/transactions?start_date='.date("Y-m-d", strtotime('-30 days')).'T00:00:00-0700&end_date='.date('Y-m-d').'T23:59:59-0700&transaction_id='.$transaction_id.'&fields=all&page_size=100&page=1';
				}else{
					$url = 'https://api.sandbox.paypal.com/v1/reporting/transactions?start_date='.date("Y-m-d", strtotime('-30 days')).'T00:00:00-0700&end_date='.date('Y-m-d').'T23:59:59-0700&transaction_id='.$transaction_id.'&fields=all&page_size=100&page=1';
				}
				
				// Prepare new cURL resource
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLINFO_HEADER_OUT, true);
				
				// Set HTTP Header for POST request 
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						'Content-Type: application/json',
						'Authorization: Bearer '. $authAccess['access_token'] 
					)
				);

				// Submit the POST request
				$result = curl_exec($ch);		
				// Close cURL session handle
				curl_close($ch);
				
				$details = json_decode($result, true);
		
				if(array_key_exists('transaction_details',$details))
					return ['status' => true, 'details' => $details];
				else
					return ['status' => false, 'name' => $details['name'], 'message' => $details['message']];
			}else{
				return $authAccess;
			}
		}else{
			return ['status' => false, 'name' => 'transaction_id', 'message' => 'Please provide valid transaction id.'];
		}
	}
}
