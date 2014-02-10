<?php
error_reporting(E_ALL);

class SOPGClassicMerchantClient
{
    var $log;
    var $debug;
    var $data;
    private $sysLang; 
    private $MinKycLevel;   
    private $debugStatus;
    private $autoCorrect;
    private $client;
	
    public function __construct($debugStatus='', $sysLang='', $autoCorrect='', $status='') {
        $this->log = array('error' => array(),'warning' => array(), 'info' => array());
        $this->MinKycLevel = array('SIMPLE','FULL');
        if($autoCorrect == ''){$this->addLog('no_auto_correct','__construct','','','warning'); $autoCorrect = 'false';}
        if($sysLang == ''){$this->addLog('no_sys_lang','__construct','','','warning'); $sysLang = 'en';}
        if($debugStatus == ''){$this->addLog('no_debug','__construct','','','warning'); $debugStatus = 'false';}
        $this->debugStatus = $debugStatus;		
        $this->sysLang = $sysLang;
        $this->autoCorrect = $autoCorrect;
        $this->reset();
        if($this->setStatus($status)){$this->clientConnect();}
    }
    
	public function merchant( $username, $password)
	{
		if ( $this->validate('password',$password ) )
		{
			$this->data['password'] = $password;
            $this->addDebug('password','>>hidden<<');
		}

		if ( $this->validate('username',$username ) )
		{
			$this->data['username'] = $username;
            $this->addDebug('username','>>hidden<<');
		}
	}
    
    public function confirmMerchantData($currency = 'EUR')
    {
        $this->setCurrency($currency);
        if(!empty($this->log['error']))
        {
            $this->addLog('error_confirm_merchant','confirmMerchantData','Error-Log',count($this->log['error']));
            $this->addDebug('confirmMerchantData','Canceled');
            return false;
        }
        $params = array(
                'username' => $this->data['username'],
                'password' => $this->data['password'],
                'currency' => $this->data['currency']
       );
        $this->clientConnect();
        $data = $this->client->getMid($params);
        if($data->getMidReturn->resultCode == '0' && $data->getMidReturn->errorCode == '0'){return true;}else{return false;}
    }

	public function setCustomer( $amount,  $currency, $mtid, $merchantClientId )
	{
       $this->setAmount( $amount );
       $this->setMerchantClientId( $merchantClientId );
       $this->setCurrency( $currency );
       $this->setMtid($mtid);	
	}
    
	public function setAmount( $amount )
	{
	   if($this->autoCorrect === true)
       {
           $tmp_amount = $amount;
	       $amount = number_format( str_replace( ',', '.', $amount ), 2, '.', '' );
           $this->addDebug('AutoCorrect_Amount',$tmp_amount .' -> '. $amount);
           if($tmp_amount !== $amount){$this->addLog('auto_correct_ammount_warning','setAmount_AutoCorrect',$tmp_amount,$amount,'warning');}
       }
        if($this->validate('amount',$amount))
        {
            $this->data['amount'] = $amount;
            $this->addDebug('setAmount',$this->data['amount']);
        }  
	}   
    
	public function setMerchantClientId( $merchantClientId )
	{
		if($this->validate('merchantClientId',$merchantClientId))
        {
            $this->data['merchantClientId'] = $merchantClientId;
            $this->addDebug('setMerchantClientId',$this->data['merchantClientId']);
        }
        
	}

	public function setCurrency( $currency )
	{
		if ( $this->validate('currency',$currency) )
        {
            $this->data['currency'] = $currency;
            $this->addDebug('setCurrency',$this->data['currency']);
        }
        
	}    
	
	public function setShopId( $shopId )
	{
	   if($this->validate('shopId',$shopId))
       {
			$this->data['shopId'] = $shopId;
            $this->addDebug('setShopId',$this->data['shopId']);
	   }
	}
    
	public function setShoplabel( $shopLabel )
	{
		if($this->validate('shopLabel',$shopLabel))
        {
			$this->data['shopLabel'] = $shopLabel;
            $this->addDebug('setShopLabel',$shopLabel);
		}
	}
    
	public function setMtid( $mtid )
	{
		if($this->validate('mtid',$mtid))
        {
			$this->data['mtid'] = $mtid;
            $this->addDebug('setMtid',$mtid);
		}
	}
    
	public function SetRestricedCountry( $country )
	{
	   if($this->autoCorrect === true)
       {
            $tmp_country = $country;
            $country = strtoupper($country);
            $this->addDebug('autoCorrect_RestricedCountry',$tmp_country . ' -> ' . $country);
            if($tmp_country !== $country){$this->addLog('auto_correct_res_country','SetRestricedCountry_AutoCorrect',$tmp_country,$country,'warning');}
       }
		if($this->validate('restricedCountry',$country))
        {
            $this->data['dispositionRestrictions']['restricedCountry'][] = $country;
            $this->addDebug('SetRestricedCountry',$country);
        }
	}
    
	public function setMinAge( $age )
	{
		if($this->validate('minAge',$age))
        {
            $this->data['dispositionRestrictions']['minAge'] = $age;
            $this->addDebug('setMinAge',$age);
        }
	}
    
    public function setSubId( $subId )
	{
		if($this->validate('subId',$subId))
        {
            $this->data['subId'] = $subId;
            $this->addDebug('setSubId',$subId);
        }
	}
    
    public function setClose( $close )
	{
		if($this->validate('close',$close))
        {
            $this->data['close'] = $close;
            $this->addDebug('setClose',$close);
        }
	}
    
	public function setMinKycLevel( $level )
	{
		if($this->validate('MinKycLevel',$level))
        {
			$this->data['dispositionRestrictions']['MinKycLevel'] = $level;
            $this->addDebug('setMinKycLevel',$level);
		}
	}
    
	public function setUrl($ok_url, $nok_url, $pn_url)
	{
	   
       if($this->autoCorrect === true)
       {
        
        if(strpos($ok_url,':') !== false OR strpos($ok_url,'/') !== false)
        {
            $tmp_ok_url = $ok_url;
            $ok_url = rawurlencode( $ok_url );
            $this->addLog('auto_correct_set_ok_url','setUrl_AutoCorrect',$tmp_ok_url,$ok_url,'warning');
        }
        
        if(strpos($nok_url,':') !== false OR strpos($nok_url,'/') !== false)
        {
            $tmp_nok_url = $nok_url;
            $nok_url = rawurlencode( $nok_url );
            $this->addLog('auto_correct_set_nok_url','setUrl_AutoCorrect',$tmp_nok_url,$nok_url,'warning');
        }
        
        if(strpos($pn_url,':') !== false OR strpos($pn_url,'/') !== false)
        {
            $tmp_pn_url = $pn_url;
            $pn_url = rawurlencode( $pn_url );
            $this->addLog('auto_correct_set_pn_url','setUrl_AutoCorrect',$tmp_pn_url,$pn_url,'warning');
        }
        
       }
       
	   if($this->validate('ok_url',$ok_url))
       {
            $this->data['ok_url'] = $ok_url;
            $this->addDebug('setUrl_ok_url',$this->data['ok_url']);
       }
       
       if($this->validate('nok_url',$nok_url))
       {
            $this->data['nok_url'] = $nok_url;
            $this->addDebug('setUrl_nok_url',$this->data['nok_url']);
       }
       
       if($this->validate('pn_url',$pn_url))
       {
            $this->data['pn_url'] = $pn_url;
            $this->addDebug('setUrl_pn_url',$this->data['pn_url']);
       }
	}
    
    private function setStatus($status)
    {
        if ( $status == 'test' or $status == '' )
		{
			$this->data['url'] = 'https://soatest.paysafecard.com/psc/services/PscService?wsdl';
            $this->data['redirect_url'] = 'https://customer.test.at.paysafecard.com/psccustomer/GetCustomerPanelServlet';
            $this->addDebug('setUrl_url',$this->data['url']);
            $this->addDebug('setUrl_redirect_url',$this->data['redirect_url']);
            if($status == ''){$this->addLog('no_status','warning');}
            return true;
            
		}
		elseif ( $status == 'live' )
		{
			$this->data['url'] = 'https://soa.paysafecard.com/psc/services/PscService?wsdl';
            $this->data['redirect_url'] = 'https://customer.cc.at.paysafecard.com/psccustomer/GetCustomerPanelServlet';
            $this->addDebug('setUrl_url',$this->data['url']);
            $this->addDebug('setUrl_redirect_url',$this->data['redirect_url']);
            return true;
		}
		else
		{
			$this->addLog('invalide_status');
            return false;
		}
    }
	
    
    public function createDisposition()
    {
        if(!empty($this->log['error']))
        {
            $this->addLog('create_disp_is_error','createDisposition','Error-Log',count($this->log['error']));
            $this->addLog('msg_create_disposition','','','','info');
            $this->addDebug('createDisposition','Canceled');
            return false;
        }
        
        $params = array('username'=>$this->data['username'], 
                        'password'=>$this->data['password'],
                        'mtid'=>$this->data['mtid'],
                        'subId'=>$this->data['subId'],
                        'amount'=>$this->data['amount'],
                        'currency'=>$this->data['currency'],
                        'okUrl'=>$this->data['ok_url'],
                        'nokUrl'=>$this->data['nok_url'],
                        'merchantclientid'=>$this->data['merchantClientId'],
                        'pnUrl'=>$this->data['pn_url'],
                        'clientIp'=>$this->data['clientIp'],
                        'dispositionrestrictions' => $this->data['dispositionRestrictions'],
                        'shopId' => $this->data['shopId'],
                        'shoplabel' => $this->data['shopLabel']);
                        
        $response = $this->client->createDisposition($params);
        if($response->createDispositionReturn->resultCode === 0 AND $response->createDispositionReturn->errorCode === 0)
        {
            $this->data['mid'] = $response->createDispositionReturn->mid;
            $this->addDebug('createDisposition_mid',$response->createDispositionReturn->mid);
            return($this->getCustomerPanel());
        }
        else
        {
            $this->data['mid'] = 0;
            $this->addDebug('createDisposition_mid','0');
            $params['username'] = $params['password'] = '>>hidden<<';
            $log = '';
            foreach($params as $key => $para_value)
            {
                if(empty($para_value))
                {
                    $para_value = '>>empty<<';
                }
                $log .= $key.": ".$para_value."\n";
            }
            $this->addLog('error_create_disposition','createDisposition',$log,'ResultCode: '.$response->createDispositionReturn->resultCode .' - ErrorCode: '.$response->createDispositionReturn->errorCode);
            $this->addDebug('createDisposition_params',$log);
            $this->addDebug('createDisposition_response','ResultCode: '.$response->createDispositionReturn->resultCode .' - ErrorCode: '.$response->createDispositionReturn->errorCode);
            $this->addLog('msg_create_disposition','','','','info');
            return false;
        }
    }
    
    private function getCustomerPanel()
    {
        $url = $this->data['redirect_url'];
        $url .= '?currency='.$this->data['currency'];
        $url .= '&mtid='.$this->data['mtid'];
        $url .= '&amount='.$this->data['amount'];
        $url .= '&mid='.$this->data['mid'];
        if(!empty($this->data['language']))
        {
            $url .= '&language='.$this->data['language'];
        }        
        if(!empty($this->data['locale']))
        {
            $url .= '&locale='.$this->data['locale'];
        }
        
        $this->addDebug('getCustomerPanel_generated_url',$url);
        
        return $url;
    }
    
     public function getSerialNumbers($mtid,$currency,$subId)
    {
        $this->setMtid($mtid);
        $this->setCurrency($currency);
        $this->setSubId($subId);
        
        if(!empty($this->log['error']))
        {
            $this->addLog('get_serial_num_is_error','getSerialNumbers','Error-Log',count($this->log['error']));
            $this->addLog('payment_unknown_error','','','','info');
            $this->addDebug('getSerialNumbers','Canceled');
            return false;
        }       
        
        $params = array('username'=>$this->data['username'], 
                        'password'=>$this->data['password'],
                        'mtid'=>$this->data['mtid'],
                        'subId'=>$this->data['subId'],
                        'currency'=>$this->data['currency']
                       );
        
        $response = $this->client->getSerialNumbers($params);
        if($response->getSerialNumbersReturn->resultCode === 0 AND $response->getSerialNumbersReturn->errorCode === 0)
        {
            $this->addDebug('getSerialNumbers_errorCode','0,0');
            $this->data['dispositionState'] = $response->getSerialNumbersReturn->dispositionState;
            $this->addDebug('getSerialNumbers_dispositionState',$response->getSerialNumbersReturn->dispositionState);
            if($this->data['dispositionState'] === 'S' OR $this->data['dispositionState'] === 'E')
            { 
                return 'execute';
            }
            elseif($this->data['dispositionState'] === 'O')
            {
                $this->addLog('payment_done','','','','info');
                return true;
            }
            else
            {
                $params['username'] = $params['password'] = '>>hidden<<';
                $log = '';
                foreach($params as $key => $para_value)
                {
                    if(empty($para_value))
                    {
                        $para_value = '>>empty<<';
                    }
                $log .= $key.": ".$para_value."\n";
                }
                if($this->data['dispositionState'] === 'R')
                {
                    $this->addLog('payment_invalide','','','','info');
                    $this->addLog('payment_invalide','getSerialNumbers',$log,'R');
                }
                elseif($this->data['dispositionState'] === 'L')
                {
                    $this->addLog('payment_cancelled','','','','info');
                    $this->addLog('payment_cancelled','getSerialNumbers',$log,'L');
                }
                elseif($this->data['dispositionState'] === 'X')
                {
                    $this->addLog('payment_expired','','','','info');
                    $this->addLog('payment_expired','getSerialNumbers',$log,'X');
                }
                else
                {
                    $this->addLog('msg_execute_debit','','','','info');
                    $this->addLog('msg_execute_debit','getSerialNumbers',$log,'ERROR');
                }                
                return false;
            }
            
        }
        else
        {
            
            $params['username'] = $params['password'] = '>>hidden<<';
            $log = '';
            foreach($params as $key => $para_value)
            {
                if(empty($para_value))
                {
                    $para_value = '>>empty<<';
                }
                $log .= $key.": ".$para_value."\n";
            }
            $this->addDebug('getSerialNumbers','ResultCode: '.$response->getSerialNumbersReturn->resultCode .' - ErrorCode: '.$response->getSerialNumbersReturn->errorCode);
            $this->addLog('error_get_serial_num','getSerialNumbers',$log,'ResultCode: '.$response->getSerialNumbersReturn->resultCode .' - ErrorCode: '.$response->getSerialNumbersReturn->errorCode);
            $this->addLog('msg_execute_debit','','','','info');
            $this->addDebug('getSerialNumbers_params',$log);
            $this->addDebug('getSerialNumbers_response','ResultCode: '.$response->getSerialNumbersReturn->resultCode .' - ErrorCode: '.$response->getSerialNumbersReturn->errorCode);
            return false;
        }   
    }
    
    public function executeDebit($amount,$close='1')
    {
        $this->setAmount($amount);
        $this->setClose($close);
        if(!empty($this->log['error']))
        {
            $this->addLog('execute_debit_is_error','executeDebit','Error-Log',count($this->log['error']));
            $this->addLog('msg_execute_debit','','','','info');
            $this->addDebug('executeDebit','Canceled');
            return false;
        }
        
        $params = array('username'=>$this->data['username'], 
                        'password'=>$this->data['password'],
                        'mtid'=>$this->data['mtid'],
                        'subId'=>$this->data['subId'],
                        'amount'=>$this->data['amount'],
                        'currency'=>$this->data['currency'],
                        'close' => $this->data['close'],
                        );
                        
        $response = $this->client->executeDebit($params);
        if($response->executeDebitReturn->resultCode === 0 AND $response->executeDebitReturn->errorCode === 0)
        { 
			$this->addDebug('executeDebit_response','ResultCode: '.$response->executeDebitReturn->resultCode .' - ErrorCode: '.$response->executeDebitReturn->errorCode);
			$this->addLog('payment_done','','','','info');
			return true;
		}
        else
        {
            
            $params['username'] = $params['password'] = '>>hidden<<';
            $log = '';
            foreach($params as $key => $para_value)
            {
                if(empty($para_value))
                {
                    $para_value = '>>empty<<';
                }
                $log .= $key.": ".$para_value."\n";
            }
            $this->addLog('execute_debit_error','executeDebit',$log,'ResultCode: '.$response->executeDebitReturn->resultCode .' - ErrorCode: '.$response->executeDebitReturn->errorCode);
            $this->addLog('msg_execute_debit','','','','info');
            $this->addDebug('executeDebit_params',$log);
            $this->addDebug('executeDebit_response','ResultCode: '.$response->executeDebitReturn->resultCode .' - ErrorCode: '.$response->executeDebitReturn->errorCode);
            return false;
        }
    }
    
    private function validate($type = '',$value)
    {
        if($type == '' && empty($value))
        {
            $this->addLog('error_validation','validate','type & value','>>empty<<'); return false;
        }
        switch ($type)
        {
            case 'username':
                if ( empty( $value ) ){$this->addLog('username_empty','validate_username'); return false;}
        		elseif ( strlen( $value ) <= '3' ){$this->addLog('username_length','validate_username','>>hidden<<'); return false;}
        		else{return true;}
                break;
            case 'password':
                if ( empty( $value ) ){$this->addLog('password_empty','validate_password','>>hidden<<'); return false;}
        		elseif ( strlen( $value ) <= '5' ){$this->addLog('passwor_length','validate_password','>>hidden<<'); return false;}
        		else{return true;}
                break;
            case 'amount':
                if ( $value == ''){$this->addLog('empty_amount','validate_amount'); return false;}
                elseif(strlen( $value ) <= '3' ){$this->addLog('wrong_amount','validate_amount',$value); return false;}
                elseif((strpos($value,',') !== false) OR (strpos($value,'.') === false)){$this->addLog('dot_amount','validate_amount',$value); return false;}
                else{
                    $amountParts = explode('.',$value);
                    if(!isset($amountParts[1]) OR strlen($amountParts[1]) != 2){$this->addLog('null_amount','validate_amount',$value);return false;}
                    else{return true;}
                }
                break;
            case 'merchantClientId':
                if (empty($value)){$this->addLog('invalid_client_id','validate_clientId',$value); return false;}
                else{return true;}
                break;
            case 'currency':
                if ( strlen( $value ) != '3' ){$this->addLog('wrong_currency','validate_currency',$value); return false;}
                elseif(preg_match( '/^[A-Z]{3}$/', $value ) != 1){$this->addLog('wrong_currency_case','validate_currency',$value); return false;}
                else{return true;}
                break;
            case 'shopId':
                if (empty($value))
        		{
        			if ( strlen( $value ) > 60 ){$this->addLog('shopid_oversize','validate_shopId',$value); return false;}
        			elseif ( strlen( $value ) < 1 ){$this->addLog('shopid_undersize','validate_shopId',$value); return false;}
        			else{$this->addLog('shopid_invalid','validate_shopId',$value); return false;}
        		}
        		else{return true;}
                break;
            case 'shopLabel':
                if (empty($value))
        		{
        			if ( strlen( $value ) > 60 ){$this->addLog('shoplabel_oversize','validate_shopLabel',$value); return false;}
        			elseif ( strlen( $value ) < 1 ){$this->addLog('shoplabel_undersize','validate_shopLabel',$value); return false;}
        			else{$this->addLog('shoplabel_invalid','validate_shopLabel',$value); return false;}
        		}
                else{return true;}
                break;
            case 'mtid':
        		if(empty($value))        
                {        
                    if(strlen($value) > 60){$this->addLog('mtid_oversize','validate_mtid',$value); return false;}                            
                    elseif(strlen($value) < 1){$this->addLog('mtid_undersize','validate_mtid',$value); return false;}   
                    else{$this->addLog('mtid_invalid','validate_mtid',$value); return false;}         
        	    }        
                else{return true;}
                break;
            case 'subId':
        		return true;
                break;
            case 'close':
        		if($value != '1' AND $value != '0'){$this->addLog('invalid_close','validate_close',$value,''); return false;}
                else{return true;}
                break;
            case 'nok_url':
        		if (empty($value) OR strlen($value) < 10){$this->addLog('invalid_nok_url','validate_nokUrl',$value); return false;}
                else{return true;}
                break;
            case 'ok_url':
        		if (empty($value) OR strlen($value) < 10){$this->addLog('invalid_ok_url','validate_okUrl',$value); return false;}
                else{return true;}
                break;
            case 'pn_url':
        		if (empty($value) OR strlen($value) < 10){$this->addLog('invalid_pn_url','validate_pnUrl',$value); return false;}
                else{return true;}
                break;
            case 'minAge':
                if ( preg_match( '/^ \b[0-9]{1,2}\b$/', $value ) != 1 ){$this->addLog('min_age_invalide','validate_minAge',$value); return false;}
        		else{return true;}  
                break;
            case 'MinKycLevel':
                if ( !in_array($value,$this->MinKycLevel) ){$this->addLog('min_kyc_level_invalide','validate_MinKycLevel',$value); return false;}
        		else{return true;}
                break;
            case 'restricedCountry':
                if( strlen($value) != 2){$this->addLog('restricted_country_invalide','validate_restricedCountry',$value); return false;}
                elseif(preg_match( '/^[A-Z]{2}$/', $value ) != 1){$this->addLog('restricted_country_case','validate_restricedCountry',$value); return false;}
                else{return true;}
                break;
            default:
                if($type == ''){$this->addLog('error_validation_type','validate_default'); return false;}
                else{$this->addLog('error_validation','validate_default'); return false;} 
        }
    }
    
    
    private function addLog($msg_code,$call,$call_params='_null_',$result='_null_',$type='error')
    {
        if($type != 'info')
        {
            $this->log[$type][] = array(
                'msg_code'      => $msg_code,
                'action'        => $call,
                'action_params' => $call_params,
                'result'        => $result
            );
        }
        else
        {
            $this->log['info'] = array('msg_code' => $msg_code);
        }
        
    }
    
    public function getLog($type='info')
    {
        if(key_exists($type,$this->log) && !empty($this->log[$type]))
        {
            if($type == 'info')
            {
                $return = $this->speaking($this->log['info']['msg_code']);
            }
            else
            {
                foreach($this->log[$type] as $key => $log_value)
                {
                    $log_value['msg'] = $this->speaking($log_value['msg_code']);
                    $return[$key] = $log_value;
                }
            }
            
            
            return($return);
        }
        return(0);
    }
    
    
    private function addDebug($key,$value)
    {
        if($this->debugStatus === true)
        $this->debug[$key] = $value;
    }
    
    private function speaking($key)
    {
        $language['de'] = array(
                'username_empty'                => 'Benutzername ist leer!',
                'username_length'               => 'Der Benutzername muss mehr als 3 Zeichen haben.',
                'password_empty'                => 'Das Passwort ist leer!',
                'passwor_length'                => 'Das Passwort muss mehr als 5 Zeichen haben.',
                'invalid_client_id'             => 'Die angegebene Merchant-Client-ID ist ungültig. Die Merchant-Client-ID muss zwischen 1 und 50 Zeichen lang sein. Erlaubte Zeichen: AZ, az, 0-9 sowie - (Bindestrich) und _ (Unterstrich).',
                'no_auto_correct'               => 'Es wurde keine Auto-Korrektur festgelegt.',
                'no_sys_lang'                   => 'Es wurde keine Systemsprache festgelegt.',
                'no_debug'                      => 'Es wurde kein Debug-Status angegeben.',
                'shopid_oversize'               => 'ShopId ist ungültig. Die ShopId darf nicht mehr als 60 Zeichen haben.',
                'shopid_undersize'              => 'ShopId ist ungültig. Die ShopId muss mehr als 20 Zeichen haben.',
                'shopid_invalid'                => 'Die Shop-ID ist ungültig. Erlaubte Zeichen A-Z, a-z, 0-9 und – (Bindestrich) und _ (Unterstrich).',
                'shoplabel_oversize'            => 'Shoplabel ist ungültig. Shoplabel darf nicht mehr als 60 Zeichen haben.',
                'shoplabel_undersize'           => 'Shoplabel ist ungültig. Shoplabel muss mehr als 1 Zeichen haben.',
                'shoplabel_invalid'             => 'Shoplabel ist ungültig. Erlaubte Zeichen A-Z, a-z, 0-9 und – (Bindestrich) und _ (Unterstrich).',
                'mtid_oversize'                 => 'Mtid ist ungültig. Mtid darf nicht mehr als 60 Zeichen haben.',
                'mtid_undersize'                => 'Mtid ist ungültig. Mtid muss mehr als 20 Zeichen haben.',
                'mtid_invalid'                  => 'Mtid ist ungültig. Erlaubte Zeichen A-Z, a-z, 0-9 und – (Bindestrich) und _ (Unterstrich).',
                'error_validation_value'        => 'Fehler bei der Validierung. Es wurde kein Wert zur Validierung übergeben!',
                'error_validation_type'         => 'Fehler bei der Validierung. Es wurde kein gültiger Typ zur Validierung angegeben!',
                'error_validation'              => 'Fehler bei der Validierung.',
                'min_age_invalide'              => 'Ungültige Altersbeschränkung. Das Alter muss ein positiver Zahlenwert sein.',
                'min_kyc_level_invalide'        => 'Ungültige Einschränkung. Das Level muss SIMPLE oder FULL sein.',
                'restricted_country_invalide'   => 'Invalid restricted country. 2 characters required. The value accepts ISO 3166-1 country codes.',
                'restricted_country_case'       => 'Ungültige Ländereinschränkung. Es sind nur Großbuchstaben erlaubt. Der Wert akzeptiert ISO 3166-1 Ländercodes.',
                'invalide_status'               => 'Ungültiger Status. Der Status kann "test" order "live" sein.',
                'no_status'                     => 'Es wurde keine Status angegeben.',
                'create_disp_is_error'          => 'createDisposition wurde abgebrochen. Bitte erst alle Fehler beseitigen.',
                'execute_debit_is_error'        => 'executeDebit wurde abgebrochen. Bitte erst alle Fehler beseitigen.',
                'execute_debit_error'           => 'executeDebit war nicht erfolgreich.',
                'wrong_currency'                => 'Ungültige Währung. Die Währung muss 3 Zeichen lange sein (ISO 4217).',
                'wrong_currency_case'           => 'Ungültige Währung. Die Währung darf nur in Großbuchstaben angegeben werden.',
                'dot_amount'                    => 'Der Betrag muss mit einem Punkt getrennt werden.',
                'invalid_nok_url'               => 'Die angegebene nok-URL ist ungültig!',
                'invalid_ok_url'                => 'Die angegebene ok-URL ist ungültig!',
                'invalid_pn_url'                => 'Die angegebene pn-URL ist ungültig!',
                'auto_correct_set_pn_url'       => 'Die angegebene pn-URL wurde mit AutoCorrect berichtigt. Bitte Eingabe überarbeiten!',
                'auto_correct_set_nok_url'      => 'Die angegebene nok-URL wurde mit AutoCorrect berichtigt. Bitte Eingabe überarbeiten!',
                'auto_correct_set_ok_url'       => 'Die angegebene ok-URL wurde mit AutoCorrect berichtigt. Bitte Eingabe überarbeiten!',
                'auto_correct_res_country'      => 'Die Eingabe zu den Ländereinschränkungen wurden mit AutoCorrect berichtig. Bitte Eingabe überarbeiten!',
                'auto_correct_ammount_warning'  => 'Der angegebene Betrag wurde mit AutoCorrect berichtigt. Der angegebene Betrag entspricht nicht der vorgeschriebenen Formatierung!',
                'get_serial_num_is_error'       => 'getSerialNumbers wurde abgebrochen. Bitte erst alle Fehler beseitigen.',
                'error_get_serial_num'          => 'getSerialNumbers konnte nicht erfolgreich ausgeführt werden!',
                'error_create_disposition'      => 'createDisposition konnte nicht erfolgreich ausgeführt werden!',
                /* CUSTOMER MESSAGES */
                'msg_create_disposition'        => 'Bei der Bezahlung ist ein Fehler aufgetreten. Vermutlich ist dies ein temporärer Fehler und die Bezahlung kann durch Neu-Laden der Seite abgeschlossen werden. Falls dieses Problem weiterhin besteht, kontaktieren Sie bitte unseren Support.',
                'msg_execute_debit'             => 'Die Zahlung konnte nicht abgeschlossen werden. Es besteht ein temporäres Verbindungsproblem. Bitte drücken Sie den „reload“ Botton im Browser oder den folgenden Link um die Zahlung abzuschließen. <a href="'.$this->data['ok_url'].'">Neuladen</a> <br> Falls dieses Problem weiterhin besteht wenden Sie sich bitte an den Support Sie können auf der paysafecard Guthabenübersicht (https://customer.cc.at.paysafecard.com/psccustomer/GetWelcomePanelServlet?language=de) Erfahren wann der reservierte Betrag wieder freigegeben wird.',
                'payment_invalide'              => 'Der Bezahlvorgang wurde nicht ordnungsgemäß abgeschlossen',
                'payment_cancelled'             => 'Der Bezahlvorgang wurde auf Ihren Wunsch abgebrochen',
                'payment_expired'               => 'Das Zeitfenster ist abgelaufen. Bitte starten Sie den Bezahlvorgang erneut.',
                'payment_unknown_error'         => 'Unbekannter Fehler bei der Zahlung. Bitte starten Sie den Bezahlvorgang erneut. Sollte der Fehler weiter auftreten, wenden Sie sich bitte an unseren Support',
                'payment_done'                  => 'Der Zahlvorgang wurde erfolgreich abgeschlossen.'
			);

        
        $language['en'] = array(
                'username_empty'                => 'Username is empty!',
                'username_length'               => 'The username must have more than 3 characters.',
                'password_empty'                => 'Password is empty!',
                'passwor_length'                => 'The password must have more than 5 characters.',
                'invalid_client_id'             => 'The specified Merchant-Client-ID is invalid. The Merchant-Client-ID must be between 1 and 20 characters. Only the following is allowed A-Z, a-z, 0-9 as well as – (hypen) and _ (underline).',
                'no_auto_correct'               => 'No auto-correct specified.',
                'no_sys_lang'                   => 'No system language specified.',
                'no_debug'                      => 'No debug-status specified.',
                'shopid_oversize'               => 'ShopID is invalid. ShopID maximum length is 60 characters.',
                'shopid_undersize'              => 'ShopID is invalid. ShopID must have at least 20 characters.',
                'shopid_invalid'                => 'ShopID is invalid. Only the following is allowed A-Z, a-z, 0-9 as well as – (hypen) and _ (underline).',
                'shoplabel_oversize'            => 'Shoplabel is invalid. Shoplabel maximum length is 60 characters.',
                'shoplabel_undersize'           => 'Shoplabel is invalid. Shoplabel must have at least 1 characters.',
                'shoplabel_invalid'             => 'Shoplabel is invalid. Only the following is allowed A-Z, a-z, 0-9 as well as – (hypen), _ (underline) and spaces.',
                'mtid_oversize'                 => 'Mtid is invalid. Mtid maximum length is 60 characters.',
                'mtid_undersize'                => 'Mtid is invalid. Mtid must have at least 1 characters.',
                'mtid_invalid'                  => 'Mtid is invalid. Only the following is allowed A-Z, a-z, 0-9 as well as – (hypen), _ (underline) and spaces.',
                'error_validation_value'        => 'Validation errors. There was no value is passed to the validation!',
                'error_validation_type'         => 'Validation errors. It was not specified a valid type for the validation!',
                'error_validation'              => 'Validation errors.',
                'min_age_invalide'              => 'Invalid restricted age. The age must be a positive numbervalue.',
                'min_kyc_level_invalide'        => 'Invalid restricted level. The level must be SIMPLE or FULL.',
                'restricted_country_invalide'   => 'Invalid restricted country. 2 characters required. The value accepts ISO 3166-1 country codes.',
                'restricted_country_case'       => 'Invalid restricted country. There are only capital letters allowed. The value accepts ISO 3166-1 country codes.',
                'invalide_status'               => 'Invalid module status. Status can only be "live" or "test".',
                'no_status'                     => 'It was not specified a status.',
                'create_disp_is_error'          => 'create disposition was aborted. Please remove all errors.',
                'execute_debit_is_error'        => 'executeDebit was aborted. Resolve all errors before proceeding.',
                'execute_debit_error'           => 'executeDebit was not successful.',
                'wrong_currency'                => 'Invalid currency. The currency must be 3 characters long (ISO 4217).',
                'wrong_currency_case'           => 'Invalid currency. The currency may only be specified in uppercase.',
                'dot_amount'                    => 'The amount must be separated with a dot.',
                'invalid_nok_url'               => 'Specified nok-URL is invalid!',
                'invalid_ok_url'                => 'Specified ok-URL is invalid!',
                'invalid_pn_url'                => 'Specified pn-URL is invalid!',
                'auto_correct_set_pn_url'       => 'Specified pn-URL was corrected with AutoCorrect. Please revise entry!',
                'auto_correct_set_nok_url'      => 'Specified nok-URL was corrected with AutoCorrect. Please revise entry!',
                'auto_correct_set_ok_url'       => 'Specified ok-URL was corrected with AutoCorrect. Please revise entry!',
                'auto_correct_res_country'      => 'Country restrictions entry was corrected with AutoCorrect. Please revise entry!',
                'auto_correct_ammount_warning'  => 'Specified amount was corrected with AutoCorrect. The specified entry does not have the required formatting!',
                'get_serial_num_is_error'       => 'getSerialNumbers was aborted. Resolve all errors before proceeding.',
                'error_get_serial_num'          => 'getSerialNumbers was not executed successfully!',
                'error_create_disposition'      => 'createDisposition was not executed successfully!',
                /* CUSTOMER MESSAGES */
                'msg_create_disposition'        => 'Transaction could not be initiated due to connection problems. If the problem persists, please contact our support.',
                'msg_execute_debit'             => 'Payment could not be completed. There is a temporary connection problem. Please press the "reload" button in your browser or click the following link to complete payment. <a href="'.$this->data['ok_url'].'">Reload</a> <br> If this issue persists, please contact Support On the paysafecard credit overview (https://customer.cc.at.paysafecard.com/psccustomer/GetWelcomePanelServlet?language=de) find out when the reserved amount is released again.',
                'payment_invalide'              => 'Failed to complete the payment transaction properly',
                'payment_cancelled'             => 'Payment transaction was aborted at your request',
                'payment_expired'               => 'Timeout. Please restart the payment transaction.',
                'payment_unknown_error'         => 'Unknown error during payment. Please restart the payment transaction. If this issue persists, please contact Support',
                'payment_done'                  => 'Payment transaction was completed successfully.'
			);
        
        if(array_key_exists($key,$language[$this->sysLang]))
        {
            return $language[$this->sysLang][$key];
        }
        elseif(array_key_exists($key,$language['en']))
        {
            return $language['en'][$key];
            
        }
        else
        {
            return('Unknown error: '.$key);
        }
        
    }
   
    private function reset()
    {
        $fields = array(
            'username','password','mtid','clientIp','subId','merchantClientId','amount','currency','language','locale','close','mid','shopLabel','shopId','ok_url','nok_url','pn_url','dispositionRestrictions','dispositionState'
        );
        foreach($fields as $field)
        {
            $this->data[$field] = '';
        }        
        if($this->autoCorrect === true)
        {
            $this->autoSet();            
        }
        
    }
    
    private function autoSet()
    {        
        $this->data['currency'] = 'EUR';
        $this->addDebug('AutoSet_currency',$this->data['currency']);
    }
    
    public function __destruct()
    {
        unset($this->data);
        unset($this->log['error']);
        unset($this->log['warning']);
        unset($this->client);
        unset($this->debug);
        unset($this->MinKycLevel);
    }
    
    private function clientConnect()
    {
        $this->client = new SoapClient($this->data['url']);
    }
}

?>