<?php
    include_once('../bd/dbconn.php');
    
    $conn = new bd();
    $conn -> conectar();
    $json = file_get_contents('php://input');

    $data = json_decode($json);


    $id_pedido = $data->id_pedido;

    $query = "SELECT id_bulto from bulto where id_pedido=".$id_pedido;

    if(mysqli_num_rows($conn->mysqli->query($query))>0){
        echo json_encode(array("status"=>1));
    }
    else{
        echo json_encode(array("status"=>0));
    }
?>