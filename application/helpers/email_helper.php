<?php

function send_mail_to_admin($email = null,$body,$subject = null)
{
	$ci=&get_instance();

   	$ci->load->library('PHPMail'); 
	
	$mail = new PHPMailer();
	//$mail->IsSMTP(); // we are going to use SMTP
	$mail->SMTPAuth   = true; // enabled SMTP authentication
	$mail->SMTPSecure = "TLS";  // prefix for secure protocol to connect to the server
	$mail->Host 	= "smtp.gmail.com";    // setting GMail as our SMTP server
	$mail->Port       = 587;                   // SMTP port to connect to GMail
	 $mail->Username   = "events@mrai.org.in";  // user email address
	$mail->Password   = "mrai_01112011";          // password in GMail
	$mail->SetFrom("events@mrai.org.in",'events .');  //Who is sending
	$mail->addReplyTo( 'events@mrai.org.in','events .');
	//$mail->AddCC("events@mrai.org.in");
	if(!empty($subject)){
		$mail->Subject = $subject;	
	}else{
		$mail->Subject = "User Registration With MRAI";  
	}
	$mail->IsHTML(true);
	$mail->Body      = $body; 
	$mail->AddAddress("events@mrai.org.in"); 
	
	$mail->Send();
	return $mail;
} 

function send_mail($email,$body)
{
	$ci=&get_instance();

   	$ci->load->library('PHPMail'); 
	
	$mail = new PHPMailer();
	//$mail->IsSMTP(); // we are going to use SMTP
	$mail->SMTPAuth   = true; // enabled SMTP authentication
	$mail->SMTPSecure = "TLS";  // prefix for secure protocol to connect to the server
	$mail->Host       = "smtp.gmail.com";      // setting GMail as our SMTP server
	$mail->Port       = 587;                   // SMTP port to connect to GMail
	$mail->Username   = "events@mrai.org.in";  // user email address
	$mail->Password   = "mrai_01112011";            // password in GMai 
	$mail->SetFrom("events@mrai.org.in", 'events .');  //Who is sending
	$mail->addReplyTo( 'events@mrai.org.in', 'events .' );
	$mail->Subject    = "User Registration With MRAI";  
	$mail->IsHTML(true);
	$mail->Body      = $body; 
	//$email ="events@mrai.org.in"; // Who is addressed the email to  
	$mail->AddAddress($email);
	
	$mail->Send();
	
	return $mail;
	
}

function send_otp($email,$body)
{
	$ci=&get_instance();

   	$ci->load->library('PHPMail'); 
	
	$mail = new PHPMailer();
	//$mail->IsSMTP(); // we are going to use SMTP
	$mail->SMTPAuth   = true; // enabled SMTP authentication
	$mail->SMTPSecure = "TLS";  // prefix for secure protocol to connect to the server
	$mail->Host       = "smtp.gmail.com";      // setting GMail as our SMTP server
	$mail->Port       = 587;                   // SMTP port to connect to GMail
	$mail->Username   = "events@mrai.org.in";  // user email address
	$mail->Password   = "mrai_01112011";            // password in GMail
	$mail->SetFrom("events@mrai.org.in", 'events .');  //Who is sending
	$mail->addReplyTo( 'events@mrai.org.in', 'events .' );
	$mail->Subject    = "Login OTP";  
	$mail->IsHTML(true);
	$mail->Body      = $body; 
	//$email ="events@mrai.org.in"; // Who is addressed the email to  
	$mail->AddAddress($email);
	
	$mail->Send();
	
	return $mail;
	
} 

function send_request($email,$body,$replyto,$replytoname)
{
	$ci=&get_instance();

   	$ci->load->library('PHPMail'); 
	
	$mail = new PHPMailer();
	//$mail->IsSMTP(); // we are going to use SMTP
	$mail->SMTPDebug = true;                                       
    $mail->isSMTP();    
	$mail->SMTPAuth   = true; // enabled SMTP authentication
	$mail->SMTPSecure = "tls";  // prefix for secure protocol to connect to the server
	$mail->Host       = "smtp.hostinger.com";      // setting GMail as our SMTP server
	$mail->Port       = 587;                   // SMTP port to connect to GMail
	$mail->Username   = "test@careers4me.in";  // user email address
	$mail->Password   = "Careers@123";            // password in GMail
	$mail->SetFrom("test@careers4me.in", 'events .');  //Who is sending
	//$mail->addReplyTo( 'events@mrai.org.in', 'events .' );
	$mail->addReplyTo($replyto, $replytoname);
	$mail->Subject    = "A Request For Meeting";
	$mail->IsHTML(true);
	$mail->Body      = $body; 
	//$email ="events@mrai.org.in"; // Who is addressed the email to  
	$mail->AddAddress($email);
	
	$mail->Send();
	
	return $mail;
	
} 


 
?>