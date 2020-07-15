<?php

return [
	'REQUIRE_INVOICE_TYPE' => 'Monthly',
	'RECORDS_PER_PAGE' => env('RECORDS_PER_PAGE', 50),
	'NO_RECORD_FOUND' => 'No Record Found',
	'DEFAULT_MINIMUM_ORDER' => env('DEFAULT_MINIMUM_ORDER', 50),
	'TOTAL_DELIVERY_DAYS' => env('TOTAL_DELIVERY_DAYS', 5),
	'INVOICE_STATUS' => array(1=>'UNPAID', 2=>'PAID', 3=>'BAD DEBT'),
	'PAYMENT_METHODS' => array('monthly-invoice'=>'Monthly Invoice', 'secpay'=>'Credit Card : SECPay', 'cheque'=>'Cheque', 'bacs'=>'Paypal'),
	'MONTHS' =>array(1=>'January', 2=>'February', 3=>'March', 4=>'April', 5=>'May', 6=>'June', 7=>'July', 8=>'August', 9=>'September', 10=>'October', 11=>'November', 12=>'December'),
	'DISCOUNT_TYPES' => array(1=>'Coupon', 2=>'Quality', 3=>'Non-Charge of Carriage', 4=>'Loyalty Discount', 5 => 'Missing item'),
	'NatureOfIssue' => array(0 => 'Select Nature Of Issue', 1 => 'Product Missing',2 => 'Complete Order Missing',3 => 'Quality Issue',4 => 'Late Delivery',5 => 'Wrong Order',6 => 'Miscellaneous',7 => 'Order Stolen',8 => 'Incorrect Address',9 => 'Order Left In Wrong place'),

	'LossType' => array(0 => 'Select Loss Type', 1 => 'N/A',2 => 'Re-Delivery',3 => 'Refund',4 => 'Discount on Month End',5 => 'Discount on Next order',6 => 'Miscellaneous',7 => 'Voucher Code',8 => 'Lost Client Completely'),

	'LeadStatusMost' => array(1 => 'Pending',2 => 'Sold',3 => 'Not Sold'),

	'LeadStatus' => array(1 => 'Pending',2 => 'Sold',3 => 'Not Sold',4 => 'Sign Up No Order',5 => 'No Order 14 Days',6 => 'SHOW CLOSE LEADS'),

	'Hear_About_Us' => array('Google' => 'Google','Referral' => 'Referral','Magazine' => 'Magazine','Television' => 'Television','Newspaper' => 'Newspaper','OurDeliveryVans' => 'Our Delivery Vans','Radio' => 'Radio','Flyer' => 'Flyer','Charity_Run' => 'Charity Run','Other' => 'Other'),
	'MILK_PRODUCT_CATEGORIES' => array(20,21,22,23,24,25,27,28,29),
	'PREP_PRODUCE_PRODUCTS_ARRAY' => array('1246','1715','1810','1929','1251','1244','1245','1243','1963'),
	'FNV_CATEGORY_ARRAY' => array('1','6','10','16'), //Fruit and Veg: Category - VEGETABLES, FRUIT, SALAD, MUSHROOMS
	'OTHER_ITEMS_CATEGORY_ARRAY' => array('46','40','19','17','31','44'), // Other Items: Categories - FRESH FRUIT JUICE,	DRIED FRUIT & NUTS,	'HERBS, EDIBLE FLOWERS & SPICES',	'DAIRY, BREAD, JUICES & WATER',	FROZEN PRODUCE,	OILS
	
	
	'google' => [
		'captcha' => [
			/*** version 3 ****/
			
			//'site_key' => '6Lc9iccUAAAAACBd_o1YCx4Bjy8qKOM42sIrIflu',
			//'secret_key' => '6Lc9iccUAAAAAJze0k-KaTb0uL2arGkpfHnzo4o5',
			
			/*** version 2 ***/
			
			'site_key' => '6LcSi8cUAAAAAFVMHjsxzqX2-BqPzGC6NTNoNvaz',
			'secret_key' => '6LcSi8cUAAAAAB4ZHsAQr4KeAmD6bvWwAQQvtr5X',
		],
		'api_key' => 'AIzaSyAODPMcF0mpfCpcioDzw910r2R-pZ-A0hM',
	],
	
	'address' => [
		'latitude' => '51.572460',
		'longitude' => '0.183920',
	],
	
	'order_delivered_id' => 3,
	
	'country_id' => 222,
	
	'pay360' => [
		/* Mukesh sir details */
		
		'username' => 'XV7TVP3HO5HMBE7KNKA2RHBBDQ',
		'password' => 'TcI0sF84+0lpGtCLtC7GcA==',
		'publishable_key' => '5WHxbKXKRpm_VV3ZqhR92g',
		'installation_key' => '5305657',
		//'installation_key' => '5305656',
		
		/* Jitendra Details */
		
		// 'username' => '2CZ7MMPICFGU5KXJN76H7OKLGI',
		// 'password' => 'htKp6y1+j3vTyw3pb97cwQ==',
		// 'publishable_key' => '5WHxbKXKRpm_VV3ZqhR92g',
		// 'CardLock_ID' => 'qIofl7FaQmSEMtFO2RsjtQ',
		// 'installation_key' => '5305890',
		
		'transaction_env' => 'Test',
	],
	
	'paypal' => [
		'transaction_env' => 'test',
		'sandbox_url' => 'https://api.sandbox.paypal.com/',
		'live_url' => 'https://api.paypal.com/',
		'clientId' => 'AQklJAoBs5JcYuPGGEncu8pJ7HH8YTT8RDgvBJRjOReg8t38Vi3U9ZDNwg4pH1Uueci3S2IAEnDp8Unm',
		'secret' => 'EM8nIggxflxetVnOa39yLyNiCYhquXKWjPCxtFilkk3R1nLJHeFyfsajlHko8pVdmQNZf5dAiifgVWaj',
	],
	
	'week_days' => [
		1 => 'Monday',
		2 => 'Tuesday',
		3 => 'Wednesday',
		4 => 'Thursday',
		5 => 'Friday',
		6 => 'Saturday',
		7 => 'Sunday',
	],
	'week_days_short_name' => [
		1 => 'Mon',
		2 => 'Tues',
		3 => 'Wed',
		4 => 'Thur',
		5 => 'Fri',
		6 => 'Sat',
		7 => 'Sun',
	],
	
	'card_type' => [
		'VISA' => ['ELECTRON', 'VISA_DEBIT', 'VISA_CREDIT'],
		'MASTERCARD' => ['MAESTRO', 'MC_DEBIT', 'SWITCH', 'SOLO', 'MC_CREDIT'],
		'AMEX' => ['AMEX'],
		'JCB' => ['JCB'],
		'LASER' => ['LASER'],
		'DISCOVER' => ['DISCOVER'],
		'DINERS' => ['DINERS'],
	],
	
	'dashboard_sections' => [
		'top_section' => 4,
		'quality' => 5,
		'easy_order' => 6,
		'happy_customer' => 7,
		'deliver_to' => 8,
	],
	'delivery_window_options' => [
		'8:30am__09:30am' => '8:30am - 9:30am',
		'9:30am__10:30am' => '9:30am - 10:30am',
		'10:30am__11:30am' => '10:30am - 11:30am',
		'11:30am__12:30pm' => '11:30am - 12:30pm',
		'12:30pm__1:30pm' => '12:30pm - 1:30pm',
		'1:30pm__2:30pm' => '1:30pm - 2:30pm',
		'2:30pm__3:30pm' => '2:30pm - 3:30pm',
		'3:30pm__4:30pm' => '3:30pm - 4:30pm',
		'4:30pm__5:30pm' => '4:30pm - 5:30pm',
		'5:30pm__6:30pm' => '5:30pm - 6:30pm',
		'6:30pm__7:30pm' => '6:30pm - 7:30pm',
		'7:30pm__8:30pm' => '7:30pm - 8:30pm',
	],
];

