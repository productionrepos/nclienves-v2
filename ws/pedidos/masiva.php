<?php 
date_default_timezone_set("America/Santiago");
session_start();
$id_cliente = $_SESSION['cliente']->id_cliente;
    try{

        
        require_once('/xampp/htdocs/nclientesv2/ws/bd/dbconn.php');    
        $json = file_get_contents('php://input');

        $conn = new bd();
        $conn->conectar();
        $idpaquete = 0;

        $data = json_decode($json);
        //echo $returnjsin;
        $id_bodega = $data[0];

        $hora = time();

        $qpedido =  "INSERT INTO pedido (id_pedido,timestamp_pedido,estado_pedido,id_cliente,id_bodega,gestionado_pedido,descuento,estado_logistico)
                        VALUES (null,$hora,0,$id_cliente,$id_bodega,0,0,0)";


        if($conn->mysqli->query($qpedido))
        {
            $id_pedido = $conn -> mysqli -> insert_id;

            

            $counter = 0;
            $jsonbultotemporal = [];

            for($i = 1; $i <= count($data)-1 ; $i++)
            {
               $counter ++;
               array_push($jsonbultotemporal,$data[$i]);
               if($counter == 8) {
                    $tipoenviostr = $jsonbultotemporal[7];
                    $counter = 0;
                    
                    // $querycomuna = 'SELECT co.id_comuna from comuna co where co.nombre_comuna="'.$jsonbultotemporal[4].'"';
                    $querycomuna = 'SELECT co.id_comuna from comuna co where co.nombre_comuna = "'.$jsonbultotemporal[4].'"';
                    
                   
                    $queryregionbodega = "Select re.id_region from bodega pe INNER join bodega bo on bo.id_bodega = pe.id_bodega INNER join comuna co on co.id_comuna = bo.id_comuna INNER join provincia pro on pro.id_provincia = co.id_provincia inner join region re on re.id_region = pro.id_region where pe.id_bodega = $data[0]";
                    $queryregion = 'Select re.id_region from pedido pe INNER join bodega bo  on bo.id_bodega = pe.id_bodega INNER join comuna co on co.id_comuna = bo.id_comuna INNER join provincia pro on pro.id_provincia = co.id_provincia inner join region re on re.id_region = pro.id_region where co.nombre_comuna ="'. $jsonbultotemporal[4].'"';
                    
                    $querybultotemporal = "INSERT INTO bulto_temporal (id_bulto_temporal,json_bulto_temporal,json_error,id_archivo,id_pedido)
                                       VALUES(null,'a','a',1,$id_pedido)";


                     if($idcomuna = $conn->mysqli->query($querycomuna)){
                       
                        while($idcomu = $idcomuna ->fetch_object())
                        {
                            $comunaid [] = $idcomu;
                        }
                    }
                    foreach($comunaid as $ci)
                    {
                        $idcom = $ci ->id_comuna;
                    }
                    
                    

                    if($resreg = $conn->mysqli->query($queryregion))
                    { 
                        while($datares = $resreg ->fetch_object())
                        {
                            $region [] = $datares;
                        }
                    }
                    else{
                        echo $conn->mysqli->error;
                    }
                    foreach($region as $reg){
                            $idregion = $reg->id_region;
                    }


                    if($regionbodega = $conn->mysqli->query($queryregionbodega))
                    { 
                        while($rbod = $regionbodega ->fetch_object())
                        {
                            $rb [] = $rbod;
                        }
                    }
                    else{
                        echo $conn->mysqli->error;
                    }
                    foreach($rb as $r){
                            $idregionbodega = $r->id_region;
                    }
                    //COMPROBAR SI ID DE BODEGA E ID DE ENTREGA SON LAS MISMAS REGIONES PARA DETERMINAR EL TIPO DE ENVIO
                    $tipo_servicio = "";
                    if($idregion == $idregionbodega){
                        $tipo_servicio = "Intercomunal urbano";
                    }
                    else{
                        $tipo_servicio = "Interregional urbano";
                    }
                    $valor = 0;
                    if($tipoenviostr == "Mini")
                    {
                        $idpaquete = 1;
                    }
                    if($tipoenviostr == "Medium")
                    {
                        $idpaquete = 2;
                    }
                    //BUSCAR PRECIOS EN BD
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
                    valor_declarado_bulto, precio_bulto, tipo_servicio_bulto, codigo_bulto, codigo_barras_bulto,id_paquete, id_comuna, id_pedido, estado_logistico,track_spread)
                    VALUES (null,'".
                     $jsonbultotemporal[0]."','".
                     $jsonbultotemporal[1]."',".
                     $jsonbultotemporal[2].",'".
                     $jsonbultotemporal[3]."','".
                     $jsonbultotemporal[5]."',".
                     $jsonbultotemporal[6].",".
                     $valor.",'".
                     $tipo_servicio."','abc',".
                     $barcode.",".
                     $idpaquete.",".
                     $idcom.",".
                     $id_pedido.",0,NULL);";
                   
                    $jsonbultotemporal = [];
                    if($conn->mysqli->query($querybulto)){

                        
                        // echo "El id_region de BODEGA ES =>> ".$idregionbodega." El id_region de ENVIO ES =>> ".$idregion;
                        // echo $querybulto;
                        // //print_r($data);
                       
                    }else{
                        // echo $querybulto;
                        echo $conn->mysqli->error;
                    }
               }
                
               
            }
            echo  $id_pedido;
            
        }
        else{
            echo "NO HAY NUEVO PEDIDO";
        }
        // echo $returnjsin;
    }
    catch(Exception $e){
         echo $e;
    }
    


?>