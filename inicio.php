<?php 
    date_default_timezone_set("America/Santiago");
    if(!isset($_SESSION['cliente'])):
        header("Location: index.php");
    endif;

    $id_cliente = $_SESSION['cliente']->id_cliente;

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
                            pe.timestamp_pedido as fecha 
                            from bulto bu inner join pedido pe on pe.id_pedido = bu.id_pedido 
                            inner join bodega bo on bo.id_bodega = pe.id_bodega where pe.id_pedido =".$pp->id_pedido;
            if($resbultosretiro = $conexion->mysqli->query($querybultos)){
                while($bultospend = $resbultosretiro->fetch_object()){
                    $bultospendientes [] = $bultospend;
                }

                foreach($bultospendientes as $bultopendiente){
                    $trackid = $bultopendiente->trackid;
                    $direccion = $bultopendiente->direccion;
                    $fecha = $bultopendiente->fecha;
                    $hora = date('h:j:s',$fecha);
                    $fechaestimada = "";
                    if($hora > '12:00:00'){
                        $fechaestimada = date('d/m/Y',strtotime($fecha."+ 1 day"));
                    }else{
                        $fechaestimada = date('d/m/Y',$fecha);
                    }
                    
                    array_push($arraybultospendientes,["trackid" => $trackid,
                                                        "direccion" => $direccion,
                                                        "fechaestimada" => $fechaestimada]);
                }
            }
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
                    <div class="resumen-envios  mt-1">
                        <div class="row">
                            <h4 style="color:#3e3e3f">Envíos de este mes</h4>
                        </div>
                        <div class="masteresume row">
                        
                            <div class="col-lg-2 col-12 col-md-6 card colresume">
                                <div class="row">
                                    <a href=""><span class="envtitle"><h5>Total de envios</h5></span></a>
                                </div>
                                <div class="row dataresenv">
                                    <div class="col-lg-6 col-md-6 col-sm-12 envresitems">
                                        <i class="fa-solid fa-truck"></i>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 envresitems"> <h4><?php echo $cantEnvios->suma ?></h4></div>
                                </div>
                            </div>
                    
                                
                            
                            <div class="col-lg-2 col-12 col-md-6 card colresume">
                                <div class="row">
                                    <a href="">
                                        <span class="envtitle"><h5>Entregados</h5></span>
                                    </a>
                                </div>
                                <div class="row dataresenv">
                                    <div class="col-lg-6 col-md-6 col-sm-12 envresitems"> <i class="fa-solid fa-check"></i></div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 envresitems"> <h4><?php echo $cantEnviosEntregados->suma ?></h4></div>
                                </div> 
                            </div>

                            <div class="col-lg-2 col-12 col-md-6 card colresume">
                                <div class="row">
                                    <a href=""><span class="envtitle"><h5>En Transito</h5></span></a>
                                </div>
                                <div class="row dataresenv">
                                    <div class="col-lg-6 col-md-6 col-sm-12 envresitems"> 
                                        <i class="fa-regular fa-clock"></i>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 envresitems">
                                        <h4><?php echo $cantEnviosEnTransito->suma ?></h4>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-2 col-12 col-md-6 card colresume">
                                <div class="row">
                                    <a href=""><span class="envtitle"><h5>Problemas en la entrega</h5></span></a>
                                </div>
                                <div class="row dataresenv">
                                    <div class="col-lg-6 col-md-6 col-sm-12 envresitems"> 
                                        <i class="fa-regular fa-clock"></i>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 envresitems">
                                        <h4><?php echo $cantEnviosConProblemas->suma ?></h4>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                   
                   <?php
                        if($hasretiros):
                   ?>
                        <div class="resumen-envios  mt-1">
                            <div class="row">
                                <h4 style="color:#3e3e3f">Retiros Pendientes</h4>
                            </div>
                            <div class="masteresume row">
                                <div class="col-lg-2 col-12 col-md-6 card colresume">
                                    <div class="row">
                                        <a href=""><span class="envtitle"><h5>Pendientes</h5></span></a>
                                    </div>
                                    <div class="row dataresenv">
                                        <div class="col-lg-6 col-md-6 col-sm-12 envresitems">
                                            <i class="fa-solid fa-truck"></i>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 envresitems"> <h4><?php echo $cantidadpendientes?></h4></div>
                                    </div>

                                    
                                </div>
                                <div class="col-lg-2 col-12 col-md-6 card colresume">
                                    <div class="row dataresenv">
                                        <div class="col-lg-6 col-md-6 col-sm-12 envresitems">
                                            <table>
                                                <thead>
                                                    <th>NRO GUIA</th>
                                                    <th>DIRECCIÓN RETIRO</th>
                                                    <th>FECHA ESTIMADA RETIRO</th>
                                                </thead>
                                                <!-- "trackid" => $trackid,
                                                "direccion" => $direccion,
                                                "fechaestimada" => $fechaestimada -->
                                                <?php
                                                    print_r($arraybultospendientes );
                                                ?>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                    <?php endif;?>
                      


                    <section class="resumen-envios  mt-1" >

                        <div class="row justify-content-center">
                            <div class="singleimgmenu col-lg-8 col-sm-12">
                                <a href="./PedidosPendientes.php">    
                                    <div class="card" style="overflow-y: auto">
                                        <div class="card-body"  id="imgmenu">
                                            <div class="row">
                                                <div class="col-md-4" id="cardicon">
                                                    <div class="stats-icon green">
                                                        <i class="fa-solid fa-warehouse"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 menutxt">
                                                    <h4 class="font-semibold">Continuar con envíos pendientes</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="row ">
                            <div class="singleimgmenu col-lg-6 col-sm-12">
                                <div class="card">
                                    <div class="card-body" id="imgmenu">
                                        <a href="./seleccionBultos.php">
                                            <div class="row" >
                                                <div class="col-md-4" id="cardicon">
                                                    <div class="stats-icon green">
                                                        <i class="fa-solid fa-paper-plane"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 menutxt">
                                                    <h4 class="font-semibold"> Envía Ahora </h4>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="singleimgmenu col-lg-6 col-sm-12">
                                <div class="card">
                                    <div class="card-body" id="imgmenu">
                                        <a href="./PedidosRealizados.php">
                                            <div class="row">
                                                <div class="col-md-4 "id="cardicon">
                                                    <div class="stats-icon green">
                                                        <i class="fa-solid fa-box"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 menutxt">
                                                    <h4 class="font-semibold">Mis Envíos</h4>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </section>

                    <div class="resumen-envios mt-1" >
                        
                        
                        <div class="row justify-content-center">
                            <div class="col-lg-8 col-sm-12">
                                <div id="faq" role="tablist" aria-multiselectable="true">
                                    <div class="card">
                                        <div class="card-header" role="tab" id="questionTwo" style="text-align: center;">
                                            <div class="row">
                                                <h4 style="color:#3e3e3f">Sigue un envío</h4>
                                            </div>
                                                <form>
                                                    <div class="row">
                                                        <div class="mb-3 col-10 justify-content-end" >
                                                        <input type="text"class="form-control" 
                                                                name="" id="trackid" aria-describedby="helpId" 
                                                                placeholder="Número de guía">

                                                        <small id="helpId" class="form-text text-muted"></small>

                                                        </div>
                                                        <div class="col-2">
                                                            <button type="button" id="datapackage" class="btn collapsed btn-success" data-bs-toggle="modal" data-bs-target="#xlarge">
                                                                <i style="font-size:20px" class="fa-solid fa-magnifying-glass"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            


            <!-- MODAL LARGE-->
        <div class="modal fade text-left w-100" id="xlarge" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel16" aria-hidden="true" style="padding: 60px; border-radius: 50px;">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl"
                role="document">
                <div class="modal-content" style="padding: 0px 50px;">
                    <form class="form form" id="toValdiateBulto" >
                        <div class="modal-header">
                            <h4 class="modal-title" id="trackIdlbl"></h4>
                            <input  style="display: none;" type="text" name="vid_bulto"/>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="gg">Nombre</label>
                                    <input type="text" id="nombredestinatario" class="form-control" name="nombredestinatario" placeholder="Nombre Destinatario"/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="contact">Teléfono</label >
                                    <input type="number" id="numtel" class="form-control" name="numtel" placeholder="Teléfono" />
                                </div>
                            </div>
                            <div class="col-4 justify-content-start">
                                <button type="button"  class="btn btn-spread" id="closemodal">Inserte texto acorde</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
            

            <?php
                include_once('./include/footer.php')
            ?>
           
</body>
<script>
    $(document).ready(function(e){
        // $(".singleimgmenu").click(function(e){
        //     e.preventDefault();
        //     var url = $(this).attr('data-url');
        //     console.log(url);
        //     window.location.href = url;
        // })
    })

    $('#datapackage').on('click',function(e){
        e.preventDefault();
        let trackid = $(this).closest('#questionTwo').find('#trackid').val()
        // document.getElementById('trackIdlbl').innerHTML = ""
        document.getElementById('trackIdlbl').innerHTML='Número de Guía: '+trackid

        $.ajax({
            type: "POST",
            url: "ws/bulto/getbultobytrackId.php",
            dataType:'json',
            data: {"track_id":trackid},
            success: function(data) {

                $.each(data,function(key,value){
                    console.log(value.track)
                    console.log(value.nombre)
                    console.log(value.direccion)
                    console.log(value.correo)
                    console.log(value.telefono)
                    console.log(value.valor)
                    console.log(value.item)
                    console.log(value.servicio)
                    console.log(value.region)
                    console.log(value.comuna)
                })
            },
                error: function(data){
                    console.log(data.query);
            }
        })
    })
   

</script>
</html>