<?php

include('mailer/PHPMailerAutoload.php');
header("Content-Type: text/html; charset=utf-8");

function mail_olvido_password ($asunto, $email, $url) {
	
	$html = '';
	include_once('../../include/email_header.php');
	$html = $html.' '.email_header($asunto);

	include_once('../../include/email_boton.php');
	$html = $html.' '.email_boton($asunto,$url);

	include_once('../../include/email_separador.php');
	$html = $html.' '.$email_separador_oscuro;

	include_once('../../include/email_footer.php');
	$html = $html.' '.$email_footer;


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

	$mail->Host = getenv('smtp_host');
	$mail->SMTPAuth = true;
	$mail->Username = getenv('smtp_username');
	$mail->Password = getenv('smtp_pass');
	$mail->SMTPSecure = 'tls';
	$mail->Port = getenv('smtp_port');

	$mail->From = getenv('smtp_username');

	$mail->FromName = 'Spread';
	$mail->addAddress($email);
	$mail->isHTML(true);

	$mail->Subject =  $asunto;
	$mail->Body    =  $html;
	$mail->AltBody = 'Es imposible enviar su contenido por que no soporta html';
	$mail->send();
};
