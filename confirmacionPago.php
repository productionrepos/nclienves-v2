<?php
session_start();

$existeSesion = 0;
if(!isset($_SESSION['cliente'])){
    $existeSesion = 0;
}else{
    $existeSesion = 1;
}

$date = (new DateTime('now',new DateTimeZone('Chile/Continental')))->format('Y-m-d H:i:s');

require_once('./ws/flow/FlowApi.class.php');
require_once('./ws/bd/dbconn.php');

if(!isset($_POST["token"])) {
    throw new Exception("No se recibio el token", 1);
}
$token = filter_input(INPUT_POST, 'token');

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

// busqueda de integración con Appolo
$querybulto = 'SELECT bu.id_bulto as guide, bu.nombre_bulto as nombre, bu.email_bulto as correo, bu.telefono_bulto as telefono,
bu.direccion_bulto as direccion, co.nombre_comuna as comuna,re.nombre_region as region, bu.precio_bulto as precio,
bu.codigo_barras_bulto as barcode
FROM bulto bu 
INNER JOIN comuna co on co.id_comuna = bu.id_comuna
INNER JOIN provincia pro on pro.id_provincia = co.id_provincia
INNER JOIN region re on re.id_region = pro.id_region
where bu.id_pedido ='. $id_pedido;

if($resdatabulto = $conn->mysqli->query($querybulto)){
  while($datares = $resdatabulto->fetch_object()){
    $datosbultos [] = $datares;
  }
  $dataAppolo =[];
  foreach($datosbultos as $databul){
    $dataAppolo[]= Array(
      "guide" => $databul->barcode,
      "name_client" => $databul->nombre,
      "email" => $databul->correo,
      "phone"=> $databul->telefono ,
      "street"=> $databul->direccion,
      "number"=> "" ,
      "commune" => $databul->comuna,
      "region"=> $databul->region,
      "dpto_bloque"=> "",
      "id_pedido"=> $id_pedido,
      "valor"=> "",
      "descripcion"=> ""
      );
  }
}



if($respuesta->status==2) {
    $query = "UPDATE pedido SET estado_pedido=2 and estado_logistico=1 WHERE id_pedido=$id_pedido";
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
        include_once('./include/head.php');
    ?>
<body>
    <?php
    if($existeSesion == 0){
        include_once('./include/scriptsFooter.php');
    }else{
    ?>
    <div id="app">
        <!-- SideBar -->
        <?php
            include_once('./include/sidebar.php');
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
                            <img src="include/Logotipo_Spread.png" class="img img-fluid" />
                            <h2 class="mb-4 text-center">Comprobante de pago</h2>
                            <p>Estimado <?=$datos_cliente->nombres_datos_contacto?> <?=$datos_cliente->apellidos_datos_contacto?>, hemos confirmado el pago para el pedido #<?=$id_pedido?>.</p>
                            <p>Nos pondremos en contacto contigo prontamente para coordinar el retiro de los paquetes incluidos.</p>
                            <p>Nuestro compromiso es recepcionarlo dentro de las 24 horas próximas.</p>
                            <a href="detallepedido.php?id_pedido=<?php echo $id_pedido ?>" class="btn btn-primary mb-4 btn-block text-white">Ir al pedido y continuar con el proceso</a>
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
                        <img src="include/Logotipo_Spread.png" class="img img-fluid" />
                        <h2 class="mb-4 text-center">No se ha podido cursar tu pedido</h2>
                        <p>Estimado <?=$datos_cliente->nombres_datos_contacto?> <?=$datos_cliente->apellidos_datos_contacto?>, hemos tenido problemas para confirmar su pedido #<?=$id_pedido?>.</p>
                        <p>No se he realizado ningún cargo a su cuenta.</p>
                        <p>Lamentamos las molestias, usted podría volver a intentarlo en este enlace.</p>
                        <a href="invoice.php?id_pedido=<?=$id_pedido?>" class="btn btn-primary mb-4 btn-block text-white">Ir al pedido y reintentar el pago.</a>
                    </div>
		        </div>
            <?php endif;?>

    <?php
        include_once('./include/footer.php');
    }
    ?>
</body>

<script>
    var IdPedido = <?php echo $id_pedido; ?>;
    var existeSesion = <?php echo $existeSesion; ?>;
    var status = <?php echo $respuesta->status ?>

    var appoloData =<?php echo json_encode($dataAppolo);?>;
    const fecha = '<?php echo $date;?>';
    var request = "";
    var newTrackId;
    var url = 'http://localhost:8000/api/pymes/ingresarPyme'
    // var url = 'https://spreadfillment-back-dev.azurewebsites.net/api/pymes/ingresarPyme'

    $(document).ready(function(){

        if(existeSesion == 0){
            $.ajax({
                type: "POST",
                url: "./ws/cliente/ingresarById.php",
                data: JSON.stringify({"IdPedido":IdPedido}),
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    if(data.success==1) {
                        // console.log('estoy aqui');
                        window.location.reload();
                        // window.location.href = "./";
                    }
                    else {
                        // swal.fire(data.titulo, data.message, "error");
                        // $("#password_cliente").val("");
                    }
                },
                error: function(data){
                    // console.log(data.responseText);
                    // console.log('fallé');
                }
            });
        }

        if(status == 2){
            newTrackId = "";
            appoloData.forEach((ap,i) => {
				setTimeout(function () {
					request = {"guide" : ap.guide,
								"name_client" : ap.name_client,
								"email": ap.email,
								"phone": ap.phone ,
								"street": ap.street,
								"number": "" ,
								"commune": ap.commune,
								"region": ap.region,
								"dpto_bloque": "",
								"id_pedido": ap.id_pedido,
								"fecha": fecha,
								"valor": "",
								"descripcion": ""};
					// console.log(request);
					
					(async () => {
					const rawResponse = await fetch( url , {
						method: 'POST',
						headers: {
						'Accept': 'application/json',
						'Content-Type': 'application/json'
						},
						body: JSON.stringify({body:request})
					})
					.then(async (response) => {
						let estadoResponse = await response.json();
						// console.log(estadoResponse);

						if(estadoResponse.trackId){
							newTrackId = (estadoResponse.trackId);
						}else{
							if(estadoResponse.error.sql){
								const Response = await await fetch( url , {
									method: 'POST',
									headers: {
										'Accept': 'application/json',
										'Content-Type': 'application/json'
									},
									body: JSON.stringify({body:request})
								})
								.then(async (response2) => {
									let estadoResponse2 = await response2.json();
									if(estadoResponse2.trackId){
										newTrackId = (estadoResponse2.trackId);
									}
								})
							}
						}
					})

					// console.log(newTrackId);

                    var params = {
                        "trackid": newTrackId,
                        "codigo_barra": ap.guide
                    }

					$.ajax({
                        data: JSON.stringify(params),
                        type: "post",
                        url: "./ws/bulto/insertTrackId2.php",
                        dataType: 'json',
                        success: function(data) {
                            // console.log(data.status)
                            // procesado = 1;
                            // console.log(procesado);
                            Swal.fire(
                            'Pago procesado!',
                            'Tú pedido fue procesado por credito!',
                            'success'
                            );
                            window.location="detallepedido.php?id_pedido="+id_pedido;
                        },error:function(data){
                            // console.log(data);
                        }
                    })

					})();
				}, i * 2000);
			})
        }
    })
    
</script>

</html>