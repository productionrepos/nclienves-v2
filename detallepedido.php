<?php
  require_once('./ws/bd/dbconn.php');
  $conn = new bd();
  $conn->conectar();
  $id_pedido = $_GET['id_pedido'];
  $querybulto = 'SELECT bu.id_bulto as guide, bu.nombre_bulto as nombre, bu.email_bulto as correo, bu.telefono_bulto as telefono,
  bu.direccion_bulto as direccion, co.nombre_comuna as comuna,re.nombre_region as region, bu.precio_bulto as precio,
  bu.codigo_barras_bulto as barcode
  FROM bulto bu 
  INNER JOIN comuna co on co.id_comuna = bu.id_comuna
  INNER JOIN provincia pro on pro.id_provincia = co.id_provincia
  INNER JOIN region re on re.id_region = pro.id_region
  where bu.id_pedido ='. $id_pedido;

  if($resdatabulto = $conn->mysqli->query($querybulto)){
    while($datares = $resdatabulto->fetch_object())
    {
    $datosbultos [] = $datares;
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
        <div class="row">
            <div class="card">
                <div class="col-12 col">
                    <input type="file" class="form-control" id="excel-input">
                </div>
            </div>
        </div>
    </div>

    <a href="https://<?php echo $_SERVER['HTTP_HOST']?>/ws/pdf/?id_pedido=<?=$id_pedido?>&token=<?=md5($id_pedido."pdf_etiquetas")?>" 
       type="button" class="btn btn-lg btn-block btn-success"><i class="fa fa-download" aria-hidden="true">
       </i> Descargue aquí el archivo para imprimir las etiquetas que debe adherir en los bultos
    </a>


<?php
require_once('./include/footer.php')
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
</script>

</body>
</html>