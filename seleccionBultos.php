<?php
 session_start();
?>

<!DOCTYPE html>
<html lang="en">
<?php 
    require_once('include/head.php');
?>
<body>
    <?php 
        include_once('include/sidebar.php');
    ?>


<div id="main" class="layout-navbar" style="background-image: url('./include/img/backgound-forest.jpg');">
            <?php
                include_once('./include/topbar.php');
            ?>

            <!-- <div class="page-heading">
                <h3></h3>
            </div> -->
            <div class="page-content">
                <div class="resumen-envios row mt-2">
                <div class="row">
                    <h2 style="color:#3e3e3f; text-align: center" class="mb-5 mt-5">¿Cuántas entregas deseas realizar?</h2>
                </div>
                </div>
                    <div class="resumen-envios row mt-2">
                        <div class="col-lg-1 col-md-12 mt-2 mb-2"></div>
                        <div class="col-lg-3 col-md-12 mt-2 mb-2">
                            <div class="card">
                                <a href="./unitario.php">
                                    <div class="card-body px-4 py-4-5" style="height: 150px;">
                                        <div class="row">
                                            <div class="col-4 d-flex justify-content-center" style="padding: 5px;">
                                                <div class="stats-icon green">
                                                    <i class="fa-solid fa-box"></i>
                                                </div>
                                            </div>
                                            <div class="col-8 menutxt">
                                                <h3 class="font-semibold">1 paquete</h3>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 mt-2 mb-2">
                            <div class="card">
                                <a href="./multibulto.php">
                                    <div class="card-body px-4 py-4-5" style="height: 150px;">
                                        <div class="row">
                                            <div class="col-md-4 d-flex justify-content-center" style="padding: 10px;">
                                                <div class="stats-icon green">
                                                <i class="fa-solid fa-boxes-stacked"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8 menutxt">
                                                <h3 class="font-semibold">2 a 10 paquetes</h3>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 mt-2 mb-2">
                            <div class="card">
                                <a href="./cargamasiva.php">
                                    <div class="card-body px-4 py-4-5" style="height: 150px;">
                                        <div class="row">
                                            <div class="col-md-4 d-flex justify-content-center">
                                                <div class="stats-icon green">
                                                    <i class="fa-solid fa-list"></i>                                                        
                                                </div>
                                            </div>
                                            <div class="col-md-8 menutxt">
                                                <h3 href="" class="font-semibold">Carga masiva</h3>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-1 col-md-12 mt-2 mb-2"></div>
                            
                    </div>
            </div>
<?php
 require_once('include/footer.php')
?>
</div>
</body>
</html>