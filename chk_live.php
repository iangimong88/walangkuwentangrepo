<?php
set_time_limit(0);
error_reporting(0);
date_default_timezone_set('America/Sao_Paulo');


// extract($_GET);
$botToken = "689347298:AAEcpBcIifMqdT0dmFBm2Jo6xI8LiiJvHOU";
$botUrl = "https://api.telegram.org/bot".$botToken;

$update = file_get_contents("php://input");
$data = json_decode($update, TRUE);

$chatId = $data["message"]["chat"]["id"];
$message = $data["message"]["text"];

switch($message){
	case "/start":
		sendMessage($chatId, "Maligayang pagdating sa aking bot. Ito ay para sa mga jolly batibot.");
		break;
	case strpos($message, "/test") !== false:
		$string = str_ireplace("/test", "", $message);
		
		
		if(preg_match('/[^0-9\n|\-]/', $string) !== false){
			$filtered_string = preg_replace('/[^0-9\n|\-]/', '', $string);

			sendMessage($chatId, $filtered_string);
		}
		else{
			sendMessage($chatId, "Hindi ma-test ang mensahe.");
		}
		break;
	default:
		sendMessage($chatId, "Hindi kita maunawaan.");
}

function sendMessage($chatId, $message){
	$url = $GLOBALS["botUrl"]."/sendMessage?chat_id=" .$chatId. "&text=" .urlencode($message);
	file_get_contents($url);
}

function authenticate($cc, $mes, $ano, $cvv){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://payments.braintree-api.com/graphql');
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $GLOBALS["botUrl"].'/cookie.txt');
	curl_setopt($ch, CURLOPT_COOKIEJAR, $GLOBALS["botUrl"].'/cookie.txt');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Accept:*/*',
		'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJFUzI1NiIsImtpZCI6IjIwMTgwNDI2MTYtcHJvZHVjdGlvbiIsImlzcyI6Imh0dHBzOi8vYXBpLmJyYWludHJlZWdhdGV3YXkuY29tIn0.eyJleHAiOjE2MDg3OTY3OTUsImp0aSI6IjdmOGMzMDk2LTI3ZWUtNDI1MS04NzYwLTQ3NzlhOTg3MmRiZSIsInN1YiI6InRoaHZ6YmpzMm1nbnJ6ZHoiLCJpc3MiOiJodHRwczovL2FwaS5icmFpbnRyZWVnYXRld2F5LmNvbSIsIm1lcmNoYW50Ijp7InB1YmxpY19pZCI6InRoaHZ6YmpzMm1nbnJ6ZHoiLCJ2ZXJpZnlfY2FyZF9ieV9kZWZhdWx0IjpmYWxzZX0sInJpZ2h0cyI6WyJtYW5hZ2VfdmF1bHQiXSwic2NvcGUiOlsiQnJhaW50cmVlOlZhdWx0Il0sIm9wdGlvbnMiOnsibWVyY2hhbnRfYWNjb3VudF9pZCI6Imdsb3J5dGVjaGxsY19pbnN0YW50In19.IFhQplOdUpCQ6e7Ue8W5nTB25n6O9VteTya2RpTVA8uTZ7ig_HDupSf7jRuJQ-VD2ba89BZhZZV0N5yofpUlfA',
		'braintree-version: 2018-05-10',
		'content-type: application/json',
		'origin: https://assets.braintreegateway.com',
		'referer: https://assets.braintreegateway.com/'
	));
	curl_setopt($ch, CURLOPT_POSTFIELDS, '{"clientSdkMetadata":{"source":"client","integration":"custom","sessionId":"3ecb2e70-8094-4ac8-b58e-248cae6c2dbd"},"query":"mutation TokenizeCreditCard($input: TokenizeCreditCardInput!) {   tokenizeCreditCard(input: $input) {     token     creditCard {       bin       brandCode       last4       expirationMonth      expirationYear      binData {         prepaid         healthcare         debit         durbinRegulated         commercial         payroll         issuingBank         countryOfIssuance         productId       }     }   } }","variables":{"input":{"creditCard":{"number":"'.$cc.'","expirationMonth":"'.$mes.'","expirationYear":"'.$ano.'","cvv":"'.$cvv.'"},"options":{"validate":false}}},"operationName":"TokenizeCreditCard"}');
	$pagamento = curl_exec($ch);
	$data_response = json_decode($pagamento, true);
	
	return $data_response['data']['tokenizeCreditCard']['token'];
				
}




?>
