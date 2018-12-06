<?php 
	class cURL{
		public static function simple($action, $values = false){
				$con = curl_init();
				$url = "https://api.moloni.pt/v1/".$action."/?access_token=".ACCESS_TOKEN;
				curl_setopt($con, CURLOPT_URL, $url);
				curl_setopt($con, CURLOPT_POST, true);
				curl_setopt($con, CURLOPT_POSTFIELDS, $values ? http_build_query($values) : false);
				curl_setopt($con, CURLOPT_HEADER, false);
				curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false);
								 
				$res_curl = curl_exec($con);
				curl_close($con);
								 
				// análise do resultado
				$res_txt = json_decode($res_curl, true);
				if(!isset($res_txt['error'])){
					return($res_txt);
				}
				else{
					base::genError($url, $values, $res_txt);
					return(FALSE);
				}
			}
			
			public static function test(){
				$con = curl_init();
				$url = "https://api.moloni.pt/v1/products/getOne/?access_token=FAKETOKEN";   /* Substituir pelo token atual */
								 
				curl_setopt($con, CURLOPT_URL, $url);
				curl_setopt($con, CURLOPT_POST, true);
				curl_setopt($con, CURLOPT_POSTFIELDS, http_build_query($my_values));
				curl_setopt($con, CURLOPT_HEADER, false);
				curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false);
								 
				$res_curl = curl_exec($con);
				curl_close($con);
								 
				$res_txt = json_decode($res_curl, true);
				if(isset($res_txt['error'])){
					return(TRUE);
				}
				else{
					return(FALSE);
				}
			}
			
			public static function login($user, $pass){
				$con = curl_init();
				$url = "https://api.moloni.pt/v1/grant/?grant_type=password&client_id=easyhost&client_secret=0cda51a36424accefda337259bf5d951f3235ed2&username=$user&password=$pass";
				curl_setopt($con, CURLOPT_URL, $url);
				curl_setopt($con, CURLOPT_POST, FALSE);
				curl_setopt($con, CURLOPT_POSTFIELDS, FALSE);
				curl_setopt($con, CURLOPT_HEADER, false);
				curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false);
								 
				$res_curl = curl_exec($con);
				curl_close($con);
								 
				// análise do resultado
				$res_txt = json_decode($res_curl, true);
				if(!isset($res_txt['error'])){
					return($res_txt);
				}
				else{
					base::genError($url, $values, $res_txt);
					return(FALSE);
				}
			}
			
			public static function refresh($refresh){
				$con = curl_init();
				$url = "https://api.moloni.pt/v1/grant/?grant_type=refresh_token&client_id=easyhost&client_secret=0cda51a36424accefda337259bf5d951f3235ed2&refresh_token=$refresh";
				curl_setopt($con, CURLOPT_URL, $url);
				curl_setopt($con, CURLOPT_POST, FALSE);
				curl_setopt($con, CURLOPT_POSTFIELDS, FALSE);
				curl_setopt($con, CURLOPT_HEADER, false);
				curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false);
								 
				$res_curl = curl_exec($con);
				curl_close($con);
								 
				// análise do resultado
				$res_txt = json_decode($res_curl, true);
				if(!isset($res_txt['error'])){
					return($res_txt);
				}
				else{
					//base::genError($url, $values, $res_txt);
					return(FALSE);
				}
			}
	}

?>