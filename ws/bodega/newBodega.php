<?php
    session_start();
    $id_cliente = $_SESSION['cliente']->id_cliente;
    $json = file_get_contents('php://input');

    $data = json_decode($json);
    

    include_once('/xampp/htdocs/nclientesv2/ws/bd/dbconn.php');

    $id_cliente = $_SESSION['cliente']->id_cliente;
    $direccion = $data->direccion;
    $numero = $data->numero;
    $nombre = $data->nombre;
    $comuna = $data->comuna;
    $conn = new bd();

    $conn ->conectar();
    
    $query = "INSERT INTO bodega (id_bodega,nombre_bodega,calle_bodega,numero_bodega,principal_bodega,isDelete,DeleteDate,user_delete_id,id_cliente,id_comuna) 
              VALUES(null,'$nombre','$direccion','$numero','0','0',null,null,'$id_cliente','$comuna')";
    $checkunique ="SELECT id_bodega from bodega where id_cliente= ".$id_cliente.' and IsDelete = 0';
    $count = mysqli_num_rows($conn->mysqli->query($checkunique));
              
        if($conn->mysqli->query($query)){

            $last  = $conn->mysqli->insert_id;
            
            if($count==0){
                $updatequery = "UPDATE bodega SET principal_bodega = 1 WHERE id_bodega=".$last;
                if($conn->mysqli->query($updatequery)){
                    echo json_encode(array("status"=> 2, "accion"=>"Creado y Principal"));
                }
            }else{
                echo json_encode(array("status"=> 1, "accion"=>"Creado","count"=>$count,"lsat"=>$last,"checkunique"=>$checkunique));
            }
        } else {
            echo false;
        }
?>