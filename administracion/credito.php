<?php 
session_start();
if(!isset($_SESSION['cliente'])):
    header("Location: ../index.php");
endif;
date_default_timezone_set("America/Santiago");

// $administrador = $_SESSION['cliente']->rol;

// if($administrador == 1) { 
//     exit();
// }

require_once('../ws/bd/dbconn.php');
$conexion = new bd();
$conexion->conectar();

$fecha_actual = date("d-m-Y");
$fecha_busqueda = date("d-m-Y",strtotime($fecha_actual."- 45 days"));
$fecha_timestamp = strtotime($fecha_busqueda);


if($datos_pedidos = $conexion->mysqli->query("SELECT p.id_pedido,p.estado_pedido,dc.nombres_datos_contacto,
                                            dc.apellidos_datos_contacto,dc.telefono_datos_contacto,
                                            dc.email_datos_contacto,p.timestamp_pedido,p.gestionado_pedido
                                            FROM pedido p
                                            INNER JOIN datos_contacto dc ON (p.id_cliente=dc.id_cliente)
                                            where p.timestamp_pedido > $fecha_timestamp and p.estado_pedido = 3
                                            order by p.timestamp_pedido desc")) {
    $pedidos = array();
    while ($dato = $datos_pedidos->fetch_object()) {
        $pedidos[] = $dato;
    }
    $datos_pedidos->close();
}
else {
    echo $conexion->mysqli->error;
    exit();
}

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
        
            <div class="page-content">
                <div class="resumen-envios row m-2">
                    <div class="row">
                        <h4 style="color:#3e3e3f">Clientes con credito</h4>
                    </div>
                <div class="masteresume row">
                    <div class="col card">
                        <table class="table nowrap" style="max-width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Datos</th>
                                    <th>Cliente</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Fecha creación</th>
                                    <th>Cantidad Bultos</th>
                                    <th>Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                foreach($pedidos as $pedido):
                                    $cantidad_bultos = $conexion->mysqli->query("SELECT count(id_bulto) as suma FROM bulto WHERE id_pedido=$pedido->id_pedido")->fetch_object()->suma;
                                    $total_pedido = $conexion->mysqli->query("SELECT SUM(precio_bulto) as suma FROM bulto WHERE id_pedido=$pedido->id_pedido")->fetch_object()->suma;
                                ?>
                                <tr>
                                    <td><?=$pedido->id_pedido?> </td>
                                    <td>
                                        <?=$pedido->nombres_datos_contacto?> <?=$pedido->apellidos_datos_contacto?>
                                    </td>
                                    <td>
                                        <?=$pedido->telefono_datos_contacto?>
                                    </td>
                                    <td>
                                        <?=$pedido->email_datos_contacto?>
                                    </td>
                                    <td>
                                        <?=date("d-m-Y H:i:s", $pedido->timestamp_pedido)?>
                                    </td>
                                    <td><?=$cantidad_bultos?></td>
                                    <td>
                                        <a href="javascript:void(0)" class="btn btn-icon btn-default" data-toggle="tooltip" data-placement="top" title="" data-html="true" data-original-title="<?=$resultado = $finalizado? 'Pedido a la espera de pago' : 'Aún no es posible procesar el pago';?>"><i class="fas fa-<?=$resultado = $finalizado? 'check' : 'times';?>"></i></a>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" class="btn btn-icon btn-default" data-toggle="tooltip" data-placement="top" title="" data-html="true" data-original-title="<?=$resultado = $pagado? 'Pedido pagado' : 'Pedido no pagado';?>"><i class="fas fa-<?=$resultado = $pagado? 'check' : 'times';?>"></i></a>
                                    </td>
                                </tr>
                                <?php
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <!-- </div>
    </div> -->

    <?php
    include_once('../include/footer.php')
    ?>

</body>
</html>