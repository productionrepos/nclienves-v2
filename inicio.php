<?php 
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

    $fin = date("Y-m-t");
    $timestamp2 = strtotime($fin);

    $cantEnvios = totalEnvios($id_cliente,$timestamp1,$timestamp2);
    $cantEnviosEntregados = totalEnviosEntregados($id_cliente,$timestamp1,$timestamp2);
    $cantEnviosEnTransito = totalEnviosEnTransito($id_cliente,$timestamp1,$timestamp2);
    $cantEnviosConProblemas = totalEnviosConProblemas($id_cliente,$timestamp1,$timestamp2);


?>


<!DOCTYPE html>
<html lang="en">
    <?php
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


                    <section class="resumen-envios  mt-1" >

                        <div class="row">
                            <div class="col-lg-2 col-sm-2 col-md-2"></div>
                            <div class="singleimgmenu col-lg-8 col-sm-8 col-md-8">
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
                            <div class="col-lg-2 col-sm-2 col-md-2"></div>
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
                        
                        <div class="row">
                            <h4 style="color:#3e3e3f">Sigue un envío</h4>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-lg-8 col-sm-12">
                                <div id="faq" role="tablist" aria-multiselectable="true">
                                    <div class="card">
                                        <div class="card-header" role="tab" id="questionTwo" style="text-align: center;">
                                            <h5 class="card-title">
                                                <a class="collapsed" style="color: black;  font-family: arial; text-decoration: none" data-bs-toggle="collapse" data-parent="#faq" href="#answerTwo" aria-expanded="false" aria-controls="answerTwo">
                                                    Sigue tu pedido aquí
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="answerTwo" class="collapse" role="tabcard" aria-labelledby="questionTwo">
                                            <div class="card-body">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            

            <?php
                include_once('./include/footer.php')
            ?>
           

<!-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> -->

</body>
<script>
    $(document).ready(function(e){
        $(".singleimgmenu").click(function(e){
            e.preventDefault();
            var url = $(this).attr('data-url');
            window.location.href = url;
        })
    })
</script>
</html>