<?php
include_once('../bd/dbconn.php');

    if($_POST){

        $idpedido = $_POST['columnid'];

        $conn = new bd();
        $conn ->conectar();

        $query ='SELECT b.nombre_bulto as name, b.direccion_bulto as dir, b.email_bulto as correo,
                b.telefono_bulto as telefono,b.estado_logistico,p.timestamp_pedido,b.track_spread
                from bulto b
                inner join pedido  p on b.id_pedido = p.id_pedido
                where b.id_pedido = '.$idpedido ;

        $existe = false;

        if($res = $conn->mysqli->query($query)){
            $datapedido = array();

            while($datares = mysqli_fetch_array($res))
                {
                    $track = $datares['track_spread'];
                    $nombre = $datares['name'];
                    $dir = $datares['dir'];
                    $correo = $datares['correo'];
                    $telefono = $datares['telefono'];
                    $estado = $datares['estado_logistico'];
                    $fecha_creacion = date("d-m-Y H:i:s",$datares['timestamp_pedido']);

                    $return_array[]=array(
                        "track" => $track,
                        "nombre" => $nombre,
                        "direccion"=>$dir,
                        "correo" => $correo,
                        "telefono" => $telefono,
                        "estado" => $estado,
                        "fecha_creacion" => $fecha_creacion
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