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
            WHERE c.id_cliente='. $id_cli .' AND p.estado_pedido<2';

// 'Select pe.id_pedido, pe.timestamp_pedido from pedido pe
// inner join cliente cli on cli.id_cliente = pe.Cliente
// where cli.id_cliente ='.$id_cli.' and estado_pedido < 2;';


    if($res = $conn->mysqli->query($query)){
        $datapedido = array();
        while($datares = $res ->fetch_object())
        {
            $datapedido [] = $datares;
        }
        $res -> close();
        $datapedido = (object)$datapedido;
    }
    else{
        echo $conn->mysqli->error;
        exit();
    }

    $conn -> desconectar();
    

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
            
            <div class="page-heading">
                <div class="row">
                    <div class="col-sm-9">
                        <h3>Pedidos Pendientes || Spread</h3>
                    </div>
                </div>
            </div>
            <div class="page-content">
                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            Pendientes
                        </div>
                        <div class="card-body" id="tablepp">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>FECHA</th>
                                        <th>HORA</th>
                                        <th>BODEGA</th>
                                        <th>ACCION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                            
                                            foreach($datapedido as $pedido):      
                                    ?>
                                        <tr>
                                            <td class="idpedido"><?=$pedido->id_pedido?></td>
                                            <td><?=date("d-m-Y ", $pedido->timestamp_pedido)?></td>
                                            <td><?=date("H:i:s", $pedido->timestamp_pedido)?></td>
                                            <td><?=$pedido->nombre_bodega?></td>
                                            <td>
                                                <a href=""><span class="badge bg-light-success">Continuar</span></a>
                                                <a><span class="badge  modpedido bg-light-warning">Modificar</span></a>
                                                <span class="badge bg-light-danger"  style="cursor: pointer;" data-bs-toggle="modal"
                                                    data-bs-target="#danger">Eliminar</span>
                                            </td>
                                        </tr>
                                    <?php 
                                        endforeach;
                                    ?>

                                </tbody>
                                
                            </table>
                        </div>
                    </div>
                    
                </section>
            </div>

            <!--Danger theme Modal -->
            <div class="modal fade text-left" id="danger" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel120" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
                role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title white" id="myModalLabel120">Eliminar Pedido
                        </h5>
                        <button type="button" class="close" data-bs-dismiss="modal"
                            aria-label="Close">
                            <i data-feather="x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        Desea eliminar el pedido
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary"
                            data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="button" class="btn btn-danger ml-1"
                            data-bs-dismiss="modal">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Accept</span>
                        </button>
                    </div>
                </div>
         </div>
        </div>
        </div>

    <!-- Footer contiene div de main app div -->
    <?php
        include_once('../nclientesv2/include/footer.php')
    ?>
</body>

<script>
$('.modpedido').on('click',function(){
    let id_pedido = $(this).closest('tr').find('.idpedido').text()
    console.log(id_pedido)
    $.ajax({
            type: "POST",
            url: "ws/pedidos/hasrows.php",
            dataType: 'json',
            data: JSON.stringify({
                "id_pedido" : id_pedido
            }),
            success: function(data) {
                console.log(data);
                if(data.status ==1){
                    window.location = "confirmarpedido.php?id_pedido="+id_pedido
                }
                if(data.status == 0){
                    Swal.fire({
                        title: 'Este pedido no posee bultos',
                        text: "Será redireccionado para que cree un nuevo envío",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Vamos!'
                        }).then((result)=>{
                            if(result){
                                window.location = "seleccionBultos.php?"
                            }
                            else{
                                swal.close()
                            }
                        })
                }

            }
        })
})
</script>

</html>