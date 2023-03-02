<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$email_cliente = filter_input(INPUT_POST, "email_cliente", FILTER_SANITIZE_EMAIL);
$password_cliente = filter_input(INPUT_POST, "password_cliente", FILTER_SANITIZE_STRING);
$password_cliente2 = filter_input(INPUT_POST, "password_cliente2", FILTER_SANITIZE_STRING);

if(!filter_var($email_cliente, FILTER_VALIDATE_EMAIL)):
    print_r(json_encode(array("success" => 0, "message" => "El email ingresado no es válido")));
    exit();
endif;

if(empty($password_cliente) || strlen($password_cliente)<6):
    print_r(json_encode(array("success" => 0, "message" => "La contraseña debe poseer 6 caracteres mínimos")));
    exit();
endif;

if($password_cliente != $password_cliente2):
    print_r(json_encode(array("success" => 0, "message" => "Las contraseñas no coinciden")));
    exit();
endif;




include('../bd/dbconn.php');
$conexion = new bd();
$conexion->conectar();

if($datos = $conexion->mysqli->query("SELECT id_cliente FROM cliente WHERE email_cliente='$email_cliente'")) {
    if($datos->num_rows > 0) {
        print_r(json_encode(array("success" => 0, "message" => "El email ingresado ya se encuentra en uso")));
    }
    else {
        $password_cliente = md5($password_cliente);
        $token_cliente = md5($email_cliente.rand(100, 10000).'registro');
        $insertar = "INSERT INTO cliente (id_cliente, email_cliente, password_cliente, verificado_cliente, alta_cliente, activo_cliente, token_cliente)
                    VALUES (null, '$email_cliente', '$password_cliente', 0, 0, 1, '$token_cliente' )";
        if($conexion->mysqli->query($insertar)) {
            
            include('../include/email_activacion.php');
            mail_activacion('Activación de cuenta', $email_cliente, 'http://'.$_SERVER['HTTP_HOST'].'/completar_registro.php?token='.$token_cliente);
            
            // header("Content-Type: application/json");
            print_r(json_encode(array("success" => 1, "message" => "Hemos enviado un correo electrónico a $email_cliente para validar su cuenta, en 
                                caso de no recibirlo revise su carpeta de correos no deseados [SPAM]")));
        }
        else {
            print_r(json_encode(array("success" => 0, "message" => 'Error registro: '.$conexion->mysqli->error)));
        }
    }
}
else {
    print_r(json_encode(array("success" => 0, "message" => 'Error validación: '. $conexion->mysqli->error)));
}
$conexion->desconectar();