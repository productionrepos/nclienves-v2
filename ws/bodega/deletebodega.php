<?php
    date_default_timezone_set("America/Santiago");
    include_once('../bd/dbconn.php');
    session_start();
    $id_cliente = $_SESSION['cliente']->id_cliente;
    $json = file_get_contents('php://input');
    $data = json_decode($json);

    $conn = new bd();
    $conn->conectar();

    $id = $data->id_bodega;
    $idnewmain= 0;
    $date = date('Y-m-d H:i:s');

    $querydelete = 'UPDATE bodega set IsDelete = 1, principal_bodega = 0,user_delete_id='.$id_cliente.", DeleteDate ='".$date.
                                      "' where id_bodega= ".$id;
    $querynewmain = 'SELECT id_bodega, nombre_bodega from bodega where id_cliente ='.$id_cliente.' and IsDelete = 0 limit 1';
    $lookifmain = 'SELECT principal_bodega from bodega where id_bodega='.$id;
    $lookmain = $conn->mysqli->query($lookifmain);

    $maincheck = $lookmain->fetch_object();

    $main = $maincheck->principal_bodega;
    if($querydelete = $conn->mysqli->query($querydelete)){

        if($main ==1){
            $ressnewmain = $conn -> mysqli->query($querynewmain);
            $setnewmain = $ressnewmain->fetch_object();
            $idnewmain = $setnewmain->id_bodega;
            $nombrenewmain = $setnewmain->nombre_bodega;
            $updatenewmain = 'UPDATE bodega set principal_bodega =1 where id_bodega ='.$idnewmain;
            if($conn->mysqli->query($updatenewmain)){
                echo json_encode(array("status"=>1,"message"=>"Bodega eliminada exitosamente","namenewmain"=>$nombrenewmain,
                                        "querynewmain"=> $main));
            }
        }
        if($main == 0){
            echo json_encode(array("status"=>2,"message"=>"Bodega eliminada exitosamente","main"=>$main));
        }
        
    }
?>