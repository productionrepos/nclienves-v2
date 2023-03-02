<?php
    include_once('../bd/dbconn.php');
    $conn = new bd();
    $conn ->conectar();

    $id_bulto = $_POST['id_bulto'];
    $correo = $_POST['correo'];

    $querydelete = 'UPDATE bulto SET Deleted = 1, deletedBy ="'.$correo.'" where id_bulto ='.$id_bulto;

    if($ress = $conn->mysqli->query($querydelete)){
        echo "Pedido Eliminado Exitosamente";
    }

?>