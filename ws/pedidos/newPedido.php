<?php
    session_start();
    date_default_timezone_set("America/Santiago");
    include_once('../bd/dbconn.php');
    
    $json = file_get_contents('php://input');

    $data = json_decode($json);

    $id_cliente = $_SESSION['cliente']->id_cliente;
    $nombre = $data->nombre;
    $direccion = $data->direccion;
    $telefono = $data->telefono;
    $correo = $data->correo;
    $item = $data->item;
    $costo = $data->costo;
    $idpaquete = $data->idpaquete;
    $comuna = $data->comuna;
    $region = $data->region;
    $id_bodega = $data->idbodega;
    $rut = $data->rut;
    $timestamp = time();

    $conn = new bd();

    $conn ->conectar();
   
    $query =  "INSERT INTO pedido (id_pedido,timestamp_pedido,estado_pedido,id_cliente,id_bodega,gestionado_pedido,descuento,estado_logistico)
               VALUES (null,$timestamp,0,$id_cliente,$id_bodega,0,0,0)";
        
    if($conn->mysqli->query($query)) {

        $id_pedido = $conn-> mysqli->insert_id;

       
        $queryregion = "Select re.id_region  from pedido pe 
                        INNER join bodega bo  on bo.id_bodega = pe.id_bodega
                        INNER join comuna co on co.id_comuna = bo.id_comuna
                        INNER join provincia pro on pro.id_provincia = co.id_provincia
                        inner join region re on re.id_region = pro.id_region
                        where pe.id_pedido = $id_pedido";

        $querybultotemporal = "INSERT INTO bulto_temporal (id_bulto_temporal,json_bulto_temporal,json_error,id_archivo,id_pedido)
                                VALUES(null,'a','a',1,$id_pedido)";

        if($resreg = $conn->mysqli->query($queryregion))
        {
            $region = array();
            
            while($datares = $resreg ->fetch_object())
            {
                $region [] = $datares;
            }
        }
        else{
            echo $conn->mysqli->error;
        }

            $idregion = $region[0];
            $tipo_servicio = "";
            $valor = 0;
        if($idregion == $region){
            $tipo_servicio = "Intercomunal urbano";
        }
        else{
            $tipo_servicio = "interrecional urbano";
        }
        
        if($idpaquete == 1){
            $valor = 3570;
            
        }
        elseif($idpaquete == 2){
            $valor = 4760;
        }
        if($conn->mysqli->query($querybultotemporal)){
            $idbultotemporal = $conn ->mysqli->insert_id;
        }
        $barcode = 78472947729 + $idbultotemporal;
        
        $querybulto = "INSERT INTO bulto (id_bulto, nombre_bulto, direccion_bulto, telefono_bulto,email_bulto,descripcion_bulto,
                    valor_declarado_bulto, precio_bulto, tipo_servicio_bulto, codigo_bulto, codigo_barras_bulto, 
                    id_paquete, id_comuna, id_pedido,rut_cliente, estado_logistico,track_spread)
                    VALUES (null,'".$nombre."','".$direccion."',".$telefono.",'".$correo."','".$item."',".$costo.",".$valor.",'".$tipo_servicio."','abc',".$barcode.",".$idpaquete.",".$comuna.",".$id_pedido.',"'.$rut.'",0,NULL)';
        // echo $querybulto;
        if($conn->mysqli->query($querybulto)){
           echo $id_pedido;
        }else{
            // echo $querybulto;
            echo $conn->mysqli->error;
        }
        // echo $querybulto;
    }
    else {
        
    }
    $conn->desconectar();
?>