<?php
session_start();
$id_cliente = $_SESSION['cliente']->id_cliente;

$json = file_get_contents('php://input');
$data = json_decode($json);

include_once('../bd/dbconn.php');
$conn = new bd();
$conn->conectar();


$id_pedido = $data->id_pedido;

$querydeletebul = "UPDATE bulto SET Deleted = 1, deletedBy =".$id_cliente.' where id_pedido='.$id_pedido;
$querydeletepedido = "UPDATE pedido SET IsDeleted = 1, deletedBy =".$id_cliente.' where id_pedido='.$id_pedido;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

if($conn->mysqli->query($querydeletebul) && $conn->mysqli->query($querydeletepedido))
{
    echo json_encode(array("status"=>1,"querytemporal"=>$querydeletebul,"deletepedido"=>$querydeletepedido));
}else{
    echo json_encode(array("status"=>0,"error"=>$conn->mysqli->error));
}

?>