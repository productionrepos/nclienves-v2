<?php 
session_start();
if(!isset($_SESSION['cliente'])):
    header("Location: ../index.php");
endif;

$id_cliente = $_SESSION['cliente']->id_cliente;

require_once('../ws/bd/dbconn.php');
$conexion = new bd();
$conexion->conectar();

?>

<!DOCTYPE html>
<html lang="en">
    <?php
        include_once('../include/head.php');
    ?>



    <body>
        <div id="app">
            <!-- SideBar -->
            <?php
                include_once('../include/sidebar.php');
            ?>
        
            <div id="main"  class="layout-navbar">
                <?php
                    include_once('../include/topbar.php');
                ?>
        
                <div class="page-content">

                </div>
            </div>
        </div>

        <?php
        include_once('../include/footer.php')
        ?>

    </body>
</html>