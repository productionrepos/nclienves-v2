<?php
session_start();
if(!isset($_SESSION['cliente'])){
    header('Location: index.php');
}

require_once('./ws/bd/dbconn.php');
$conn = new bd();
$conn->conectar();

$id_pedido = $_GET['id_pedido'];

$querybulto = 'SELECT b.id_bulto,b.codigo_barras_bulto,b.track_spread,p.estado_pedido,p.estado_logistico FROM bulto b 
                inner join pedido p on b.id_pedido = p.id_pedido
                where b.id_pedido ='. $id_pedido;

$sinTrack = array();
$estadoPedido = 0;
if($resdatabulto = $conn->mysqli->query($querybulto)){
    while($datares = $resdatabulto->fetch_object())
    {
        if($datares->track_spread == ""){
            $sinTrack[] = $datares->id_bulto;
        }
        $estadoPedido = $datares->estado_pedido;
    }
}

if( $estadoPedido == 0 ){
    header('Location: index.php');
    exit();
}

$existeSinTrack = 0;
if( count($sinTrack) > 0 ){
    $existeSinTrack = 1;
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