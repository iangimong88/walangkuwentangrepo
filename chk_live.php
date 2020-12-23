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
		$string = str_ireplace("/test","",$message);
		switch($string){
			case is_numeric($string) === true:
				sendMessage($chatId, "numeric.");
				break;
			default;
				sendMessage($chatId, "non numeric.");
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
