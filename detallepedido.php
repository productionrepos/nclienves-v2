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
                              WHERE bulto.id_pedido =".$id_pedido;


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

if($resdatabulto = $conn->mysqli->query($querybulto)){
    while($datares = $resdatabulto->fetch_object())
    {
        if($datares->track_spread == ""){
            $sinTrack[] = $datares->id_bulto;
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

if($_SERVER['HTTP_HOST'] == 'localhost:8080'){
$http = 'http://';
}else{
    $http = 'https://';
}
?>

<!DOCTYPE html>
<html style=" overflow: hidden" lang="en">
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
<body style=" overflow: hidden"  lang="en">
    <div id="overlay">
        <div class="cv-spinner">
            <span class="spinner"></span>
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
                        <div class="col-lg-6 col-md-6 col-12" style="text-align: center;">hola 2</div>
                    </div>
                    <div class="row">
                        <div class="col-12" style="text-align: center;">
                            <div class="row">
                                <h4 style="color:black;">Resumen paquetes enviados</h4>
                            </div>
                            <div class="row">
                                <table class="table" style="width: 100%; background-color:#bfffd7ad;">
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
                </div>
                

            </div>

<?php
require_once('./include/footer.php')
?>

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
        <div style="margin-bottom:70px; margin-top:70px;margin-left: 33%;"> 
          <table width="100%">
            <tr>
              <td width="100%" style="border: 0px;">
                <div>
                    <canvas id="barcode<?php echo $bulto->track;?>"></canvas>
                    <script>
                        JsBarcode("#barcode<?php echo $bulto->track; ?>", "<?php echo $bulto->track; ?>");
                    </script>
                  <!-- <strong>EAN-13:</strong>
                  <span class="ean-barcode">11011999</span> -->
                </div>
                <!-- <div class="codigo_barra" style="text-align: center;">
                  <barcode code="123223" type="EAN128A" class="barcode" size="4" height="0.5"/>
                </div> -->
              </td>
            </tr>
            <tr>
              <td>
                  <h1><?php echo $bulto->track?></h1>
                  <p>Numero de Guia</p>
              </td>
            </tr>
          </table>
        </div>
        <div class="row justify-content-center">
          <table class="col-10">
            <thead class="personaldatalabelhead">
              <tr>
                <td class="text-center" colspan="2"><b class="titulo">Destinatario</b></td>
              </tr>
            </thead>
            <tbody class="personaldatalabelbody">
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

    </div> 
    <?php
      endforeach;
    ?>

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
<script>
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

          let width = canvas.width;
          let height = canvas.height;
        // var width = doc.internal.pageSize.getWidth();
        // var height = doc.internal.pageSize.getHeight();
          if(i>0){
            doc.addPage();
          }
          doc.setPage(i+1);
          let dataURL = canvas.toDataURL('image/jpeg');
          doc.addImage(dataURL, 'JPEG', 0, 0, width*0.70, height*0.65 )
          
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