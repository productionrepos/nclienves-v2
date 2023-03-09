<?php
    include_once('../bd/dbconn.php');
    $conn = new bd();
    $conn->conectar();
    $id = $_POST['id'];

    $query = 'Select nombre, rut, telefono,calle,numero,casablock, correo,region, comuna from cliente_frecuente
                where id="'.$id.'"';


    if($response = $conn->mysqli->query($query)){
        $datafrecli [] = $response->fetch_object();

       echo json_encode($datafrecli);
    }

?>