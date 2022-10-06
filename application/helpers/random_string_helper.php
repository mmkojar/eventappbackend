<?php

/*****

Returns a randomized string of chars/sybols/numbers for specified length

@param $l = length (default=9)

@param $add_dashes = (default=FALSE)

@param $available_sets = (l=LOWER, u= Upper , d = Digits, s=spacial chareectur)

*****/

function trimMsg($msg) {
    $string = trim( $msg ); 
    $paragraphBreak = array("\r\n\r\n", "\n\r\n\r", "\n\n", "\r\r");
    $string = '<p>'.str_replace( $paragraphBreak, '</p><p>', $msg ).'</p>';
    $string = nl2br($msg);
    return $string;
}

function gen_string($length = 9, $add_dashes = false, $available_sets = 'luds')

{ 


        $sets = array();

        if(strpos($available_sets, 'l') !== false)

        $sets[] = 'abcdefghjkmnpqrstuvwxyz';

        if(strpos($available_sets, 'u') !== false)

        $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';

        if(strpos($available_sets, 'd') !== false)

        $sets[] = '23456789';

        if(strpos($available_sets, 's') !== false)

        $sets[] = '!#@$%&';

       



        $all = '';

        $password = '';

        foreach($sets as $set)

        {

        $password .= $set[array_rand(str_split($set))];

        $all .= $set;

        }



        $all = str_split($all);

        for($i = 0; $i < $length - count($sets); $i++)

        $password .= $all[array_rand($all)];



        $password = str_shuffle($password);



        if(!$add_dashes)

        return $password;



        $dash_len = floor(sqrt($length));

        $dash_str = '';

        while(strlen($password) > $dash_len)

        {

        $dash_str .= substr($password, 0, $dash_len) . '-';

        $password = substr($password, $dash_len);

        }

        $dash_str .= $password;

        return $dash_str;

}





function generateSalt(){



	$salt_length = 12;



	$salt = substr(md5(uniqid()), 0, $salt_length);



	return $salt;



}



function encryptPassword($pwd, $salt){



    $hashed_password = sha1($salt . $pwd);



	return $hashed_password;



}


function cleanQueryParameter($string) {



	//remove extraneous spacess

	$string = trim($string);



	/* prevents duplicate backslashes:

	One way to check if Magic Quotes is running is to run get_magic_quotes_gpc(). 

	*/

	/*if(get_magic_quotes_gpc()) { 

		$string = stripslashes($string);

	}*/

		

	/*escape the string with backward compatibility

	Escapes special characters in the unescaped_string, 

	taking into account the current character set of the connection so that it is safe to place it in a mysql_query(). 

	mysql_real_escape_string() calls MySQL's library function mysql_real_escape_string, 

	which prepends backslashes to the following characters: \x00, \n, \r, \, ', " and \x1a.	

	*/

	/* if (phpversion() >= '4.3.0'){

		$string = mysql_real_escape_string($string);

	} else{

		$string = mysql_escape_string($string);

	} */

	return $string;

}

function setError($errorCode,$errMsg){
	$returnArr["errorCode"] = "$errorCode";
	$returnArr["errMsg"]["msg"] = "$errMsg";
	print(json_encode($returnArr));
}

