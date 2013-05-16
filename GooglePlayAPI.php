<?php

namespace Antyzero\Lib;

/**
 * @author Iwo Polański
 */

class GooglePlayAPI {

	const URL_AUTH = "https://www.google.com/accounts/ClientLogin";
	const URL_API  = "http://android.clients.google.com/market/api/ApiRequest";
	
	private $authToken = "";
	
	/**
	 * 
	 */

	public function __construct( $email, $password ) {
		
		$this->authToken( $email, $password );
	}
	
	/**
	 * 
	 */
	
	private function authToken( $email, $password ) {
		
		
		/* POST fields */
		
		$post = $this->buildPostFields( $email, $password );


		/* Headers */		
		
		$headers = array(
			"User-Agent: Android-Market/2 (sapphire PLAT-RC33); gzip",
			"Content-Type: application/x-www-form-urlencoded",
			"Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7", );
		
		
		/* CURL */
		
		$curl = curl_init();
		
		curl_setopt_array($curl, array(
				CURLOPT_URL            => GooglePlayAPI::URL_AUTH,
				CURLOPT_HEADER         => 0        ,
				CURLOPT_RETURNTRANSFER => 1        ,
				CURLOPT_POST           => 1        ,
				CURLOPT_POSTFIELDS     => $post    ,
				CURLOPT_FOLLOWLOCATION => true     ,
				CURLOPT_SSL_VERIFYPEER => 0        ,
				CURLOPT_HTTPHEADER     => $headers
			)
		);
		
		
		$result = curl_exec( $curl );
		
		curl_close( $curl );
		
		
		/* Process response */
		/*
		Convert answer into url-like string. 
		If there is an Auth, then it will be the variable $Auth
		*/
		parse_str(str_replace("\n", "&", $result));
		return isset($Auth) ? $Auth : false;
	}
	
	/**
	 * Build POST fields for login request
	 */
	
	private function buildPostFields( $email, $password ){
		
		$postFields	= array(
			"Email"		=> urlencode( $email    ),
			"Passwd"	=> urlencode( $password ),
			"accountType"	=> urlencode( "GOOGLE"  ),
			"service"	=> urlencode( "android" ), 
		);
			
		return http_build_query($postFields);
	}

}
?>
