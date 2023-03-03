<?php
    session_start();
    $id_cliente = $_SESSION['cliente']->id_cliente;
    $json = file_get_contents('php://input');

    $data = json_decode($json);
    

    include_once('../bd/dbconn.php');

    $id_cliente = $_SESSION['cliente']->id_cliente;
    $direccion = $data->direccion;
    $numero = $data->numero;
    $detalle = $data->detalle;
    $nombre = $data->nombre;
    $comuna = $data->comuna;
    $conn = new bd();

    $conn ->conectar();
    $queryupdate = "UPDATE bodega SET principal_bodega = 0  WHERE id_cliente =".$id_cliente;
    $conn->mysqli->query($queryupdate);
    $query = "INSERT INTO bodega (id_bodega,nombre_bodega,calle_bodega,numero_bodega,detalle_bodega,principal_bodega,isDelete,DeleteDate,user_delete_id,id_cliente,id_comuna) 
              VALUES(null,'$nombre','$direccion','$numero','$detalle','1','0',null,null,'$id_cliente','$comuna')";
    $checkunique ="SELECT id_bodega from bodega where id_cliente= ".$id_cliente.' and IsDelete = 0';
    //$count = mysqli_num_rows($conn->mysqli->query($checkunique));
              
        if($conn->mysqli->query($query)){
            echo "creada";
        } else {
            echo false;
        }
?>