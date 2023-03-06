<?php
  session_start();
  if(!isset($_SESSION['cliente'])){
    header('Location: index.php');
  }
  
  if($_SESSION['cliente']->id_cliente != 1394 && $_SESSION['cliente']->id_cliente != 1373){
    header('Location: index.php');
  }
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
  <a id="apretameclick" class="col-2 btn btn-success">Apretame</a>
  <a id="apretameclick2" class="col-2 btn btn-success">Apretame2</a>
<br>

<?php
require_once('./include/footer.php');
$date = (new DateTime('now',new DateTimeZone('Chile/Continental')))->format('Y-m-d H:i:s');
echo $date;
?>

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
  var busquedaGet = '149093811717';
  $('#apretameclick').on('click',function(){
    console.log(busquedaGet);
    // console.log(appoloData);
    $.ajax({
        type: "GET",
        headers: {
            'X-AUTH-TOKEN':'4471afc1f7ee5051458a39d3bd5df4a5107ee7df1753a1bf8affef9b29aace75',
            'Content-Type':'application/json'
        },
        url: "https://app.beetrack.com/api/external/v1/dispatches/"+busquedaGet,
        dataType: 'json',
        success: function(data) {
            console.log(data)
            console.log(data.Cliente)
        }
    })
  });

        // var appoloData =<?php echo json_encode($dataAppolo);?>;
        // var id_pedido = <?php echo $id_pedido;?>;
        // const fecha = '<?php echo $date;?>';
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


