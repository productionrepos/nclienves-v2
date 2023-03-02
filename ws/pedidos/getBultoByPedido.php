<?php
include_once('../bd/dbconn.php');

    if($_POST){

        $idpedido = $_POST['columnid'];

        $conn = new bd();
        $conn ->conectar();

        $query ='SELECT nombre_bulto as name, direccion_bulto as dir, email_bulto as correo,
                        telefono_bulto as telefono from bulto where id_pedido ='.$idpedido ;

        $existe = false;

        if($res = $conn->mysqli->query($query)){
            $datapedido = array();

            while($datares = mysqli_fetch_array($res))
                {
                    $nombre = $datares['name'];
                    $dir = $datares['dir'];
                    $correo = $datares['correo'];
                    $telefono = $datares['telefono'];
                    

                    $return_array[]=array(
                        "nombre" => $nombre,
                        "direccion"=>$dir,
                        "correo" => $correo,
                        "telefono" => $telefono
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