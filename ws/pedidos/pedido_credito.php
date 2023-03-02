<?php
session_start();
$id_cliente = $_SESSION['cliente']->id_cliente;

$id_pedido = filter_input(INPUT_POST, "id_pedido", FILTER_SANITIZE_NUMBER_INT);
$token = filter_input(INPUT_POST, "token", FILTER_SANITIZE_STRING);

if(empty($id_pedido) && !is_numeric($id_pedido)):
    print_r(json_encode(array("success" => 0, "message" => "Algo anda muy mal")));
    exit();
endif;

if(empty($token) || strlen($token)!=32):
    print_r(json_encode(array("success" => 0, "message" => "Se requiere el token, intente recargando la página")));
    exit();
endif;

if($token!=md5($id_pedido.$id_cliente."pedido_credito#")) {
    print_r(json_encode(array("success" => 0, "message" => "Token inválido, intente recargando la página")));
    exit();
}

include('../bd/dbconn.php');
$conexion = new bd();
$conexion->conectar();

$query = "SELECT * FROM pedido
		INNER JOIN datos_contacto ON (pedido.id_cliente=datos_contacto.id_cliente)
		INNER JOIN datos_comerciales ON (pedido.id_cliente=datos_comerciales.id_cliente)
		INNER JOIN bodega ON (pedido.id_bodega=bodega.id_bodega)
		INNER JOIN comuna ON (bodega.id_comuna=comuna.id_comuna)
		INNER JOIN provincia ON (comuna.id_provincia=provincia.id_provincia)
		INNER JOIN region ON (provincia.id_region=region.id_region) 
		INNER JOIN cliente ON (pedido.id_cliente=cliente.id_cliente)
		AND pedido.id_pedido=$id_pedido";

$datos_pedido = $conexion->mysqli->query($query)->fetch_object();

$html = "
<table border=1>
	<tr>
		<td>Pedido</td>
		<td>$datos_pedido->id_pedido</td>
	<tr>
		<td>Nombre</td>
		<td>$datos_pedido->nombres_datos_contacto $datos_pedido->apellidos_datos_contacto </td>
	</tr>
	<tr>
		<td>Teléfono</td>
		<td>$datos_pedido->telefono_datos_contacto</td>
	</tr>
	<tr>
		<td>Email</td>
		<td>$datos_pedido->email_cliente</td>
	</tr>
	<tr>
		<td>Bodega</td>
		<td>$datos_pedido->nombre_bodega</td>
	</tr>
</table>

";

include('../include/email_nuevo_credito.php');
mail_nuevo_credito("Nuevo pedido a crédito #$id_pedido", 'contacto@sendcargo.cl', $html);



header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

if($conexion->mysqli->query("UPDATE pedido SET estado_pedido=3 WHERE id_pedido=$id_pedido")) {
    print_r(json_encode(array("success" => 1, "message" => "Su pago a crédito ha sido procesado exitosamente")));
}
else {print_r(json_encode(array("success" => 0, "message" => $conexion->mysqli->error)));
}

$conexion->desconectar();