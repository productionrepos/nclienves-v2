<?php
session_start();
print_r($_SESSION);

require_once('./ws/bd/dbconn.php');
$conexion = new bd();
$conexion->conectar();
$id_pedido = $_GET['id_pedido'];
$query = "SELECT p.id_pedido,dc.nombres_datos_contacto,dc.apellidos_datos_contacto from pedido p
		INNER JOIN cliente c ON (p.id_cliente=c.id_cliente)
		INNER JOIN datos_contacto dc ON (c.id_cliente=dc.id_cliente)
		WHERE id_pedido=$id_pedido";
    if($datos = $conexion->mysqli->query($query)) {
        if($datos->num_rows==1) {
            $datos_cliente = $datos->fetch_object();
        }
        else {
            echo $datos->num_rows;
            exit();
        }
    }
    else {
        echo $conexion->mysqli->error;
    }
?>
<!DOCTYPE html>
<html lang="en">

<?php
    $head = 'Error en el pago';
    include_once('./include/head.php')
?>

<div class="auth-wrapper">
	<div class="blur-bg-images"></div>
	<div class="auth-content">
		<div class="auth-bg">
			<span class="r"></span>
			<span class="r s"></span>
			<span class="r s"></span>
			<span class="r"></span>
		</div>
		<div class="card">
			<div class="card-body">
				<img src="include/Logotipo_Spread.png" class="img img-fluid" style="margin-top: -15%; margin-bottom: -4%;"/>
				<h2 class="mb-4 text-center">No se ha podido cursar tu pedido</h2>
				<p>Estimado <?=$datos_cliente->nombres_datos_contacto?> <?=$datos_cliente->apellidos_datos_contacto?>, hemos tenido problemas para confirmar su pedido #<?=$id_pedido?>.</p>
				<p>No se he realizado ningún cargo a su cuenta.</p>
				<p>Lamentamos las molestias, usted podría volver a intentarlo en este enlace.</p>
				<a href="confirmarPedido.php?id_pedido=<?=$id_pedido?>" class="btn btn-primary mb-4 btn-block text-white">Ir al pedido y reintentar el pago.</a>
			</div>
		</div>
	</div>
</div>

<style>
.auth-wrapper .auth-content:not(.container) {
    width: 520px;
}
</style>
</body>

</html>
