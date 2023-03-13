<?php
session_start();
if(!isset($_SESSION['cliente'])){
    header('Location: index.php');
}

if(!isset($_GET['id_pedido'])):
    header("Location: index.php");
  endif;

//   $id_pedido = $_GET['id_pedido'];

  $bultoss = [];
  
  $totalbultos = 0;

require_once('./ws/bd/dbconn.php');
$conn = new bd();
$conn->conectar();

$id_pedido = $_GET['id_pedido'];

$querybultosporpedido = "SELECT pedido.id_pedido AS pedido,bulto.codigo_barras_bulto AS codigo_barras, datos_comerciales.id_cliente, rut_datos_comerciales AS rut_comercio, telefono_datos_comerciales as telefono_comercio, nombre_fantasia_datos_comerciales AS nombre_comercio, nombre_bulto AS nombre_destinatario, direccion_bulto AS direccion_destinatario, telefono_bulto AS telefono_destinatario, email_bulto AS email_destinatario, comuna_destino.nombre_comuna AS comuna_destinatario, region_destino.nombre_region AS region_destinatario, comuna_destino.carril_comuna AS carril,concat(calle_bodega,' ',numero_bodega) AS direccion_origen, nombre_bodega AS nombre_bodega_origen, comuna_origen.nombre_comuna AS comuna_origen, bulto.track_spread as track
                              FROM bulto
                              INNER JOIN comuna AS comuna_destino ON (bulto.id_comuna = comuna_destino.id_comuna)
                              INNER JOIN provincia AS provincia_destino ON (comuna_destino.id_provincia=provincia_destino.id_provincia)
                              INNER JOIN region AS region_destino ON (provincia_destino.id_region=region_destino.id_region)
                              INNER JOIN pedido ON (bulto.id_pedido=pedido.id_pedido)
                              INNER JOIN bodega ON (pedido.id_bodega=bodega.id_bodega)
                              INNER JOIN comuna AS comuna_origen ON (bodega.id_comuna = comuna_origen.id_comuna)
                              INNER JOIN datos_comerciales ON (pedido.id_cliente=datos_comerciales.id_cliente)
                              WHERE bulto.id_pedido =".$id_pedido." and bulto.deleted = 0";


$querybulto = 'SELECT b.id_bulto,b.track_spread,b.nombre_bulto,b.direccion_bulto,b.precio_bulto,b.codigo_barras_bulto,p.estado_pedido,p.estado_logistico
                FROM bulto b
                inner join pedido p on b.id_pedido = p.id_pedido
                where b.id_pedido ='. $id_pedido;

$queryPedido = 'SELECT p.timestamp_pedido,b.calle_bodega,b.numero_bodega,c.nombre_comuna,b.detalle_bodega FROM pedido p
                inner join bodega b on p.id_bodega = b.id_bodega
                inner join comuna c on b.id_comuna = c.id_comuna
                where p.id_pedido ='. $id_pedido;
// echo $querybulto."<br>";

$sinTrack = array();
$estadoPedido = 0;
$bultos = array();
$precioFinal = 0;
$id_bultos = "";

if($resdatabulto = $conn->mysqli->query($querybulto)){
    while($datares = $resdatabulto->fetch_object())
    {
        if($datares->track_spread == "" || $datares->track_spread == "0" || $datares->track_spread == NULL ){
            $sinTrack[] = $datares->id_bulto;
            if($id_bultos == ""){
                $id_bultos = $datares->id_bulto;
            }else{
                $id_bultos = $id_bultos.','.$datares->id_bulto;
            }
        }
        $bultos[] = $datares;
        $precioFinal = $precioFinal + $datares->precio_bulto;
        $estadoPedido = $datares->estado_pedido;
    }
}

if($resPedido = $conn->mysqli->query($queryPedido)){
    while($dataPedidos = $resPedido->fetch_object()){
        $direccion = $dataPedidos->calle_bodega." ".$dataPedidos->numero_bodega.", ".$dataPedidos->nombre_comuna;
        $fecha = date('d/m/Y',$dataPedidos->timestamp_pedido);
    }
}

if($responsebultos = $conn->mysqli->query($querybultosporpedido)){
    while($ressbultos = $responsebultos->fetch_object()){
      $bultoss [] = $ressbultos;
    }
    $totalbultos = $responsebultos->num_rows; 
}

// print_r($bultos);

if( $estadoPedido == 0 ){
    header('Location: index.php');
    exit();
}

$existeSinTrack = 0;

if( count($sinTrack) > 0 ){
    $existeSinTrack = 1;
}



$date = (new DateTime('now',new DateTimeZone('Chile/Continental')))->format('Y-m-d H:i:s');

$querybulto = 'SELECT bu.id_bulto as guide, bu.nombre_bulto as nombre, bu.email_bulto as correo, bu.telefono_bulto as telefono,
bu.direccion_bulto as direccion, co.nombre_comuna as comuna,re.nombre_region as region, bu.precio_bulto as precio,
bu.codigo_barras_bulto as barcode
FROM bulto bu 
INNER JOIN comuna co on co.id_comuna = bu.id_comuna
INNER JOIN provincia pro on pro.id_provincia = co.id_provincia
INNER JOIN region re on re.id_region = pro.id_region
where bu.id_bulto in ('. $id_bultos.') and bu.Deleted = 0';

$dataAppolo =['hola'];

if($resdatabulto = $conn->mysqli->query($querybulto)){
  while($datares = $resdatabulto->fetch_object()){
    $datosbultos [] = $datares;
  }
  $dataAppolo = [];
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


if($_SERVER['HTTP_HOST'] == 'localhost:8080'){
$http = 'http://';
}else{
    $http = 'https://';
}
?>

<!DOCTYPE html>
<html lang="en">
<!-- <?php
  //require_once('./include/head.php')
?> -->
<head >
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php if(isset($head)){
        echo "<title>".$head."</title>";
    }
    else {
        echo "<title>SPREAD</title>";
    } ?>

    <meta name="author" content="Spread" />
	<!-- <link rel="icon" href="assets/images/Logotipo_Spread_13.png" type="image/x-icon"> -->
    
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" href="assets/css/bootstrap.css"> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <link rel="stylesheet" href="/">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/92ca8b0db6.js" crossorigin="anonymous"></script>
    <!-- <link rel="stylesheet" href="dist/assets/vendors/perfect-scrollbar/perfect-scrollbar.css"> -->
    <link rel="stylesheet" href="../assets/css/main/app.css">
    <link rel="stylesheet" href="../assets/css/main/ncliapp.css">
    <!-- <link rel="shortcut icon" href="dist/assets/images/favicon.svg" type="image/x-icon"> -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.css">
  
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/choices.js/2.8.3/styles/css/choices.css">

    <link
      rel="shortcut icon"
      href="../include/img/Logotipo Spread_--13.png"
      type="image/png"
    />
    <link rel="stylesheet" href="./assets/css/shared/iconly.css" />
    <!-- <link rel="stylesheet" href="../assets/css/main/timeline.sass"> -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>    
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js" 
        integrity="sha512-QEAheCz+x/VkKtxeGoDq6nsGyzTx/0LMINTgQjqZ0h3+NjP+bCsPYz3hn0HnBkGmkIFSr7QcEZT+KyEM7lbLPQ==" 
        crossorigin="anonymous" 
        referrerpolicy="no-referrer">
    </script>

</head>
<body lang="en">
    <div id="overlay">
        <div class="cv-spinner">
            <span class="spinner"></span><br>
            <p style="font-weight: bolder; color: white; font-size: larger; margin-top: 40px;">Generando las etiquetas</p>
        </div>
    </div>    
    
    <div id="app">
        <!-- SideBar -->
        <?php
            include_once('./include/sidebar.php');
        ?>
       
        <div id="main"  class="layout-navbar">

            <?php
                include_once('./include/topbar.php');
            ?>
            <div class="row"  style="padding: 20px; text-align: center;">
                <div class="card resumen-envios col-lg-6 col-md-6 col-sm-8 col-12" style="padding: 20px; text-align: center;">
                    <div class="">
                            <h3 style="color: black; font-weight: 700;">Información del pedido</h3>
                    </div>
                </div>
            </div>

            <div class="page-content" style="padding: 3px; margin: 15px !important;">

                <div class="resumen-envios ">
                    <div class="row">
                        <h4 style="color:black;">Descargue las etiquetas aquí</h4>
                    </div>
                    <div class="row">
                        <a id="pdfbyJS" 
                            type="button" class="btn btn-lg btn-block btn-spread">
                            <i class="fa fa-download d-flex"></i>
                            Descargue aquí el archivo para imprimir las etiquetas que debe adherir en los bultos
                        </a>
                    </div>
                </div>    

                <div class="resumen-envios">
                    <div class="row">
                        <div class="col-12" style="text-align: center;">
                            <div class="row">
                                <h5 style="color:black;">Resumen Pedido</h5>
                            </div>
                            <div class="d-flex justify-content-center">
                                <table class="table" style=" color:white; align-self:center;">
                                    <thead>
                                        <tr>
                                            <th>Fecha pedido</th>
                                            <th>Punto de retiro</th>
                                            <th>Costo total de envío</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $fecha ?></td>
                                            <td><?php echo $direccion ?></td>
                                            <td><?php echo moneda($precioFinal); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-12" style="text-align: center; justify-content: center;">
                        <h4 style="color:black;">Resumen paquetes enviados</h4>

                        <table class="table " style="background-color:#bfffd7ad; width: 100% !important;">
                            <thead>
                                <tr>
                                    <th>Guia</th>
                                    <th>Clientes</th>
                                    <th>Dirección</th>
                                    <th>Costo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($bultos as $bulto){?>
                                    <tr>
                                        <td><?php echo $bulto->track_spread; ?></td>
                                        <td><?php echo $bulto->nombre_bulto; ?></td>
                                        <td><?php echo $bulto->direccion_bulto; ?></td>
                                        <td><?php echo moneda($bulto->precio_bulto); ?></td>
                                    </tr>
                                <?php }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                

            </div>

      <footer >
        <div class="footer clearfix mb-0 text-muted">
            <div class="float-start">
                <p style="color:#3e3e3f">2023 &copy; Spread</p>
            </div>
        </div>
      </footer> 
    <div style= "max-height: 1px;overflow: hidden;">
      <?php
        $counter = 0;
        foreach($bultoss as $bulto):
          $counter ++;
      ?>
      <div class="formpdf" style="margin-left: 10%; z-index: -10;">
          <div style="margin-bottom:10px">
            <table style="margin-left: 2%;border-color: white !important;">
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
          <table style="margin-left: 5%;">
            <tbody>
              <tr>
                <td width="50%" class="text-center"><b class="titulo-td">Comercio</b></td>
                <td width="50%" class="text-center"><b class="titulo-td">Bodega</b></td>
              </tr>
              <tr>
                <td width="50%" class="text-center"><?php echo $bulto->nombre_comercio?></b></td>
                <td width="50%" class="text-center"><?php echo $bulto->nombre_bodega_origen?></b></td>
              </tr>
              <tr>
                <td width="50%" class="text-center"><b class="titulo-td">Dirección</b></td>
                <td width="50%" class="text-center"><b class="titulo-td">Comuna</b></td>
              </tr>
              <tr>
                <td width="50%" class="text-center"><?php echo $bulto->direccion_origen?></b></td>
                <td width="50%" class="text-center"><?php echo $bulto->comuna_origen?></b></td>
              </tr>
              <tr>
                <td width="50%" class="text-center"><b class="titulo-td">Teléfono</b></td>
                <td width="50%" class="text-center"><b class="titulo-td">Nro Pedido </b></td>
              </tr>
              <tr>
                <td width="50%" class="text-center"><?php echo $bulto->telefono_comercio?></b></td>
                <td width="50%" class="text-center"><?php echo $bulto->pedido?></b></td>
              </tr>
            </tbody>
          </table>
          <div style="margin-bottom:5px; margin-top:10px;margin-left: 33%;"> 
            <table width="100%">
              <tr>
                <td width="100%" style="border: 0px;">
                  <div>
                      <canvas id="barcode<?php echo $bulto->track;?>"></canvas>
                      <script>
                          JsBarcode("#barcode<?php echo $bulto->track; ?>", "<?php echo $bulto->track; ?>");
                      </script>
                  </div>
                </td>
              </tr>
            </table>
            <table>
                <tr>
                <td style="text-align: center;">
                    <h1><?php echo $bulto->track?></h1>
                    <p>Numero de Guia</p>
                </td>
                </tr>
            </table>
          </div>
            <table style="margin: 5%; width: auto;">
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
                  <td class="text-center"><?php echo $bulto->nombre_destinatario;?></td>
                  <td class="text-center"><?php echo $bulto->direccion_destinatario;?></td>
                </tr>
                <tr>
                  <td width="50%" class="text-center"><b class="titulo-td">Comuna</b></td>
                  <td width="50%" class="text-center"><b class="titulo-td">Región</b></td>
                </tr>
                <tr>
                  <td class="text-center"><?php echo $bulto->comuna_destinatario;?></td>
                  <td class="text-center"><?php echo $bulto->region_destinatario;?></td>
                </tr>
                <tr>
                  <td width="50%" class="text-center"><b class="titulo-td">Teléfono</b></td>
                  <td width="50%" class="text-center"><b class="titulo-td">Email</b></td>
                </tr>
                <tr>
                  <td class="text-center"><?php echo $bulto->telefono_destinatario;?></td>
                  <td class="text-center"><?php echo $bulto->email_destinatario;?></td>
                </tr>
              </tbody>
            </table>

      </div> 
      <?php
        endforeach;
      ?>

    </div>

  <script src="../assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
  <script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
  <script src="../assets/js/pages/dashboard.js"></script>
  <script src="../assets/js/bootstrap.js"></script>
  <script src="../assets/js/app.js"></script>
  <script src="../assets/extensions/jquery/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/choices.js/2.8.3/choices.js"></script>
  <script src="assets/js/pages/form-element-select.js"></script>
    

</body>
<style>
    body {
      font-size: 20px;
      min-height: 900px;
    }
    /* table {
      display: block;
      width: 100%;
      border-collapse: collapse;
      border-spacing: 2px;
      border-color: grey;
      margin-top: 5px;
    } */
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
<script>
    let existeSinTrack = <?php echo $existeSinTrack; ?>;
    let appoloData =<?php echo json_encode($dataAppolo);?>;
    var url = 'https://spreadfillment-back.azurewebsites.net/api/pymes/ingresarPyme'
    var urlGetInitial = 'https://spreadfillment-back.azurewebsites.net/api/pymes/ingresarPyme'
    // var urlGetInitial = 'http://localhost:8000/api/pymes/revisarDocumentNumber'
    const fecha = '<?php echo $date;?>';
    var request = "";
    var newTrackId;
    var conteoAppolo;
    var erroresAppolo;
    var totalAppolo;

    $(document).ready( async function (){
        if(existeSinTrack > 0){
            await appoloData.forEach((ap,i) => {
                console.log('dentro del foreach');
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
                (async () => {
                    const rawResponse = await fetch( url , {
                        method: 'POST',
                        headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({body:request})
                    }).then(async (response) => {
                        let estadoResponse = await response.json();

                        if(estadoResponse.trackId){
                            newTrackId = (estadoResponse.trackId);
                        }
                        if(estadoResponse.errors['0'].msg == `El numero de guia '${ap.guide}' ya existe en la base de datos`){
                            urlGet = "";
                            urlGet = urlGetInitial+'/'+ap.guide;
                            const responetGet = await fetch( urlGet , {
                                method: 'GET',
                                headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                                }
                            }).then(async (responseGet) => {
                                let getTrackId = await responseGet.json();
                                newTrackId = getTrackId.TrackID;
                            })
                        }
                    })
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
                            conteoAppolo = conteoAppolo + 1;
                            if(data.status == 1){
                                // console.log(data);
                            }else{
                                erroresAppolo = erroresAppolo + 1;
                            }
                            if(conteoAppolo == totalAppolo){
                                window.location.reload();
                            }
                        },error:function(data){
                            console.log(data);
                        }
                    })
                })();
                }, i * 3000);
            })
        }
    })
    // JsBarcode("#barcode", "358853358");

    window.jsPDF = window.jspdf.jsPDF;
    window.html2canvas = html2canvas;
    var totalbultos = <?=$totalbultos;?>;
    
  $('#pdfbyJS').on('click', async function(){
    var doc = new jsPDF('p','pt', 'a4');

    let canvases = document.querySelectorAll(".formpdf");
    $("#overlay").fadeIn(300);
    for(var i = 0; i < canvases.length ; i++){
      console.log(canvases[i]);
      
      await html2canvas(canvases[i]).then(canvas=>{

          if(i>0){
            doc.addPage();
          }
          doc.setPage(i+1);
          let dataURL = canvas.toDataURL('image/jpeg');
          doc.addImage(dataURL, 'JPEG', 0, 0, 500, 600)
        })
      }
      // doc.output("dataurlnewwindow");.
      $("#overlay").fadeOut(300);
      doc.save();
  })
  </script>
</html>
<?php
function moneda($number) {
    return '$'.number_format($number, 0, ',', '.');
}
?>