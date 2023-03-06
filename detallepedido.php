<?php
session_start();
if(!isset($_SESSION['cliente'])){
    header('Location: index.php');
}

require_once('./ws/bd/dbconn.php');
$conn = new bd();
$conn->conectar();

$id_pedido = $_GET['id_pedido'];

$querybulto = 'SELECT bu.id_bulto as guide, bu.nombre_bulto as nombre, bu.email_bulto as correo, bu.telefono_bulto as telefono,
bu.direccion_bulto as direccion, co.nombre_comuna as comuna,re.nombre_region as region, bu.precio_bulto as precio,
bu.codigo_barras_bulto as barcode
FROM bulto bu 
INNER JOIN comuna co on co.id_comuna = bu.id_comuna
INNER JOIN provincia pro on pro.id_provincia = co.id_provincia
INNER JOIN region re on re.id_region = pro.id_region
where bu.id_pedido ='. $id_pedido;

if($resdatabulto = $conn->mysqli->query($querybulto)){
    while($datares = $resdatabulto->fetch_object())
    {
    $datosbultos [] = $datares;
    }
}

if($_SERVER['HTTP_HOST'] == 'localhost:8080'){
$http = 'http://';
}else{
    $http = 'https://';
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
  require_once('./include/head.php')
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
            <div class="page-content">
                <div class="resumen-envios  mt-1">
                    <div class="row">
                        <h4 style="color:#3e3e3f">Información del pedido</h4>
                    </div>
                    <div class="row">
                        <a href="<?php echo $http.$_SERVER['HTTP_HOST']?>/ws/pdf/?id_pedido=<?=$id_pedido?>&token=<?=md5($id_pedido."pdf_etiquetas")?>" 
                        type="button" class="btn btn-lg btn-block btn-spread">
                            <i class="fa fa-download d-flex"></i>
                            Descargue aquí el archivo para imprimir las etiquetas que debe adherir en los bultos
                        </a>
                    </div>
                </div>

            </div>

<?php
require_once('./include/footer.php')
?>

</body>
</html>