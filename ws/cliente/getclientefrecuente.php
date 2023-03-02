<?php
    include_once('../bd/dbconn.php');
    $conn = new bd();
    $conn->conectar();
    $rut = $_POST['rut'];

    $query = 'Select nombre, rut, telefono,direccion, correo,region, comuna from cliente_frecuente
                where rut="'.$rut.'"';


    if($response = $conn->mysqli->query($query)){
        $datafrecli [] = $response->fetch_object();

       echo json_encode($datafrecli);
    }

?>