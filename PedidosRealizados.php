<?php
    session_start();
    if(!isset($_SESSION['cliente']))
    {
        header("Location: index.php");
    }
    $id_cli = $_SESSION['cliente']->id_cliente;

    include_once('./ws/bd/dbconn.php');

    $conn = new bd();
    $conn->conectar();



    $query ='SELECT p.id_pedido,p.timestamp_pedido,b.nombre_bodega FROM pedido p
            INNER JOIN cliente c ON (p.id_cliente=c.id_cliente)
            INNER JOIN bodega b ON (p.id_bodega=b.id_bodega)
            WHERE c.id_cliente='. $id_cli .' AND p.estado_pedido>=2 
            order by p.timestamp_pedido desc';

    $existe = false;
    if($res = $conn->mysqli->query($query)){
        $datapedido = array();
        
        while($datares = $res ->fetch_object())
        {
            $datapedido [] = $datares;
        }
        $res -> close();
        $datapedido = (object)$datapedido;
        $existe = true;
       
        
    }
    else{
        echo $conn->mysqli->error;
        exit();
    }
    $suma=0;
?>

<!DOCTYPE html>
<html lang="en">

<?php
    include_once('./include/head.php')
?>

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
            <div class="row">
                <div class="card" style="padding: 20px">
                    <div class="row">
                        <div class="col-sm-8">
                            <h3>Pedidos Realizados || Spread</h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="page-content">

                <section class="section">
                    <div class="card">
                        <!-- <div class="card-header">
                            <input type="search" id="myInput" onkeyup="myFunction()" placeholder="Search for names..">
                        </div> -->
                        <div class="card-body" id="tablepr">
                            <table class="table table-striped" id="table">
                                <thead>
                                    <tr>
                                        <!-- <th>Id</th> -->
                                        <th>Numero Pedido</th>
                                        <th>Fecha creación pedido</th>
                                        <th>Dirección retiro</th>
                                        <th>Cantidad de envios</th>
                                        <th>Resumen Pedido</th>
                                        <th>Estado</th>
                                        <th>Información</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    <?php
                                        $index =0;
                                        $conn->conectar();
                                            if($existe):
                                                foreach($datapedido as $pedido):
                                                    $queryCantidad = 'select count(id_bulto) as suma from bulto where id_pedido ='.$pedido->id_pedido;
                                                    if($response = $conn->mysqli->query($queryCantidad)){
                                                        $suma = $response ->fetch_object();
                                                    }
                                    ?>
                                                    <tr>
                                                        <td><span class="idpedido"><?=$pedido->id_pedido?></span></td>
                                                        <td><?=date('d/m/Y',$pedido->timestamp_pedido)?></td>
                                                        <td><?=$pedido->nombre_bodega?></td>
                                                        <td ><?=$suma->suma?></td>
                                                        <td>
                                                            <a
                                                                class="btnGetData"
                                                                id="<?php echo$index?>"
                                                                type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#collapseExample<?php echo$index?>"
                                                                aria-expanded="false"
                                                                aria-controls="collapseExample<?php echo$index?>"
                                                            >
                                                                <i style="font-size: 35px; color:#60cbb196; cursor: pointer;" class="fa-solid fa-arrow-down"></i>

                                                            </a>
                                                        </td>
                                                        <td>Check verde - Información rojo - reloj (pendientes)</td>
                                                        <td><a href="detallepedido.php?id_pedido=<?=$pedido->id_pedido?>" class='btn btn-spread'>Revisar Pedido</a></td>
                                                        
                                                    </tr>
                                                    <thead class="collapse exp<?php echo$index?>" id="collapseExample<?php echo$index?>">
                                                   
                                                    </thead>
                                    <?php
                                                    $index ++;
                                                endforeach;
                                            endif;                                
                                    ?>
                                </tbody>
                            </table> 
                        </div>
                    </div>   
                </section>
            </div>

            
        </div>
   <!-- Footer contiene div de main app div -->
   <?php
        include_once('./include/footer.php')
    ?>
    <!-- <script src="assets/js/plugins/jquery.dataTables.min.js"></script>
    <script src="assets/js/plugins/dataTables.bootstrap4.min.js"></script> -->
    <script src="./assets/extensions/datatables.net-bs5/js/dataTables.bootstrap4.min.js"></script>
    <script src="./assets/extensions/datatables.net-bs5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function(){

        $("#table").on('click', '.btnGetData', function() {
            // get the current row
                    var currentRow = $(this).closest("tr");
                    var id = $(this).attr("id");
                    var exp = $(".exp"+id).attr('id');
                    var colId = currentRow.find(".idpedido").html();
                    
                    var filas = $("#"+exp+" tr").length;
                    //alert (filas);
                    var params = {
                        "columnid": colId
                    }
                     $.ajax({
                        data:  params,
                        url:   'ws/pedidos/getBultoByPedido.php',
                        type:  'post',
                        dataType: 'json',
                         success:  function (response) {
                            var head =  " <tr>"+
                                        '<td style="font-weight:800">Número Guia</th>'+
                                        '<td style="font-weight:800">Destinatario</th>'+
                                        '<td style="font-weight:800">Direccion</th>'+
                                        '<td style="font-weight:800">Correo</th>'+
                                        '<td style="font-weight:800">Telefono</th>'+
                                        '<td style="font-weight:800; text-align:center;" colspan="2">Estado</th>'+
                                    "</tr>";
                            if(filas > 0)
                            {

                            }
                            else{
                                    var len = response.length;
                                        $("#"+exp).append(head);
                                        for(var i=0; i<len; i++){
                                            var track = response[i].track;
                                            var nombre = response[i].nombre;
                                            var direccion = response[i].direccion;
                                            var correo = response[i].correo;
                                            var telefono = response[i].telefono;
                                            var estado = response[i].estado;
                                            var fecha_creacion = response[i].fecha_creacion;
                                            var img = "";
                                            var title = "";
                                            if(estado == 0){
                                                img = 'include/img/status/1_creado.PNG'
                                                title = 'Creado\n ' + fecha_creacion;
                                            }
                                            else if(estado == 2){
                                                img = 'include/img/status/2_retirado.PNG'
                                                title = 'retirado';
                                            }
                                            else if(estado == 3){
                                                img = 'include/img/status/3_recepcion_bodega.PNG'
                                                title = 'recepcion_bodega';
                                            }
                                            else if(estado == 4){
                                                img = 'include/img/status/4_en_ruta.PNG'
                                                title = 'en_ruta';
                                            }
                                            else if(estado == 5){
                                                img = 'include/img/status/5_entregado.PNG'
                                                title = 'entregado';
                                            }
                                            else if(estado == 6){
                                                img = 'include/img/status/5_no_entregado.PNG'
                                                title = 'no_entregado';
                                            }
                                            var tr_str = 
                                                "<tr>" +
                                                "<td align='center'>" + track + "</td>" +
                                                "<td align='center'>" + nombre + "</td>" +
                                                "<td align='center'>" + direccion + "</td>" +
                                                "<td align='center'>" + correo + "</td>" +
                                                "<td align='center'>" + telefono + "</td>" +
                                                "<td align='center' colspan='2'>" +
                                                        "<a " +
                                                        "href='#'" +
                                                        "data-bs-toggle='tooltip'" +
                                                        "title='" + title + "'>" +
                                                        "<img src='" + img + "'>" +
                                                        "</a></td>"+
                                                "</tr>";
                                                $("#"+exp).append(tr_str);
                                                // $("#"+exp).html(response);
                                        }
                                        
                            }
                            
                        
                        }
                    });
                });

        // $('#table').DataTable({
        //     "order": [
        //         [0, "desc"]
        //     ],
        //     "pagingType": "full_numbers"
        // });

    });

    document.addEventListener(
        "DOMContentLoaded",
        function () {
          var tooltipTriggerList = [].slice.call(
            document.querySelectorAll('[data-bs-toggle="tooltip"]')
          );
          var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
          });
        },
        false
      );

</script>
</body>

</html>