<?php
define('SEND_SMS_URL', 'https://www.onlinesms.in/api/sendSms.php?');
define('SMS_API_KEY','6445d6da063d8464ee00c73866182471');
define('SMS_URL_SNDRID','OPTINS');

function sendSMS($msg='',$mobileNum)
{
	$ch = curl_init();
	$url = SEND_SMS_URL;
	$api_key = SMS_API_KEY;
	$sndID = SMS_URL_SNDRID;
	$mobileNum = $mobileNum;
	
	$hit_url = "https://www.onlinesms.in/api/sendSms.php?api_key=".$api_key."&msg=".$msg."&senderid=".$sndID."&mobnum=".$mobileNum;
	$url = $hit_url; 

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL,$url);
	curl_setopt($curl, CURLOPT_HEADER, 1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$buffer = curl_exec($curl);
	/* print_r($buffer); */
	curl_close($curl);
	return $buffer;

}
 
?>