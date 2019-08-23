<?php

class okexAPIException extends \ErrorException {};

class okexAPI
{
    protected $key;         // API Key
    protected $secret;      // API Secret
    protected $passphrase;  // API PassPhrase
    protected $url;         // API base URL
    protected $locale;      // Language
    protected $getinfo;     // Additional connection information
    protected $curl;        // CURL handle

    function __construct($key, $secret, $passphrase, $getinfo=false, $url='https://www.okex.com', $locale='en_US', $sslverify=true, $timeout=10000)
    {
		//
        $this->key = $key;
        $this->secret= ($secret);
        $this->passphrase = $passphrase;
        $this->url = $url;
        $this->locale = $locale;
        $this->getinfo = $getinfo;
        $this->curl = curl_init();

        curl_setopt_array($this->curl, array
        (
            CURLOPT_USERAGENT => 'executium OKEX PHP-SDK',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => $sslverify,
			CURLOPT_TIMEOUT_MS=> 100
        )
        );

    }

    function __destruct()
    {
        curl_close($this->curl);
    }

    function epoch()
    {
        return substr(microtime(true),0,-1);
    }

    function deposit_address_btc()
    {
        return $this->query('/api/account/v3/deposit/address?currency=btc',array());
    }

    function order_information($orderID,$instrumentID)
    {
        return $this->query('/api/spot/v3/orders/'.$orderID.'?instrument_id='.$instrumentID,array());
    }

    function order_market($side,$instrument,$size,$notional)
    {
		$arr=array('type'=>'market','side'=>$side,'instrument_id'=>$instrument,'size'=>$size,'notional'=>$notional);
		##/
		return $this->query('/api/spot/v3/orders',$arr);
    }

    function order_cancel($orderID,$instrument)
    {
		##/
		return $this->query('/api/spot/v3/cancel_orders/'.$orderID,array('instrument_id'=>$instrument));
    }

    function order_limit($side,$instrument,$price,$size,$notional)
    {
		$arr=array('type'=>'limit','side'=>$side,'price'=>$price,'instrument_id'=>$instrument,'size'=>$size,'notional'=>$notional);
		##/
		return $this->query('/api/spot/v3/orders',$arr);
    }

    function order_book($instrument,$size=5,$depth=0.2)
    {
		##/
		return $this->query('/api/spot/v3/instruments/'.$instrument.'/book?size='.$size.'&depth='.$depth,array());
    }

    function token_pair_details()
    {
		##/
		return $this->query('/api/spot/v3/instruments',array());
    }

    function ticker($instrument)
    {
		##/
		return $this->query('/api/spot/v3/instruments/'.$instrument.'/ticker',array());
    }

    function trades($instrument,$limit=20)
    {
		##/
		return $this->query('/api/spot/v3/instruments/'.$instrument.'/trades?limit='.$limit,array());
    }

    function sign($timestamp,$method,$path,$json,$secret)
    {
		##/
        $sign=hash_hmac('sha256',($timestamp.$method.$path.$json),$secret,true);
		##/
        return base64_encode($sign);
    }

    function query($path, array $request = array())
    {
        try
        {
	        ##/
	        $method='GET'; $json='';

			/*
				##/
				Assigning the correct METHOD is essential to OKEX working, if you send a query with parameters when its not required you will get a very confusing 405, but this just means that you sent data that wasn't required.
			*/

			##/ Encode array to JSON
	        if(count($request)>0)
	        {
	            ##/
		        $json = json_encode($request);
		        ##/
		        $method='POST';
	        }

	        ##/ Timestamp - Epoch
			$timestamp = $this->epoch();

			##/
	        $headers = array
	        (
				"Content-Type: application/json; charset=UTF-8",
				"Cookie: locale=".$this->locale,
			    'Content-Length: '.strlen($json),
	            'OK-ACCESS-KEY: '.$this->key,
	            'OK-ACCESS-SIGN: '.$this->sign($timestamp,$method,$path,$json,$this->secret),
	            'OK-ACCESS-TIMESTAMP: '.$timestamp,
	            'OK-ACCESS-PASSPHRASE: '.$this->passphrase,
	        );

	        // make request
	        curl_setopt($this->curl, CURLOPT_URL, $this->url . $path);
	        curl_setopt($this->curl, CURLINFO_HEADER_OUT, false);
	        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);

	        ##/
	        if($method=='POST')
	        {
	            ##/
		        curl_setopt($this->curl, CURLOPT_POST, true);
				curl_setopt($this->curl, CURLOPT_POSTFIELDS, $json);
	        }
	        else
	        {
	            ##/
		        curl_setopt($this->curl, CURLOPT_POST, false);
	        }

	        ##/
	        $result = json_decode(curl_exec($this->curl), JSON_FORCE_OBJECT);
			##/
			if($this->getinfo==true)
			{
				##/
				$result['getinfo']=curl_getinfo($this->curl);
	            ##/
		        if($result['getinfo']['http_code']==0)
		        {
		            $result['executium_helper'][]='Connection timed out, check your connection or increase the timeout variable';
		        }
			}

	        ##/ Trying to be helpful just encase you haven't read the class
	        if(isset($result['code']))
	        {
	            ##/
		        if($result['code']==405)
		        {
		            ##/
		            $result['executium_helper'][]='Does this query require data to be passed via the second argument? If not, then try removing it and run the query again.';
		        }
	        }

	        ##/
		    return $result;
        }
		catch (Exception $e)
		{
			return array('error'=>$e->getMessage());
		}
    }
}
