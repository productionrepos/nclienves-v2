<?php 
session_start();
if(!isset($_SESSION['cliente'])){
    header('Location: index.php');
}
$id_cliente = $_SESSION['cliente']->id_cliente;
require_once('ws/bd/dbconn.php');
$conn = new bd();
$conn->conectar();
$existbodegas = 1;
$counterbodegas =0;
$bodegas = [];

$queryregion='Select Nombre_region as nombre,id_region as id from region where id_region in (6,7,8)';
if($res = $conn->mysqli->query($queryregion))
{
    while($datares = $res ->fetch_object())
    {
        $regiones [] = $datares;
    }
}
else{
    echo $conn->mysqli->error;
}



$querybodega =  'SELECT bo.nombre_bodega as nombre,
                        bo.calle_bodega as calle, 
                        bo.numero_bodega as numero,
                        bo.principal_bodega as principal, 
                        co.nombre_comuna as comuna,
                        re.nombre_region as region,
                        bo.id_bodega as id
FROM bodega bo
inner join comuna co on co.id_comuna = bo.id_comuna
inner join provincia pro on pro.id_provincia = co.id_provincia
inner join region re on re.id_region = pro.id_region
where bo.id_cliente ='.$id_cliente.' and IsDelete = 0';


if( $res = $conn->mysqli->query($querybodega)){
    while($datares = $res ->fetch_object()){

        $bodegas [] = $datares;
    }
    
    $counterbodegas = mysqli_num_rows($res);
        
    if($counterbodegas > 0){

        $existbodegas = 1;
    }else{

        $existbodegas = 0;
    }
}



$query='Select Nombre_region as nombre,id_region as id from region';

if($res = $conn->mysqli->query($query))
{
    $comunas = array();
    
    while($datares = $res ->fetch_object())
    {
        $comunas [] = $datares;
    }
}
else{
    echo $conn->mysqli->error;
}

?>


<!DOCTYPE html>
<html lang="en">
    <?php
        include_once('./include/head.php');
    ?>
<body>
    <h1 id="id_bodega_envio" style="display: none;"></h1>
    <div id="app">
        <!-- SideBar -->
        <?php
            include_once('./include/sidebar.php');
        ?>
       
        <div id="main"  class="layout-navbar">

            <?php
                include_once('./include/topbar.php');
            ?>

                    <form class="form hidewhenbod1" id="formdir2">
                            <div class="direnvio row" style="background-color: #66cab2;">
                                <div class="col-8">
                                    <label for=""><h3>Mi Dirección</h3> (lugar donde retiraremos tú pedido)</label>
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-8" >
                                    <div class="form-group">
                                        <label for="form_dir">Dirección</label>
                                        <input type="text" id="form_dir2" name="form_dir2" class="form-control"
                                            placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="form_numero">Número</label>
                                        <input type="text" id="form_numero2" name="form_numero2" class="form-control"
                                            placeholder="Número de dirección">
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="first-name-column">Depto/casa/block etc.</label>
                                        <input type="text" id="form_detalledir2" name="form_detalledir2" class="form-control"
                                            placeholder="Casa, Depto, Bodega, etc.">
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="first-name-column">Nombre</label>
                                        <input type="text" id="form_nombre2" name="form_nombre2" class="form-control"
                                            placeholder="Nombre del punto de retiro">
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-6 col-sm-8">
                                    <label for="Comuna">Región </label>
                                    <select class="form-select" name="select_regioncli2" id="select_regioncli2">
                                        <option value=""></option>
                                        <?php 
                                            foreach($regiones as $reg)
                                            {
                                                echo '<option value="'.$reg->id.'">'.$reg->nombre.'</option>';
                                            }
                                        ?>  
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-8">
                                    <label for="Comuna">Comuna</label>
                                    <select class="form-select" name="select_comunacli2" id="select_comunacli2">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-end">
                                <button  type="submit" class="submit btn btn-spread me-1 mb-1" value="Submit"> Usar esta dirección </button>
                            </div>
                    </form>
            

<div class="page-content" >
            <div class="container">
                        <div class="card">
                    
                            <div class="dropdown">
                                <button class="btn btn-spread col-12 " style="padding: 5px;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                    <div class="row">
                                        <div class="col-md-4 col-12" style="text-align: start;">
                                                <label for="">
                                                        Punto de retiro
                                                </label>
                                        </div>
                                        <div class="col-md-4 col-12" style="text-align: start;">

                                            <?php

                                                foreach($bodegas as $bodega):
                                                    
                                                    $main = $bodega->principal;
                                                    if($main):
                                                ?>
                                                    <label id="resumemyData" style="text-align: center;">
                                                    <?php echo $bodega->nombre.' | '. $bodega->calle.' '.$bodega->numero?>                                                   
                                                    </label>
                                                <?php
                                                        endif;
                                                    endforeach;
                                                ?>
                                        </div>
                                        <div class="col-md-4 col-12" style="text-align: right;">
                                            <i class="fa-solid fa-arrow-down"></i>
                                        </div>
                                    </div>

                                </button>
                                <div class="collapse" id="collapseExample">
                                    <div class="row justify-content-center align-items-center g-2">
                                    <section id="multiple-column-form">
                                        <div class="row match-height">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-content">
                                                            <div class="row" style="justify-content: space-between;">
                                                                <div class="col-sm-4 col-12">
                                                                    <h4>Mis Direcciones</h4>
                                                                </div>
                                                                <div class="col-sm-4 col-12">
                                                                    <a class="btn btn-spread" data-bs-toggle="collapse" data-bs-target="#collapseotherdir" 
                                                                        aria-expanded="false" aria-controls="collapseotherdir">
                                                                                Enviaré desde otra dirección
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        <div class="card-bod">
                                                                <div class="row">
                                                                
                                                                    
                                                                    <form >
                                                                        <div class="row carddireccion" style="justify-content: center;" id="<?php echo $bodega->id?>">
                                                                            <?php
                                                                                foreach($bodegas as $bodega):
                                                                                $main = $bodega->principal;
                                                                            ?>
                                                                                <div class="col-lg-3 col-md-col-4 col-sm-8 col-12" >
                                                                                        <div class="card bodega">
                                                                                            <div class="card-content" style="justify-content: center;">
                                                                                                <div class="card-body" id="cardbodywarehouse" >
                                                                                                    <div class="row">
                                                                                                        <h4 class="card-title col-10"><?php echo $bodega->nombre?></h4>
                                                                                                        <?php if($main==1):?>
                                                                                                            <input class="col-2" style="align-items: flex-start;" value="<?php echo $bodega->id?>" type="radio" name="Usar" id="usardir" checked>

                                                                                                        <?php else:?>
                                                                                                            <input class="col-2" style="align-items: flex-start;" value="<?php echo $bodega->id?>" type="radio" name="Usar" id="usardir" >
                                                                                                            
                                                                                                        <?php endif;?>
                                                                                                    </div>
                                                                                                    
                                                                                                    <p style="flex-direction: column-reverse;"><?php echo $bodega->calle.' '.$bodega->numero?></p>
                                                                                                    <p class="card-text">
                                                                                                    <?php echo $bodega->comuna.', '.$bodega->region?>
                                                                                                    </p>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>
                                                                            <?php
                                                                                    endforeach;                                                                    
                                                                            ?>
                                                                        </div>
                                                                    </form>
                                                            </div>
                                                            <div class="row collapse  form" id=collapseotherdir>
                                                                            <form class="form" id="formdir">
                                                                                    <div class="direnvio row" style="background-color: #66cab2;">
                                                                                        <div class="col-8">
                                                                                            <label for=""><h3>Mi Dirección</h3> (lugar donde retiraremos tú pedido)</label>
                                                                                        </div>
                                                                                        <div class="col-md-6 col-lg-6 col-sm-8" >
                                                                                            <div class="form-group">
                                                                                                <label for="form_dir">Dirección</label>
                                                                                                <input type="text" id="form_dir" name="form_dir" class="form-control"
                                                                                                    placeholder="">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3 col-lg-3 col-sm-6">
                                                                                            <div class="form-group">
                                                                                                <label for="form_numero">Número</label>
                                                                                                <input type="text" id="form_numero" name="form_numero" class="form-control"
                                                                                                    placeholder="Casa, Depto, Bodega, etc." >
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3 col-lg-3 col-sm-6">
                                                                                            <div class="form-group">
                                                                                                <label for="first-name-column">Depto/casa/block etc.</label>
                                                                                                <input type="text" id="form_detalledir" name="form_detalledir" class="form-control"
                                                                                                    placeholder="Casa, Depto, Bodega, etc.">
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-3 col-lg-3 col-sm-6">
                                                                                            <div class="form-group">
                                                                                                <label for="first-name-column">Nombre</label>
                                                                                                <input type="text" id="form_nombre" name="form_nombre" class="form-control"
                                                                                                    placeholder="Casa, Depto, Bodega, etc.">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-6 col-lg-6 col-sm-8">
                                                                                            <label for="Comuna">Región </label>
                                                                                            <select class="form-select" name="select_regioncli" id="select_regioncli">
                                                                                                <option value=""></option>
                                                                                                <?php 
                                                                                                    foreach($regiones as $reg)
                                                                                                    {
                                                                                                        echo '<option value="'.$reg->id.'">'.$reg->nombre.'</option>';
                                                                                                    }
                                                                                                ?>  
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-6 col-lg-6 col-sm-8">
                                                                                            <label for="Comuna">Comuna</label>
                                                                                            <select class="form-select" name="select_comunacli" id="select_comunacli">
                                                                                                <option value=""></option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12 d-flex justify-content-end">
                                                                                        <button  type="submit" class="submit btn btn-spread me-1 mb-1" value="Submit"> Usar esta dirección </button>
                                                                                    </div>
                                                                            </form>
                                                                    </div>
                                                                        
                                                                </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    </div>
                                </div>
                            </div>
                            
                            
                        </div>
                        
                    </div>
    <div class="container">
            <div class="card" style="background-color: #d3ffe7d6; margin: 12px">
                <div class="row m-2">
                    <div class="col-lg-6 col-sm-12 m-1">
                        <input type="file" class="form-control filepond--drop-label" id="excel-input">
                    </div>
                    <div class="col-lg-4 col-sm-12 m-1 justify-content-end">
                        <a class="btn btn-warning" download href="/xlsx/excel_tipo.xlsx">Descarga nuestro Excel tipo</a>
                    </div>
                </div>
            </div>
    </div>

    
    <div class="card" style="background-color: #d3ffe7d6; margin: 12px">
        <div class="container" >
            <!-- <div class="row card-header" > -->
                <div class="col-md-8 col-12 mt-3">
                    <h3>Resumen Pedido</h3>
                    <h6>Si existen errores podrás editarlos en la misma tabla!</h6>
                </div>
            <!-- </div> -->
        </div>
        
        <div id="tablepp">
            <table class="table table-striped" id="excel_table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Rut</th>
                        <th>Dirección</th>
                        <th>Número</th>
                        <th>Depto/Bloque</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Comuna</th>
                        <th>Item</th>
                        <th>Valor</th>
                        <th>Tipo Envío</th>
                    </tr>
                </thead>

                <tbody class="tbodyclick">
                </tbody>
                    
                </table>
                
                <div class="row">
                    <div class="mb-2" style="justify-content: right; margin:15px;">
                        <button class="btn btn-spread col-4" onclick="getTableData()">Enviar</button>
                    </div>
                </div>

        </div>
    </div>
    
    <script src="js/xlsxReader.js"></script>
    <script src="https://unpkg.com/read-excel-file@5.x/bundle/read-excel-file.min.js"></script>

    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>


    
    <div class="page-content" style="color:3e3e3f;">
    <?php
        include_once('./include/footer.php')
    ?>
    <script src="assets/js/jquery-validation/jquery.validate.js"></script>

    <script src="./js/newPedido.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
<script>

var comuna =""
var tipo= ""
<?php 
        foreach($bodegas as $bodega):
            if($bodega->principal == 1):
    ?>

    var id_bodega=<?=$bodega->id?>;
    
<?php
        endif;
    endforeach;
?>

    var countbodegas = <?php echo $counterbodegas;?>;
	var existbodegas=<?php echo $existbodegas;?>;

$(document).ready(function(){
        // console.log(existbodegas);
        // console.log(countbodegas);
            if(existbodegas == 0){
                Swal.fire({
                position: 'center',
                icon: 'info',
                title: 'No tienes puntos de retiro!',
                text: "No te preocupes. Crea uno ahora!",
                confirmButtonText: 'Vamos'
                })
            $('.page-content').hide()
            $('.hidewhenbod1').show()
        }
        else{
            $('.hidewhenbod1').hide()
        }  

    })
    $("#select_regioncli").on('change',function(){
        var idregion = this.value;
        var comuna = document.getElementById("select_comunacli");
        comuna.options = new Option("");
        comuna.options.length = 0;
        $.ajax({
            type: "POST",
            url: "ws/pedidos/getComunaByRegion.php",
            dataType: 'json',
            data: {
                "idregion" : idregion
            },
            success: function(data) {
                // console.log(data);

                $.each(data, function (key, value){
                    let select = document.getElementById("select_comunacli");
                    select.options[select.options.length] = new Option(value.nombre,value.id);
                })
                
            },
                error: function(data){
            }
        })
    })

    $("#select_regioncli2").on('change',function(){
        var idregion = this.value;
        var comuna = document.getElementById("select_comunacli2");
        comuna.options = new Option("");
        comuna.options.length = 0;
        $.ajax({
                        type: "POST",
                        url: "ws/pedidos/getComunaByRegion.php",
                        dataType: 'json',
                        data: {
                            "idregion" : idregion
                        },
                        success: function(data) {
                            // console.log(data);

                            $.each(data, function (key, value){
                                let select = document.getElementById("select_comunacli2");
                                select.options[select.options.length] = new Option(value.nombre,value.id);
                            })
                            
                        },
                            error: function(data){
                        }
        })
    })

    $('#formdir2').validate({
                    rules:{
                        form_dir2:{
                            required :true,
                            minlength : 4
                        },
                        form_numero2:{
                            required: true
                        },form_detalledir2:{
                            required:true,
                            minlength:2
                        },

                        form_nombre2:{
                            required:true
                        },
                        form_comunacli2:{
                            required:true
                        },
                        form_regioncli2:{
                            reqiured:true
                        }
                    },
                    messages:{
                        form_dir2:{
                            required :"Debe ingresar una dirección para el retiro",
                            minlength : "La direccion debe tener al menos 4 caracteres"
                        },
                        form_numero2:{
                            required: "Debe ingresar un numero de dirección",
                        },form_detalledir2:{
                            required:"Información necesaria",
                            minlength:"Largo mínimo 2 caracteres"
                        },
                        form_nombre2:{
                            required:"Ingrese un nombre para su dirección"
                        },
                        form_comunacli2:{
                            required:"Seleccione una comuna"
                        },
                        form_regioncli2:{
                            required:"Debe Seleccionar una región"
                        }
                    },
                    submitHandler: function(form){
                            //console.log("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa");
                    
                        try{
                            let vdir = document.getElementById('form_dir2').value;
                            let vnumero = document.getElementById('form_numero2').value;
                            let vdetalle = document.getElementById('form_detalledir2').value
                            let vnombre = document.getElementById('form_nombre2').value;
                            let vcomuna = document.getElementById('select_comunacli2');
                            let vcomunavalue = vcomuna.value; 
                            let vregion = document.getElementById('select_regioncli2').value;

                            let dataajax = {direccion : vdir,
                                            numero: vnumero,
                                            detalle : vdetalle,
                                            nombre : vnombre,
                                            comuna : vcomunavalue,
                                            region: vregion};
                            
                    
                            //alert(JSON.stringify(dataajax));
                                    $.ajax({
                                    url: "ws/bodega/newBodega.php",
                                    type: "POST",
                                    dataType: 'json',
                                    data: JSON.stringify(dataajax),
                                    success:function(resp){
                                        // console.log(resp);
                                        if(existbodegas){
                                        }
                                        if(existbodegas == false){
                                            location.reload()
                                        }
                                    },error:function(resp){
                                        // console.log(resp.query);
                                        if(existbodegas){
                                            location.reload()
                                        }
                                        if(existbodegas == false){
                                            location.reload()
                                        }
                                    }
                                    
                                })
                        }
                        catch(error){
                            console.log(error);
                            return false;
                        }    
                        
                           
                           
                            
                    }
                        
                    
                })


                $('#formdir').validate({
                    rules:{
                        form_dir:{
                            required :true,
                            minlength : 4
                        },
                        form_numero:{
                            required: true
                        },form_detalledir:{
                            required:true,
                            minlength:2
                        },
                        form_nombre:{
                            required:true
                        },
                        form_comunacli:{
                            required:true
                        },
                        form_regioncli:{
                            reqiured:true
                        }
                    },
                    messages:{
                        form_dir:{
                            required :"Debe ingresar una dirección para el retiro",
                            minlength : "La direccion debe tener al menos 4 caracteres"
                        },
                        form_numero:{
                            required: "Debe ingresar un numero de dirección",
                        },form_detalledir:{
                            required:"Información necesaria",
                            minlength:"Largo mínimo 2 caracteres"
                        },
                        form_nombre:{
                            required:"Ingrese un nombre para su dirección"
                        },
                        form_comunacli:{
                            required:"Seleccione una comuna"
                        },
                        form_regioncli:{
                            required:"Debe Seleccionar una región"
                        }
                    },
                    submitHandler: function(form){
                            //console.log("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa");
                    
                        try{
                            let vdir = document.getElementById('form_dir').value;
                            let vnumero = document.getElementById('form_numero').value;
                            let vdetalle = document.getElementById('form_detalledir').value
                            let vnombre = document.getElementById('form_nombre').value;
                            let vcomuna = document.getElementById('select_comunacli');
                            let vcomunavalue = vcomuna.value;
                            let vregion = document.getElementById('select_regioncli').value;
                            
                            
                            

                            let dataajax = {direccion : vdir,
                                            numero: vnumero,
                                            detalle : vdetalle,
                                            nombre : vnombre,
                                            comuna : vcomunavalue,
                                            region: vregion};
                            
                    
                            //alert(JSON.stringify(dataajax));
                                    $.ajax({
                                    url: "ws/bodega/newBodega.php",
                                    type: "POST",
                                    dataType: 'json',
                                    data: JSON.stringify(dataajax),
                                    success:function(resp){
                                        // console.log(resp.query);
                                        if(existbodegas){
                                            location.reload()
                                        }
                                        if(existbodegas == false){
                                            location.reload()
                                        }
                                    },error:function(resp){
                                        // console.log(resp.query);
                                        if(existbodegas){
                                            location.reload()
                                        }
                                        if(existbodegas == false){
                                            location.reload()
                                        }
                                    }
                                    
                                })
                        }
                        catch(error){
                            // console.log(error);
                            return false;
                        }    
                        
                           
                           
                            
                    }
                        
                    
                })




$('#presstest').on('click',function(){
    //  console.log(id_bodega);
})

$('.tbodyclick').on('click','.btnEliminar',function(){
    Swal.fire({
        title: 'Quieres Eliminar este bulto?',
        text: "Se quitará este registro de tú pedido",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, borralo!'
        }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
            'Eliminado!',
            'Tú bulto ha sido eliminado exitosamente',
            'success'
            )
            
            $(this).closest('tr').remove()
            

            //  $(this).closest('tr').find('td').each(function(){
            //     console.log($(this).attr('class'));
            //     if($(this).hasClass('err')){
            //         $(this).attr('class').split(' ')[0]
            //     }
            // })
            



        }
        })
})

$('.tbodyclick').on('change','#select_type',function(){
    tipo = $(this).val()
    if(tipo=="")
    {
        // console.log("no hay Tipo");
    }
    // console.log(tipo);
    
})

$('.tbodyclick').on('change','#select_comuna',function(){
    comuna = $(this).val()
    if(comuna=="")
    {
        // console.log("no hay Comuna");
    }
    // console.log("LA COMUNA ESSSSSSSSSS!"+comuna);
})

function validatenombre(valor,clase){
    let check =true
        if( valor == "" ){
            check = false
            // console.log(check);
            return "vacio"
            
        }
        if(valor.length < 5){
            check = false
        //    console.log(check);
           return "corto"
           
        } 
        if(check == true){
            // console.log(check);
           return "bien"
        }
}


function getTableData(){
    var arraydatos = []
    var regularizar = []
    
    let counter = 0
    var error = false 


    $(".tbodyclick td").hasClass('err')? error = true : error=false;
    // console.log(error)

    if(error)
    {
        Swal.fire({
        title: 'UPS',
        text: "Existen errores en los registros, porfavor corríjalos antes de continuar",
        icon: 'warning',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Entendido!'
        })
    }
    else{
            $('.tbodyclick tr').each(function(){
                
                    let nombre = $(this).find('td').eq(0).text()
                    let rut = $(this).find('td').eq(1).text()
                    let direccion = $(this).find('td').eq(2).text()
                    let numdireccion = $(this).find('td').eq(3).text()
                    let casablock = $(this).find('td').eq(4).text()
                    // 
                    let telefono = $(this).find('td').eq(5).text()
                    let correo = $(this).find('td').eq(6).text()
                    let comuna = $(this).find('#select_comuna').val()
                    let item = $(this).find('td').eq(8).text()
                    let valor = $(this).find('td').eq(9).text()
                    let ctipo = $(this).find('#select_type').val()
                    arraydatos.push(nombre,rut,direccion,telefono,correo,comuna,item,valor,ctipo,numdireccion,casablock)
        })
        // console.log(arraydatos);
        arraydatos.unshift(id_bodega)
        var send_data = JSON.stringify(arraydatos)
        // console.log(send_data);


        $.ajax({
            url: "ws/pedidos/masiva.php",
            type: "POST",
            data: send_data,    
            success:function(data){
                // console.log(data);
                // console.log("Estoy de vuelta");
                swal.fire({
                        title : "Hecho",
                        text : "Tú pedido fue creado exitosamente!",
                        icon: "success",
                        showConfirmButton: false,
                        type : "success",
                        timer : 2500
                        
                    }).then(function() {
                       window.location = "confirmarpedido.php?id_pedido="+data;
                })
            },error:function(data){
                // console.log("Volvi, pero no sirvo para nada");
                console.log(data.responseText);
            }
        })
    }
}


$('.tbodyclick').on('blur','td',function(){
    //$(this).css('border', 'none')
    let clase = $(this).attr('class').split(' ')[0]
    let valor = $(this).text().trim()    
    var letras="abcdefghyjklmnñopqrstuvwxyz-*/+,.<>/?|:;'{}[]-=()*&^%$#@!`~"
    let simbolos = `-*/+,.<>/?|:;'{}[]-=()"*&^%$#@!`+'`';
    // console.log("El valor de comuna es "+comuna);
    // console.log(clase);
    // console.log("VALOR DEL TD INGRESADO" + valor+"|"+clase);
    // console.log("cadena mide "+valor.trim().length);
    // console.log("este es el valor del select tipo "+tipo);
    var tn = 0;
    var ts = 0;
    if(clase == 'tdtel'){
        texto = valor.toLowerCase();
        for(i=0; i<texto.length; i++){
                if (letras.indexOf(texto.charAt(i),0)!=-1){
                    // console.log("tiene letrassssss");
                    tn++
                }
            }
    }

    if(clase == 'tdnom'){
        texto = valor.toLowerCase();
        for(i=0; i<texto.length; i++){
                if (simbolos.indexOf(texto.charAt(i),0)!=-1){
                    // console.log("tiene letrassssss");
                    ts++
                }
            }
    }
        if(clase == "tddelete"){
          
        }
        else if(clase == "tdnom" && valor == "" ){
            
            $(this).css('border', '1px solid red')
            $(this).prop('title','Debe ingresar un nombre')
            $(this).addClass('err')
        }else if(clase =='tdnom' && ts>0){
            $(this).css('border', '1px solid red')
            $(this).prop('title','El nombre posee carcteres especiales')
            $(this).addClass('err')
            $(this).text("")
        }
        else if(clase == "tdnom" && valor.length < 5){

            $(this).css('border', '1px solid red')
            $(this).prop('title','El nombre debe tener 5 caracteres como min')
            $(this).addClass('err')
        
        }else if(clase == "tdrut" && valor == ""){
            $(this).css('border', '1px solid red')
            $(this).prop('title','Ingrese un rut')
            $(this).addClass('err')
        }else if(clase == "tdrut" && valor.length < 9){
            $(this).css('border', '1px solid red')
            $(this).prop('title','El rut debe tener 8 caracteres como min')
            $(this).addClass('err')
        }else if(clase == "tddir" && valor == ""){
            $(this).css('border', '1px solid red')
            $(this).prop('title','Debe ingresar una dirección')
            $(this).addClass('err')

        }else if(clase == "tddir" && valor.trim().length < 5 ){
           
            $(this).css('border', '1px solid red')
            $(this).prop('title','La dirección debe tener al menos 5 caracteres')
            $(this).addClass('err')
        }else if(clase == "tdnumdir" && valor == ""){
            $(this).css('border', '1px solid red')
            $(this).prop('title','Debe ingresar un número de dirección')
            $(this).addClass('err')
        }else if(clase == "tdcablo" && valor == ""){
            $(this).css('border', '1px solid red')
            $(this).prop('title','Complete este campo')
            $(this).addClass('err')
        }else if(clase == "tdcablo" && valor.trim().length < 2 ){
            $(this).css('border', '1px solid red')
            $(this).prop('title','Largo mínimo 2 caracteres')
            $(this).addClass('err')
        }else if(clase == "tdtel" && valor == ""){

            $(this).css('border', '1px solid red')
            $(this).prop('title','Debe ingresar una dirección')
            $(this).addClass('err')

        }else if(tn>0){
            $(this).css('border', '1px solid red')
            $(this).prop('title','Solo se pueden ingresar numeros')
            $(this).addClass('err')
            $(this).text("")
        }else if(clase == "tdtel" && valor.length < 9 ){

            $(this).css('border', '1px solid red')
            $(this).prop('title','El teléfono debe tener al menos 9 caracteres')
            $(this).addClass('err')
        }
        else if(clase == "tdcorr" && valor.trim == "" ){

            $(this).css('border', '1px solid red')
            $(this).prop('title','Debe ingresar un correo')
            $(this).addClass('err')

        }else if(clase == "tdcorr" && valor.length < 7){

            $(this).css('border', '1px solid red')
            $(this).prop('title','El correo debe tener al menos 7 caracteres')
            $(this).addClass('err')

        }else if(clase == "tdcom" && comuna == "" || comuna == null){
            $(this).css('border', '1px solid red')
            $(this).prop('title','Debe ingresar una comuna')
            $(this).addClass('err')

        }else if(clase == "tditem" && valor == "" ){

            $(this).css('border', '1px solid red')
            $(this).prop('title','Debe ingresar un item')
            $(this).addClass('err')

        }else if(clase == "tditem" && valor.length < 3){

            $(this).css('border', '1px solid red')
            $(this).prop('title','El item debe tener al menos 3 caracteres')
            $(this).addClass('err')

        }else if(clase == "tdval" && valor == "" ){

            $(this).css('border', '1px solid red')
            $(this).prop('title','Debe ingresar un correo')
            $(this).addClass('err')

        }else if(clase == "tdval" && valor > 500000){

            $(this).css('border', '1px solid red')
            $(this).prop('title','El valor no puede exceder los $500000')
            $(this).addClass('err')

        }else if(clase == "tdval" && valor < 1000){

            $(this).css('border', '1px solid red')
            $(this).prop('title','El valor no puede ser menora  $1000')
            $(this).addClass('err')

        }else if(clase == "tdtype" && tipo == "" ){

            $(this).css('border', '1px solid red')
            $(this).prop('title','Debe ingresar un tipo de envío')
            $(this).addClass('err')

        } else{
            $(this).css('border', 'none')
            $(this).prop('title','')
            $(this).removeClass('err')
        }

    

    //VALIDAR NOMBRE
   
})
    

$('#select_comuna').on('click', function(){
    comunas.forEach(function(comunas){
        $(this).add(new Option(comunas))
    })
})
    $('#pressme').click(function(){
    })
    function ExportToExcel(type, fn, dl) {
        var elt = document.getElementById('excel-table');
        var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
        return dl ?
            XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64'}):
            XLSX.writeFile(wb, fn || ('MySheetName.' + (type || 'xlsx')));
        }





 $("#select_regioncli").on('change',function(){
    var idregion = this.value;
    var comuna = document.getElementById("select_comunacli");
    comuna.options = new Option("");
    comuna.options.length = 0;
    $.ajax({
                    type: "POST",
                    url: "ws/pedidos/getComunaByRegion.php",
                    dataType: 'json',
                    data: {
                        "idregion" : idregion
                    },
                    success: function(data) {
                        // console.log(data}});

                        $.each(data, function (key, value){
                            let select = document.getElementById("select_comunacli");
                            select.options[select.options.length] = new Option(value.nombre,value.id);
                        })
                        
                    },
                        error: function(data){
                    }
    })
})
document.querySelectorAll("#usardir").forEach(el => {
            el.addEventListener("click", e => {
                let id = e.target.getAttribute("value");
                id_bodega = id;
                // alert(id);
                $.ajax({
                    type: "POST",
                    url: "ws/bodega/getbodegaById.php",
                    dataType: 'json',
                    data: {
                        "id_bodega" : id
                    },
                    success: function(data) {
                        // console.log(data);

                        $.each(data, function (key, value){
                            
                            // document.getElementById("dirchose").innerHTML = ""+value.direccion+' '+value.numero+', '+value.comuna+', '+value.region+"";
                            
                            document.getElementById("resumemyData").innerHTML = ""+value.nombre+'| '+value.direccion+' '+value.numero+"";
                        })
                        
                    },
                        error: function(data){
                    }
                })
            });
        });
        $('#formdir').validate({
                    rules:{
                        form_dir:{
                            required :true,
                            minlength : 4
                        },
                        form_numero:{
                            required: true
                        },
                        form_nombre:{
                            required:true
                        },
                        form_comunacli:{
                            required:true
                        },
                        form_regioncli:{
                            reqiured:true
                        }
                    },
                    messages:{
                        form_dir:{
                            required :"Debe ingresar una dirección para el retiro",
                            minlength : "La direccion debe tener al menos 4 caracteres"
                        },
                        form_numero:{
                            required: "Debe ingresar un numero de dirección",
                        },
                        form_nombre:{
                            required:"Ingrese un nombre para su dirección"
                        },
                        form_comunacli:{
                            required:"Seleccione una comuna"
                        },
                        form_regioncli:{
                            reqiured:"Debe Seleccionar una región"
                        }
                    },
                    submitHandler: function(form){
                            
                    
                        try{
                            let vdir = document.getElementById('form_dir').value;
                            let vnumero = document.getElementById('form_numero').value;
                            let vnombre = document.getElementById('form_nombre').value;
                            let vcomuna = document.getElementById('select_comunacli');
                            let vcomunavalue = vcomuna.value;
                            let vregion = document.getElementById('select_regioncli').value;

                            let dataajax = {direccion : vdir,
                                            numero: vnumero,
                                            nombre : vnombre,
                                            comuna : vcomunavalue,
                                            region: vregion};

                            //alert(JSON.stringify(dataajax));
                                    $.ajax({
                                    url: "ws/bodega/newBodega.php",
                                    type: "POST",
                                    data: JSON.stringify(dataajax),
                                    success:function(resp){
                                        
                                        if(resp==="error"){
                                            // console.log("creado");
                                            return false; 
                                        }
                                        else{
                                            return false;
                                        }
                                    }
                                })
                        }
                        catch(error){
                            // console.log(error);
                            return false;
                        }      
                    }
                })
   
</script>
</html>