<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$nombres_datos_contacto = filter_input(INPUT_POST, "nombres_datos_contacto", FILTER_SANITIZE_STRING);
$apellidos_datos_contacto = filter_input(INPUT_POST, "apellidos_datos_contacto", FILTER_SANITIZE_STRING);
$rut_datos_contacto = filter_input(INPUT_POST, "rut_datos_contacto", FILTER_SANITIZE_STRING);
$telefono_datos_contacto = filter_input(INPUT_POST, "telefono_datos_contacto", FILTER_SANITIZE_NUMBER_INT);
$email_datos_contacto = filter_input(INPUT_POST, "email_datos_contacto", FILTER_SANITIZE_EMAIL);
$token_cliente = filter_input(INPUT_POST, "token_cliente", FILTER_SANITIZE_STRING);



if(empty($nombres_datos_contacto) || strlen($nombres_datos_contacto)<2):
    print_r(json_encode(array("success" => 0, "message" => "Los nombres debe poseer 2 caracteres mínimos")));
    exit();
endif;


if(empty($apellidos_datos_contacto) || strlen($apellidos_datos_contacto)<6):
    print_r(json_encode(array("success" => 0, "message" => "Los apellidos debe poseer 6 caracteres mínimos")));
    exit();
endif;


if(empty($rut_datos_contacto) || strlen($rut_datos_contacto)<6):
    print_r(json_encode(array("success" => 0, "message" => "El rut debe poseer 6 caracteres mínimos")));
    exit();
endif;


if(empty($telefono_datos_contacto) || strlen($telefono_datos_contacto)!=9 || !is_numeric($telefono_datos_contacto)):
    print_r(json_encode(array("success" => 0, "message" => "El teléfono debe poseer 9 números")));
    exit();
endif;

if(!filter_var($email_datos_contacto, FILTER_VALIDATE_EMAIL)):
    print_r(json_encode(array("success" => 0, "message" => "El email ingresado no es válido")));
    exit();
endif;

if(strlen($token_cliente)!=32):
    print_r(json_encode(array("success" => 0, "message" => "Tokén inválido")));
    exit();
endif;


include_once('../bd/dbconn.php');
$conexion = new bd();
$conexion->conectar();


if($datos = $conexion->mysqli->query("SELECT * FROM cliente WHERE token_cliente='$token_cliente' AND verificado_cliente=0")) {
    if($datos->num_rows==1) {
        $datos_cliente = $datos->fetch_object();
        $completar_registro = "INSERT INTO datos_contacto (id_datos_contacto, nombres_datos_contacto, apellidos_datos_contacto, rut_datos_contacto, telefono_datos_contacto, email_datos_contacto, id_cliente) 
                                                    VALUES (null, '$nombres_datos_contacto', '$apellidos_datos_contacto', '$rut_datos_contacto', '$telefono_datos_contacto', '$email_datos_contacto', $datos_cliente->id_cliente)";
        if($conexion->mysqli->query($completar_registro)) {
            $token_cliente = md5($datos_cliente->email_cliente.rand(1000, 10000).'verificado');
            if($conexion->mysqli->query("UPDATE cliente SET verificado_cliente=1, token_cliente='$token_cliente' WHERE id_cliente=$datos_cliente->id_cliente")) {
                print_r(json_encode(array("success" => 1, "message" => "Usuario validado correctamente")));
            }
            else {
                print_r(json_encode(array("success" => 0, "message" => $conexion->mysqli->error)));
                $conexion->desconectar();
                exit();
            }
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

