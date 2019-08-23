<?php

/*
*
	##
	## OKEX PHP SDK PACKAGE
	##
	// Copyright (c) 2019 Executium LTD (support@executium.com)
	//
	// Permission is hereby granted, free of charge, to any person obtaining a
	// copy of this software and/or associated documentation files (the
	// "Materials"), to deal in the Materials without restriction, including
	// without limitation the rights to use, copy, modify, merge, publish,
	// distribute, sublicense, and/or sell copies of the Materials, and to
	// permit persons to whom the Materials are furnished to do so, subject to
	// the following conditions:
	//
	// The above copyright notice and this permission notice shall be included
	// in all copies or substantial portions of the Materials.
	//
	// THE MATERIALS ARE PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
	// EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
	// MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
	// IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
	// CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
	// TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
	// MATERIALS OR THE USE OR OTHER DEALINGS IN THE MATERIALS.
*
*/
	##/
	require 'okex.class.php';

	##/
	define("OKEX_KEY","YOUR-KEY");
	define("OKEX_SECRET","YOUR-SECRET");
	define("OKEX_PASSPHRASE","YOUR-PASSPHRASE");

	##/
	$okexAPI = new okexAPI(OKEX_KEY,OKEX_SECRET,OKEX_PASSPHRASE);

	##/ A list without all of the comments to begin with.


		##/ Last Traded, Default 20
		$output = $okexAPI->trades('BTC-USDT',20);
		print_r($output);

		##/ Last traded price, 24hr volume, for all trading volumes
		//$output = $okexAPI->ticker('BTC-USDT');
		//print_r($output);

		##/ Snapshot of market data from OKEX
		//$output = $okexAPI->token_pair_details();
		//print_r($output);

		##/ OKEX ORDERBOOK
		//$output = $okexAPI->order_book('BTC-USDT',5,0.2);
		//print_r($output);

		##/ OKEX BTC DEPOSIT ADDRESS
		//$output = $okexAPI->deposit_address_btc();
		//print_r($output);

		##/ BUY LIMIT on OKEX
		//$output = $okexAPI->order_limit('buy','BTC-USDT',9001,0.001,10);
		//print_r($output);

		##/ SELL LIMIT on OKEX
		//$output = $okexAPI->order_limit('sell','BTC-USDT',11900,0.001,10);
		//print_r($output);

		##/ ORDER INFORMATION on OKEX
		//$output = $okexAPI->order_information('3390549023331328','BTC-USDT');
		//print_r($output);

		##/ CANCEL ORDER on OKEX
		//$output = $okexAPI->order_cancel(3394063533169664,'BTC-USDT');
		//print_r($output);

	/*

		QUERY ANYTHING - Access any action
		https://www.okex.com/docs/en

	*/

	##/ Query Anything - Consult the documentation and take the path given
	##/ Always note if there is parameters to be added or not, if you add parameters when there is no requirement for any your query will fail.

	//$output = $okexAPI->query('/api/account/v3/deposit/address?currency=btc',array());
	//print_r($output);

	/*
		We have included some easy functions you may require that are cleaner
		This is by no means comprehensive as it is much easier to consult the documentation provided by OKEX and make use of the query() function
	*/

	##/ We have included some easy functions you may require that are cleaner
	//$output = $okexAPI->deposit_address_btc();
	//print_r($output);


	##/ When you place an order, OKEX will just let you know if the order failed or not, if you want the true status of how it sits with regards to filling, avg price etc you will need to query the order.

	##/ As of 23rd August 2019, the minimum order size is 0.001 for BTCUSDT
	##/ The notional issue, you will also need to provide the equivilent value for a side
	//$output = $okexAPI->order_market('buy','BTC-USDT',0,11);
	//print_r($output);

	//$output = $okexAPI->order_market('sell','BTC-USDT',0.001,0);
	//print_r($output);

	/*
		##/ IF you get the following error

		Array
		(
		    [client_oid] =>
		    [error_code] => 30024
		    [error_message] => Parameter value filling error
		    [order_id] => -1
		    [result] =>
		)

		##/ You need to set the notional to the BTC equivilant, for example, if the market price of BTC-USDT is 10,000 then 0.001 BTC is equal to 10 USD, 10,000*0.001=10 this should be set in the notional

    */

	##/ BUY LIMIT on OKEX
	//$output = $okexAPI->order_limit('buy','BTC-USDT',9001,0.001,10);
	//print_r($output);

	##/ SELL LIMIT on OKEX
	//$output = $okexAPI->order_limit('sell','BTC-USDT',11900,0.001,10);
	//print_r($output);


	##/ It is important to store your orderID and Instrument used when querying your order

	//$output = $okexAPI->order_information('1234567890','BTC-USDT');
	//print_r($output);

	/*
		##/ Example of order information output
		Array
		(
		    [client_oid] =>
		    [created_at] => 2019-08-22T11:01:12.366Z
		    [filled_notional] => 10.0146
		    [filled_size] => 0.001
		    [funds] =>
		    [instrument_id] => BTC-USDT
		    [notional] =>
		    [order_id] => 1234567890
		    [order_type] => 0
		    [price] => 10014.6
		    [price_avg] => 10014.6
		    [product_id] => BTC-USDT
		    [side] => buy
		    [size] => 0.001
		    [state] => 2
		    [status] => filled
		    [timestamp] => 2019-08-22T11:01:12.366Z
		    [type] => limit
		)
	*/

	##/ CANCEL ORDER on OKEX
	//$output = $okexAPI->order_cancel(1234567890,'BTC-USDT');
	//print_r($output);


