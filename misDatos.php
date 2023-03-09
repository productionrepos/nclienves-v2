<?php
    session_start();
    if(!isset($_SESSION['cliente'])){
        header('Location: index.php');
    }
    include('ws/bd/dbconn.php');

    $id_cliente = $_SESSION['cliente']->id_cliente;
    $conn = new bd();
    $conn -> conectar();

    $countdatoscomerciales = 0;

    $queryregion='Select Nombre_region as nombre,id_region as id from region where id_region in (6,7,8)';
    
    if($resbod = $conn->mysqli->query($queryregion)){
        while($databod = $resbod ->fetch_object())
        {
            $regiones [] = $databod;
        }
    }
    else{
        echo $conn->mysqli->error;
    }

    $querydatoscomerciales = 'SELECT  dc.nombre_fantasia_datos_comerciales as nombre, dc.rut_datos_comerciales as rut, razon_social_datos_comerciales as razon,
                                    dc.telefono_datos_comerciales as telefono, dc.calle_datos_comerciales  as calle, dc.numero_datos_comerciales as numero,
                                    dc.id_comuna as comuna , re.id_region as region
                                    from datos_comerciales dc
                            inner join comuna co on co.id_comuna = dc.id_comuna
                            INNER JOIN provincia pro on pro.id_provincia = co.id_provincia
                            INNER JOIN region re on re.id_region = pro.id_region
                            where dc.id_cliente ='.$id_cliente .' limit 1';





    if($rescomercial = $conn->mysqli->query($querydatoscomerciales)){
        if($rescomercial->num_rows > 0)
        {
            while($datacomercial = $rescomercial ->fetch_object())
            {
                $datoscomerciales [] = $datacomercial;
            }

            $countdatoscomerciales=1;
        }else{
            $countdatoscomerciales=0;
        }

        
    }
    else{
        echo $conn->mysqli->error;
    }

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
       
        <div id="main" class="layout-navbar">
            <?php
                include_once('./include/topbar.php');
            ?>

            <div class="row">
                <div class="card resumen-envios col-lg-6 col-md-6 col-sm-8 col-12" style="padding: 20px; text-align: center;">
                    <div class="">
                            <h3 style="color: black; font-weight: 700;">Mis datos</h3>
                    </div>
                </div>
            </div>
            <div class="page-content">
                
                <div class="row personal" style="color:black;font-size: 18px; font-weight: 600; justify-content: space-between;">
                    <div class="col-lg-5 col-md-12 resumen-envios">
                        <div>
                            <div class="card-header">
                                <h4 class="card-title">Datos Personales</h4>
                            </div>
                            <div class="card-content">
                                <div class="bodycard" id="cngpd">
                                    <form id="datospersonales" class="form form-vertical">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="first-name-vertical">Nombres</label>
                                                        <input type="text" id="fName"
                                                            class="form-control" name="fName"
                                                            placeholder="Nombre" value="<?php echo $_SESSION['cliente']->nombres_datos_contacto ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="first-name-vertical">Apellidos</label>
                                                        <input type="text" id="fLastName"
                                                            class="form-control" name="fLastName"
                                                            placeholder="Apellidos" value="<?php echo $_SESSION['cliente']->apellidos_datos_contacto ?>">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="email-id-vertical">Correo</label>
                                                        <input type="email" id="email_id"
                                                            class="form-control" name="email_id"
                                                            placeholder="Correo Electronico" value="<?php echo $_SESSION['cliente']->email_datos_contacto ?>">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="contact-info-vertical">Rut</label>
                                                        <input type="text" id="contact"
                                                            class="form-control" name="contact"
                                                            placeholder="Rut" value="<?php $rut =  $_SESSION['cliente']->rut_datos_contacto; echo strval($rut) ?>">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="password-vertical">Celular</label>
                                                        <input type="text" id="telefono"
                                                            class="form-control" name="telefono"
                                                            placeholder="Celular" value="<?php echo $_SESSION['cliente']->telefono_datos_contacto ?>">
                                                    </div>
                                                </div>
                                                <div class="col-12 d-flex justify-content-end">
                                                    <input type="submit" class="btn btn-primary me-1 mb-1" value="Guardar"></input>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-12 resumen-envios" style="color:black;font-size: 18px; font-weight: 600; margin: 10px;">
                        <div class="card" style="background-color: #7ad9c200;">
                            <div class="">
                                <h4 class="card-title">Modificar contraseña</h4>
                            </div>
                            <div class="card-content">
                                <div class="bodycard" id="cngpd">
                                    <form id="passchange" class="form form-vertical">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="first-name-vertical">Constraseña actual</label>
                                                        <input type="password" id="currentpass"
                                                            class="form-control" name="currentpass" placeholder="Constraseña">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12" style="margin:30px 0px;">
                                                    <h5>
                                                        Nueva Contraseña
                                                    </h5>

                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <input type="password" id="newpass"
                                                            class="form-control" name="newpass"
                                                            placeholder="Nueva contraseña" >
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <input type="password" id="confirmnewpass"
                                                            class="form-control" name="confirmnewpass"
                                                            placeholder="Confirmar contraseña" >
                                                    </div>
                                                </div> 
                                            </div>
                                            <br>
                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="submit"
                                                    class="btn btn-primary me-1 mb-1">Submit</button>
                                                
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                </div>
               
            </div>



          
            <div class="row" style="justify-content: space-between; margin: 10px; color:black;font-size: 18px; font-weight: 600;">
                        <div class="col-lg-5 col-md-12 resumen-envios">
                            <div>
                                <div class="card-header">
                                    <h4 class="card-title">Modificar Datos Comerciales</h4>
                                </div>
                                <div class="" >
                                    <div class="" id="cngcd">
                                        <form id="datoscomerciales" class="form form-vertical">
                                            <div class="form-body">
                                                <h5>Datos comerciales</h5>
                                                <div class="row">
                                                    <?php 
                                                        if($countdatoscomerciales==1):
                                                            foreach($datoscomerciales as $dc):
                                                    ?>
                                                    <div class="col-md-12" >
                                                        <div class="form-group">
                                                        <label for="first-name-vertical">Nombre Fantasia</label>
                                                            <input type="text" id="fancom"
                                                                class="form-control" name="fancom"
                                                                placeholder="Nombre fantasía" value="<?php echo $dc->nombre?>">
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="form-group col-8">
                                                                <label for="first-name-vertical">Calle</label>
                                                                <input type="text" id="dircom"
                                                                    class="form-control" name="dircom"
                                                                    placeholder="Calle"  value="<?php echo $dc->calle?>">
                                                            </div>
                                                            <div class="form-group col-4">
                                                                <label for="first-name-vertical">Num dirección</label>
                                                                <input type="number" id="numcom"
                                                                    class="form-control" name="numcom"
                                                                    placeholder="Num dirección"  value="<?php echo $dc->telefono?>">
                                                            </div>
                                                        </div>
                                                       
                                                        <div class="form-group">
                                                            <label for="first-name-vertical">Rut</label>
                                                            <input type="text" id="rutcom"
                                                                class="form-control" name="rutcom"
                                                                placeholder="Rut"  value="<?php echo $dc->rut?>">
                                                        </div>
                                                      

                                                        <div class="form-group">
                                                            <label for="contact-info-vertical">Teléfono</label>
                                                            <input type="number" id="telcom"
                                                                class="form-control" name="telcom"
                                                                placeholder="Teléfono"  value="<?php echo $dc->telefono?>">
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="contact-info-vertical">Razón Social</label>
                                                            <input type="text" id="razonsocial"
                                                                class="form-control" name="razonsocial"
                                                                placeholder="Razón Social" value="<?php echo $dc->razon?>">
                                                        </div>
                                                        <div class="form-group">
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

                                                        <div class="form-group">
                                                            <label for="Comuna">Comuna </label>
                                                            <select class="form-select" name="select_comunacli" id="select_comunacli">
                                                                <option value=""></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <button type="submit"
                                                        class="btn btn-primary me-1 mb-1">Cambiar datos comerciales
                                                    </button>
                                                <?php endforeach;?>  
                                                <?php else:?>
                                                    <div class="col-md-12" >
                                                        <div class="form-group">
                                                        <label for="first-name-vertical">Nombre Fantasia</label>
                                                            <input type="text" id="fancom"
                                                                class="form-control" name="fancom"
                                                                placeholder="Nombre fantasía" >
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group col-8">
                                                                <label for="first-name-vertical">Calle</label>
                                                                <input type="text" id="dircom"
                                                                    class="form-control" name="dircom"
                                                                    placeholder="Calle">
                                                            </div>
                                                            <div class="form-group col-4">
                                                                <label for="first-name-vertical">Num dirección</label>
                                                                <input type="number" id="numcom"
                                                                    class="form-control" name="numcom"
                                                                    placeholder="Num dirección"  >
                                                            </div>
                                                        </div>
                                                       
                                                        <div class="form-group">
                                                            <label for="first-name-vertical">Rut</label>
                                                            <input type="text" id="rutcom"
                                                                class="form-control" name="rutcom"
                                                                placeholder="Rut" >
                                                        </div>
                                                      

                                                        <div class="form-group">
                                                            <label for="contact-info-vertical">Teléfono</label>
                                                            <input type="number" id="telcom"
                                                                class="form-control" name="telcom"
                                                                placeholder="Teléfono" >
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="contact-info-vertical">Razón Social</label>
                                                            <input type="text" id="razonsocial"
                                                                class="form-control" name="razonsocial"
                                                                placeholder="Razón Social" >
                                                        </div>
                                                        <div class="form-group">
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

                                                        <div class="form-group">
                                                            <label for="Comuna">Comuna </label>
                                                            <select class="form-select" name="select_comunacli" id="select_comunacli">
                                                                <option value=""></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <button type="submit"
                                                        class="btn btn-primary me-1 mb-1">Cambiar datos comerciales
                                                    </button>
                                                    <?php endif;?>
                                                </div>
                                            </div>
                                        </form>
                                    </div>     
                                </div>
                            </div>
                        </div>

                            <div id="logochange" class="col-lg-5 col-md-12 resumen-envios align-content-center">
                                <div class="">
                                    <div class="card-header">
                                        <h4 class="card-title">Cambiar Logo</h4>
                                    </div>
                                    <div class="">
                                        <div class="" id="cngcd">
                                            <form class="form form-vertical">
                                                <div class="form-body">
                                                    <h5>Datos comerciales</h5>
                                                    <div class="row">
                                                        <div class="">
                                                            <div class="card">
                                                                <div class="card-body imgdrop" style="border:1px solid black; transition: all .5s ease; border-radius: 10px;">
                                                                    <p class="card-text">Seleccione un logotipo con las especificaciones mencionadas
                                                                    </p>
                                                                    <!-- File uploader with image preview -->
                                                                    <input type="file" class="image-preview-filepond">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p>
                                                            Este logotipo es usado en la etiqueta adhesiva en los bultos que serán enviados. <br>
                                                            
                                                            Para correcta visualización, el logo debe ser horizontal con extensión PNG y fondo transparente.
                                                            
                                                            Su relación debe ser de 1:2.5 y con la finalidad de mantener la calidad de la imagen, de alto 300px exactos.</p>
                                                        <button type="submit"
                                                            class="btn btn-primary me-1 mb-1">Actualizar Logotipo
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
            </div>

           <?php
             include_once('./include/footer.php')
           ?>
        </div>
    <!-- Footer contiene div de main app div -->
   
    
    <script src="assets/js/jquery-validation/jquery.validate.js"></script>
    <!-- <script src="./js/newPedido.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./js/rut.js"></script>

</body>

<script>

    <?php foreach($datoscomerciales as $dc):?>
    const region = <?php echo $dc->region;?>;
    const comunacomercial = <?php echo $dc->comuna;?>;
    <?php endforeach;?>
    const comercialdatarows = <?php echo $countdatoscomerciales;?>;
    $(document).ready(function(){
        document.getElementById('select_regioncli').value = region;
        $('#select_regioncli').change()
        console.log(comunacomercial);



    })

$('#datospersonales').validate({
        rules:{
            fName:{
                required :true,
                minlength : 4
            },
            fLastName:{
                required: true,
                minlength :4
            },email_id:{
                required:true,
                minlength:7
            },
            contact:{
                required:true
            },
            telefono:{
                required:true,
                minlength:4
            }
        },
        messages:{
            fName:{
                required :"Debe ingresar un nombre",
                minlength : "El nombre debe tener al menos 4 caracteres"
            },
            fLastName:{
                required: "Debe ingresar un numero de dirección",
                minlength : "El apelldio debe tener al menos 4 caracteres"
            },email_id:{
                required:"Ingrese un correo",
                minlength:"Largo mínimo 7 caracteres"
            },
            contact:{
                required:"Ingrese un rut"
            },telefono:{
                required:"Ingrese un teléfono",
                minlength:8
            }
        },
        submitHandler: function(form,e){
           
        
            try{
                console.log("dentro");
                let vname = document.getElementById('fName').value;
                let vapellido = document.getElementById('fLastName').value;
                let vcorreo = document.getElementById('email_id').value
                let vrut = document.getElementById('contact').value;
                let vtelefono = document.getElementById('telefono').value;
                
                
                let dataajax = {name : vname,
                                apellido : vapellido,
                                correo : vcorreo,
                                rut : vrut,
                                telefono : vtelefono};
                $.ajax({
                    url: "ws/cliente/updatepersonalData.php",
                    type: "POST",
                    dataType: 'json',
                    data: JSON.stringify(dataajax),
                    success:function(resp){
                        if(resp.status == 1){
                            Swal.fire({
                                position: 'bottom',
                                icon: 'success',
                                title: resp.response,
                                showConfirmButton: false,
                                timer: 1500
                            })
                            location.reload()
                        }

                        if(resp.status == 0){
                            Swal.fire({
                                position: 'bottom',
                                icon: 'error',
                                title: resp.response,
                                showConfirmButton: false,
                                timer: 2500
                            })
                        }
                     
                        return false;
                        
                    },error:function(resp){
                        console.log(resp.responseText);
                        return false;
                    }
                })
            }
            catch(error){
                return false;
            }    
        }
})





$('#passchange').validate({
        rules:{
            currentpass:{
                required :true
            },
            newpass:{
                required: true
                
            },confirmnewpass:{
                required:true
            }
        },
        messages:{
            currentpass:{
                required :"Ingrese un valor"
            },
            newpass:{
                required :"Ingrese un valor"
                
            },confirmnewpass:{
                required :"Ingrese un valor"
            }
            
        },
        submitHandler: function(form){
            event.preventDefault()
            console.log("dentropass");
            let vcurrentpass = document.getElementById('currentpass').value;
            let vnewpass = document.getElementById('newpass').value;
            let vconfirmnewpass = document.getElementById('confirmnewpass').value
            console.log(vcurrentpass+'     '+vnewpass+'       '+vconfirmnewpass);
            let dataajax =     {pass : vcurrentpass,
                                newpass : vnewpass,
                                confirmnewpass : vconfirmnewpass,
                                action: "currentpass"}
            $.ajax({
                url: "ws/cliente/updatepass.php",
                type: "POST",
                dataType: 'json',
                data: JSON.stringify(dataajax),
                success:function(resp){
                    //console.log(resp);  

                    if(resp.status == 0){
                        Swal.fire({
                            position: 'bottom',
                            icon: 'error',
                            title: resp.response,
                            showConfirmButton: false,
                            timer: 2500
                        })
                    }

                    if(resp.status == 1){
                        if(vnewpass == vconfirmnewpass){

                            let dataajax = {pass : vcurrentpass,
                                            newpass : vnewpass,
                                            confirmnewpass : vconfirmnewpass,
                                            action: "changepass"}

                            $.ajax({
                                url: "ws/cliente/updatepass.php",
                                type: "POST",
                                dataType: 'json',
                                data: JSON.stringify(dataajax),
                                success:function(resp){
                                    if(resp.status == 1 ){
                                        Swal.fire({
                                            position: 'bottom',
                                            icon: 'success',
                                            title: resp.response,
                                            showConfirmButton: false,
                                            timer: 2500
                                        })
                                        location.reload();
                                    }
                                    if(resp.status == 0 ){
                                        Swal.fire({
                                            position: 'bottom',
                                            icon: 'error',
                                            title: resp.response,
                                            showConfirmButton: false,
                                            timer: 2500
                                        })
                                    }
                                },error:function(resp){

                                    console.log(resp.responseText);     

                                }
                            })

                        }else{
                            Swal.fire({
                                position: 'bottom',
                                icon: 'error',
                                title: "Las NUEVAS contraseñas no coinciden, porfavor intenta nuevamente",
                                showConfirmButton: false,
                                timer: 3500
                            })
                        }
                    }
                    return false;
                },error:function(resp){
                    console.log(resp.responseText);
                   
                    return false;
                }
            })         
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
            
            var select = document.getElementById("select_comunacli");
            $.each(data, function (key, value){
                
                if(value.id == comunacomercial){
                    select.options[select.options.length] = new Option(value.nombre,value.id,false,true);
                }else{
                    select.options[select.options.length] = new Option(value.nombre,value.id,false,false);
                }
               
                
            })
            
            
        },
            error: function(data){
        }
    })
})

$('#datoscomerciales').validate({
        rules:{
            fancom:{
                required:true,
                minlength:4
            },
            dircom:{
                required:true,
                minlength:4
            },
            numcom:{
                required:true
            },
            rutcom:{
                required:true,
                minlength:8
            },
            telcom:{
                required:true,
                minlength:8
            },
            razonsocial:{
                required:true,
                minlength:4
            },
            select_regioncli:{
                required : true
            },select_comunacli:{
                required : true
            }
        },
        messages:{
            fancom:{
                required:"Debe ingresar este valor",
                minlength:"Largo mínimo 4 caracteres"
            },
            dircom:{
                required:"Debe ingresar este valor",
                minlength:"Largo mínimo 4 caracteres"
            },
            numcom:{
                required:"Debe ingresar este valor"
            },
            rutcom:{
                required:"Debe ingresar este valor",
                minlength:"Largo mínimo 8 caracteres"
            },
            telcom:{
                required:"Debe ingresar este valor",
                minlength:"Largo mínimo 8 caracteres"
            },
            razonsocial:{
                required:"Debe ingresar este valor",
                minlength:"Largo mínimo 4 caracteres"
            },select_regioncli:{
                required : "Ingrese una región"
            },select_comunacli:{
                required : "Ingerse una comuna"
            }
        },
        submitHandler: function(form,e){
           event.preventDefault()
           console.log(comercialdatarows);
           
                const vfancom = document.getElementById('fancom').value
                console.log(vfancom);
                const vdircom = document.getElementById('dircom').value
                const vnumcom = document.getElementById('numcom').value
                const vrutcom = document.getElementById('rutcom').value
                const vtelcom = document.getElementById('telcom').value
                const vrazonsocial = document.getElementById('razonsocial').value
                const vcomuna = document.getElementById('select_comunacli').value
                if(comercialdatarows == 0){
                    console.log(comercialdatarows);
                    console.log("insert");
                    let dataajax = {fancom : vfancom,
                                dircom : vdircom,
                                numcom : vnumcom,
                                rutcom : vrutcom,
                                telcom : vtelcom,
                                razonsocial : vrazonsocial,
                                comuna : vcomuna,
                                action:"insert"};
                    $.ajax({
                        url: "ws/cliente/updatecomecialdata.php",
                        type: "POST",
                        dataType: 'json',
                        data: JSON.stringify(dataajax),
                        success:function(resp){
                            if(resp.status == 1){
                                Swal.fire({
                                    position: 'bottom',
                                    icon: 'success',
                                    title: resp.response,
                                    showConfirmButton: false,
                                    timer: 2500
                                })  
                                location.reload()

                            }

                            if(resp.status == 0){
                                Swal.fire({
                                    position: 'bottom',
                                    icon: 'error',
                                    title: resp.response,
                                    showConfirmButton: false,
                                    timer: 2500
                                })  
                                location.reload()

                            }
                           
                            return false;
                        },error:function(resp){

                            return false;
                        }
                    })
                }
                if(comercialdatarows == 1){
                    console.log(comercialdatarows);
                    console.log("update");
                    let dataajax = {fancom : vfancom,
                                dircom : vdircom,
                                numcom : vnumcom,
                                rutcom : vrutcom,
                                telcom : vtelcom,
                                razonsocial : vrazonsocial,
                                comuna : vcomuna,
                                action:"update"};
                     $.ajax({
                        url: "ws/cliente/updatecomecialdata.php",
                        type: "POST",
                        dataType: 'json',
                        data: JSON.stringify(dataajax),
                        success:function(resp){

                            if(resp.status == 1){
                                Swal.fire({
                                    position: 'bottom',
                                    icon: 'success',
                                    title: resp.response,
                                    showConfirmButton: false,
                                    timer: 2500
                                })  
                                location.reload()
                            }

                            if(resp.status == 0){
                                Swal.fire({
                                    position: 'bottom',
                                    icon: 'error',
                                    title: resp.response,
                                    showConfirmButton: false,
                                    timer: 2500
                                })  
                                location.reload()

                            }
                            return false;
                        },error:function(resp){

                            return false;
                        }
                    })
                }
        }
})                         
</script>

<style>

    .error{
        color:red;
    }
</style>

</html>

