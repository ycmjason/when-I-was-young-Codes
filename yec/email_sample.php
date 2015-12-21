<?php
require("./ phpmailer/class.phpmailer.php");

$myusername = $_GET["myusername"];
$message = $myusername."This is a testing message for Jason.";

				// // Send a thank you letter
				$mail = new PHPMailer();
				$mail->IsSMTP(); // send via SMTP
				$mail->Host = "smtp.pacific.net.hk"; // your ISP SMTP servers
				$mail->SMTPAuth = False; // turn on SMTP authentication
				$mail->From = "yec@unicef.org.hk";
				$mail->FromName = "Unicef <yec@unicef.org.hk>";
				$mail->AddAddress("ycm.jason@gmail.com");
				$mail->WordWrap = 50; // set word wrap
				$mail->IsHTML(true); // send as HTML
				$mail->Subject = "Testing Email for Jason";
                $mail->Body = $message;
				$mail->Send();	
				
?>



