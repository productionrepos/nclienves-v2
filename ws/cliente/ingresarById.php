<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$json = file_get_contents('php://input');
$data = json_decode($json);
$idPedido = $data->IdPedido;

// $idPedido = filter_input(INPUT_POST, "IdPedido");


include('../bd/dbconn.php');
$conexion = new bd();
$conexion->conectar();

$query ="SELECT c.*,dc.* FROM cliente c LEFT JOIN datos_contacto dc ON (c.id_cliente=dc.id_cliente) inner join pedido p on p.id_cliente = c.id_cliente WHERE p.id_pedido =".$idPedido;

if($datos = $conexion->mysqli->query($query)) {
    $datos_cliente = $datos->fetch_object();
    // print_r(json_encode(array("success" => 1, "query bonita" => $query)));
    if($datos->num_rows!=0) {
        $_SESSION['cliente'] = $datos_cliente;
        print_r(json_encode(array("success" => 1, "query validada" => $query)));
    }
    else {
        print_r(json_encode(array("success" => 0, "titulo" => "Incorrecto", "message" => "Pedido no existe", "query" => $query)));
        $conexion->desconectar();
        exit();
    }
}
else {
    print_r(json_encode(array("success" => 0, "query fea" => $query)));
    // print_r(json_encode(array("success" => 0, "message" => "Error validación: ". $conexion->mysqli->error, "query" => $query)));
}

?>
