<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once('../bd/dbconn.php');

$conexion = new bd();
$conexion->conectar();

$json = file_get_contents('php://input');
$data = json_decode($json);


$id_pedido = $data->id_pedido;
$token = $data->token;


$bultos = array();

$query = "
SELECT pedido.id_pedido AS pedido,bulto.codigo_barras_bulto AS codigo_barras, datos_comerciales.id_cliente, rut_datos_comerciales AS rut_comercio, telefono_datos_comerciales as telefono_comercio, nombre_fantasia_datos_comerciales AS nombre_comercio, nombre_bulto AS nombre_destinatario, direccion_bulto AS direccion_destinatario, telefono_bulto AS telefono_destinatario, email_bulto AS email_destinatario, comuna_destino.nombre_comuna AS comuna_destinatario, region_destino.nombre_region AS region_destinatario, comuna_destino.carril_comuna AS carril,concat(calle_bodega,' ',numero_bodega) AS direccion_origen, nombre_bodega AS nombre_bodega_origen, comuna_origen.nombre_comuna AS comuna_origen, bulto.track_spread as track
FROM bulto
INNER JOIN comuna AS comuna_destino ON (bulto.id_comuna = comuna_destino.id_comuna)
INNER JOIN provincia AS provincia_destino ON (comuna_destino.id_provincia=provincia_destino.id_provincia)
INNER JOIN region AS region_destino ON (provincia_destino.id_region=region_destino.id_region)
INNER JOIN pedido ON (bulto.id_pedido=pedido.id_pedido)
INNER JOIN bodega ON (pedido.id_bodega=bodega.id_bodega)
INNER JOIN comuna AS comuna_origen ON (bodega.id_comuna = comuna_origen.id_comuna)
INNER JOIN datos_comerciales ON (pedido.id_cliente=datos_comerciales.id_cliente)
WHERE bulto.id_pedido=$id_pedido
";

if($datos = $conexion->mysqli->query($query)) {
	if($datos->num_rows>0) {
		while($dato = $datos->fetch_assoc()) {
			array_push($bultos, $dato);
		}
	}
	else {
		header("Location: /misDatos.php?pdf=fallo&id_pedido=".$id_pedido);
		$conexion->desconectar();
		exit();
	}
}
else {
	echo $conexion->mysqli->error;
	$conexion->desconectar();
	exit();
}


$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new \Mpdf\Mpdf([
	'mode' => 'utf-8',
	'format' => [200, 400], //dividir por 2
	'margin_left' => 0,
	'margin_right' => 10,
	'margin_left' => 10,
	'margin_top' => 10,
	'margin_bottom' => 0,
	'margin_header' => 0,
	'margin_footer' => 0,
    'fontDir' => array_merge($fontDirs, [
        __DIR__ . '/fuentes_sendcargo',
    ]),
    'fontdata' => $fontData + [
        'roboto' => [
            'R' => 'Roboto-Regular.ttf',
            'I' => 'Roboto-Italic.ttf',
            'B' => 'Roboto-Bold.ttf',
        ]
    ],
    'default_font' => 'roboto'
]);

$plantilla = file_get_contents('etiqueta.html');


foreach($bultos as $datos_bulto) {

	$html = preg_replace_callback(
		"|{(\w*)}|",
		function ($matches) use ($datos_bulto) {
			return $datos_bulto[$matches[1]];
		},
		$plantilla
	);
	$mpdf->WriteHTML($html);
}

$mpdf->Output("Etiquetas solicitud #$id_pedido.pdf", 'D');  

?>