<?php

    session_start();

    if(!isset($_SESSION['cliente'])):
        header("Location: index.php");
    endif;
    
    if(!isset($_GET['id_pedido'])):
        header("Location: index.php");
    endif;

    require_once('./ws/bd/dbconn.php');
    require_once('./ws/flow/FlowApi.class.php');
    $conn = new bd();
    $conn->conectar();
    $id_pedido = $_GET['id_pedido'];
    $credito = $_SESSION['cliente']->alta_cliente;
    $id_cliente = $_SESSION['cliente']->id_cliente;
    $correo = $_SESSION['cliente']->email_cliente;
    $busquedaget = 116367;
    
    //HARDCODE DESCUENTO 
    $descuento = 0;
    //HARDCODE DESCUENTO



    $querypedido = "SELECT estado_pedido as estado FROM pedido
        INNER JOIN bodega ON (pedido.id_bodega=bodega.id_bodega)
        INNER JOIN comuna ON (bodega.id_comuna=comuna.id_comuna)
        INNER JOIN provincia ON (comuna.id_provincia=provincia.id_provincia)
        INNER JOIN cliente ON (pedido.id_cliente=cliente.id_cliente)
        AND pedido.id_pedido=$id_pedido AND cliente.id_cliente=$id_cliente";

    if($pedido = $conn->mysqli->query($querypedido)){
        $datos_pedido = $pedido->fetch_object();
    }

    if($datos_pedido->estado >= 2){
        header("Location: detalleFpedido.php?id_pedido=$id_pedido");
        exit();
    }



    $querytotal = 'SELECT sum(precio_bulto) as total from bulto where id_pedido='.$id_pedido;
    if($restotal = $conn->mysqli->query($querytotal)){
        $totalneto = $restotal->fetch_object()->total;
    }



    // $querybulto = 'SELECT id_bulto as guide, nombre_bulto as nombre, email_bulto as correo, telefono_cliente as telefono,
    //                       direccion_bulto as direccion, , precio_bulto as precio
    //                     FROM bulto where id_pedido ='.$id_pedido;


    $querybulto = 'SELECT bu.id_bulto as guide, bu.nombre_bulto as nombre, bu.email_bulto as correo, bu.telefono_bulto as telefono,
                        bu.direccion_bulto as direccion, co.nombre_comuna as comuna,re.nombre_region as region, bu.precio_bulto as precio,
                        bu.codigo_barras_bulto as barcode
                    FROM bulto bu 
                    INNER JOIN comuna co on co.id_comuna = bu.id_comuna
                    INNER JOIN provincia pro on pro.id_provincia = co.id_provincia
                    INNER JOIN region re on re.id_region = pro.id_region
                    where bu.id_pedido ='. $id_pedido .' and bu.Deleted = 0';

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




    $querydatcomerciales = 'SELECT * FROM cliente cli
                                inner join datos_comerciales dc on cli.id_cliente = dc.id_cliente
                                inner join datos_contacto dco on dco.id_cliente = cli.id_cliente
                                where cli.id_cliente='.$id_cliente;

    $querydatpersonales = 'SELECT * FROM cliente cli
                                inner join datos_contacto dco on dco.id_cliente = cli.id_cliente
                                where cli.id_cliente='.$id_cliente;

    if(mysqli_num_rows($resdatacomercial = $conn->mysqli->query($querydatcomerciales))>0){
        while($datares = mysqli_fetch_array($resdatacomercial))
        {
            $nombre= $datares['nombre_fantasia_datos_comerciales'];
            $rut = $datares['rut_datos_comerciales'];
        }
    }
    else if($datospersonales = $conn->mysqli->query($querydatpersonales)){
        
        while($datares = mysqli_fetch_array($datospersonales))
        {
            $nombre= $datares['nombres_datos_contacto'].' '.$datares['apellidos_datos_contacto'];
            
            $rut = $datares['rut_datos_contacto'];
        }
    }
   
    if($credito ==1) {
        $params = array(
            "commerceOrder" => $id_pedido,
            "subject" => "Pedido #$id_pedido",
            "currency" => "CLP",
            //dejar desc en 0
            "amount" => $totalneto-$descuento,
            "email" => $correo,
            "paymentMethod" => 9,
            "urlConfirmation" => "https://".$_SERVER['HTTP_HOST']."/confirmacionPago.php",
            "urlReturn" => "https://".$_SERVER['HTTP_HOST']."/confirmacionPago.php",
            "optional" => ""
        );
    
      
    
        try {
            $flowApi = new FlowApi;
            $respuesta = $flowApi->send("payment/create", $params,"POST");
            $url_pago = $respuesta["url"] . "?token=" . $respuesta["token"];
        } catch (Exception $e) {
            echo $e->getCode() . " - " . $e->getMessage();
            exit();
        }

    }
  

?>
<!DOCTYPE html>
<html lang="en">
    <?php
        include_once('../nclientesv2/include/head.php');
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
       

            <div class="page-content" style="color:3e3e3f;">
                <div class="container mt-5 mb-3">
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="d-flex flex-row p-2"> <img src="../include/img/LogoInvoice.png"  width="450" height="60">
                                    <div class="d-flex flex-column"> <span class="font-weight-bold">ID Pedido</span> <h4><?=$id_pedido?></h4> </div>
                                </div>
                                <hr>
                                <div class="table-responsive p-2">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr class="add">
                                                <td>Para</td>
                                                <td>De</td>
                                            </tr>
                                            <tr class="content">
                                            <td class="font-weight-bold"><?=$nombre?><br><?=$rut?><br><?=$correo?></td>
                                                <td class="font-weight-bold">Spread <br> Gamero 2085, Independencia, Santiago <br> contacto@spread.cl</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                                <div class="products p-2">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr class="add">
                                                <td>Nombre</td>
                                                <td>Dirección</td>
                                                <td>Precio</td>
                                            </tr>

                                            <?php
                                            
                                                foreach($datosbultos as $datobulto):
                                                    $valor = ($datobulto->precio);
                                            ?>
                                                <tr>
                                                    <td><?=$datobulto->nombre?></td>
                                                    <td><?=$datobulto->direccion?></td>
                                                    <td><?=$datobulto->precio?></td>
                                                    
                                                </tr>
                                            <?php
                                                endforeach;
                                            ?>
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                                <div class="products p-2">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr class="add">
                                                <td></td>
                                                <td>Subtotal</td>
                                                <td>IVA(19%)</td>
                                                <td class="text-center">Total</td>
                                            </tr>
                                            <tr class="content">
                                                <td></td>
                                                <td><?=round($totalneto/1.19,0)?></td>
                                                <td><?=round(($totalneto/1.19)*0.19,0)?></td>
                                                <td class="text-center"><?=round($totalneto,0)?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                                <div class="address p-2">
                                    <div class="container">
                                        <div class="finish" style="justify-content: end;">
                                            <div class="row justify-content-end align-items-center g-2">
                                                <?php
                                                        if($credito == 1):
                                                    ?>
                                                        <a id="procesarcredito" class="col-2 btn btn-success">Procesar credito</a>
                                                        
                                                    <?php else:?>
                                                        <a href="<?=$url_pago?>" class="col-2 btn btn-success">Flow</a>
                                                    
                                                <?php endif;?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <table class="table table-borderless">
                                        <tbody>
                                            <tr class="add">
                                                <td>Bank Details</td>
                                            </tr>
                                            <tr class="content">
                                                <td> Bank Name : ADS BANK <br> Swift Code : ADS1234Q <br> Account Holder : Jelly Pepper <br> Account Number : 5454542WQR <br> </td>
                                            </tr>
                                        </tbody>
                                    </table> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a id="apretameclick" class="col-2 btn btn-success">Procesar credito</a>
            <?php
                include_once('../nclientesv2/include/footer.php')
            ?>
<!-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> -->
    <script>
        var appoloData =<?php echo json_encode($dataAppolo);?>;
        var id_pedido = <?= $id_pedido;?>;
        var busquedaget = <?= $busquedaget;?>;
        var request = ""

        $('#apretameclick').on('click',function(){
            console.log("COTELOYOLA");
            console.log(appoloData);
            // $.ajax({
            //         type: "get",
            //         url: "https://spreadfillment-back-dev.azurewebsites.net/api/pymes/revisarPedido/"+busquedaget,
            //         success: function(data) {
            //             console.log(data)
            //         }
            //     })
            
            appoloData.forEach(ap => {

                var dataAppolo = []
                dataAppolo = {
                                "guide" : ap.guide,
                                "name_client" : ap.name_client,
                                "email": ap.email,
                                "phone": ap.phone ,
                                "street": ap.street,
                                "number": "" ,
                                "commune": ap.commune,
                                "region": ap.region,
                                "dpto_bloque": "",
                                "id_pedido": ap.id_pedido,
                                "valor": "",
                                "descripcion": ""
                            }
                            request = JSON.stringify({"body":{"guide" : ap.guide,
                                                "name_client" : ap.name_client,
                                                "email": ap.email,
                                                "phone": ap.phone ,
                                                "street": ap.street,
                                                "number": "" ,
                                                "commune": ap.commune,
                                                "region": ap.region,
                                                "dpto_bloque": "",
                                                "id_pedido": ap.id_pedido,
                                                "valor": "",
                                                "descripcion": ""}});
                            console.log(request);    

                    $.ajax({
                        type: "POST",
                        url: "https://spreadfillment-back-dev.azurewebsites.net/api/pymes/ingresarPyme",
                        data: request,
                        dataType: 'json',
                        success: function(data) {
                            console.log(data);
                        },error:function(data){
                            console.log(data.responseText);
                        }
                    })
                })
                //console.log({"body" : request});

             })

            // console.log(appoloData);
            

            
        

        $('#procesarcredito').on('click',function(){
           
            Swal.fire({
            title: '¿Quieres procesar este pedido con tú crédito?',
            text: "Esta acción confirmará tú pago ",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Procesar pago!'
            }).then((result) => {
            if (result.isConfirmed) {
                

                $.ajax({
                        type: "POST",
                        url: "ws/pedidos/pedido_credito.php",
                        data: {"id_pedido": <?=$id_pedido?>, "token": "<?=(md5($id_pedido.$id_cliente."pedido_credito#"))?>"},
                        success: function(data) {
                            if(data.success == 1) {
                                    
                                Swal.fire(
                                'Pago procesado!',
                                'Tú pedido fue procesado por credito!',
                                'success'
                                )
                                location.reload();
                            }
                            else {
                                swal("Error", data.message, "error");
                                finalizado();
                            }
                        },
                        error: function(data){
                            console.log(data.message);
                        }
                    })
            }
            else{ 
                swal("Se ha cancelado el pago de este pedido", {
                            icon: "error",
                })
            }

            }) 
        })
    </script>
</body>
</html>