<?php 
    date_default_timezone_set("America/Santiago");
    if(!isset($_SESSION['cliente'])):
        header("Location: index.php");
    endif;

    $id_cliente = $_SESSION['cliente']->id_cliente;

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
    <?php
    $head = 'Dashboard Spread';
    include_once('./include/head.php');
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
       
            <div class="container-fluid" id="containermainmenu">
                <div class="page-content" style="color:3e3e3f;">
                    <div class="resumen-envios  mt-1">
                        <div class="row">
                            <h4 style="color:#3e3e3f">Envíos de este mes</h4>
                        </div>
                        <div class="masteresume row">
                        
                            <div class="col-lg-2 col-12 col-md-6 card colresume">
                                <div class="row">
                                    <a href=""><span class="envtitle"><h5>Total de envios</h5></span></a>
                                </div>
                                <div class="row dataresenv">
                                    <div class="col-lg-6 col-md-6 col-sm-12 envresitems">
                                        <i class="fa-solid fa-truck"></i>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 envresitems"> <h4><?php echo $cantEnvios->suma ?></h4></div>
                                </div>
                            </div>
                    
                                
                            
                            <div class="col-lg-2 col-12 col-md-6 card colresume">
                                <div class="row">
                                    <a href="">
                                        <span class="envtitle"><h5>Entregados</h5></span>
                                    </a>
                                </div>
                                <div class="row dataresenv">
                                    <div class="col-lg-6 col-md-6 col-sm-12 envresitems"> <i class="fa-solid fa-check"></i></div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 envresitems"> <h4><?php echo $cantEnviosEntregados->suma ?></h4></div>
                                </div> 
                            </div>

                            <div class="col-lg-2 col-12 col-md-6 card colresume">
                                <div class="row">
                                    <a href=""><span class="envtitle"><h5>En Transito</h5></span></a>
                                </div>
                                <div class="row dataresenv">
                                    <div class="col-lg-6 col-md-6 col-sm-12 envresitems"> 
                                        <i class="fa-regular fa-clock"></i>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 envresitems">
                                        <h4><?php echo $cantEnviosEnTransito->suma ?></h4>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-2 col-12 col-md-6 card colresume">
                                <div class="row">
                                    <a href=""><span class="envtitle"><h5>Problemas en la entrega</h5></span></a>
                                </div>
                                <div class="row dataresenv">
                                    <div class="col-lg-6 col-md-6 col-sm-12 envresitems"> 
                                        <i class="fa-regular fa-clock"></i>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 envresitems">
                                        <h4><?php echo $cantEnviosConProblemas->suma ?></h4>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                   
                   <?php
                        if($hasretiros):
                   ?>
                        <div class="resumen-envios  mt-1">
                            <div class="row">
                                <h4 style="color:#3e3e3f">Retiros Pendientes</h4>
                            </div>
                            <div class="masteresume row">
                                <div class="col-lg-4 col-12 col-md-6 card colresume">
                                    <div class="row">
                                        <a href=""><span class="envtitle"><h5>Número de pedidos pendientes</h5></span></a>
                                    </div>
                                    <div class="row dataresenv">
                                        <div class="col-lg-6 col-md-6 col-sm-12 envresitems">
                                            <i class="fa-solid fa-truck"></i>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 envresitems"> <h4><?php echo $cantidadpendientes?></h4></div>
                                    </div>
                                </div>
                                <section class="section">
                                    <div class="card">
                                        <div class="card-header">
                                            Números de guias correspondientes
                                            <?php 
                                                if($cantidadpendientes == 1){
                                                    echo " al retiro pendiente";
                                                }else{
                                                    echo " a los retiros pendientes";
                                                }
                                            ?>
                                        </div>
                                        <div class="card-body" id="tablepp">
                                            <table class="table table-striped" id="table1">
                                                <thead>
                                                    <tr>
                                                        <th>NRO GUIA</th>
                                                        <th>DIRECCIÓN RETIRO</th>
                                                        <th>FECHA ESTIMADA RETIRO</th>
                                                        <th>NRO PEDIDO</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        // var_dump($arraybultospendientes);
                                                        foreach($arraybultospendientes as $index=>$bulpen):
                                                            echo '<tr>';
                                                            foreach($bulpen as $key=>$bp):
                                                    ?>
                                                            <td><?php echo $bp?></td>
                                                    <?php
                                                            endforeach;
                                                            echo '</tr>';
                                                        endforeach;
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>  
                    <?php endif;?>
                      


                    <section class="resumen-envios  mt-1" >
                        <div class="row justify-content-center">
                            <div class="singleimgmenu col-lg-8 col-sm-12">
                                <a href="./PedidosPendientes.php">    
                                    <div class="card" style="overflow-y: auto">
                                        <div class="card-body"  id="imgmenu">
                                            <div class="row">
                                                <div class="col-md-4" id="cardicon">
                                                    <div class="stats-icon green">
                                                        <i class="fa-solid fa-warehouse"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 menutxt">
                                                    <h4 class="font-semibold">Continuar con envíos pendientes</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="row ">
                            <div class="singleimgmenu col-lg-6 col-sm-12">
                                <div class="card">
                                    <div class="card-body" id="imgmenu">
                                        <a href="./seleccionBultos.php">
                                            <div class="row" >
                                                <div class="col-md-4" id="cardicon">
                                                    <div class="stats-icon green">
                                                        <i class="fa-solid fa-paper-plane"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 menutxt">
                                                    <h4 class="font-semibold"> Envía Ahora </h4>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="singleimgmenu col-lg-6 col-sm-12">
                                <div class="card">
                                    <div class="card-body" id="imgmenu">
                                        <a href="./PedidosRealizados.php">
                                            <div class="row">
                                                <div class="col-md-4 "id="cardicon">
                                                    <div class="stats-icon green">
                                                        <i class="fa-solid fa-box"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 menutxt">
                                                    <h4 class="font-semibold">Mis Envíos</h4>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </section>

                    <div class="resumen-envios mt-1" >
                        
                        
                        <div class="row justify-content-center">
                            <div class="col-lg-8 col-sm-12">
                                <div id="faq" role="tablist" aria-multiselectable="true">
                                    <div class="card">
                                        <div class="card-header" role="tab" id="questionTwo" style="text-align: center;">
                                            <div class="row">
                                                <h4 style="color:#3e3e3f">Sigue un envío</h4>
                                            </div>
                                                <form>
                                                    <div class="row">
                                                        <div class="mb-3 col-10 justify-content-end" >
                                                        <input type="text"class="form-control" 
                                                                name="" id="trackid" aria-describedby="helpId" 
                                                                placeholder="Número de guía">

                                                        <small id="helpId" class="form-text text-muted"></small>

                                                        </div>
                                                        <div class="col-2">
                                                            <button type="button" id="datapackage" class="btn collapsed btn-success">
                                                                <i style="font-size:20px" class="fa-solid fa-magnifying-glass"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL LARGE-->
            <div class="modal fade text-left" id="xlarge" tabindex="-1" role="dialog"
                aria-labelledby="myModalLabel16" aria-hidden="true" style="border-radius: 50px;">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl"
                    role="document" style="height: 25%;" >
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #00a77f;" >
                            <h2 class="modal-title" id="trackIdlbl"></h2>
                        </div>

                        <div class="container">  
                            <div class="row">
                                <div class="row justify-content-center">
                                    <div class="col-2" style="text-align: end;">
                                        <h4>Guía :</h4>
                                    </div>
                                    <div class="col-2">
                                        <h4 id="numguia" style="font-weight: 800;"></h4>      
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row justify-content-center" id="infoFormulario">
                                <!-- <div id="infoFormulario"></div> -->
                            </div>
                        </div>
                        <div style="display: none;" id="datosSeguimiento"></div>

                    </div>
                </div>
            </div>


    <?php
        include_once('./include/footer.php')
    ?>
</div>
            

           
</body>
<script>
    var http = '<?php echo $http; ?>';
    var url_busqueda;

    // if(http == 'local'){
    //     var url_busqueda = 'http://localhost:8000/api/infoBeetrack/infoPackage/';
    // }
    // else if(http == 'servidor'){
    //     var url_busqueda = 'https://spreadfillment-back-dev.azurewebsites.net/api/infoBeetrack/infoPackage/';
    // }
    url_busqueda = 'https://spreadfillment-back-dev.azurewebsites.net/api/infoBeetrack/infoPackage/'

    $('#datapackage').on('click',function(e){
        e.preventDefault();
        let trackid = $(this).closest('#questionTwo').find('#trackid').val()
        document.getElementById('infoFormulario').innerHTML = "";
        document.getElementById('trackIdlbl').innerHTML='';
        document.getElementById('datosSeguimiento').innerHTML='';
        document.getElementById('numguia').innerHTML='';
        numguia
        let horaFecha = "";
        let hora = "";
        let html = "";
        let estado = "";
        let subStatus = "";
        
        $.ajax({
            type: "POST",
            url: "ws/bulto/getbultobytrackId.php",
            dataType:'json',
            data: {"track_id":trackid},
            success: function(data) {
                $.each(data,function(key,value){
                    $('#xlarge').modal('show');
                    // console.log(value.estado);
                    document.getElementById('trackIdlbl').innerHTML='Evidencia'
                    document.getElementById('numguia').innerHTML= trackid
                    if(value.estado <= 3){
                        // estados internos
                        if(value.estado == 0){
                            document.getElementById('datosSeguimiento').innerHTML='<h4>Creado en sistema</h4>'
                        }
                        else if (value.estado == 2){
                            document.getElementById('datosSeguimiento').innerHTML='<h4>Retirado</h4>'
                        }
                        else if (value.estado == 3){
                            document.getElementById('datosSeguimiento').innerHTML='<h4>Recepcionado en bodega Spread</h4>'
                        }
                    }else if(value.estado > 3){
                        // estados beetrack
                        let url = url_busqueda + trackid
                        // console.log(url);
                        getBeetrack();
                        async function getBeetrack(){
                            const response = await fetch(url, {
                                method: 'GET',
                                dataType: 'json',
                            })
                            .then(async (response) => {
                                // console.log(response);
                                let { identifier,status,status_id,substatus,substatus_code,
                                    arrived_at,number_of_retries,histories,evaluation_answers
                                } = await response.json();

                                horaFecha = arrived_at.split(' ');
                                hora = horaFecha[1].split('+');

                                if(status_id == 1){
                                    estado = 'En Ruta'
                                }
                                else if(status_id == 2){
                                    estado = 'Entregado'
                                }
                                else if(status_id == 3){
                                    estado = 'No Entregado'
                                }
                                // console.log(horaFecha);
                                // console.log(hora);
                                // console.log(estado);
                                
                                html += `<div class="panel-body col-8" style="background-color: #60cbb196; margin: 20px; border-radius: 50px;padding: 30px;" >
                                            <span class="timeline-date"><h5 class="blue">${horaFecha[0]}</h5> ${hora[0]}</span>

                                            <span>
                                                <h5 style="text-decoration: none;color: red;">${estado}</h5>
                                                <h5 style="text-decoration: none;color: #3e3e3f;" class="blue">${substatus}</h5>
                                            </span>`;
                                
                                evaluation_answers.forEach(respuesta => {
                                    if(respuesta.cast == 'photo'){
                                        html += `<div style="font-weight: 800; font-size: 18px;">${respuesta.name}</div>
                                        <div class="album row">`;
                                        fotos = respuesta.value.split(',')
                                        for(let i = 0; i < fotos.length ; i++){
                                            html += `<div class="col-md-3" style=" padding: 0 2% 2% 0;">
                                            <a href="${fotos[i]}" target="_blank" style="width: 100%;">
                                            <img alt="package-deliver-img" class="img-responsive" src="${fotos[i]}" style="width:100%;height:100px">
                                            </a>
                                            </div>`;
                                        }
                                        html += `</div>`;
                                    }
                                    else if(respuesta.cast == 'signature'){
                                        html += `<div style="font-weight: 800;font-size: 18px; padding: 3% 0 0 0;">${respuesta.name}</div>
                                        <div class="album row">
                                        <div class="col-md-3" style=" padding: 0 2% 2% 0;">
                                        <a href="${respuesta.value}" target="_blank" style="width: 100%;">
                                        <img alt="package-deliver-img" class="img-responsive" src="${respuesta.value}" style="width:100%;height:100px">
                                        </a>
                                        </div>
                                        </div>`;
                                    }
                                    else{
                                        html += `<p style="margin: 0px;padding:0px;">${respuesta.name} : ${respuesta.value}</p>`;
                                    }
                                });
                                html += `</div>`;

                                document.getElementById('infoFormulario').innerHTML=`${html}`;

                                // if(estadoResponse.response){
                                //     console.log(estadoResponse.response);
                                //     let jsonResponse = estadoResponse.response
                                //     let fecha;
                                //     let status;
                                //     //let fecha2;
                                //     if(jsonResponse.status_id == 2 || jsonResponse.status_id == 3){
                                //         status = jsonResponse.substatus
                                //         fecha = jsonResponse.arrived_at
                                //         fecha = new Date(fecha)
                                //         fecha = fecha.toLocaleString()
                                //     }else{
                                //         fecha = 'buscar'
                                //         status = "Pendiente de entrega"
                                //     }
                                //     document.getElementById('datosSeguimiento').innerHTML=`<div class="row">
                                //         <div class='col-6'>
                                //             <table class="table">
                                //                 <tr>
                                //                     <td>
                                //                         Estado actual del Pedido
                                //                     </td>
                                //                     <td>
                                //                         Fecha cambio de estado
                                //                     </td>
                                //                 </tr>
                                //                 <tr>
                                //                     <td>
                                //                         ${status}
                                //                     </td>
                                //                     <td>
                                //                         ${fecha}
                                //                     </td>
                                //                 </tr>
                                //             </table>
                                //         </div>
                                //         <div class='col-6'>
                                //             foto
                                //         </div>
                                //     </div>`
                                // }else{
                                //     console.log('Sin datos');
                                // }
                            })
                        
                        }
                    }
                })
            },
            error: function(data){
                document.getElementById('trackIdlbl').innerHTML='';
                document.getElementById('datosSeguimiento').innerHTML='';
                document.getElementById('trackIdlbl').innerHTML='Número de Guía: '+trackid+', No existe';
                Swal.fire({
                    title: 'ERROR',
                    text: "El numero de guia ingresado no existe en el sistema.",
                    icon: 'error',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Entendido!'
                })
            }
        })
    })
   

</script>
</html>