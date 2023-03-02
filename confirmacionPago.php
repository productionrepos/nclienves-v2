<?php
$dev = false;
    session_start();
    $id_cliente = $_SESSION['cliente']->id_cliente;

    require_once('./ws/flow/FlowApi.class.php');
    require_once('./ws/bd/dbconn.php');

   if($dev) {
		if(!isset($_GET["token"])) {
			header("Location: resultado_pago.php?token=CF80BE4D0C061BFC8EE01D80D3615D491EA726CN");
		}
		$token = filter_input(INPUT_GET, 'token');
	}
	else {
		if(!isset($_POST["token"])) {
			throw new Exception("No se recibio el token", 1);
		}
		$token = filter_input(INPUT_POST, 'token');
	}
    
	$params = array(
		"token" => $token
	);
	$serviceName = "payment/getStatus";
	$flowApi = new FlowApi();
	$respuesta = (object)$flowApi->send($serviceName, $params, "GET");
	$informacion_pago_pedido = json_encode($respuesta);

    $id_pedido = $respuesta->commerceOrder;


    $conexion = new bd();
	$conexion->conectar();


    if($respuesta->status==2) {
		$query = "UPDATE pedido SET estado_pedido=2 WHERE id_pedido=$id_pedido";
		if(!$conexion->mysqli->query($query)) {
			echo $conexion->mysqli->error;
			$conexion->desconectar();
			exit();
		}
		//importante!!!!!!!!!!!!!!!!!!!!!!!!!!
		if($datos = $conexion->mysqli->query("SELECT * FROM pago_pedido WHERE codigo_transaccion='$token' AND timestamp_pago_pedido>0 AND importe_pago_pedido>0")) {
			if($datos->num_rows==0) {
				$timestamp_pago_pedido = time();
				$id_comercio = 1;
				$importe_pago_pedido = $respuesta->paymentData['amount'];
				$query = "INSERT INTO pago_pedido (id_pago_pedido, id_pedido, timestamp_pago_pedido, importe_pago_pedido, 
                                      codigo_transaccion, informacion_pago_pedido, id_comercio) 
                          VALUES (NULL, $id_pedido, $timestamp_pago_pedido, $importe_pago_pedido, '$token', '$informacion_pago_pedido', 1)";
				if(!$conexion->mysqli->query($query)) {
					echo $conexion->mysqli->error;
					$conexion->desconectar();
					exit();
				}
			}
		}
		else {
			echo $conexion->mysqli->error;
			$conexion->desconectar();
			exit();
		}
	}


    if($respuesta->status!=2){
		$querynotpay = "INSERT INTO pago_pedido (id_pago_pedido, id_pedido, timestamp_pago_pedido, importe_pago_pedido, 
                              codigo_transaccion, informacion_pago_pedido, id_comercio) 
                  VALUES (NULL, $id_pedido, 0, 0, '$token', '$informacion_pago_pedido', 1)";
		if(!$conexion->mysqli->query($querynotpay)) {
			echo $conexion->mysqli->error;
			$conexion->desconectar();
			exit();
		}
		else {
			header("Location: error_pago.php?token=$token&id_pedido=$id_pedido");
		}
		exit();
	}



    $querydatos = "SELECT * from pedido
                    INNER JOIN cliente ON (pedido.id_cliente=cliente.id_cliente)
                    INNER JOIN datos_contacto ON (cliente.id_cliente=datos_contacto.id_cliente)
                    WHERE id_pedido=$id_pedido";
	if($datos = $conexion->mysqli->query($querydatos)) {
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
        include_once('../nclientesv2/include/head.php');
    ?>
<body>
    <div id="app">
        <!-- SideBar -->
        <?php
            include_once('../nclientesv2/include/sidebar.php');
        ?>
       
        <div id="main"  class="layout-navbar">

            <?php
                include_once('./include/topbar.php');
            ?>
            <div class="page-content" style="color:3e3e3f;">
            <?php
            
             //href="detalle_pedido.php?id_pedido= PHP  $id_pedido  PHP"
                if($respuesta->status == 2):
                     
            ?>
          
                <div class="card">
                        <div class="card-body">
                            <img src="assets/images/logo_horizontal.png" class="img img-fluid" style="margin-top: -15%; margin-bottom: -4%;"/>
                            <h2 class="mb-4 text-center">Comprobante de pago</h2>
                            <p>Estimado <?=$datos_cliente->nombres_datos_contacto?> <?=$datos_cliente->apellidos_datos_contacto?>, hemos confirmado el pago para el pedido #<?=$id_pedido?>.</p>
                            <p>Nos pondremos en contacto contigo prontamente para coordinar el retiro de los paquetes incluidos.</p>
                            <p>Nuestro compromiso es recepcionarlo dentro de las 24 horas próximas.</p>
                            <a href="inicio.php" class="btn btn-primary mb-4 btn-block text-white">Ir al pedido y continuar con el proceso</a>
                            <div class="table-responsive mt-4">
                                <table class="table invoice-detail-table table-bordered">
                                    <thead>
                                        <tr>
                                            <td class="text-center" colspan="2"><b>Información de pago</b></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                N° de orden:
                                            </td>
                                            <td>
                                                <?=$respuesta->flowOrder?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Concepto:
                                            </td>
                                            <td>
                                                Pedido #<?=$respuesta->commerceOrder?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Fecha y Hora:
                                            </td>
                                            <td>
                                                <?=date("d-m-Y H:i:s", strtotime($respuesta->paymentData['date']))?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Monto:
                                            </td>
                                            <td>
                                                <?=(int)$respuesta->paymentData['amount']?> <?=$respuesta->paymentData['currency']?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Medio de pago:
                                            </td>
                                            <td>
                                                <?=$respuesta->paymentData['media']?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table invoice-detail-table table-bordered">
                                    <thead>
                                        <tr>
                                            <td class="text-center" colspan="2"><b>Detalle</b></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach($respuesta->optional as $concepto => $precio):
                                        ?>
                                        <tr>
                                            <td>
                                                <?=$concepto?>
                                            </td>
                                            <td>
                                                <?=$precio?>
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
                </div>
            <?php else:?>
                <div class="card">
                    <div class="card-body">
                        <img src="include/Logotipo_Spread.png" class="img img-fluid" style="margin-top: -15%; margin-bottom: -4%;"/>
                        <h2 class="mb-4 text-center">No se ha podido cursar tu pedido</h2>
                        <p>Estimado <?=$datos_cliente->nombres_datos_contacto?> <?=$datos_cliente->apellidos_datos_contacto?>, hemos tenido problemas para confirmar su pedido #<?=$id_pedido?>.</p>
                        <p>No se he realizado ningún cargo a su cuenta.</p>
                        <p>Lamentamos las molestias, usted podría volver a intentarlo en este enlace.</p>
                        <a href="invoice.php?id_pedido=<?=$id_pedido?>" class="btn btn-primary mb-4 btn-block text-white">Ir al pedido y reintentar el pago.</a>
                    </div>
		        </div>
            <?php endif;?>

    <?php
        include_once('../nclientesv2/include/footer.php')
    ?>
    

<!-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> -->

</body>