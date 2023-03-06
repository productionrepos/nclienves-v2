<?php
    include_once('../bd/dbconn.php');
    $conn = new bd();
    $conn->conectar();
    session_start();
    $id_cliente = $_SESSION['cliente']->id_cliente;

    $query = 'Select * from cliente_frecuente
                where id_cliente='.$id_cliente;

    if($response = $conn->mysqli->query($query)){

        while($databod = mysqli_fetch_array($response)){
            $id = $databod['id'];
            $nombre = $databod['nombre'];
            $direccion = $databod['direccion'];
            $rut = $databod['rut'];
            $return_array[]=array(
                "id" => $id,
                "nombre" => $nombre,
                "direccion"=> $direccion,
                "rut" => $rut
            );
    }
        echo json_encode($return_array, JSON_FORCE_OBJECT);
    }
?>