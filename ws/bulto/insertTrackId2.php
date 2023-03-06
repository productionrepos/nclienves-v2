<?php

    include_once('../bd/dbconn.php');
    $conn = new bd();

    $json = file_get_contents('php://input');
    $data = json_decode($json);
    
    
    $track = $data->trackid;
    $codigo_barra = $data->codigo_barra;
   
    $conn ->conectar();

    $query = 'UPDATE bulto SET track_spread = "'.$track.'" WHERE codigo_barras_bulto = "'.$codigo_barra.'"';;

    // echo json_encode(array("query"=>$query));
    if($conn->mysqli->query($query))
    {
        echo json_encode(array("status"=>"1","Resultado"=>"Creado"));
    }else{
        echo json_encode(array("status"=>"0","Resultado"=>"Error","Errorlog"=>$conn->mysqli->error));
    }
?>
