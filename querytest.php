<?php
  session_start();
  if(!isset($_SESSION['cliente'])){
    header('Location: index.php');
  }
  
//   if($_SESSION['cliente']->id_cliente != 1394 && $_SESSION['cliente']->id_cliente != 1373){
//     header('Location: index.php');
//   }
require_once('./ws/bd/dbconn.php');
$conn = new bd();
$conn->conectar();

$id_pedido = 35812;

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
  $html = "";
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
    
    $html = $html.'<tr>
        <td>'.$databul->barcode.'</td>
        <td>'.$databul->nombre.'</td>
        <td>'.$databul->correo.'</td>
        <td>'.$databul->telefono.'</td>
        <td>'.$databul->direccion.'</td>
        <td></td>
        <td>'.$databul->comuna.'</td>
        <td>'.$databul->region.'</td>
        <td></td>
        <td>'.$id_pedido.'</td>
        <td></td>
        <td></td>
        ';

    $html = $html.'</tr>';
  }
}


?>



<!DOCTYPE html>
<html lang="en">
<?php
  require_once('./include/head.php')
?>
<body>
    <div class="container">
      <div class="container-fluid">
          <row class="col-12">
              <table id="excel-table" class="table">
                  <thead> 
                      <tr>
                        <td>guia</td>
                        <td>Clientes</td>
                        <td>email</td>
                        <td>telefono</td>
                        <td>calle</td>
                        <td>numero</td>
                        <td>comuna</td>
                        <td>region</td>
                        <td>dpto/bloque</td>
                        <td>id_pedido</td>
                        <td>valor</td>
                        <td>descripcion</td>
                      </tr>
                  </thead>
                  <tbody>
                      <?php echo $html; ?>
                  </tbody>
              </table>
          </row>
      </div>
    </div>
  <button class="btn btn-spread" onclick="ExportToExcel('xlsx')">Export table to excel</button>
  <br>
  <?php //print_r($dataAppolo) ?>
  <br>
  <input type="text" name="trackid" id="trackid" aria-describedby="helpId" placeholder="Número de pedido" value="125958">
  <a id="apretameclick" class="col-2 btn btn-success">Apretame</a>
  <a id="apretameclick2" class="col-2 btn btn-success">Apretame2</a>
<br>
<div id="datosSeguimiento"></div>

<?php
require_once('./include/footer.php');
$date = (new DateTime('now',new DateTimeZone('Chile/Continental')))->format('Y-m-d H:i:s');
// echo $date;

$params = array( 
    "apiKey" => "3FCFF277-D0F0-45FE-821F-8F963B64L7B1",
    "commerceId" => "36399"
  ); 
  $keys = array_keys($params);
  sort($keys);
$secretKey = '88efefedfe738f4a77e19cbe48a804c5461af01d';

$toSign = "";
foreach($keys as $key) {
  $toSign .= $key . $params[$key];
};
$url = 'https://www.flow.cl/api';
$url = $url . '/payment/getStatusByCommerceId';
// agrega la firma a los parámetros
$signature = hash_hmac('sha256', $toSign , $secretKey);
$params["s"] = $signature;
//Codifica los parámetros en formato URL y los agrega a la URL
$url = $url . "?" . http_build_query($params);

echo "<br>".$url."<br>";
?>

<form method="post" action="confirmacionPago.php">
    <!-- <input hidden="token" value=""> -->
    <input type="hidden" name="token" value="179F0C1F1C8E629E0DCCA56ADFF9F1F4FD461CDY">
    <!-- <input type="hidden" name="token" value="CEB6DE9D686709637010BDEFE77DAAA14F71FCCW"> -->
    <input type="submit">
</form>
<!-- https://www.flow.cl/app/web/pay.php?token=CEB6DE9D686709637010BDEFE77DAAA14F71FCCW -->

<script src="./js/testjs.js"></script>
<script src="js/xlsxReader.js"></script>
<script src="https://unpkg.com/read-excel-file@5.x/bundle/read-excel-file.min.js"></script>

<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

<script>
  function ExportToExcel(type, fn, dl) {
    var elt = document.getElementById('excel-table');
    var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
    return dl ?
        XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64'}):
        XLSX.writeFile(wb, fn || ('MySheetName.' + (type || 'xlsx')));
  }
  
  //var busquedaGet = <?php //echo $id_pedido; ?>;
  var busquedaGet = '125958';
  var url_local = 'http://localhost:8000/api/infoBeetrack/infoPackage/';
  var url_spread = 'https://spreadfillment-back-dev.azurewebsites.net/api/infoBeetrack/infoPackage/';
  var url_beetrack = 'https://app.beetrack.com/api/external/v1/dispatches/';
  var token_beetrack = '4471afc1f7ee5051458a39d3bd5df4a5107ee7df1753a1bf8affef9b29aace75'

  $('#apretameclick').on('click',function(){
    document.getElementById('datosSeguimiento').innerHTML='';
    let valor = document.getElementById('trackid').value
    let fotos;
    let formulario;
    let estadoResponse;
    let html;

    // console.log(valor);
    // console.log(busquedaGet);
    let url = url_local + valor;
    // console.log(url);

    getBeetrack();

    async function getBeetrack(){
        const response = await fetch(url, {
            method: 'GET',
            dataType: 'json',
        })
        .then(async (response) => {
            // console.log(response);
            estadoResponse = await response.json();
            console.log(estadoResponse);
            if(estadoResponse){
                formulario = estadoResponse.evaluation_answers
                html = `<table calss="table">`;
                formulario.forEach(respuesta => {
                    html += `<tr>`;
                    if(respuesta.cast == 'photo'){
                        fotos = respuesta.value.split(',')
                        html += `
                        <td>${respuesta.name}</td>
                        <td>`;
                        // console.log(fotos);
                        for(let i = 0; i < fotos.length ; i++){
                            // console.log(fotos[i]);
                            html += `<a href="${fotos[i]}" target="_blank" style="width: 100%;">
                                        <img alt="package-deliver-img" class="img-responsive" src="${fotos[i]}" style="width:100%;height:100px">
                                    </a>`;
                        }
                        html += `</td>`;
                    }else{
                        html += `
                        <td>${respuesta.name}</td>
                        <td>${respuesta.value}</td>
                        `;
                    }
                });
                // console.log(estadoResponse);
                html += `</table>`;
                document.getElementById('datosSeguimiento').innerHTML=html;
            }else{
                console.log('Sin datos');
            }
        })
    }
});





    // $.ajax({
    //     type: "GET",
    //     // headers: {
    //     //     'X-AUTH-TOKEN':'4471afc1f7ee5051458a39d3bd5df4a5107ee7df1753a1bf8affef9b29aace75',
    //     //     'Content-Type':'application/json'
    //     // },
    //     beforeSend: function(xhr){xhr.setRequestHeader('X-AUTH-TOKEN', token_beetrack);},
    //     // headers: {"X-AUTH-TOKEN" : token_beetrack},
    //     url: url_beetrack+valor,
    //     // url: url_spread+valor,
    //     crossDomain: true,
    //     dataType: 'jsonp',
    //     success: function(data) {
    //         console.log(data)
    //     },error: function(data){
    //         console.log(data)
    //     }
    // })

        // var appoloData =<?php //echo json_encode($dataAppolo);?>;
        // var id_pedido = <?php //echo $id_pedido;?>;
        // const fecha = '<?php //echo $date;?>';
        // var request = "";
        // var newTrackId;
        // var url = 'http://localhost:8000/api/pymes/ingresarPyme'
        // var url = 'https://spreadfillment-back-dev.azurewebsites.net/api/pymes/ingresarPyme'

        // $('#apretameclick2').on('click',function(){
    //     $(document).ready(function(){
		// 	newTrackId = "";
    //         appoloData.forEach((ap,i) => {
		// 		setTimeout(function () {
		// 			request = {"guide" : ap.guide,
		// 						"name_client" : ap.name_client,
		// 						"email": ap.email,
		// 						"phone": ap.phone ,
		// 						"street": ap.street,
		// 						"number": "" ,
		// 						"commune": ap.commune,
		// 						"region": ap.region,
		// 						"dpto_bloque": "",
		// 						"id_pedido": ap.id_pedido,
		// 						"fecha": fecha,
		// 						"valor": "",
		// 						"descripcion": ""};
		// 			// console.log(request);
					
		// 			(async () => {
		// 			const rawResponse = await fetch( url , {
		// 				method: 'POST',
		// 				headers: {
		// 				'Accept': 'application/json',
		// 				'Content-Type': 'application/json'
		// 				},
		// 				body: JSON.stringify({body:request})
		// 			})
		// 			.then(async (response) => {
		// 				let estadoResponse = await response.json();
		// 				console.log(estadoResponse);

		// 				if(estadoResponse.trackId){
		// 					newTrackId = (estadoResponse.trackId);
		// 				}else{
		// 					if(estadoResponse.error.sql){
		// 						const Response = await await fetch( url , {
		// 							method: 'POST',
		// 							headers: {
		// 								'Accept': 'application/json',
		// 								'Content-Type': 'application/json'
		// 							},
		// 							body: JSON.stringify({body:request})
		// 						})
		// 						.then(async (response2) => {
		// 							let estadoResponse2 = await response2.json();
		// 							if(estadoResponse2.trackId){
		// 								newTrackId = (estadoResponse2.trackId);
		// 							}
		// 						})
		// 					}
		// 				}
		// 			})

		// 			console.log(newTrackId);
		// 			// aca insertar a bd send cargo el trackid

		// 			})();
		// 		}, i * 2000);
		// 	})
		// })

      
</script>

</body>
</html>



   

<?php



$date1 = "2023-02-01";

$inicio = date("Y-m-01");
$timestamp1 = strtotime($inicio);

$fin = date("Y-m-t");
$timestamp2 = strtotime($fin);



?>


<div class="panel-body col-8" style="background-color: #60cbb196; margin: 20px; border-radius: 50px;padding: 30px;" >
    <span class="timeline-date">19:00</span>
    <h5 class="blue">03/03/2023</h5>
        <p style="text-decoration: none;color: red;">Entregado</p>
        <span>
            <p style="text-decoration: none;color: #3e3e3f;" class="blue">Entrega exitosa</p>
        </span>
    <p style="margin: 0px;padding:0px;">Nombre completo de quien recibe : Tamara Palomari</p>
    <!-- <p style="margin: 0px;padding:0px;">Rut Receptor : 105613640</p>
    <p style="margin: 0px;padding:0px;">Parentesco (Conserje, Mamá, Cliente, etc..) : amiga</p> -->

    <div style="font-weight: 800;font-size: 18px; margin: top 5px;">Firma digital</div>
    <div class="album row">
            <div class="col-md-3" style=" padding: 0 2% 0 0;">
                <a href="https://cdn.beetrack.com/mobile_evaluations/images/signature_answer_b8aff20f-e6e7-4717-a074-70368df3cc82.png" target="_blank" style="width: 100%;">
                    <img alt="package-deliver-img" class="img-responsive" src="https://cdn.beetrack.com/mobile_evaluations/images/signature_answer_b8aff20f-e6e7-4717-a074-70368df3cc82.png" style="width:100%;height:100px">
                </a>
            </div>
    </div>
    <div style="font-weight: 800; font-size: 18px;">Fotos </div>
    <div class="album row">
            <div class="col-md-3" style=" padding: 0 2% 0 0;">
                <a href="https://cdn.beetrack.com/mobile_evaluations/images/COMP_IMG_20230303_160013_5914975997838589505.jpg" target="_blank" style="width: 100%;">
                    <img alt="package-deliver-img" class="img-responsive" src="https://cdn.beetrack.com/mobile_evaluations/images/COMP_IMG_20230303_160013_5914975997838589505.jpg" style="width:100%;height:100px">
                </a>
            </div>
            <div class="col-md-3" style=" padding: 0 2% 0 0;">
                <a href="https://cdn.beetrack.com/mobile_evaluations/images/COMP_IMG_20230303_160018_8675189283889503001.jpg" target="_blank" style="width: 100%;">
                    <img alt="package-deliver-img" class="img-responsive" src="https://cdn.beetrack.com/mobile_evaluations/images/COMP_IMG_20230303_160018_8675189283889503001.jpg" style="width:100%;height:100px">
                </a>
            </div>
            <div class="col-md-3" style=" padding: 0 2% 0 0;">
                <a href="https://cdn.beetrack.com/mobile_evaluations/images/COMP_IMG_20230303_160023_5014969434535705222.jpg" target="_blank" style="width: 100%;">
                    <img alt="package-deliver-img" class="img-responsive" src="https://cdn.beetrack.com/mobile_evaluations/images/COMP_IMG_20230303_160023_5014969434535705222.jpg" style="width:100%;height:100px">
                </a>
            </div>
            <div class="col-md-3" style=" padding: 0 2% 0 0;">
                <a href="https://cdn.beetrack.com/mobile_evaluations/images/COMP_IMG_20230303_160031_5811682112350141718.jpg" target="_blank" style="width: 100%;">
                    <img alt="package-deliver-img" class="img-responsive" src="https://cdn.beetrack.com/mobile_evaluations/images/COMP_IMG_20230303_160031_5811682112350141718.jpg" style="width:100%;height:100px">
                </a>
            </div>
    </div>
</div>