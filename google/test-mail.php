<?php
require_once 'C:\Users\Administrator\vendor\autoload.php';
 

    // Create the SMTP Transport
//$transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
//        ->setUsername('dansuternz@gmail.com')
 //       ->setPassword('nbbyvjfrnzbktlbp');

$transport = (new Swift_SmtpTransport('smtp.zoho.com', 587, 'tls'))
        ->setUsername('dan@antidote.org.nz')
        ->setPassword('Lovelifenow');
 
    // Create the Mailer using your created Transport
    
// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

// Create a message
$message = (new Swift_Message('Wonderful Subject'))
  ->setFrom(['dan@antidote.org.nz' => 'Daniel Suter'])
  ->setTo(['dansuternz@gmail.com', 'other@domain.org' => 'Daniel Suter'])
  ->setBody('Here is the message itself')
  ;

// Send the message
$result = $mailer->send($message);
