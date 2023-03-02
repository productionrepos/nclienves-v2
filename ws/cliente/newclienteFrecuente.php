<?php

    session_start();  
    $id_cliente = $_SESSION['cliente']->id_cliente;

    include_once('../bd/dbconn.php');
    $conn = new bd();
   
  
    

    $json = file_get_contents('php://input');
    $data = json_decode($json);
    
    
    $nombre = $data->nombre;
    $direccion = $data->direccion;
    $telefono = $data->telefono;
    $correo = $data->correo;
    $item = $data->item;
    $costo = $data->costo;
    $idpaquete = $data->idpaquete;
    $comuna = $data->comuna;
    $region = $data->region;
    $id_bodega = $data->idbodega;
    $rut = $data->rut;
    $timestamp = time();


   
    $conn ->conectar();

    $query = 'INSERT INTO cliente_frecuente (rut, nombre, direccion, descripciondir,correo, telefono, comuna, region, id_cliente)
                    VALUES("'.$rut.'","'.$nombre.'","'.$direccion.'",null,"'.$correo.'",'.$telefono.','.$comuna.','.$region.','.$id_cliente.')';

    if($conn->mysqli->query($query))
    {
        echo json_encode(array("status"=>"1","Resultado"=>"Creado","insert"=>$conn->mysqli->insert_id));
    }else{
        echo json_encode(array("status"=>"0","Resultado"=>"Error","Errorlog"=>$conn->mysqli->error));
    }
?>