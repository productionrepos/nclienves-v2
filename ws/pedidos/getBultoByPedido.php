<?php
include_once('../bd/dbconn.php');

    if($_POST){

        $idpedido = $_POST['columnid'];

        $conn = new bd();
        $conn ->conectar();

        $query ='SELECT nombre_bulto as name, direccion_bulto as dir, precio_bulto as precio from bulto where id_pedido ='.$idpedido;

        $existe = false;

        if($res = $conn->mysqli->query($query)){
            $datapedido = array();

            while($datares = mysqli_fetch_array($res))
                {
                    $nombre = $datares['name'];
                    $dir = $datares['dir'];
                    $precio = $datares['precio'];

                    $return_array[]=array(
                        "nombre" => $nombre,
                        "direccion"=>$dir,
                        "precio" => $precio
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