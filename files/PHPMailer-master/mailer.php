﻿<?php
include_once('PHPMailerAutoload.php');
$Body = "Hi thanks for ";//file_get_contents('http://www.laptopbattery.co.nz/');
$mail = new PHPMailer(); // create a new object
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
$mail->Host = "smtp.gmail.com";
$mail->Port = 465; // or 587
$mail->IsHTML(true);
$mail->Username = "dan@laptopbattery.co.nz";
$mail->Password = "onholiday1";
$mail->SetFrom("dan@laptopbattery.co.nz");
$mail->Subject = $_POST['s'];
$mail->Body = $_POST['b'];
$mail->AddAddress("dan@antidote.org.nz");
 if(!$mail->Send())
    {
    echo "Mailer Error: " . $mail->ErrorInfo;
    $haystack=$mail->ErrorInfo;
    $needle="SMTP AUTH ERROR";
	 	$index = strpos($haystack, $needle);
   	if($index === false) {return false;}
   	else {echo "SMTP AUTH ERROR";}
  } //send second time hopefully no error? }
else {echo "Message has been sent";}

?>