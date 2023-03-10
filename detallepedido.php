<?php
session_start();
if(!isset($_SESSION['cliente'])){
    header('Location: index.php');
}

require_once('./ws/bd/dbconn.php');
$conn = new bd();
$conn->conectar();

$id_pedido = $_GET['id_pedido'];

$querybulto = 'SELECT b.id_bulto,b.track_spread,b.nombre_bulto,b.direccion_bulto,b.precio_bulto,b.codigo_barras_bulto,p.estado_pedido,p.estado_logistico
                FROM bulto b
                inner join pedido p on b.id_pedido = p.id_pedido
                where b.id_pedido ='. $id_pedido;

$queryPedido = 'SELECT p.timestamp_pedido,b.calle_bodega,b.numero_bodega,c.nombre_comuna,b.detalle_bodega FROM pedido p
                inner join bodega b on p.id_bodega = b.id_bodega
                inner join comuna c on b.id_comuna = c.id_comuna
                where p.id_pedido ='. $id_pedido;
// echo $querybulto."<br>";

$sinTrack = array();
$estadoPedido = 0;
$bultos = array();
$precioFinal = 0;

if($resdatabulto = $conn->mysqli->query($querybulto)){
    while($datares = $resdatabulto->fetch_object())
    {
        if($datares->track_spread == ""){
            $sinTrack[] = $datares->id_bulto;
        }
        $bultos[] = $datares;
        $precioFinal = $precioFinal + $datares->precio_bulto;
        $estadoPedido = $datares->estado_pedido;
    }
}

if($resPedido = $conn->mysqli->query($queryPedido)){
    while($dataPedidos = $resPedido->fetch_object()){
        $direccion = $dataPedidos->calle_bodega." ".$dataPedidos->numero_bodega.", ".$dataPedidos->nombre_comuna;
        $fecha = date('d/m/Y',$dataPedidos->timestamp_pedido);
    }
}

// print_r($bultos);

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
            <div class="row"  style="padding: 20px; text-align: center;">
                <div class="card resumen-envios col-lg-6 col-md-6 col-sm-8 col-12" style="padding: 20px; text-align: center;">
                    <div class="">
                            <h3 style="color: black; font-weight: 700;">Información del pedido</h3>
                    </div>
                </div>
            </div>

            <div class="page-content" style="padding: 10px; margin:10px">

                <div class="resumen-envios  mt-1">
                    <div class="row">
                        <h4 style="color:black;">Descargue las etiquetas aquí</h4>
                    </div>
                    <div class="row">
                        <a href="<?php echo $http.$_SERVER['HTTP_HOST']?>/ws/pdf/?id_pedido=<?=$id_pedido?>&token=<?=md5($id_pedido."pdf_etiquetas")?>" 
                        type="button" class="btn btn-lg btn-block btn-spread">
                            <i class="fa fa-download d-flex"></i>
                            Descargue aquí el archivo para imprimir las etiquetas que debe adherir en los bultos
                        </a>
                    </div>
                </div>    

                <div class="resumen-envios  mt-1">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-12" style="text-align: center;">
                            <div class="row">
                                <h5 style="color:black;">Resumen Pedido</h5>
                            </div>
                            <div class="d-flex justify-content-center">
                                <table class="table" style="width: 60%; color:white; align-self:center;">
                                    <tbody>
                                        <tr>
                                            <th>Fecha pedido</th>
                                            <td><?php echo $fecha ?></td>
                                        </tr>
                                        <tr>
                                            <th>Punto de retiro</th>
                                            <td><?php echo $direccion ?></td>
                                        </tr>
                                        <tr>
                                            <th>Costo total de envío</th>
                                            <td><?php echo moneda($precioFinal); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12" style="text-align: center;">hola 2</div>
                    </div>
                    <div class="row">
                        <div class="col-12" style="text-align: center;">
                            <div class="row">
                                <h4 style="color:black;">Resumen paquetes enviados</h4>
                            </div>
                            <div class="row">
                                <table class="table" style="width: 100%; background-color:#bfffd7ad;">
                                    <thead>
                                        <tr>
                                            <th>Guia</th>
                                            <th>Clientes</th>
                                            <th>Dirección</th>
                                            <th>Costo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach($bultos as $bulto){?>
                                            <tr>
                                                <td><?php echo $bulto->track_spread; ?></td>
                                                <td><?php echo $bulto->nombre_bulto; ?></td>
                                                <td><?php echo $bulto->direccion_bulto; ?></td>
                                                <td><?php echo moneda($bulto->precio_bulto); ?></td>
                                            </tr>
                                        <?php }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                

            </div>

<?php
require_once('./include/footer.php')
?>

</body>


</html>
<?php
function moneda($number) {
    return '$'.number_format($number, 0, ',', '.');
}
?>