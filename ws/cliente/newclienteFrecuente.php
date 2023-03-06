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
    $numerodir = $data->numerodir;
    $casablock = $data->casablock;
    $timestamp = time();
    $conn ->conectar();

    
    $querybuscarclifre = 'SELECT rut from cliente_frecuente where LOWER(nombre) ="'.strtolower($nombre).'" and LOWER(calle)= "'.strtolower($direccion) .'"';
            
    if(mysqli_num_rows($conn->mysqli->query($querybuscarclifre))==0){
       
        
        $queryinsertclifre = 'INSERT INTO cliente_frecuente (rut, nombre, calle, numero,casablock,correo, telefono, comuna, region, id_cliente)
        VALUES("'.$rut.'","'.$nombre.'","'.$direccion.'","'.$numerodir.'","'.$detalle.'","'.$correo.'",'.$telefono.','.$comuna.','.$region.','.$id_cliente.')';
           
        
        if( $conn->mysqli->query($queryinsertclifre))
        {
            echo json_encode(array("status"=>"1","Resultado"=>"Creado","insert"=>$conn->mysqli->insert_id));
        }else{
            echo json_encode(array("status"=>"0","Resultado"=>"Error","Errorlog"=>$conn->mysqli->error));
        }
    }







   
?>