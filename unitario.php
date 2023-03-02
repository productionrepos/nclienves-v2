<?php
    session_start();
    include('ws/bd/dbconn.php');

    $id_cliente = $_SESSION['cliente']->id_cliente;
    $conn = new bd();

    $conn -> conectar();
    $existbodegas = 1;
    $counterbodegas =0;
    $bodegas = [];

    $queryclientefrecuente='Select * from cliente_frecuente where id_cliente='.$id_cliente;
    $query='Select Nombre_region as nombre,id_region as id from region where id_region in (6,7,8)';
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
                        where bo.id_cliente ='.$id_cliente.' and IsDelete = 0 ';


   
    if( $res = $conn->mysqli->query($querybodega))
    {
        while($datares = $res ->fetch_object())
            {
                $bodegas [] = $datares;
            }
        $counterbodegas = mysqli_num_rows($res);
        if($counterbodegas > 0){
            $existbodegas = 1;
        }else{
            $existbodegas = 0;
        }
       
    }

   
    if($resbod = $conn->mysqli->query($query)){
        while($databod = $resbod ->fetch_object())
        {
            $regiones [] = $databod;
        }
    }
    else{
        echo $conn->mysqli->error;
    }



    if($resclientefrecuente = $conn->mysqli->query($queryclientefrecuente)){
        while($clientesfrecuentesdata = $resclientefrecuente ->fetch_object())
        {
            $clientesfre [] = $clientesfrecuentesdata;
        }
    }
    else{
        echo $conn->mysqli->error;
    }
    

?>
<!DOCTYPE html>
<html lang="en">
 <?php
    require_once('include/head.php');
 ?>
<body>
<div id="app">
        <!-- SideBar -->
        <?php
            include_once('../nclientesv2/include/sidebar.php');
        ?>
            

</div>
    
    <div id="main" class="layout-navbar" style="background-image: url('./include/img/backgound-forest.jpg');">
            <?php
                include_once('./include/topbar.php');
            ?>
            <form class="form hidewhenbod1" id="formdir">
                            <div class="direnvio row" style="background-color: #66cab2;">
                                <div class="col-8">
                                    <label for=""><h3>Mi Dirección</h3> (lugar donde retiraremos tú pedido)</label>
                                </div>
                                <div class="form-check form-switch col-4" style="justify-items: end;">
                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Guardar dirección</label>
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
                                <button  type="submit" class="submit btn btn-primary me-1 mb-1" value="Submit"> Usar esta dirección </button>
                            </div>
            </form>
            
            <div class="page-content" >
            <div class="container">
                <div class="card">
               
                    <div class="dropdown">
                        <button class="btn btn-primary col-12 " style="padding: 5px;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
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
                                                            <a class="btn rounded-pill" data-bs-toggle="collapse" data-bs-target="#collapseotherdir" 
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
                                                                                <div class="form-check form-switch col-4" style="justify-items: end;">
                                                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                                                    <label class="form-check-label" for="flexSwitchCheckDefault">Guardar dirección</label>
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
                                                                                <button  type="submit" class="submit btn btn-primary me-1 mb-1" value="Submit"> Usar esta dirección </button>
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
                    <section>
                        <div class="row match-height" style="margin-top: 20px;">
                            <div>
                                <div class="card">
                                    <div class="card-header row" style="margin: 0px 5px;">
                                         <div class="col-md-5 col-10">
                                            <h4 class="card-title">Formulario de envío(Datos destinatario)</h4>
                                        </div>
                                        <div class="col-md-3 col-3">
                                            <label ><h5>Guardar Cliente</h5><input id="savecliente"  type="checkbox"></label>                                                       
                                        </div>
                                        <div class="col-md-4 col-10" style="justify-items: end;">
                                            <a class="btn btn-primary" id="showtipo" data-bs-toggle="collapse" href="#srchclientefrecuente" role="button" 
                                                            aria-expanded="false" aria-controls="collapseExample">
                                                                Busque a un cliente frecuente
                                            </a>
                                        </div> 
                                    </div>
                                    <div class="collapse row" id="srchclientefrecuente">
                                        <section class="section">
                                            <div class="card">
                                                <div class="card-header">
                                                    Jquery Datatable
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <select class="choices form-select" id="clifreselect">
                                                            <optgroup label="Clientes Frecuentes">
                                                                <option value=""></option>
                                                                <?php
                                                                    foreach($clientesfre as $key=>$cliente ):
                                                                ?>
                                                                <option value="<?=$cliente->rut?>"><?php echo $cliente->nombre.' | '.$cliente->direccion?></option>
                                                                <?php endforeach;?>
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                        </section>
                                    </div>
                                <div class="card-content">
                                    <div class="form-bodyenvio">
                                    <form class="form form" id="toValdiateBulto">
                                        <div class="form-body">
                                        <div class="row justify-content-center">
                                            <div class="col-lg-6 col-md-6 col-sm-9 col-12">
                                                <div class="form-group">
                                                    <label for="gg">Nombre</label>
                                                    <input type="text" id="nombredestinatario" class="form-control" name="nombredestinatario" placeholder="Nombre Destinatario"/>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-9 col-12">
                                                <div class="form-group mb-3">
                                                    <label class="floating-label" for="rut_datos_contacto">RUT</label>
                                                    <input type="text" class="form-control" name="rut_datos_contacto" id="rut_datos_contacto" placeholder="">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-9 col-12">
                                                <div class="form-group">
                                                    <label for="contact">Teléfono</label >
                                                    <input type="number" id="numtel" class="form-control" name="numtel" placeholder="Teléfono"/>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-9 col-12">
                                            <div class="form-group">
                                                <label for="email-id">Dirección</label>
                                                <input type="text" id="dir" class="form-control" name="dir" placeholder="Dirección"/>
                                            </div>
                                            </div>
                                            
                                            <div class="col-lg-6 col-md-6 col-sm-9 col-12">
                                                <div class="form-group">
                                                    <label for="Correo">Correo </label>
                                                    <input type="email" id="correo" class="form-control" name="correo" placeholder="Correo"/>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6 col-md-6 col-sm-9 col-12">
                                                <label for="select_region">Región </label>
                                                <select name="select_region" class="form-select" id="select_region">
                                                    <option value=""></option>
                                                    <?php 
                                                    foreach($regiones as $reg)
                                                    {
                                                        echo '<option value="'.$reg->id.'">'.$reg->nombre.'</option>';
                                                    }
                                                    ?>  
                                                </select>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-9 col-12">
                                                <label for="Comuna">Comuna</label>
                                                <select name="select_comuna" class="form-select" id="select_comuna">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                            
                                            <!-- <div class="col-4 justify-content-start">
                                                <button type="submit" class="submit btn btn-primary me-1 mb-1 col-12" value="Submit"> Enviar </button>
                                            </div> -->
                                            <div class="row mt-3" style="justify-content: space-between;">
                                                <div class="col-md-2 col-2">
                                                    <a onclick="resetClienteData()" style="cursor: pointer" title="Limpiar formulario"> <i class="fa-solid fa-hand-sparkles" style="font-size: 30px;"></i> </a>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <button type="submit" class="submit btn btn-primary me-1 mb-1 col-12" id="deploy"> Continuar</button>
                                                </div>
                                               
                                            </div>        
                                            
                                        </div>
                                    </form>
                                    </div>
                                   
                                </div>
                                </div>
                            </div>
                            <div class="card" id="packagedata" style="margin-top:20px; padding:30px">
                                <div class="card-content">
                                    <form id="deployform">
                                        <div class="row formdisplay justify-content-center align-items-end " >
                                            <div class="col-lg-5 col-md-4 col-sm-8 col-12  align-items-end  mb-2" >
                                                <div class="form-group">
                                                    <label for="Item">Describe brevemente lo que estas enviando </label>
                                                    <input type="text" id="item" class="form-control" name="item" placeholder="producto"/>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-4 col-sm-8 col-12  align-items-end  mb-2">
                                                <div class="form-group">
                                                    <label for="Costo">Costo producto </label>
                                                    <input type="text" id="cost" class="form-control" name="cost" placeholder="Precio"/>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-8 col-12  align-items-end  mb-3" style="text-align: center;">
                                            <label for="Usar"></label>
                                            
                                                <a class="btn btn-primary" id="showtipo" data-bs-toggle="collapse" href="#sizeselect" role="button" 
                                                    aria-expanded="false" aria-controls="collapseExample">
                                                        Seleccione el rango de peso
                                                </a>
                                            
                                                
                                            </div>
                                            <div class="col-12">
                                                <div class="collapse row" style="justify-content: center;" id="sizeselect">
                                                    <div class="col-lg-3 col-md-col-4 col-sm-8 col-12" >
                                                        <div class="card bodega cardbod" >
                                                            <label style="cursor: pointer;">
                                                                <div class="card-content" style="justify-content: center;">
                                                                
                                                                    <div class="card-body" id="cardbodywarehouse" >
                                                                        <div class="row">
                                                                            <h4 class="card-title col-10">Mini</h4>
                                                                            <input class="col-2 chcksize" style="align-items: flex-start;" value="1" type="radio" name="Usar" id="useMini" required>
                                                                        </div>
                                                                        <p style="flex-direction: column-reverse;">Bulto con un peso máximo de 5kg</p>
                                                                        
                                                                    </div>
                                                                </div>
                                                            </label>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-col-4 col-sm-8 col-12" >
                                                        <div class="card bodega cardbod">
                                                            <label style="cursor: pointer;">
                                                                <div class="card-content" style="justify-content: center;">
                                                                    <div class="card-body" id="cardbodywarehouse" >
                                                                        <div class="row">
                                                                            <h4 class="card-title col-10">Medium</h4>
                                                                            <input class="col-2 chcksize" style="align-items: flex-start;" value="2" type="radio" name="Usar" id="useMedium"  required>
                                                                        </div>
                                                                        <p style="flex-direction: column-reverse;">Bulto con un peso entre de 5.01 kg y 10 kg</p>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="row justify-content-end">
                                            <div class="col-4 ">
                                                <button type="submit" class="btn btn-primary  col-12" id="submitpedido" > Enviar </button>
                                            </div>
                                        </div>
                                    </form>
                                </div> 
                            </div>
                        
                            
                        
                        </div>
                </section> 
            </div>
            <button id="buttonsubmit">
                 pressme                                       
            </button>

            <?php
                include_once('../nclientesv2/include/footer.php')
            ?>
            <script src="assets/js/jquery-validation/jquery.validate.js"></script>

            <script src="./js/newPedido.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script src="./js/rut.js"></script>
<script>

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
    var crearcliente = document.getElementById('savecliente')
    var selectcomuna = 0;

    $('#buttonsubmit').on('click', function(){
        console.log(crearcliente.checked);
    })

    $("input#rut_datos_contacto").rut({
		formatOn: 'keyup',
	    minimumLength: 6,
		validateOn: 'change'
	});

    function resetClienteData(){
        document.getElementById('toValdiateBulto').reset()
    }

    $('#clifreselect').on('change',function(){
        let rut = $(this).val()
        console.log(rut);


        $.ajax({
            type: "POST",
            url: "ws/cliente/getclientefrecuente.php",
            dataType: 'json',
            data: {
                "rut" : rut
            },
            success: function(data) {
                console.log(data);

                $.each(data, function (key, value){
                    console.log(value.comuna);
                    document.getElementById("nombredestinatario").value = value.nombre
                    document.getElementById('dir').value = value.direccion
                    document.getElementById('numtel').value = value.telefono
                    document.getElementById('correo').value = value.correo
                    // let vcomuna = document.getElementById('select_comuna').value;
                    let region = document.getElementById('select_region')
                    region.value =value.region;
                    $('#select_region').change();
                    document.getElementById('rut_datos_contacto').value = value.rut
                    // $('section').find('#select_regionm').val(value.region).change()
                    // document.getElementById('select_region').trigger('change')
                    selectcomuna = value.comuna
                    
                })
                
            },
                error: function(data){
            }
        })

    })


    var existbodegas=<?php echo $existbodegas;?>;
    $(document).ready(function(){
        console.log(existbodegas);
        console.log(countbodegas);
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


    $('#submitpedido').on('click',function(){
        console.log("El vaor de tipo es ::" + tipo);
        if(tipo == 0 ){
                Swal.fire({
                position: 'bottom',
                icon: 'error',
                title: 'Ingrese el tipo de bulto a transportar',
                showConfirmButton: false,
                timer: 3200
            })
            let clickit = document.getElementById('showtipo').click()
        }
    })

    // var select_box_element = document.querySelector('#select_box');

    // dselect(select_box_element, {
    //     search: true
    // });
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
                        console.log(data);

                        $.each(data, function (key, value){
                            
                            // document.getElementById("dirchose").text()=""+value.direccion+' '+value.numero+', '+value.comuna+', '+value.region+""
                            
                            document.getElementById("resumemyData").innerHTML = ""+value.nombre+'| '+value.direccion+' '+value.numero+""
                            
                        })
                        
                    },
                        error: function(data){
                    }
                })
            });
        });
    
        $("#select_type").change(function(){
           console.log(this.value);
           var value = this.value; 
           var x = document.getElementById("tipoenvio");
        //    alert(x.textContent +"   " +value);
           
            if(value==="1"){
                x.innerHTML = "Rango de peso";
                
            }
            if(value==="mini"){
                x.innerHTML = "De 0.1 a 5.0 kg";
            }
            if(value==="medium"){
                x.innerHTML = "De 5.1 a 10 kg";
            }
        })
    

$("#select_region").on('change',function(){
    var idregion = this.value;
    var comuna = document.getElementById("select_comuna");
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
                        console.log(data);
                        let select = document.getElementById("select_comuna");
                        select.options[select.options.length] = new Option("","",false,false)
                        $.each(data, function (key, value){
                            
                                    
                            if(selectcomuna == value.id){
                                select.options[select.options.length] = new Option(value.nombre,value.id,false,true)
                                
                            }
                            else{
                                select.options[select.options.length] = new Option(value.nombre,value.id,false,false)
                            }
                            
                            //let select = document.getElementById("select_comuna");
                            //select.options[select.options.length] = new Option(value.nombre,value.id);
                        })
                        
                    },
                        error: function(data){
                    }
    })
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
                        console.log(data);

                        $.each(data, function (key, value){
                            let select = document.getElementById("select_comunacli");
                            select.options[select.options.length] = new Option(value.nombre,value.id);
                        })
                        
                    },
                        error: function(data){
                    }
    })
})
    
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
                            required:"Debe Seleccionar una región"
                        }
                    },
                    submitHandler: function(form){
                            //console.log("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa");
                    
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
                                        console.log(resp);
                                        if(existbodegas){

                                        }
                                        if(existbodegas == false){
                                            // location.reload()
                                        }

                                        if(resp==="error"){
                                            console.log("creado");
                                            return false; 
                                        }
                                        else{
                                            return false;
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
   

    // function getclientData() {
    //         let idcliente = <?php
                                // echo $id_cliente?>;
           
	// 		$.ajax({
    //                 type: "POST",
    //                 url: "ws/cliente/getclientData.php",
    //                 dataType: 'json',
    //                 data: {
    //                     "id_cliente" : idcliente
    //                 },
    //                 success: function(data) {
    //                     console.log(data);

    //                     $.each(data, function (key, value){
    //                         let select = document.getElementById("select_comuna");
    //                         let name = document.getElementById("resumemyData").innerHTML = value.nombre + " " + value.apellido;
    //                         $("#first-name-column").val(value.nombre + " " + value.apellido);
    //                         //name.text(value.nombre);
    //                         //console.log(value.nombre);
    //                     })
                        
    //                 },
    //                     error: function(data){
    //                 }
    //         })
	// }



    $(".dropdown").click(function(){

        if($(".fa-arrow-down").hasClass("open")){
            $(".fa-arrow-down").removeClass('open');
            $(".fa-arrow-down").addClass('close');
        }
        else{
            $(".fa-arrow-down").addClass('open');
            if($(".fa-arrow-down").hasClass('close'))
            {
                $(".fa-arrow-down").removeClass('close');
            }
        }
    })


</script>
    
    
</body>
<style>
    .error{
        color:red;
    }
    .form-select option{
        color:black;
    }
</style>

</html>

