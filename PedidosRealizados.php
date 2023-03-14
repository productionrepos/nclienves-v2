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
                <div class="card resumen-envios col-lg-6 col-md-6 col-sm-8 col-12" style="padding: 20px; text-align: center;">
                    <div class="">
                            <h3 style="color: black; font-weight: 700;">Pedidos Realizados</h3>
                    </div>
                </div>
            </div>
            
            <div class="page-content">

                <section class="section">
                    <div class="card">
                        <!-- <div class="card-header">
                            <input type="search" id="myInput" onkeyup="myFunction()" placeholder="Search for names..">
                        </div> -->
                        <div class=" table-responsive card-body" id="tablepr">
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
                                                $counterpedido = 0;
                                                foreach($datapedido as $pedido):
                                                    $counterpedido ++;
                                                    $total = 0 ;
                                                    $fivecounter = 0;
                                                    $sixcounter = 0;
                                                    $id_estado_logistico = [];
                                                    $querylogistico = "SELECT estado_logistico FROM bulto WHERE id_pedido = ".$pedido->id_pedido;

                                                    $responsequerylogistico = $conn->mysqli->query($querylogistico);

                                                    while($datoslogisticos = $responsequerylogistico->fetch_object()){
                                                        $id_estado_logistico [] = $datoslogisticos;
                                                    }
                                                    $total_estados = count($id_estado_logistico);
                                                    $total = $total_estados;
                                                    $logo = "";
                                                    foreach($id_estado_logistico as $id_logistico){
                                                        $estado_logistico = $id_logistico->estado_logistico;
                                                        if($estado_logistico ==5){
                                                            $fivecounter ++;
                                                        }

                                                        if($estado_logistico ==6){
                                                            $sixcounter ++;
                                                        }

                                                    }

                                                    if($fivecounter == $total ){
                                                        $logo = '<i style="color: #00FF00; font-size:30px;" class="fa-solid fa-check"></i>';
                                                    }else if($sixcounter > 0 ){

                                                        $logo = '<i style="color: #D0342C ; font-size: 30px;" class="fa-solid fa-info"></i>';
                                                    }
                                                    else{
                                                        $logo = '<i class="fa-solid fa-clock" style="font-size: 30px; color: #eed202"></i>';
                                                    }

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
                                                        <td style="text-align: center;"> 
                                                            
                                                            <?php echo $logo;?>
                                                        </td>
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



   <!-- Footer contiene div de main app div -->
   <?php
        include_once('./include/footer.php')
    ?>
    <!-- <script src="assets/js/plugins/jquery.dataTables.min.js"></script>
    <script src="assets/js/plugins/dataTables.bootstrap4.min.js"></script> -->
    <!-- <script src="./assets/extensions/datatables.net-bs5/js/dataTables.bootstrap4.min.js"></script>
    <script src="./assets/extensions/datatables.net-bs5/js/jquery.dataTables.min.js"></script> -->
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
                    var head =  "<tr>"+
                                    '<td style="font-weight:800">Número Guia</td>'+
                                    '<td style="font-weight:800">Destinatario</td>'+
                                    '<td style="font-weight:800">Direccion</td>'+
                                    '<td style="font-weight:800">Correo</td>'+
                                    '<td style="font-weight:800">Telefono</td>'+
                                    '<td style="font-weight:800; text-align:center;">Retirado | Recepcionado | En Ruta | Final' +
                                        // '<thead><tr>' +
                                        //     '<th>Retirado</th>' +
                                        //     '<th>Recepcionado</th>' +
                                        //     '<th>En Ruta</th>' +
                                        //     '<th>Final</th>' +
                                        // '</tr></thead>' +
                                    '</td>'+
                                    '<td style="font-weight:800; text-align:center;">Información</td>'+
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
                            var btn;
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
                                btn =`<td><button onClick='buscarTrack("${track}")' style='cursor:pointer' class='btn btn-success trazabilidad'>Evidencia</button></td>`
                            }
                            else if(estado == 6){
                                img = 'include/img/status/5_no_entregado.PNG'
                                title = 'no_entregado';
                                btn = `<td><button onClick='buscarTrack("${track}")' style='cursor:pointer' class='btn btn-success trazabilidad'>Evidencia</button></td>`
                            }

                            var tr_str = 
                                "<tr>" +
                                    "<td align='center' id='trackid'>" + track + "</td>" +
                                    "<td align='center'>" + nombre + "</td>" +
                                    "<td align='center'>" + direccion + "</td>" +
                                    "<td align='center'>" + correo + "</td>" +
                                    "<td align='center'>" + telefono + "</td>" +
                                    "<td align='center'" +
                                            "<a " +
                                            "href='#'" +
                                            "data-bs-toggle='tooltip'" +
                                            "title='" + title + "'>" +
                                            "<img src='" + img + "'>" +
                                            "</a></td>" + btn+
                                "</tr>";
                                
                                $("#"+exp).append(tr_str);
                                btn = ""
                                // $("#"+exp).html(response);
                        }
                            
                    }
            
                }
            });
        });

        
    });

    url_busqueda = 'https://spreadfillment-back-dev.azurewebsites.net/api/infoBeetrack/infoPackage/'

    function buscarTrack(dato){
        // console.log(dato);
        document.getElementById('infoFormulario').innerHTML = "";
        document.getElementById('trackIdlbl').innerHTML='';
        let trackid = dato;
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

        // document.getElementById('trackIdlbl').innerHTML= "Guia :"+ dato
    }
    
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