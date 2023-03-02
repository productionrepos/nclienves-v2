<?php 
    include_once('../bd/dbconn.php');
    $conn = new bd();
    $conn -> conectar();


    $json = file_get_contents('php://input');
    $data = json_decode($json);
    

    $nombre = $data->nombre;
    $direccion = $data->direccion;
    $numero = $data->numero;
    $comuna = $data->comuna;
    $id = $data->id;


    $querymod = 'UPDATE bodega set nombre_bodega="'.$nombre.'", calle_bodega="'.$direccion.'", numero_bodega='.$numero.
                                   ',id_comuna ='.$comuna.' where id_bodega='.$id;

    // echo json_encode($querymod);
    if($resupdate = $conn->mysqli->query($querymod)){   
        echo json_encode(array("status"=>1,"message"=>"Bodega Modificada exitosamente"));
    }

    
?>