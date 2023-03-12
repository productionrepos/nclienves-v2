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

    include('./include/busquedas/busquedaEnvios.php');
    $inicio = date("Y-m-01");
    $timestamp1 = strtotime($inicio);
    $hasretiros = false;

    $arraybultospendientes = [];

    $fin = date("Y-m-t");
    $timestamp2 = strtotime($fin);

    $cantEnvios = totalEnvios($id_cliente,$timestamp1,$timestamp2);
    $cantEnviosEntregados = totalEnviosEntregados($id_cliente,$timestamp1,$timestamp2);
    $cantEnviosEnTransito = totalEnviosEnTransito($id_cliente,$timestamp1,$timestamp2);
    $cantEnviosConProblemas = totalEnviosConProblemas($id_cliente,$timestamp1,$timestamp2);

    if($_SERVER['HTTP_HOST'] == 'localhost:8080'){
        $http = 'local';
    }else{
        $http = 'servidor';
    }
    
    $querypendientes = "Select * from pedido where id_cliente =".$id_cliente.' and estado_pedido > 1 and estado_logistico=1';
    
    if(mysqli_num_rows($resppendientes = $conexion->mysqli->query($querypendientes))>0){

        $hasretiros = true;
        while($pendientes = $resppendientes->fetch_object()){
            $pend [] = $pendientes;
        }

        $cantidadpendientes = count($pend);
           
        foreach($pend  as $pp){
            
            $querybultos = "SELECT bu.track_spread as trackid,
                            CONCAT(bo.calle_bodega,' ',bo.numero_bodega) as direccion,
                            pe.timestamp_pedido as fecha,
                            pe.id_pedido as idpedido
                            from bulto bu inner join pedido pe on pe.id_pedido = bu.id_pedido 
                            inner join bodega bo on bo.id_bodega = pe.id_bodega where pe.id_pedido =".$pp->id_pedido;

            if($resbultosretiro = $conexion->mysqli->query($querybultos)){

                while($bultospend = $resbultosretiro->fetch_object()){
                    $bultospendientes [] = $bultospend;
                }

                foreach($bultospendientes as $bultopendiente){
                    // print_r($bultopendiente);
                    $trackid = $bultopendiente->trackid;

                    $direccion = $bultopendiente->direccion;

                    $fecha = $bultopendiente->fecha;

                    $idpedido = $bultopendiente->idpedido;

                    $hora = date('h:j:s',$fecha);
                    $fechaestimada = "";
                    if($hora > '12:00:00'){
                        $formatfecha = date('d-m-Y',$fecha);
                        $fechaplus = strtotime($formatfecha."+ 1 days");
                        $fechaestimada = date('d/m/Y',$fechaplus);
                    }else{
                        $fechaestimada = date('d/m/Y',$fecha);
                    }
                    
                    array_push($arraybultospendientes,["trackid" => $trackid,
                                                        "direccion" => $direccion,
                                                        "fechaestimada" => $fechaestimada,
                                                        "IDPEDIDO" => $idpedido]);
                }
            }

            $bultospendientes = [];
        }
       
    }


?>


<!DOCTYPE html>
<html lang="en">
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

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+EAN13+Text&display=swap" rel="stylesheet">


    



</head>
<body>
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
     <div id="pdf">
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
                        <td width="50%" class="text-center">{nombre_comercio}</b></td>
                        <td width="50%" class="text-center">{nombre_bodega_origen}</b></td>
                      </tr>
                      <tr>
                        <td width="50%" class="text-center"><b class="titulo-td">Dirección</b></td>
                        <td width="50%" class="text-center"><b class="titulo-td">Comuna</b></td>
                      </tr>
                      <tr>
                        <td width="50%" class="text-center">{direccion_origen}</b></td>
                        <td width="50%" class="text-center">{comuna_origen}</b></td>
                      </tr>
                      <tr>
                        <td width="50%" class="text-center"><b class="titulo-td">Teléfono</b></td>
                        <td width="50%" class="text-center"><b class="titulo-td">Nro Pedido </b></td>
                      </tr>
                      <tr>
                        <td width="50%" class="text-center">{telefono_comercio}</b></td>
                        <td width="50%" class="text-center">{pedido}</b></td>
                      </tr>
                    </tbody>
                  </table>



                  <div style="margin-bottom:70px; margin-top:70px"> 
                    <table width="100%">
                      <tr>
                        <td width="100%" style="border: 0px;">
                          <div>
                            <strong>EAN-13:</strong>
                            <span class="ean-barcode">11011999</span>
                          </div>
                          <!-- <div class="codigo_barra" style="text-align: center;">
                            <barcode code="123223" type="EAN128A" class="barcode" size="4" height="0.5"/>
                          </div> -->
                        </td>
                      </tr>
                      <tr>
                        <td align="center">
                            <h1>{track}</h1>
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
                        <td class="text-center">{nombre_destinatario}</td>
                        <td class="text-center">{direccion_destinatario}</td>
                      </tr>
                      <tr>
                        <td width="50%" class="text-center"><b class="titulo-td">Comuna</b></td>
                        <td width="50%" class="text-center"><b class="titulo-td">Región</b></td>
                      </tr>
                      <tr>
                        <td class="text-center">{comuna_destinatario}</td>
                        <td class="text-center">{region_destinatario}</td>
                      </tr>
                      <tr>
                        <td width="50%" class="text-center"><b class="titulo-td">Teléfono</b></td>
                        <td width="50%" class="text-center"><b class="titulo-td">Email</b></td>
                      </tr>
                      <tr>
                        <td class="text-center">{telefono_destinatario}</td>
                        <td class="text-center">{email_destinatario}</td>
                      </tr>
                    </tbody>
                  </table>


                </div> 
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

    window.jsPDF = window.jspdf.jsPDF;
    window.html2canvas = html2canvas;
    
  $('#pdfbyJS').on('click', function(){
    var doc = new jsPDF('p','px',[950,550])
    html2canvas(document.querySelector('#pdf')).then((canvas)=>{
      let img = canvas.toDataURL('image/png')
      doc.addImage(img,'PNG',15,15,900,500)
      doc.save('canvaspdf.pdf') 
    })

  

    // doc.html(document.body, {
    //   callback: function (doc) {
    //     doc.save();
    //   }
    // });

    // doc.text('Hello world!', 10, 10)
    // doc.save('a4.pdf')
  })
  </script>
</html>