<?php
session_start();
include_once('../bd/dbconn.php');
$id_cliente  = $_SESSION['cliente']->id_cliente;

$json = file_get_contents('php://input');
$data = json_decode($json);

$conn = new bd();
$conn->conectar();

$fancom = $data->fancom;
$dircom = $data->dircom;
$numcom = $data->numcom;
$rutcom = $data->rutcom;
$telcom = $data->telcom;
$razonsocial = $data->razonsocial;
$comuna = $data->comuna;
$action = $data->action;

if($action == "update"){
    $queryupdatedata = 'UPDATE datos_contacto set nombre_fantasia_datos_comerciales = "'.$fancom.'" ,rut_datos_comerciales = "'.$rutcom.'" 
                                              ,razon_social_datos_comerciales="'.$razonsocial.'" ,telefono_datos_comerciales="'.$telcom.'"
                                              ,calle_datos_comerciales = "'.$dircom.'" ,numero_datos_comerciales="'.$numcom.'" 
                                              ,id_comuna = '.$comuna.' where id_cliente = '.$id_cliente;
                                         


    $querysession ='SELECT * FROM cliente LEFT JOIN datos_contacto ON (cliente.id_cliente=datos_contacto.id_cliente)
                            WHERE cliente.id_cliente='.$id_cliente;


    echo json_encode(array("status"=>0,"response"=>$queryupdatedata));
    if($conn->mysqli->query($queryupdatedata)){
        echo json_encode(array("status"=>1,"response"=>"Datos modificados exitosamente"));
        // $datos = $conn->mysqli->query ($querysession);  
        // $datos_cliente = $datos->fetch_object();
        // $_SESSION['cliente'] = $datos_cliente;

    }else{
        echo json_encode(array("status"=>0,"response"=>"Error al modificar los datos"));
    }

}
if($action=="insert"){
    $queryinsert = 'INSERT INTO datos_comerciales (nombre_fantasia_datos_comerciales,rut_datos_comerciales,razon_social_datos_comerciales,
                                                   telefono_datos_comerciales,calle_datos_comerciales,numero_datos_comerciales,id_cliente,id_comuna)
                    VALUES("'.$fancom.'","'.$rutcom.'","'.$razonsocial.'","'.$telcom.'","'.$dircom.'","'.$numcom.'",'.$id_cliente.','.$comuna.')';

    $querysession ='SELECT * FROM cliente LEFT JOIN datos_contacto ON (cliente.id_cliente=datos_contacto.id_cliente)
                    WHERE cliente.id_cliente='.$id_cliente;


    if($conn->mysqli->query($queryupdatedata)){
    echo json_encode(array("status"=>1,"response"=>"Datos Creados exitosamente"));
    // $datos = $conn->mysqli->query ($querysession);  
    // $datos_cliente = $datos->fetch_object();
    // $_SESSION['cliente'] = $datos_cliente;

    }else{
    echo json_encode(array("status"=>0,"response"=>"Error al modificar los datos"));
    }

}





?>