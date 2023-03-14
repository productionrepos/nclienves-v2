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

$id_pedido = 36467;

if($_SERVER['HTTP_HOST'] == 'localhost:8080'){
$http = 'http://';
}else{
    $http = 'https://';
}

?>

<!DOCTYPE html>
<html lang="en">
<?php
  require_once('./include/head.php')
?>
<body>
    <!-- <div class="container">
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
                  </tbody>
              </table>
          </row>
      </div>
    </div>
  <button class="btn btn-spread" onclick="ExportToExcel('xlsx')">Export table to excel</button> -->

  <input type="text" name="trackid" id="trackid" aria-describedby="helpId" placeholder="Número de pedido" value="125958">
  <!-- <a id="apretameclick" class="col-2 btn btn-success">Apretame</a><br><br> -->

  <a href="<?php echo $http.$_SERVER['HTTP_HOST']?>/ws/pdf/ejemplo.php?id_pedido=<?=$id_pedido?>&token=<?=md5($id_pedido."pdf_etiquetas")?>" 
    type="button" class="btn btn-lg btn-block btn-spread"
    Target="_blank">
        <i class="fa fa-download d-flex"></i>
        Descargue aquí el archivo para imprimir las etiquetas que debe adherir en los bultos
    </a>
<br>

<?php
require_once('./include/footer.php');
$date = (new DateTime('now',new DateTimeZone('Chile/Continental')))->format('Y-m-d H:i:s');
// echo $date;

?>
<script src="./js/testjs.js"></script>
<script src="js/xlsxReader.js"></script>
<script src="https://unpkg.com/read-excel-file@5.x/bundle/read-excel-file.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

<script>
//   function ExportToExcel(type, fn, dl) {
//     var elt = document.getElementById('excel-table');
//     var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
//     return dl ?
//         XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64'}):
//         XLSX.writeFile(wb, fn || ('MySheetName.' + (type || 'xlsx')));
//   }
  
  //var busquedaGet = <?php //echo $id_pedido; ?>;
  var busquedaGet = '125958';
  var url_local = 'http://localhost:8000/api/infoBeetrack/infoPackage/';
  var url_spread = 'https://spreadfillment-back-dev.azurewebsites.net/api/infoBeetrack/infoPackage/';
  var url_beetrack = 'https://app.beetrack.com/api/external/v1/dispatches/';
  var token_beetrack = '4471afc1f7ee5051458a39d3bd5df4a5107ee7df1753a1bf8affef9b29aace75'



      
</script>

</body>
</html>



   

<?php



// $date1 = "2023-02-01";

// $inicio = date("Y-m-01");
// $timestamp1 = strtotime($inicio);

// $fin = date("Y-m-t");
// $timestamp2 = strtotime($fin);



?>
