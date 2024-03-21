<?php 

function sendFCMAndroid($message, $title,array $id, $type) {

	//$id = explode(',', $id);
	
	$url = 'https://fcm.googleapis.com/fcm/send';
	//$apiKey = "AIzaSyA3H41sX6_6huSSqaWqETIEN5mktGz0HZ8";
    //$apiKey = "AIzaSyD911ln5HnHIxpu32L93XlAsatKTguw3Mo";
    $apiKey = "AAAAxyaOXpA:APA91bE3Ccuycd0RXJmaW6ZXuw94CtYMyIHvd6KwMiTipiB8o_HEA7ioj_rwg8EQIVDBUOIynPRhSz_lVd_-QyNdvCLwBfuIqnbN_RdicHFafHplPYJRnowEL04f3tXmDffVjxh8UsWd";
 
	$messageData = array(
	   // 'to' => $id, 
	    'body' => $message, 
	    'title' => $title, 
	    'type' => $type,
	    'sound'=>'Default'
	  );
	
	$fields =  array(		
		'registration_ids' => $id,
		'priority' => "high",
		'notification' => $messageData,
		'data' => $messageData
	);

	$headers = array(
		'Authorization:key='.$apiKey,
		'Content-Type: application/json'
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch,CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	//echo json_encode($data);
	
	$result = curl_exec($ch);    
	//print_r($result);       
	//echo curl_error($ch);
	if ($result === FALSE) {
		return FALSE;
		die('Curl failed: ' . curl_error($ch));
		
	}
	curl_close($ch);
	
	return $result; 
}

function sendFCM($message, $title, array $id, $type) {

	//$id = explode(',', $id);
	$url = 'https://fcm.googleapis.com/fcm/send';
// 	$apiKey = "AIzaSyD911ln5HnHIxpu32L93XlAsatKTguw3Mo";
    $apiKey = "AAAAxyaOXpA:APA91bE3Ccuycd0RXJmaW6ZXuw94CtYMyIHvd6KwMiTipiB8o_HEA7ioj_rwg8EQIVDBUOIynPRhSz_lVd_-QyNdvCLwBfuIqnbN_RdicHFafHplPYJRnowEL04f3tXmDffVjxh8UsWd";
 
	$messageData = array('body' => $message, 'title' => $title, 'type' => $type,"content_available" => true, "mutable_content" => true);
	//print_r($messageData);
	$priority="high";
	
	$data= $messageData= array(
		'notification' => $messageData,
		'priority' => $priority,
		'registration_ids' => $id
	);

	$headers = array(
		'Authorization:key='.$apiKey,
		'Content-Type: application/json'
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	//echo json_encode($data);
	
	$result = curl_exec($ch);    
	//print_r($result);       
	//echo curl_error($ch);
	if ($result === FALSE) {
		return FALSE;
		die('Curl failed: ' . curl_error($ch));
		
	}
	curl_close($ch);
	
	return $result; 
}


function sendMessage($message, $title, array $id, $type , $sender_id, $receiver_id, $sender_name) {
	
	//$reg_ids = explode(',', $id);
	
	$url = 'https://fcm.googleapis.com/fcm/send';
// 	$apiKey = "AIzaSyD911ln5HnHIxpu32L93XlAsatKTguw3Mo";
    $apiKey = "AAAAxyaOXpA:APA91bE3Ccuycd0RXJmaW6ZXuw94CtYMyIHvd6KwMiTipiB8o_HEA7ioj_rwg8EQIVDBUOIynPRhSz_lVd_-QyNdvCLwBfuIqnbN_RdicHFafHplPYJRnowEL04f3tXmDffVjxh8UsWd";
		
	$messageData = array(
	    'to' => $id[0],
        'body' => $message,
        'title' => $title, 
        'type' => $type , 
        'sender_id' => $sender_id, 
        'receiver_id' => $receiver_id,
        'sender_name' => $sender_name
	 );
        		
	$priority="high";
	
	$fields= array(	 
		'data' => $messageData,
		'notification' => $messageData,
		'registration_ids' => $id,
		'priority' => $priority,
	);
	$headers = array(
		'Authorization:key='.$apiKey,
		'Content-Type: application/json'
	);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);		
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch,CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	//echo json_encode($data);
	//print_r($data);
	$result = curl_exec($ch);
	//print_r($result);
	if ($result === FALSE) {
		return FALSE;
		die('Curl failed: ' . curl_error($ch));		
	}
	
	curl_close($ch);
	//return $result; 
}

function sendPushNotificationToFCMSever($token,$sender_id, $receiver_id,$type, $message, $title,$sender_name) {
    
    $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
        
// 	$apiKey = "AIzaSyD911ln5HnHIxpu32L93XlAsatKTguw3Mo";
    $apiKey = "AAAAxyaOXpA:APA91bE3Ccuycd0RXJmaW6ZXuw94CtYMyIHvd6KwMiTipiB8o_HEA7ioj_rwg8EQIVDBUOIynPRhSz_lVd_-QyNdvCLwBfuIqnbN_RdicHFafHplPYJRnowEL04f3tXmDffVjxh8UsWd";

    $fields = array(
        'registration_ids' => $token,
        // 'to' => $id[0],
        'priority' => 10,
        'data' => array('title' => $title, 'body' =>  $message ,'type'=>$type, 'sound'=>'Default','image'=>'Notification Image','sender_id' => $sender_id, 'receiver_id' => $receiver_id,'sender_name' => $sender_name,"content_available" => true, "mutable_content" => true),
        'notification' => array('title' => $title, 'body' =>  $message ,'type'=>$type, 'sound'=>'Default','image'=>'Notification Image','sender_id' => $sender_id, 'receiver_id' => $receiver_id,'sender_name' => $sender_name,"content_available" => true, "mutable_content" => true),
    );
    //print_r($fields);	
    $headers = array(
        'Authorization:key='.$apiKey,
        'Content-Type:application/json'
    );  
     
    // Open connection  
    $ch = curl_init(); 
    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm); 
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    // Execute post   
    $result = curl_exec($ch); 
    // Close connection      
    curl_close($ch);
    return $result;
}

?>