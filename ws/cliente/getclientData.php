<?php
    require_once('/xampp/htdocs/nclientesv2/ws/bd/dbconn.php');
    
    session_start();
    if($_POST){

        $id_cliente = $_POST['id_cliente'];
        $conn = new bd();
        $conn ->conectar();
        
        $query = 'Select nombres_datos_contacto as nombre,
                        apellidos_datos_contacto as apellidos,
                        rut_datos_contacto as rut,
                        telefono_datos_contacto as telefono,
                        email_datos_contacto as correo
                        from datos_contacto where id_cliente ='.$id_cliente;


        
        
        if($res = $conn->mysqli->query($query)){

            while($datares = mysqli_fetch_array($res))
                {
                    $nombre = $datares['nombre'];
                    $apellido = $datares['apellidos'];
                    $rut = $datares['rut'];
                    $telefono = $datares['telefono'];
                    $email = $datares['correo'];
                    $return_array[]=array(
                        "nombre" => $nombre,
                        "apellido"=>$apellido,
                        "rut" => $rut,
                        "telefono" => $telefono,
                        "correo" => $email
                    );
            }
            echo json_encode($return_array);
        }
        else{
            echo $conn->mysqli->error;
            exit();
        }
    }





?>