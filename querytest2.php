<?php 
    session_start();
    date_default_timezone_set("America/Santiago");
    if(!isset($_SESSION['cliente'])):
        header("Location: index.php");
    endif;

    $id_cliente = $_SESSION['cliente']->id_cliente;

    $id_pedido = 36468;

    require_once('./ws/bd/dbconn.php');
    $conexion = new bd();
    $conexion->conectar();

    $bultos = [];
    $totalbultos = 0;

    if($_SERVER['HTTP_HOST'] == 'localhost:8080'){
        $http = 'local';
    }else{
        $http = 'servidor';
    }


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

    if($responsebultos = $conexion->mysqli->query($querybultosporpedido)){
      while($ressbultos = $responsebultos->fetch_object()){
        $bultos [] = $ressbultos;
      }
      $totalbultos = $responsebultos->num_rows; 
    }


?>


<!DOCTYPE html>
<html  style=" overflow: hidden" lang="en">
<head>
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


    <!-- ??????????? barcode ??? -->

    <!-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+EAN13+Text&display=swap" rel="stylesheet"> -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js" 
        integrity="sha512-QEAheCz+x/VkKtxeGoDq6nsGyzTx/0LMINTgQjqZ0h3+NjP+bCsPYz3hn0HnBkGmkIFSr7QcEZT+KyEM7lbLPQ==" 
        crossorigin="anonymous" 
        referrerpolicy="no-referrer">
    </script>

    


    



</head>
<body  style=" overflow: hidden">
  <div id="app">
        <!-- SideBar -->
        <?php
            include_once('./include/sidebar.php');
        ?>
       
      <div id="main"  class="layout-navbar">

              <?php
                  include_once('./include/topbar.php');
              ?>
        
                <div class="container-fluid" id="containermainmenu">
               
                  <div class="page-content" style="color:3e3e3f;">
                    
                    <a 
                        id="pdfbyJS" type="button" class="btn btn-lg btn-block btn-spread">
                        <i class="fa fa-download d-flex"></i>
                        PDF GENERADO POR JAVASCRIPT
                    </a>
                </div>
    </div>
    


    <?php
        include_once('./include/footer.php')
    ?> 
    <?php
      $counter = 0;
      foreach($bultos as $bulto):
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
        <div style="margin-bottom:70px; margin-top:70px;margin-left: 21%;"> 
          <table width="100%">
            <tr>
              <td width="100%" style="border: 0px;">
                <div>
                    <canvas id="barcode"></canvas>
                    <script>
                        JsBarcode("#barcode", "<?php echo $bulto->track; ?>");
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
        <table style="margin-left: 4%;">
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
    var doc = new jsPDF('p','mm', [200, 280]);

    let canvases = document.querySelectorAll(".formpdf");
    
    for(var i = 0; i < canvases.length ; i++){
      console.log(canvases[i]);
      
      await html2canvas(canvases[i]).then(canvas=>{

        //   let width = canvas.width;
        //   let height = canvas.height;
        var width = doc.internal.pageSize.getWidth();
        var height = doc.internal.pageSize.getHeight();
          if(i>0){
            doc.addPage();
          }

          doc.setPage(i+1);
          let dataURL = canvas.toDataURL('image/jpeg');
          doc.addImage(dataURL, 'JPEG', 0, 0,  width+50  , height+50 )
          
        })
      }
      // doc.output("dataurlnewwindow");
      doc.save();
  })
  </script>
</html>