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
SELECT pedido.id_pedido AS pedido,bulto.codigo_barras_bulto AS codigo_barras, datos_comerciales.id_cliente, rut_datos_comerciales AS rut_comercio, telefono_datos_comerciales as telefono_comercio, nombre_fantasia_datos_comerciales AS nombre_comercio, nombre_bulto AS nombre_destinatario, direccion_bulto AS direccion_destinatario, telefono_bulto AS telefono_destinatario, email_bulto AS email_destinatario, comuna_destino.nombre_comuna AS comuna_destinatario, region_destino.nombre_region AS region_destinatario, comuna_destino.carril_comuna AS carril,concat(calle_bodega,' ',numero_bodega) AS direccion_origen, nombre_bodega AS nombre_bodega_origen, comuna_origen.nombre_comuna AS comuna_origen, bulto.track_spread as track
FROM bulto
INNER JOIN comuna AS comuna_destino ON (bulto.id_comuna = comuna_destino.id_comuna)
INNER JOIN provincia AS provincia_destino ON (comuna_destino.id_provincia=provincia_destino.id_provincia)
INNER JOIN region AS region_destino ON (provincia_destino.id_region=region_destino.id_region)
INNER JOIN pedido ON (bulto.id_pedido=pedido.id_pedido)
INNER JOIN bodega ON (pedido.id_bodega=bodega.id_bodega)
INNER JOIN comuna AS comuna_origen ON (bodega.id_comuna = comuna_origen.id_comuna)
INNER JOIN datos_comerciales ON (pedido.id_cliente=datos_comerciales.id_cliente)
WHERE bulto.id_pedido=".$id_pedido;

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

// $plantilla = file_get_contents('etiqueta.html');


foreach($bultos as $datos_bulto) {
	// print_r($datos_bulto);
	$html = '<!DOCTYPE html>
	<html>
	  <head>
		<meta charset="utf-8" />
		<title>Etiqueta</title>
	  </head>
	  <body>
	
	
	
		<div style="margin-bottom:10px">
		  <table style="border-color: white !important;">
			<tbody>
			  <tr>
				<td width="60%" class="text-center" style="border: 0px;">
				  <p style="font-size:80px; color: #00a77f;">SPREAD</p>
				</td>
				<td width="40%" style="border: 0px;" class="text-left">
				 www.spread.cl
				<br>
				
				<br>
				contacto@spread.cl
				</td>
			  </tr>
			</tbody>
		  </table>
		</div>
	
	
	
		<table>
		  <tbody>
			<tr>
			  <td width="50%" class="text-center"><b class="titulo-td">Comercio</b></td>
			  <td width="50%" class="text-center"><b class="titulo-td">Bodega</b></td>
			</tr>
			<tr>
			  <td width="50%" class="text-center">'.$datos_bulto["nombre_comercio"].'</b></td>
			  <td width="50%" class="text-center">'.$datos_bulto["nombre_bodega_origen"].'</b></td>
			</tr>
			<tr>
			  <td width="50%" class="text-center"><b class="titulo-td">Dirección</b></td>
			  <td width="50%" class="text-center"><b class="titulo-td">Comuna</b></td>
			</tr>
			<tr>
			  <td width="50%" class="text-center">'.$datos_bulto["direccion_origen"].'</b></td>
			  <td width="50%" class="text-center">'.$datos_bulto["comuna_origen"].'</b></td>
			</tr>
			<tr>
			  <td width="50%" class="text-center"><b class="titulo-td">Teléfono</b></td>
			  <td width="50%" class="text-center"><b class="titulo-td">Nro Pedido </b></td>
			</tr>
			<tr>
			  <td width="50%" class="text-center">'.$datos_bulto["telefono_comercio"].'</b></td>
			  <td width="50%" class="text-center">'.$datos_bulto["pedido"].'</b></td>
			</tr>
		  </tbody>
		</table>
	
	
	
		<div style="margin-bottom:70px; margin-top:70px"> 
		  <table width="100%">
			<tr>
			  <td width="100%" style="border: 0px;">
				<div class="codigo_barra" style="text-align: center;">
				  <barcode code="'.$datos_bulto["track"].'" type="EAN128A" class="barcode" size="4" height="0.5"/>
				</div>
			  </td>
			</tr>
			<tr>
			  <td align="center">
				  <h1>'.$datos_bulto["track"].'</h1>
				  <p>Numero de Guia</p>
			  </td>
			</tr>
		  </table>
		</div>
	
	
	
	
	
	 
		<table>
		  <thead>
			<tr>
			  <td class="text-center" colspan="2"><b class="titulo">Destinatario</b></td>
			</tr>
		  </thead>
		  <tbody>
			<tr>
			  <td width="50%" class="text-center"><b class="titulo-td">Nombre</b></td>
			  <td width="50%" class="text-center"><b class="titulo-td">Dirección</b></td>
			</tr>
			<tr>
			  <td class="text-center">'.$datos_bulto["nombre_destinatario"].'</td>
			  <td class="text-center">'.$datos_bulto["direccion_destinatario"].'</td>
			</tr>
			<tr>
			  <td width="50%" class="text-center"><b class="titulo-td">Comuna</b></td>
			  <td width="50%" class="text-center"><b class="titulo-td">Región</b></td>
			</tr>
			<tr>
			  <td class="text-center">'.$datos_bulto["comuna_destinatario"].'</td>
			  <td class="text-center">'.$datos_bulto["region_destinatario"].'</td>
			</tr>
			<tr>
			  <td width="50%" class="text-center"><b class="titulo-td">Teléfono</b></td>
			  <td width="50%" class="text-center"><b class="titulo-td">Email</b></td>
			</tr>
			<tr>
			  <td class="text-center">'.$datos_bulto["telefono_destinatario"].'</td>
			  <td class="text-center">'.$datos_bulto["email_destinatario"].'</td>
			</tr>
		  </tbody>
		</table>
	
	
	
		<pagebreak></pagebreak>
	  </body>
	  <style>
		body {
		  font-size: 20px;
		  min-height: 900px;
		}
		table {
		  display: block;
		  width: 100%;
		  border-collapse: collapse;
		  border-spacing: 2px;
		  border-color: grey;
		  margin-top: 5px;
		}
		td {
		  border: 1px solid #e2e5e8;
		  padding: 10px;
		}
		.td-carril {
		  border: 3px solid black;
		  padding: 0px;
		}
		.text-center {
		  text-align: center !important;
		}
		.text-left {
		  text-align: right !important;
		  font-size: 30px;
		}
		.titulo-td {
		  font-size: 20px !important;
		}
		.titulo-td-carril {
		  font-size: 40px !important;
		}
		.titulo {
		  font-size: 24px !important;
		}
		.codigo_barra {
		  margin-bottom:40px;
		  text-align:center;
		  padding-right: 50px;
		}
		.carril {
		  font-size: 100px;
		}
	  </style>
	</html>';

	// $html = preg_replace_callback(
	// 	"|{(\w*)}|",
	// 	function ($matches) use ($datos_bulto) {
	// 		return $datos_bulto[$matches[1]];
	// 	},
	// 	$plantilla
	// );
	// echo $html;
	$mpdf->WriteHTML($html);
}
// if($debug) {
// 	$mpdf->Output();
// }
// else {
	$mpdf->Output("Etiquetas pedido ".$id_pedido.".pdf", 'D');
// }

function upca($upc_code) {
    $upc = substr($upc_code,0,11);
    if (strlen($upc) == 11 && strlen($upc_code) <= 12) {

		$oddPositions = $upc[0] + $upc[2] + $upc[4] + $upc[6] + $upc[8] + $upc[10]; 
		$oddPositions *= 3;
		$evenPositions= $upc[1] + $upc[3] + $upc[5] + $upc[7] + $upc[9];
		$sumEvenOdd = $oddPositions + $evenPositions;
		$checkDigit = (10 - ($sumEvenOdd % 10)) % 10;

	} 
	return $upc_code.$checkDigit;
}