<?php
set_time_limit(0);
error_reporting(0);
date_default_timezone_set('America/Sao_Paulo');


extract($_GET);


for ($i=0; $i <= substr_count($lista, ','); $i++) { 
	$cclist = explode(",", $lista); 
	$entry = explode("|", $cclist[$i]);
	
	$cc = $entry[0];
	$mes = $entry[1];
	$ano = $entry[2];
	$cvv = $entry[3];
	$email = "doublebrown".rand(1, 200000)."@gmail.com";
	$postal = rand(0000, 9999);


}


?>