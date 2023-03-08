<?php
    session_start();
    include_once('../bd/dbconn.php');
    $id_cliente  = $_SESSION['cliente']->id_cliente;

    $json = file_get_contents('php://input');
    $data = json_decode($json);

    $conn = new bd();
    $conn->conectar();

    $name = $data->name;
    $apellido = $data->apellido;
    $correo = $data->correo;
    $rut = $data->rut;
    $telefono = $data->telefono;

    $queryupdatedata = 'UPDATE datos_contacto set nombres_datos_contacto = "'.$name.'" ,apellidos_datos_contacto= "'.$apellido.
                                            '" ,rut_datos_contacto= "'.$rut.'" ,telefono_datos_contacto = "'.
                                             $telefono.'" ,email_datos_contacto= "'.$correo.'" where id_cliente = '.$id_cliente;

    $querysession ='SELECT * FROM cliente LEFT JOIN datos_contacto ON (cliente.id_cliente=datos_contacto.id_cliente)
                             WHERE cliente.id_cliente='.$id_cliente;

    

    if($conn->mysqli->query($queryupdatedata)){
        echo json_encode(array("status"=>1,"response"=>"Datos modificados exitosamente"));
        $datos = $conn->mysqli->query ($querysession);  
        $datos_cliente = $datos->fetch_object();
        $_SESSION['cliente'] = $datos_cliente;

    }else{
        echo json_encode(array("status"=>0,"response"=>"Error al modificar los datos"));
    }
?>