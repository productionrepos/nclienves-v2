<?php
    session_start();
    $id_cliente = $_SESSION['cliente']->id_cliente;
    include_once('../bd/dbconn.php');
    $idbodega = $_POST['id_bodega'];
    $conn = new bd();
    $conn->conectar();


        $querybodega =  'SELECT bo.nombre_bodega as nombre,
                                bo.calle_bodega as calle, 
                                bo.numero_bodega as numero,
                                co.id_comuna as comuna,
                                re.id_region as region
                                FROM bodega bo
        inner join comuna co on co.id_comuna = bo.id_comuna
        inner join provincia pro on pro.id_provincia = co.id_provincia
        inner join region re on re.id_region = pro.id_region
        where bo.id_cliente ='.$id_cliente.' and bo.id_bodega ='. $idbodega;

   

    if($res = $conn -> mysqli->query($querybodega)){
        
        while($databod = mysqli_fetch_array($res)){
                $nombre = $databod['nombre'];
                $direccion = $databod['calle'];
                $numero = $databod['numero'];
                $comuna = $databod['comuna'];
                $region = $databod['region'];
                $return_array[]=array(
                    "nombre" => $nombre,
                    "direccion" => $direccion,
                    "numero"=> $numero,
                    "comuna" => $comuna,
                    "region" => $region
                );
        }
        echo json_encode($return_array, JSON_FORCE_OBJECT);
    }


