<?php
    session_start();
    if(!isset($_SESSION['cliente']))
    {
        header("Location: index.php");
    }
    $id_cli = $_SESSION['cliente']->id_cliente;

    include_once('../nclientesv2/ws/bd/dbconn.php');

    $conn = new bd();
    $conn->conectar();



    $query ='SELECT p.id_pedido,p.timestamp_pedido,b.nombre_bodega FROM pedido p
            INNER JOIN cliente c ON (p.id_cliente=c.id_cliente)
            INNER JOIN bodega b ON (p.id_bodega=b.id_bodega)
            WHERE c.id_cliente='. $id_cli .' AND p.estado_pedido>=2';

    $existe = false;
    if($res = $conn->mysqli->query($query)){
        $datapedido = array();
        
        while($datares = $res ->fetch_object())
        {
            $datapedido [] = $datares;
        }
        $size = sizeof($datapedido);
        $res -> close();
        $datapedido = (object)$datapedido;
        $existe = true;
       
        
    }
    else{
        echo $conn->mysqli->error;
        exit();
    }
    $suma=0;
    for ($i = 0; $i<=$size; $i ++){
        
    }
?>

<!DOCTYPE html>
<html lang="en">

<?php
    include_once('../nclientesv2/include/head.php')
?>

<body>
    <div id="app">
        <!-- SideBar -->
        <?php
            include_once('../nclientesv2/include/sidebar.php');
        ?>

        <div id="main"  class="layout-navbar">
            <?php
                include_once('./include/topbar.php');
            ?>

            <div class="page-heading" style="position: relative !important; margin-top: 10px; margin-bottom: 15px;">
                <div class="row">
                    <div class="col-sm-9">
                        <h3>Pedidos Realizados || Spread</h3>
                    </div>
                </div>
            </div>
            <div class="page-content">

                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <input type="search" id="myInput" onkeyup="myFunction()" placeholder="Search for names..">
                        </div>
                        <div class="card-body" id="tablepr">
                            <table class="table table-striped" id="prtable">
                                <thead>
                                    <tr>
                                        <th>Numero Pedido</th>
                                        <th>Fecha creación pedido</th>
                                        <th>Dirección retiro</th>
                                        <th>Cantidad de envios</th>
                                        <th>Resumen Pedido</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    <?php
                                        $index =0;
                                        $conn->conectar();
                                            if($existe):
                                                foreach($datapedido as $pedido):
                                                    $req = "SELECT sum(precio_bulto)as precio from bulto where id_pedido =".$pedido->id_pedido.";";
                                                    $restotal = mysqli_query($conn->mysqli ,$req);
                                                    $row = mysqli_fetch_assoc ($restotal);
                                                    $total = $row['precio'];
                                    ?>
                                                    <tr>
                                                        <td><span class="idpedido"><?=$pedido->id_pedido?></span></td>
                                                        <td><?=date('d/m/Y',$pedido->timestamp_pedido)?></td>
                                                        <td><?=date('H:i:s',$pedido->timestamp_pedido)?></td>
                                                        <td ><?=$total?></td>
                                                        <td>
                                                            <button
                                                                class="btn btn-primary btnGetData"
                                                                id="<?php echo$index?>"
                                                                type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#collapseExample<?php echo$index?>"
                                                                aria-expanded="false"
                                                                aria-controls="collapseExample<?php echo$index?>"
                                                            >
                                                                Flecha desplegable
                                                            </button>
                                                        </td>
                                                        <td>Check verde - Información rojo - reloj (pendientes)</td>
                                                        
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
        include_once('../nclientesv2/include/footer.php')
    ?>

<script>
    $(document).ready(function(){
        

        $("#prtable").on('click', '.btnGetData', function() {
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
                        dataType: 'JSON',
                         success:  function (response) {
                            var head =  " <tr>"+
                                        '<td style="font-weight:800">Destinatario</th>'+
                                        '<td style="font-weight:800">Direccion</th>'+
                                        '<td style="font-weight:800">Correo</th>'+
                                        '<td style="font-weight:800">Telefono</th>'+
                                        '<td style="font-weight:800">Estado (beetrack)</th>'+
                                    "</tr>";
                            if(filas > 0)
                            {

                            }
                            else{
                                    var len = response.length;
                                        $("#"+exp).append(head);
                                        for(var i=0; i<len; i++){
                                            var nombre = response[i].nombre;
                                            var direccion = response[i].direccion;
                                            var precio = response[i].precio;
                                            var tr_str = 
                                                "<tr>" +
                                                "<td align='center'>" + nombre + "</td>" +
                                                "<td align='center'>" + direccion + "</td>" +
                                                "<td align='center'>" + precio + "</td>" +
                                                "</tr>";
                                                $("#"+exp).append(tr_str);
                                                // $("#"+exp).html(response);
                                        }
                                        
                            }
                            
                        
                        }
                    });
                });
    });
</script>
</body>

</html>