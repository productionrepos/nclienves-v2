<?php
$debug = false;
if(isset($_GET['debug'])) {
$debug = true;
}


require_once __DIR__ . '/vendor/autoload.php';
require_once('../bd/dbconn.php');

$conexion = new bd();
$conexion->conectar();

$id_pedido = filter_input(INPUT_GET, "id_pedido", FILTER_SANITIZE_NUMBER_INT);
$token = filter_input(INPUT_GET, "token", FILTER_SANITIZE_STRING);


if(empty($id_pedido) && !is_numeric($id_pedido)):
    print_r(json_encode(array("success" => 0, "message" => "Algo anda muy mal")));
    exit();
endif;

if(empty($token) || strlen($token)!=32):
    print_r(json_encode(array("success" => 0, "message" => "Se requiere el token")));
    exit();
endif;

if($token!=md5($id_pedido."pdf_etiquetas")) {
    print_r(json_encode(array("success" => 0, "message" => "Token inválido")));
    exit();
}











$bultos = array();

$query = "
SELECT bulto.codigo_barras_bulto AS codigo_barras, datos_comerciales.id_cliente, rut_datos_comerciales AS rut_comercio, telefono_datos_comerciales as telefono_comercio, nombre_fantasia_datos_comerciales AS nombre_comercio, nombre_bulto AS nombre_destinatario, direccion_bulto AS direccion_destinatario, telefono_bulto AS telefono_destinatario, email_bulto AS email_destinatario, comuna_destino.nombre_comuna AS comuna_destinatario, region_destino.nombre_region AS region_destinatario, comuna_destino.carril_comuna AS carril,concat(calle_bodega,' ',numero_bodega) AS direccion_origen, nombre_bodega AS nombre_bodega_origen, comuna_origen.nombre_comuna AS comuna_origen FROM bulto
INNER JOIN comuna AS comuna_destino ON (bulto.id_comuna = comuna_destino.id_comuna)
INNER JOIN provincia AS provincia_destino ON (comuna_destino.id_provincia=provincia_destino.id_provincia)
INNER JOIN region AS region_destino ON (provincia_destino.id_region=region_destino.id_region)
INNER JOIN pedido ON (bulto.id_pedido=pedido.id_pedido)
INNER JOIN bodega ON (pedido.id_bodega=bodega.id_bodega)
INNER JOIN comuna AS comuna_origen ON (bodega.id_comuna = comuna_origen.id_comuna)
INNER JOIN datos_comerciales ON (pedido.id_cliente=datos_comerciales.id_cliente)
WHERE bulto.id_pedido=$id_pedido
";

//https://via.placeholder.com/750x300/FFFFFF/000000/?text=WebsiteBuilders.com


if($datos = $conexion->mysqli->query($query)) {
	if($datos->num_rows>0) {
		while($dato = $datos->fetch_assoc()) {
			if(strlen($dato['carril'])==1) {
				$dato['carril'] = "0".$dato['carril'];
			}
			if(!file_exists('../../../uploads/logos/logo_'.md5($dato['id_cliente']).'.png')) {
				$dato['imagen_logo'] = 'https://via.placeholder.com/750x300/FFFFFF/000000/0/?text='.urlencode($dato['nombre_comercio']);
			}
			else {
				$dato['imagen_logo'] = 'https://app.sendcargo.cl/uploads/logos/logo_'.md5($dato['id_cliente']).'.png';
			}
			$dato['codigo_barras_completo'] = upca($dato['codigo_barras']);
			array_push($bultos, $dato);
			if($debug) {
				echo "<pre>";
				print_r($dato);
				exit();
			}
		}

	}
	else {
		header("Location: /datos_comerciales.php?pdf");
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
if($debug) {
	$mpdf->Output();
}
else {
	$mpdf->Output("Etiquetas solicitud #$id_pedido.pdf", 'D');
}
function upca($upc_code) {
    $upc = substr($upc_code,0,11);
    if (strlen($upc) == 11 && strlen($upc_code) <= 12) { $oddPositions = $upc[0] + $upc[2] + $upc[4] + $upc[6] + $upc[8] + $upc[10]; $oddPositions *= 3; $evenPositions= $upc[1] + $upc[3] + $upc[5] + $upc[7] + $upc[9]; $sumEvenOdd = $oddPositions + $evenPositions; $checkDigit = (10 - ($sumEvenOdd % 10)) % 10; } return $upc_code.$checkDigit;
}