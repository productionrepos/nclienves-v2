<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$password_cliente = filter_input(INPUT_POST, "password_cliente", FILTER_SANITIZE_STRING);
$password_cliente2 = filter_input(INPUT_POST, "password_cliente2", FILTER_SANITIZE_STRING);
$token_cliente = filter_input(INPUT_POST, "token_cliente", FILTER_SANITIZE_STRING);

if(empty($password_cliente) || strlen($password_cliente)<6):
    print_r(json_encode(array("success" => 0, "message" => "La contraseña debe poseer 6 caracteres mínimos")));
    exit();
endif;

if($password_cliente != $password_cliente2):
    print_r(json_encode(array("success" => 0, "message" => "Las contraseñas no coinciden")));
    exit();
endif;

if(strlen($token_cliente)!=32):
    print_r(json_encode(array("success" => 0, "message" => "Tokén inválido")));
    exit();
endif;



include_once('../bd/dbconn.php');
$conexion = new bd();
$conexion->conectar();

if($datos = $conexion->mysqli->query("SELECT * FROM cliente WHERE token_cliente='$token_cliente' AND verificado_cliente=1")) {
    if($datos->num_rows==1) {
        $datos_cliente = $datos->fetch_object();
        $token_cliente = md5($datos_cliente->email_cliente.rand(1000, 10000).'cambio');
        $password_cliente = md5($password_cliente);
        if($conexion->mysqli->query("UPDATE cliente SET password_cliente='$password_cliente', token_cliente='$token_cliente' WHERE id_cliente=$datos_cliente->id_cliente")) {
            print_r(json_encode(array("success" => 1, "message" => "Contraseña cambiada correctamente")));
        }
        else {
            print_r(json_encode(array("success" => 0, "message" => $conexion->mysqli->error)));
            $conexion->desconectar();
            exit();
        }
    }
    else {
    print_r(
        json_encode(array("success" => 0, "message" => "Tokén inválido")));
        $conexion->desconectar();
        exit();
    }
}
else {
    print_r(json_encode(array("success" => 0, "message" => $conexion->mysqli->error)));
}
$conexion->desconectar();