<?php
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
  while($datares = $resdatabulto->fetch_object())
  {
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
    <div class="container-fluid">
        <row class="col-12">
            <table id="excel-table">
                <thead> 
                    <tr>
                  <?php print_r($dataAppolo);?>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </row>
    </div>
    <button onclick="ExportToExcel('xlsx')">Export table to excel</button>

    <a href="https://<?php echo $_SERVER['HTTP_HOST']?>/ws/pdf/?id_pedido=<?=$id_pedido?>&token=<?=md5($id_pedido."pdf_etiquetas")?>" 
       type="button" class="btn btn-lg btn-block btn-success"><i class="fa fa-download" aria-hidden="true">
       </i> Descargue aquí el archivo para imprimir las etiquetas que debe adherir en los bultos
    </a>


    
<div>
  <button id="pressme">PRESSME</button>
</div>

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



   

<?php



$date1 = "2023-02-01";

$inicio = date("Y-m-01");
$timestamp1 = strtotime($inicio);

$fin = date("Y-m-t");
$timestamp2 = strtotime($fin);



// $timestamp2 = strtotime($date2);
// echo $timestamp2;


?>


    <!-- <div id="auth">
      <div class="row h-100">
        <div class="col-lg-5 col-12">
          <div id="auth-left">
            <h1 class="auth-title">Inicie Sesión</h1>
            <p class="auth-subtitle mb-5">
              Lorem ipsum dolor sit amet.
            </p>

            <form id="ingreso">
              <div class="form-group position-relative has-icon-left mb-4">
              <label class="floating-label" for="email_cliente">Ingresa tu correo</label>
                <input
                  type="email"
                  class="form-control"
                  placeholder="example@correo.cl"
                  name="email_cliente"
                  id="email_cliente"
                />
                <div class="form-control-icon">
                  <i class="bi bi-person"></i>
                </div>
              </div>
              <div class="form-group position-relative has-icon-left mb-4">
              <label class="floating-label" for="password_cliente">Ingresa tu contraseña</label>
                <input
                  type="password"
                  class="form-control"
                  placeholder="Contraseña"
                  name="password_cliente"
                  id="password_cliente"
                />
                <div class="form-control-icon">
                  <i class="bi bi-shield-lock"></i>
                </div>
              </div>
              <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5" id="btn-ingresar">
                Ingresar
              </button>
            </form>
            <div class="text-center mt-5 text-lg fs-4">
              <p class="text-gray-600">
                Don't have an account?
                <a href="auth-register.html" class="font-bold">Sign up</a>.
              </p>
              <p>
                <a class="font-bold" href="auth-forgot-password.html"
                  >Forgot password?</a
                >.
              </p>
            </div>
          </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
          <div id="auth-right"></div>
        </div>
      </div>
    </div> -->

    <!-- <div class="auth-wrapper col-3">
  <div class="auth-content">
    <div class="card text-center">
      <div class="card-body">
        <div class="row"> -->

          <!-- <div class="col-md-12">
          <div class="alert alert-info">
          <h5>Importante:</h5>
          No realizaremos envíos este 31 de octubre y 01 de noviembre. Planifica tus envíos desde el 02 de noviembre
          </div> -->

          <!-- <h3 class="mb-3">Bienvenido a <br><span class="text-c-blue">SPREAD</span></h3>
          <p>Soluciones de Última Milla.</p> -->
          <!-- ingreso -->
          <!-- <div class="toggle-block">
            <ol class="position-relative carousel-indicators justify-content-center">
            <li class="toggle-btn"></li>
            <li class="active"></li>
            </ol>
            <form id="ingreso">
            <div class="form-group mb-3">
            <label class="floating-label" for="email_cliente">Ingresa tu E-mail</label>
            <input type="email" class="form-control" name="email_cliente" id="email_cliente">
            </div>
            <div class="form-group mb-3">
            <label class="floating-label" for="password_cliente">Ingresa tu contraseña</label>
            <input type="password" class="form-control" name="password_cliente" id="password_cliente">
            </div>
            </form>
            <button class="btn btn-primary mb-4" id="btn-ingresar">Ingresar</button>
            <button class="btn btn-outline-primary mb-4 toggle-btn">¡Quiero registrarme!</button>
            <p class="mb-2 text-muted">¿Olvidaste tu contraseña? <a href="olvido_password.php" class="f-w-400">¡Reiníciala!</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> -->


