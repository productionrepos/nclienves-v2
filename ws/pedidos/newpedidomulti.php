<?php
    session_start();
    date_default_timezone_set("America/Santiago");
    include_once('../bd/dbconn.php');

    $conn =  new bd();
    $conn->conectar();

    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $id_pedido = 0;

    $id_cliente = $_SESSION['cliente']->id_cliente;
    $id_bodega = $data->idbodega;

    $timestamp = time();
    

    $querypedido =  "INSERT INTO pedido (id_pedido,timestamp_pedido,estado_pedido,id_cliente,id_bodega,gestionado_pedido,descuento,estado_logistico)
               VALUES (null,$timestamp,0,$id_cliente,$id_bodega,0,0,0)";
    if($conn->mysqli->query($querypedido)){
        $id_pedido = $conn->mysqli->insert_id;
    
        $counter = 0;

        foreach($data->arraydatos as $row){

            foreach($row as $r){
                $nombres = $r->nombres;
                $telefono = $r->telefono;
                $direccion = $r->direccion;
                $correo = $r->correo;
                $region = $r->region;
                $comuna = $r->comuna;
                $item = $r->item;
                $costo = $r->costo;
                $rut = $r->rut;
                $tipo = $r->tipo;

            }

            $querybultotemporal = "INSERT INTO bulto_temporal (id_bulto_temporal,json_bulto_temporal,json_error,id_archivo,id_pedido)
                                        VALUES(null,'a','a',1,$id_pedido)";

            $queryregion = "SELECT re.id_region as id  from pedido pe 
                                INNER join bodega bo  on bo.id_bodega = pe.id_bodega
                                INNER join comuna co on co.id_comuna = bo.id_comuna
                                INNER join provincia pro on pro.id_provincia = co.id_provincia
                                inner join region re on re.id_region = pro.id_region
                            WHERE pe.id_pedido = $id_pedido";      
            if($resreg = $conn->mysqli->query($queryregion))
            {
                while($datares = $resreg ->fetch_object())
                {
                    $regiones [] = $datares;
                }
            }
            else{
                echo $conn->mysqli->error;
            }     

            $servicio = "";
            $tiposervicio = "";
            $regionpedido = $regiones[0]->id;
            if($regionpedido== $region){
                 $servicio = "icu";
                 $tiposervicio = "Intercomunal urbano";
            }else{
                $servicio = "iru";
                $tiposervicio = "Interregional urbano";
            }

            if($tipo == 1 && $servicio == "icu" ){
                $queryprecio = 'SELECT precio_comunal_paquete as precio  from paquete where id_paquete = 1';
            }
            if($tipo == 1 && $servicio == "iru" ){
                $queryprecio = 'SELECT precio_regional_paquete as precio from paquete where id_paquete = 1';
            }

            if($tipo == 2 && $servicio == "icu" ){
                $queryprecio = 'SELECT precio_comunal_paquete as precio from paquete where id_paquete = 2';
            }
            if($tipo == 2 && $servicio == "iru" ){
                $queryprecio = 'SELECT precio_regional_paquete as precio from paquete where id_paquete = 2';
            }

            $resprecio = $conn ->mysqli->query($queryprecio);
            $precio = $resprecio->fetch_object()->precio;

            //creacionbultotemporal
            if($conn->mysqli->query($querybultotemporal)){
                $idbultotemporal = $conn ->mysqli->insert_id;
            }

            $barcode = 78472947729 + $idbultotemporal;

            $querybulto = "INSERT INTO bulto (id_bulto, nombre_bulto, direccion_bulto, telefono_bulto,email_bulto,descripcion_bulto,
                                        valor_declarado_bulto, precio_bulto, tipo_servicio_bulto, codigo_bulto, codigo_barras_bulto, 
                                        id_paquete, id_comuna, id_pedido,rut_cliente, estado_logistico,track_spread)
                           VALUES (null,'".$nombres."','".$direccion."',".$telefono.",'".$correo."','".
                                   $item."',".$costo.",".$precio.",'".$tiposervicio."','abc',".$barcode.",".
                                   $tipo.",".$comuna.",".$id_pedido.',"'.$rut.'",0,NULL)';

            if($conn->mysqli->query($querybulto)){
                $querydeletebultotemporal = "delete from bulto_temporal where id_bulto_temporal =".$idbultotemporal;
                $conn->mysqli->query($querydeletebultotemporal);
                echo json_encode(array("status"=>1, "id_pedido"=>$id_pedido));
            }
            
        }
    }
    // echo json_encode($tipo)
    // echo json_encode($data);
    // echo json_encode($id_bodega);
    // echo json_encode(array($nombres,$telefono,$direccion,$correo,$region,$comuna,$item,$costo,$rut,$tipo))

?>