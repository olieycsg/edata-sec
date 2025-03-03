<?php 

$smtpEmail = 'noreply.sabahenergy@gmail.com';
$smtpPassw = 'xhhuywqkehkcgftb';

$mail->SMTPDebug = 2;
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = $smtpEmail;
$mail->Password = $smtpPassw;
$mail->Port = 587;

?>