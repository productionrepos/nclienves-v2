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
    }else{
        echo json_encode(array("status"=>"0","Resultado"=>"Error","Errorlog"=>$conn->mysqli->error));
    }
?>

<!-- `\r\n\r\n<br />\n<b>Warning</b>:  Undefined property: stdClass::$track in <b>E:\\xampp_8.0\\htdocs\\nclienves-v2\\ws\\bulto\\insertTrackId.php</b> on line <b>10</b><br />\n{"status":"0","Resultado":"Error","Errorlog":"Unknown column 'codigo_barra_bulto' in 'where clause'"}` -->