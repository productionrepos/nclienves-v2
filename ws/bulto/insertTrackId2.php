<?php

    include_once('../bd/dbconn.php');
    $conn = new bd();

    $json = file_get_contents('php://input');
    $data = json_decode($json);
    
    
    $track = $data->trackid;
    $codigo_barra = $data->codigo_barra;
   
    $conn ->conectar();
    //BUSCAR TRACKID SI EXITE EN BULTO MANDAR RESPONSE STATUS 3 
    // $queryiftrack = 'SELECT track_spread FROM bulto WHERE codigo_barras_bulto = "'.$codigo_barra.'"';
    // if(mysqli_num_rows($conn->mysqli->query($queryiftrack))==0){
        $query = 'UPDATE bulto SET track_spread = "'.$track.'" WHERE codigo_barras_bulto = "'.$codigo_barra.'"';
        // echo json_encode(array("query"=>$query));
        if($conn->mysqli->query($query))
        {
            echo json_encode(array("status"=>"1","Resultado"=>"Creado"));
        }else{
            echo json_encode(array("status"=>"0","Resultado"=>"Error","Errorlog"=>$conn->mysqli->error));
        }
    // }
    // else{
    //     echo json_encode(array("status"=>"3","Resultado"=>"Error"));
    // }
    
?>
