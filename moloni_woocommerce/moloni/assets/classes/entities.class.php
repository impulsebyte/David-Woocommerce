<?php 

	class entities{
		
		public static function getCostumerID($orderID){
			
			$order = new WC_Order($orderID);
			$meta = WC_moloni::getOrderMeta($orderID);
			
			
			$vat = $meta[VAT_FIELD];
			
			if (substr('PT', 0, 2) == strtoupper($vat)){
				$vat = str_ireplace("PT", "", $vat);
			}
			
			if(trim($vat) == '' OR preg_match("/\b(999999999|999999990|000000000|111111111)\b/", $vat)){
				$vat = "999999990"; 
			}
			
			
			$values["company_id"] 	= COMPANY_ID;
			$values["vat"]  		= $vat;
			$values["qty"]			= "1";
			$values["offset"] 		= "0";
			$values['exact'] 		= "1";
			
			$clientID = self::getByVat($values);
			unset($values);
						
			
			if(trim($order->get_billing_company()) == ''){
				$name = $order->get_billing_first_name()." ".$order->get_billing_last_name();
				$contactName = $name;
			}else{
				$name = $order->get_billing_company();
				$contactName = $order->get_billing_first_name()." ".$order->get_billing_last_name();
			}
						
			if($order->get_billing_country() == "PT"){
				$codPostal = self::zipCheck($order->get_billing_postcode());
			}else{
				$codPostal = $order->get_billing_postcode();
			}
						
			$number = substr(CLIENT_PREFIX.($order->get_customer_id() == 0 ? rand(1000,9999) : $order->get_customer_id()) ,0 ,25);

			$values['company_id'] = COMPANY_ID;
			$meInfo = cURL::simple("companies/getOne", $values);
			unset($values);
			
			if(!$clientID OR $vat == '999999990'){			
				$values['company_id'] 				= COMPANY_ID;
				$values['name'] 					= ($name);
				$values['language_id'] 				= general::getLanguageID($order->get_billing_country() );
				$values['address'] 					= trim($order->get_billing_address_1() ." ".$order->get_billing_address_2());
				$values['zip_code'] 				= $codPostal;
				$values['city'] 					= $order->get_billing_city();
				$values['country_id'] 				= general::getCountryID($order->get_billing_country() );
				$values['email'] 					= $order->get_billing_email();
				$values['website'] 					= "";
				$values['phone'] 					= $order->get_billing_phone();
				$values['fax'] 						= "";
				$values['contact_name'] 			= $contactName;
				$values['contact_email'] 			= $order->get_billing_email();
				$values['contact_phone'] 			= $order->get_billing_phone();
				$values['notes'] 					= "";
				$values['salesman_id']				= "";
				$values['maturity_date_id'] 		= MATURITY_DATE;
				$values['payment_day'] 				= "";
				$values['discount'] 				= "";
				$values['credit_limit'] 			= "";
				$values['qty_copies_document'] 		= (!empty($meInfo["docs_copies"]) ? $meInfo["docs_copies"] : "2");
				$values['payment_method_id'] 		= PAYMENT_METHOD;
				$values['delivery_method_id'] 		= "";
				$values['field_notes'] 				= "";
				
				$numberCheck = self::getByNumber($number);	
				if(!$numberCheck){
					$values['vat'] 		= $vat;
					$values['number'] 	= $number;
					$clientID = self::costumerInsert($values);					
				}else{
					$values['customer_id'] = $numberCheck;
					$clientID = self::costumerUpdate($values);
				}
			}

			if($vat == '999999990'){
				
				#MORADAS ALTERNATIVAS
				
				#$clientID = self::costumerInsert($values);
				
				#$clientID = self::costumerUpdate($values);
				
				/*$values['company_id'] = COMPANY_ID;
				$values['customer_id'] = $clientID;
				$values['email'] = (!empty($orderInfo['postmeta']['_billing_email']) ? $orderInfo['postmeta']['_billing_email'] : $orderInfo['postmeta']['_billing_phone']);
				$alternateAddressExists = self::getAlternateAddress($values);
				unset($values);
				
				$customer['company_id'] 	= COMPANY_ID;
				$customer['customer_id']	= $clientID;
				$customer['address'] 		= trim($orderInfo['postmeta']['_billing_address_1']." ".$orderInfo['postmeta']['_billing_address_2']);
				$customer['city'] 			= ($orderInfo['postmeta']['_billing_city']);
				$customer['zip_code'] 		= $codPostal;
				$customer['country_id'] 	= general::getCountryID($orderInfo['postmeta']['_billing_country']);
				$customer['email'] 			= $orderInfo['postmeta']['_billing_email'];
				$customer['phone'] 			= $orderInfo['postmeta']['_billing_phone'];
				
				if(!$alternateAddressExists){
						
					#Caso não exista um endereço alternativo com "code" igual ao email do cliente
					$customer['designation'] 	= $name;
					$customer['code'] 			= (!empty($orderInfo['postmeta']['_billing_email']) ? $orderInfo['postmeta']['_billing_email'] : $orderInfo['postmeta']['_billing_phone']);
					
					#Inserir a morada alternativa 
					$alternateAddressID = self::alternateAddressInsert($customer);
				}else{
					
					#Caso já exista um endereço alternativo com o mesmo email o ID é o mesmo mas actualiza
					$customer['address_id']		= $alternateAddressExists;
					
					$alternateAddressID = self::alternateAddressUpdate($customer);
				}
				
				$client['address_id'] = $alternateAddressID['address_id'];
				*/
				
			}
			$client['customer_id'] = $clientID;
			
			return($client);
			
		}
		
		
		public static function getByVat($values){
			$values['exact'] = "1";
			$results = cURL::simple("customers/getByVat", $values);
			if(!empty($results[0]['customer_id'])){
				return($results[0]['customer_id']);	
			}else{
				return(FALSE);
			}
		}
		
		public static function getByNumber($number){
			$values['company_id'] = COMPANY_ID;
			$values['number'] = $number;
			$values['exact'] = "1";
			$results = cURL::simple("customers/getByNumber", $values);
			if(!empty($results[0]['customer_id'])){
				return($results[0]['customer_id']);	
			}else{
				return(FALSE);
			}
		}
		
		public static function getAlternateAddress($values){
			$results = cURL::simple("customerAlternateAddresses/getAll", $values);
			foreach($results as $result){
				if(mb_strtolower($result['code']) == mb_strtolower($values['email'])){
					return($result['address_id']);
				}
			}
			return(false);
		}
		
		public static function alternateAddressInsert($values){
			$results = cURL::simple("customerAlternateAddresses/insert", $values);
			if(!isset($results['address_id'])){
				base::genError("customerAlternateAddresses/insert", $values, $results);				
			}else{
				return($results);
			}
		}
		
		public static function alternateAddressUpdate($values){
			$results = cURL::simple("customerAlternateAddresses/update", $values);
			if(!isset($results['address_id'])){
				base::genError("customerAlternateAddresses/update", $values, $results);
			}else{
				return($results);
			}
		}
		
		public static function costumerInsert($values){
			$results = cURL::simple("customers/insert", $values);
			if(!isset($results['customer_id'])){
				base::genError("customers/insert", $values, $results);
			}else{
				return($results['customer_id']);
			}
		}
		
		public static function costumerUpdate($values){
			$results = cURL::simple("customers/update", $values);
			if(!isset($results['customer_id'])){
				base::genError("customers/update", $values, $results);
			}else{
				return($results['customer_id']);
			}
		}
		
	public static function zipCheck($input)
    {
        $zipCode = trim(str_replace(" ", "", $input));
        $zipCode = preg_replace("/[^0-9]/", "", $zipCode);

        if (strlen($zipCode) == 7) {
            $zipCode = $zipCode[0].$zipCode[1].$zipCode[2].$zipCode[3]."-".$zipCode[4].$zipCode[5].$zipCode[6];
        }

        if (strlen($zipCode) == 6) {
            $zipCode = $zipCode[0].$zipCode[1].$zipCode[2].$zipCode[3]."-".$zipCode[4].$zipCode[5]."0";
        }

        if (strlen($zipCode) == 5) {
            $zipCode = $zipCode[0].$zipCode[1].$zipCode[2].$zipCode[3]."-".$zipCode[4]."00";
        }

        if (strlen($zipCode) == 4) {
            $zipCode = $zipCode."-"."000";
        }

        if (strlen($zipCode) == 3) {
            $zipCode = $zipCode."0-"."000";
        }

        if (strlen($zipCode) == 2) {
            $zipCode = $zipCode."00-"."000";
        }

        if (strlen($zipCode) == 1) {
            $zipCode = $zipCode."000-"."000";
        }

        if (strlen($zipCode) == 0) {
            $zipCode = "1000-100";
        }
        if (self::verify($zipCode)) {
            return($zipCode);
        } else {
            #Em último caso, retorna 1000-000
            return("1000-100");
        }
    }
    #Complemento da verificação do código postal

    public static function verify($zipCode)
    {
        $regexp = "/[0-9]{4}\-[0-9]{3}/";
        if (preg_match($regexp, $zipCode)) {
            return(true);
        } else {
            return(false);
        }
    }
	}

?>