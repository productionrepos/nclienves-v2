<?php 
    if(!isset($_SESSION['cliente'])):
        header("Location: index.php");
    endif;

    $id_cliente = $_SESSION['cliente']->id_cliente;

    require_once('ws/bd/dbconn.php');
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
        include_once('../nclientesv2/include/head.php');
    ?>



<body>
    <div id="app">
        <!-- SideBar -->
        <?php
            include_once('../nclientesv2/include/sidebar.php');
        ?>
       
        <div id="main"  class="layout-navbar">

            <?php
                include_once('./include/topbar.php');
            ?>
       
            <div class="container-fluid" id="containermainmenu">
                <div class="page-content" style="color:3e3e3f;">
                    <div class="resumen-envios  mt-2">
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

                                <!-- Problemas en la entrega -->
                            </div>
                    </div>
                    <section class="row imgrowmenu" >
                        <div class="row ">
                            <div class="singleimgmenu col-lg-6 col-sm-12">
                                <a href="./seleccionBultos.php">
                                    <div class="card">
                                            <div class="card-body px-3 py-4-5" id="imgmenu">
                                                <div class="row " >
                                                    <div class="col-md-4" id="cardicon">
                                                        <div class="stats-icon green">
                                                            <i class="fa-solid fa-paper-plane"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8 menutxt">
                                                        <h4 class="font-semibold"> Envía Ahora </h4>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </a>
                            </div>

                            <div class="singleimgmenu col-lg-6 col-sm-12">
                                <a href="./PedidosRealizados.php">
                                    <div class="card">
                                        <div class="card-body px-3 py-4-5" id="imgmenu">
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
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- <div class="singleimgmenu col-lg-3 col-sm-6 col-md-6">
                                <a href="Bodegas.php">
                                    <div class="card" style="height: 200px; overflow-y: auto">
                                        <div class="card-body"  id="imgmenu">
                                            <div class="row">
                                                <div class="col-md-4" id="cardicon">
                                                    <div class="stats-icon green">
                                                        <i class="fa-solid fa-warehouse"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 menutxt">
                                                    <h4 class="font-semibold">Mis direcciones</h3>
                                                </div>
                                            </div>
                                            <div class="col-12" style="text-align: center; float:inline-end">
                                                <p>(Lugar donde iremos a retirar)</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div> -->
                            
                        </div>
                    </section>
                </div>
            </div>
            

            <?php
                include_once('../nclientesv2/include/footer.php')
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