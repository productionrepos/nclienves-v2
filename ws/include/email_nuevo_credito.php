<?php

include('../include/mailer/PHPMailerAutoload.php');
header("Content-Type: text/html; charset=utf-8");

function mail_nuevo_credito ($asunto, $email, $html) {

    $mail = new PHPMailer;

    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    //$mail->SMTPDebug = 1;
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();

    $mail->Host = 'smtp.gmail.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'noreply2@spread.cl';
	$mail->Password = 'Spread_22';
	$mail->SMTPSecure = 'tls';
	$mail->Port = 587;

	$mail->From = 'noreply2@spread.cl';

    // $mail->Host = getenv('smtp_host');
    // $mail->SMTPAuth = true;
    // $mail->Username = getenv('smtp_username');
    // $mail->Password = getenv('smtp_pass');
    // $mail->SMTPSecure = 'tls';
    // $mail->Port = getenv('smtp_port');

    // $mail->From = getenv('smtp_username');

    $mail->FromName = 'Spread';
    $mail->addAddress($email);
    $mail->isHTML(true);

    $mail->Subject =  $asunto;
    $mail->Body    =  $html;
    $mail->AltBody = 'Es imposible enviar su contenido por que no soporta html';
    $mail->send();
};
