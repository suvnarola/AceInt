<?php

// Cron Test.

//$site_domain =  trim(str_replace("www.", "", $_SERVER['SERVER_NAME']));
//$emailMessage = "Cron is Working on - ".$site_domain.".";
$emailMessage = "admin.aceint.com.au - Cron is Working.";

echo $emailMessage;

//sendmail($emailMessage, $site_domain);
sendmail($emailMessage);



/*
function sendmail($m, $d)
{
	$to = "info@atomicwebstrategy.com";
	$subject = "Cron Test from: ".$d;
	$message = $m;
	$from = "noreply@".$d;
	$headers = "From: $from";
	mail($to, $subject, $message, $headers);      // Send Mail.
}
*/

function sendmail($m)
{
	$to = "info@atomicwebstrategy.com";
	$subject = "Cron Test";
	$message = $m;
	$from = "noreply@nowhere.nothing";
	$headers = "From: $from";
	mail($to, $subject, $message, $headers);      // Send Mail.
}
?>
