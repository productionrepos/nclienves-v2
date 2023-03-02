<?php
    require_once('../bd/dbconn.php');
    $conn = new bd();
    $conn -> conectar();
    $json = file_get_contents('php://input');

    $data = json_decode($json);
    
    $nombre_bulto = $data->nombre;
    $telefono_bulto = $data->telefono;
    $direccion_bulto = $data->direccion;
    $email_bulto = $data->correo;
    $nombre_comuna = $data->comuna;
    $descripcion_bulto = $data->item;
    $valor_declarado_bulto = $data->costo;
    $id_paquete  = $data->tipo;
    $id_bulto  = $data ->id_bulto;
    
    $id_tipo = 0;
    if ($id_paquete == "Mini") {
        $id_tipo = 1;
    }
    if ($id_paquete == "Medium") {
        $id_tipo = 2;
    }


    
    $querycomuna = 'SELECT id_comuna as id from comuna where nombre_comuna ="'.  $nombre_comuna.'"';
   

    if($rescomuna = $conn->mysqli->query($querycomuna)){
        $resid_comuna = $rescomuna->fetch_object();
    }

    $id_comuna = $resid_comuna->id;

  




    $querymodbulto ='UPDATE bulto SET NOMBRE_BULTO ="'. $nombre_bulto .'", telefono_bulto ='. $telefono_bulto.',
                                      direccion_bulto ="'. $direccion_bulto .'", email_bulto = "'.$email_bulto.'",
                                      id_comuna ='. $id_comuna.', descripcion_bulto ="'. $descripcion_bulto.'", 
                                      valor_declarado_bulto ='. $valor_declarado_bulto.', id_paquete ='. $id_tipo.'
                                      where id_bulto ='. $id_bulto;  
    // echo json_encode($querymodbulto);
    if($resmodbulto = $conn->mysqli->query($querymodbulto)){
       echo json_encode(array("status"=>1,"response"=>"bulto modificado exitosamente"));
    }
    else{
        echo json_encode(array("status"=>0,"response"=>"No se ha podido moficar el bulto ".$resmodbulto->mysqli->error_log));
    }
?>