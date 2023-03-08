<?php
    session_start();
    include_once('../bd/dbconn.php');

    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $conn = new bd();
    $conn->conectar();

    $id_cliente = $_SESSION['cliente']->id_cliente;

    $pass = $data->pass;
    $newpass = $data->newpass;
    $confirmnewpass = $data->confirmnewpass;
    $action = $data->action;

    
    $querycurrentpass = "SELECT password_cliente as pass from cliente where id_cliente=".$id_cliente;

    $responsepass = $conn->mysqli->query($querycurrentpass);
    $datapass = $responsepass->fetch_object();
    $currentpass = $datapass->pass;
    // echo json_encode($currentpass);
    
    if($action == "currentpass" ){
        if(md5($pass)  == $currentpass){
            echo json_encode(array("status"=>1,"response"=>"Las contrasenas coinciden"));
        }else{
            echo json_encode(array("status"=>0,"response"=>"Tu contraseña actual es distinta a la ingresada"));
        }
    }

    if($action == "changepass"){
        $updatepass = 'UPDATE cliente set password_cliente= "'.md5($newpass).'" where id_cliente ='.$id_cliente; 
        if($conn->mysqli->query($updatepass)){
            $datos = $conn->mysqli->query ($querysession);  
            $datos_cliente = $datos->fetch_object();
            $_SESSION['cliente'] = $datos_cliente;
            echo json_encode(array("status"=>1,"response"=>"Contraseña cambiada exitosamente"));
        }else{
            echo json_encode(array("status"=>0,"query"=>"Ha ocurrido un error, intente nuevamente"));
        }
       
    }

   

    
?>