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
        WHERE c.id_cliente='. $id_cli .' AND p.estado_pedido<2
        AND IsDeleted = 0
        order by p.timestamp_pedido desc';

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
    $head = 'Pedidos Pendites';
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
            
            <div class="page-heading">
                <div class="row">
                    <div class="card resumen-envios col-lg-6 col-md-6 col-sm-8 col-12" style="padding: 20px; text-align: center;">
                        <div class="">
                                <h3 style="color: black; font-weight: 700;">Pedidos Pendientes</h3>
                        </div>
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
                                                <a><span class="badge  modpedido bg-light-warning" style="cursor: pointer;">Continuar</span></a>
                                                <span class="badge deletepedido bg-light-danger" style="cursor: pointer;">Eliminar</span>
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
        </div>


       

    <!-- Footer contiene div de main app div -->
    <?php
        include_once('./include/footer.php')
    ?>
</body>

<script>

    $('.deletepedido').on('click',function(){
        var fila = $(this).closest('tr')
        let id_pedido = $(this).closest('tr').find('.idpedido').text()
        Swal.fire({
            title: 'Eliminar pedido',
            text: "Será Eliminado permanentemente, ¿Desea continuar?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si!',
            cancelButtonText: 'No'
            }).then((result)=>{
                if(result.isConfirmed){
                    $.ajax({
                        type: "POST",
                        url: "ws/pedidos/deletepedido.php",
                        dataType: 'json',
                        data: JSON.stringify({
                            "id_pedido" : id_pedido
                        }),
                        success: function(data) {
                            // console.log(data.deletepedido);
                            // console.log(data.querytemporal);
                            if(data.status ==1){
                                Swal.fire(
                                    'Eliminado!',
                                    'Tu pedido ha sido eliminado exitosamente!',
                                    'success'
                                )
                                fila.remove();
                            }
                            if(data.status == 0){
                                
                            }

                        },error:function(data){
                            // console.log(data);
                        }
                    })
                    
                }
        }) 
    })

$('.modpedido').on('click',function(){
    let id_pedido = $(this).closest('tr').find('.idpedido').text()
    // console.log(id_pedido)
    $.ajax({
            type: "POST",
            url: "ws/pedidos/hasrows.php",
            dataType: 'json',
            data: JSON.stringify({
                "id_pedido" : id_pedido
            }),
            success: function(data) {
                // console.log(data);
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