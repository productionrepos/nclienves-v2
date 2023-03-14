<?php
 session_start();
 if(!isset($_SESSION['cliente'])){
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<?php 
    require_once('./include/head.php');
?>
<body>
    <?php 
        include_once('./include/sidebar.php');
    ?>


<div id="main" class="layout-navbar" style="background-image: url('./include/img/backgound-forest.jpg');">
            <?php
                include_once('./include/topbar.php');
            ?>

            <!-- <div class="page-heading">
                <h3></h3>
            </div> -->
            <div class="page-content">
                <div class="resumen-envios row mt-2" >
                <div class="row">
                    <h2 style="color:black;font-weight:800; text-align: center" class="mb-5 mt-5">¿Cuántas entregas deseas realizar?</h2>
                </div>
                </div>
                    <div class="resumen-envios row mt-2" style="justify-content: center;">
                        <div class="col-lg-4 col-md-8 col-12  mt-2 mb-2">
                            <div class="card colresume">
                                <a href="./unitario.php">
                                    <div class="px-4 py-4-5" style="height: 150px;">
                                        <div class="row">
                                            <div class="col-md-4 d-flex envresitems justify-content-center" style="padding: 5px;">
                                                <i class="fa-solid fa-box"></i>
                                            </div>
                                            <div class="col-md-8 envresitems">
                                                <h3 class="font-semibold">1 paquete</h3>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-8 col-12 mt-2 mb-2">
                            <div class="card colresume">
                                <a href="./multibulto.php">
                                    <div class="px-4 py-4-5" style="height: 150px;">
                                        <div class="row">
                                            <div class="col-md-4 d-flex envresitems justify-content-center" style="padding: 10px;">
                                                <i class="fa-solid fa-boxes-stacked"></i>
                                            </div>
                                            <div class="col-md-8 envresitems">
                                                <h3 class="font-semibold">2 a 10 paquetes</h3>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-8 col-12 mt-2 mb-2">
                            <div class="card colresume">
                                <a href="./cargamasiva.php">
                                    <div class=" px-4 py-4-5" style="height: 150px;">
                                        <div class="row">
                                            <div class="col-md-4 d-flex envresitems justify-content-center">
                                                <i class="fa-solid fa-list"></i> 
                                            </div>
                                            <div class="col-md-8 envresitems justify-content-center">
                                                <h3 href="" class="font-semibold">Carga masiva</h3>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
            </div>
<?php
 require_once('./include/footer.php')
?>
</div>
</body>
</html>