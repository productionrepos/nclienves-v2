<?php
session_start();
$id_cliente = $_SESSION['cliente']->id_cliente;

$id_pedido = filter_input(INPUT_POST, "id_pedido", FILTER_SANITIZE_NUMBER_INT);
$token = filter_input(INPUT_POST, "token", FILTER_SANITIZE_STRING);

if(empty($id_pedido) && !is_numeric($id_pedido)):
    echo (json_encode(array("success" => 0, "message" => "Algo anda muy mal")));
    exit();
endif;

if(empty($token) || strlen($token)!=32):
    echo (json_encode(array("success" => 0, "message" => "Se requiere el token, intente recargando la pagina")));
    exit();
endif;

if($token!=md5($id_pedido.$id_cliente."pedido_credito#")) {
    echo (json_encode(array("success" => 0, "message" => "Token invalido, intente recargando la pagina")));
    exit();
}

include('../bd/dbconn.php');
$conexion = new bd();
$conexion->conectar();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

if($conexion->mysqli->query("UPDATE pedido SET estado_pedido=3, estado_logistico=1 WHERE id_pedido=$id_pedido")) {
    echo json_encode(array("success" => 1, "message" => "Su pago a credito ha sido procesado exitosamente"));
}
else {
	echo json_encode(array("success" => 0, "message" => $conexion->mysqli->error));
}

$conexion->desconectar();