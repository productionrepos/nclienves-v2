<?php

    $id_bulto = $_POST['id_bulto'];
    require_once('/xampp/htdocs/nclientesv2/ws/bd/dbconn.php');

    $conn = new bd();

    $conn->conectar();

    $query = 'SELECT bu.nombre_bulto as nombre,
                     bu.direccion_bulto as direccion,
                      bu.telefono_bulto as telefono,
                       bu.email_bulto as correo,
                       bu.valor_declarado_bulto as valor,
                       bu.descripcion_bulto as item,
                       bu.id_paquete as servicio,
                       re.id_region as region,
                       bu.id_comuna as comuna
              from bulto bu 
              INNER JOIN comuna co on co.id_comuna = bu.id_comuna 
              inner join provincia pro on pro.id_provincia = co.id_provincia
              inner join region re on re.id_region = pro.id_region 
              where bu.id_bulto ='. $id_bulto;


    //echo json_encode( $query);

    if($res = $conn -> mysqli->query($query)){
        while($datares = mysqli_fetch_array($res))
            {
                

                $nombre    = $datares['nombre'];
                $direccion = $datares['direccion'];
                $telefono  = $datares['telefono'];
                $valor     = $datares['valor'];
                $item      = $datares['item'];
                $correo    = $datares['correo'];
                $servicio  = $datares['servicio'];
                $region    = $datares['region'];
                
                $idcomuna    = $datares['comuna'];
                $querycomuna = 'SELECT nombre_comuna from comuna where id_comuna ='.$idcomuna;
                $rescomunaname = $conn -> mysqli->query($querycomuna)->fetch_object();
                
               
               
                $return_array[]=array(
                    "nombre" => $nombre,
                    "direccion"=>$direccion,
                    "correo" => $correo,
                    "telefono" => $telefono,
                    "valor" => $valor,
                    "item" => $item,
                    "servicio" => $servicio,
                    "region" => $region,
                    "comuna" => $rescomunaname->nombre_comuna
                );
        }
        echo json_encode($return_array);
    } 
?>