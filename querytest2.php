<?php 
    session_start();
    date_default_timezone_set("America/Santiago");
    if(!isset($_SESSION['cliente'])):
        header("Location: index.php");
    endif;

    $id_cliente = $_SESSION['cliente']->id_cliente;

    $id_pedido = 36468;

    require_once('./ws/bd/dbconn.php');
    $conexion = new bd();
    $conexion->conectar();

    include('./include/busquedas/busquedaEnvios.php');
    $inicio = date("Y-m-01");
    $timestamp1 = strtotime($inicio);
    $hasretiros = false;

    $arraybultospendientes = [];

    $fin = date("Y-m-t");
    $timestamp2 = strtotime($fin);

    $cantEnvios = totalEnvios($id_cliente,$timestamp1,$timestamp2);
    $cantEnviosEntregados = totalEnviosEntregados($id_cliente,$timestamp1,$timestamp2);
    $cantEnviosEnTransito = totalEnviosEnTransito($id_cliente,$timestamp1,$timestamp2);
    $cantEnviosConProblemas = totalEnviosConProblemas($id_cliente,$timestamp1,$timestamp2);

    if($_SERVER['HTTP_HOST'] == 'localhost:8080'){
        $http = 'local';
    }else{
        $http = 'servidor';
    }
    
    $querypendientes = "Select * from pedido where id_cliente =".$id_cliente.' and estado_pedido > 1 and estado_logistico=1';
    
    if(mysqli_num_rows($resppendientes = $conexion->mysqli->query($querypendientes))>0){

        $hasretiros = true;
        while($pendientes = $resppendientes->fetch_object()){
            $pend [] = $pendientes;
        }

        $cantidadpendientes = count($pend);
           
        foreach($pend  as $pp){
            
            $querybultos = "SELECT bu.track_spread as trackid,
                            CONCAT(bo.calle_bodega,' ',bo.numero_bodega) as direccion,
                            pe.timestamp_pedido as fecha,
                            pe.id_pedido as idpedido
                            from bulto bu inner join pedido pe on pe.id_pedido = bu.id_pedido 
                            inner join bodega bo on bo.id_bodega = pe.id_bodega where pe.id_pedido =".$pp->id_pedido;

            if($resbultosretiro = $conexion->mysqli->query($querybultos)){

                while($bultospend = $resbultosretiro->fetch_object()){
                    $bultospendientes [] = $bultospend;
                }

                foreach($bultospendientes as $bultopendiente){
                    // print_r($bultopendiente);
                    $trackid = $bultopendiente->trackid;

                    $direccion = $bultopendiente->direccion;

                    $fecha = $bultopendiente->fecha;

                    $idpedido = $bultopendiente->idpedido;

                    $hora = date('h:j:s',$fecha);
                    $fechaestimada = "";
                    if($hora > '12:00:00'){
                        $formatfecha = date('d-m-Y',$fecha);
                        $fechaplus = strtotime($formatfecha."+ 1 days");
                        $fechaestimada = date('d/m/Y',$fechaplus);
                    }else{
                        $fechaestimada = date('d/m/Y',$fecha);
                    }
                    
                    array_push($arraybultospendientes,["trackid" => $trackid,
                                                        "direccion" => $direccion,
                                                        "fechaestimada" => $fechaestimada,
                                                        "IDPEDIDO" => $idpedido]);
                }
            }

            $bultospendientes = [];
        }
       
    }


?>


<!DOCTYPE html>
<html lang="en">
    <?php
    $head = 'Dashboard Spread';
    include_once('./include/head.php');
    ?>



<body>
    <div id="app">
        <!-- SideBar -->
        <?php
            include_once('./include/sidebar.php');
        ?>
       
      <div id="main"  class="layout-navbar">

              <?php
                  include_once('./include/topbar.php');
              ?>
        
              <div class="container-fluid" id="containermainmenu">
                  <div class="page-content" style="color:3e3e3f;">
                    <a href="<?php echo 'https://'.$_SERVER['HTTP_HOST']?>/ws/pdf/?id_pedido=<?=$id_pedido?>&token=<?=md5($id_pedido."pdf_etiquetas")?>"
                       id="pdfbyJS" type="button" class="btn btn-lg btn-block btn-spread">
                      <i class="fa fa-download d-flex"></i>
                      PDF GENERADO POR JAVASCRIPT
                    </a>
                  </div>
              </div>
    


    <?php
        include_once('./include/footer.php')
    ?>  
</body>
  <script>
    // var dataajax= {id_pedido: <?=$id_pedido;?>,
    //                token: "<?=md5($id_pedido."pdf_etiquetas");?>"}
    // // $('#pdfbyJS').on('click', function(){
    //     $.ajax({
    //         url: "ws/pdf/index_prueba3.php",
    //         type: "POST",
    //         dataType: 'json',
    //         data: JSON.stringify(dataajax),
    //         success:function(resp){
                
    //         },error:function(resp){

    //         }
             
    //     })
    // })
  </script>
</html>