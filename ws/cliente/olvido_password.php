<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$json = file_get_contents('php://input');
$data = json_decode($json);

$email_cliente = $data->email_cliente;

if(!filter_var($email_cliente, FILTER_VALIDATE_EMAIL)):
    print_r(json_encode(array("success" => 0, "message" => "El email ingresado no es válido")));
    exit();
endif;

include_once('../bd/dbconn.php');
$conexion = new bd();
$conexion->conectar();


if($datos = $conexion->mysqli->query("SELECT * FROM cliente WHERE email_cliente='$email_cliente'")) {
    if($datos->num_rows==1) {
        $datos_cliente =  $datos->fetch_object();

        $token_cliente = $datos_cliente->token_cliente;
        $email_cliente = $datos_cliente->email_cliente;
        include('../include/email_olvido_password.php');
        $url = 'http://'.$_SERVER['HTTP_HOST'].'/cambiar_password.php?token='.$token_cliente;
        mail_olvido_password('Reinicio de contraseña', $email_cliente, $url);
        
        // header("Content-Type: application/json");
        print_r(json_encode(array("success" => 1, "message" => "Hemos enviado un correo electrónico a $email_cliente para reiniciar su contraseña, en caso de no recibirlo revise su carpeta de correos no deseados [SPAM]")));
    }
    else {
        print_r(json_encode(array("success" => 0, "message" => "El email ingresado no se encuentra en nuestros registros")));
    }
}
else {
    print_r(json_encode(array("success" => 0, "message" => 'Error validación: '. $conexion->mysqli->error)));
}
$conexion->desconectar();