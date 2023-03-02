<?php
    session_start();
    include('ws/bd/dbconn.php');

    $id_cliente = $_SESSION['cliente']->id_cliente;
    $conn = new bd();

    $conn -> conectar();

    $query='Select Nombre_region as nombre,id_region as id from region';


    $querybodega =  'SELECT bo.nombre_bodega as nombre,
                            bo.id_bodega,
                            bo.calle_bodega as calle, 
                            bo.numero_bodega as numero,
                            bo.principal_bodega as principal, 
                            co.nombre_comuna as comuna,
                            re.nombre_region as region
                        FROM bodega bo
                        inner join comuna co on co.id_comuna = bo.id_comuna
                        inner join provincia pro on pro.id_provincia = co.id_provincia
                        inner join region re on re.id_region = pro.id_region
                        where bo.id_cliente = '.$id_cliente.' and isDelete = 0';



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


    if($resbod = $conn->mysqli->query($querybodega))
    {
        $bodegas = array();
        
        while($databod = $resbod ->fetch_object())
        {
            $bodegas [] = $databod;
        }
    }
    else{
        echo $conn->mysqli->error;
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
        
        
        <div id="main" class="layout-navbar">
       
            <?php
                include_once('./include/topbar.php');
            ?>

            <div class="page-heading">
                <div class="row">
                    <div class="col-sm-9">
                        <h3>Mis Direcciones || Spread</h3>
                    </div>
                    <div class="col-sm-3">
                    <!-- Button trigger for Crear form modal -->
                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                        data-bs-target="#inlineForm">
                        Agregar bodega
                    </button>
                </div>
            </div>
            <div class="page-content">

                <div class="resumen-bodegas" id="resumen-bodegas">
                    <div class="row">
                                <?php
                                    foreach($bodegas as $bodega):
                                    $main = $bodega->principal;
                                ?>
                                    <div class="card_bodegas col-lg-3 col-md-4 col-sm-6 col-12" >
                                            <div class="card bodega card_bodegas">
                                                <div class="card-content" style="justify-content: center;">
                                                    <div class="card-body" id="cardbodywarehouse" >
                                                        <div class="row">
                                                            <h4 class="card-title col-10"><?php echo $bodega->nombre?></h4>
                                                            <input type="text" name="" id="idbod" value="<?=$bodega->id_bodega?>">
                                                            
                                                        </div>
                                                        <p style="flex-direction: column-reverse;"><?php echo $bodega->calle?></p>
                                                        <p class="card-text">
                                                        <?php echo $bodega->comuna?>
                                                        </p>
                                                        <div class="row" style="justify-content: center;">
                                                        </div>
                                                                <button type="button" class="modbtn btn btn-warning" data-bs-toggle="modal" data-bs-target="#ModForm"
                                                                        data-bs-toggle="tooltip" title="Modificar">
                                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                                </button>
                                                            
                                                                <button type="button" class="deletebodega btn btn-danger"  data-bs-toggle="tooltip" title="Eliminar">
                                                                    <i class="fa-solid fa-trash"></i>
                                                                </button>
                                                        
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                    </div>
                                <?php
                                        endforeach;                                                                    
                                ?>
                        </div>

                </div>
                    
            </div>

            
        </div>
       



                <!--Danger theme Modal -->
                <div class="modal fade text-left" id="danger" tabindex="-1" role="dialog"
                    aria-labelledby="myModalLabel120" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
                        role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-danger">
                                <h5 class="modal-title white" id="myModalLabel120">Eliminar Punto de Retiro
                                </h5>
                                <button type="button" class="close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i data-feather="x"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                Desea eliminar el punto de retiro (Datos puntos de retiro)
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
                <!--Crear Bodega form Modal -->
                <div class="modal fade text-left" id="inlineForm"  tabindex="-1" role="dialog"
                    aria-labelledby="myModalLabel33" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
                        role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel33">Crear Bodega</h4>
                                <button type="button" class="close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i data-feather="x"></i>
                                </button>
                            </div>
                            <form action="#">
                                <div class="modal-body">
                                    <label>Nombre </label>
                                    <div class="form-group">
                                        <input type="text" placeholder="Nombre"
                                            class="form-control" id="bodnombre" required>
                                    </div>
                                    <label>Calle </label>
                                    <div class="form-group">
                                        <input type="text" placeholder="Dirección"
                                            class="form-control" id="boddireccion" required>
                                    </div>
                                    <label>Número </label>
                                    <div class="form-group">
                                        <input type="text" placeholder="Número"
                                            class="form-control" id="bodnumero" required>
                                    </div>
                                    <label>Comuna</label> </label>
                                    <label for="select_regioncre">Region</label> </label>
                                    <div class="input-group mb-3">
                                        <label class="input-group-text"
                                            for="select_regioncre">Comunas</label>
                                        <select class="form-select" name="select_regioncre" id="select_regioncre" required>
                                            <option value=""></option>
                                                <?php 
                                                    foreach($comunas as $com)
                                                    {
                                                        echo '<option value="'.$com->id.'">'.$com->nombre.'</option>';
                                                    }
                                                ?>  
                                        </select>
                                    </div>
                                    <label for="select_comunacre">Comuna</label> </label>
                                    <div class="input-group mb-3">
                                        <label class="input-group-text"
                                            for="select_comunacre">Comunas</label>
                                            <select class="form-select" name="select_comunacre" id="select_comunacre" required>
                                                <option value=""></option>
                                            </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn closemodalcrear btn-light-secondary">
                                        <i class="bx bx-x d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Cancelar</span>
                                    </button>
                                    <input type="submit" value="Agregar" class="submit btn btn-primary ml-1"
                                    >
                                    <i class="bx bx-check d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block"></span>
                                </input>
                                </div>
                            </form>
                    </div>
                </div>


            
            </div>
            <!--Modificar Bodega form Modal -->
            <form action="" class="modal fade text-left" id="ModForm"  tabindex="-1" role="dialog"
                aria-labelledby="myModalLabel33" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
                    role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel33">Modificar Bodega</h4>
                            <button type="button" class="close" data-bs-dismiss="modal"
                                aria-label="Close">
                                <i data-feather="x"></i>
                            </button>
                        </div>
                        <div id="formmodificar">
                            <div class="modal-body">
                                <label for="nombre">Nombre </label>
                                <div class="form-group">
                                    <input type="text" placeholder="Nombre"
                                        class="form-control" name="nombre" id="nombre" >
                                </div>
                                <label for="calle">Calle </label>
                                <div class="form-group">
                                    <input type="text" placeholder="Dirección"
                                        class="form-control" name="calle" id="calle" >
                                </div>
                                <label for="numero">Número </label>
                                <div class="form-group">
                                    <input type="text" placeholder="Número"
                                        class="form-control" name="numero" id="numero" >
                                </div>
                                <label for="select_regionmod">Region</label> </label>
                                <div class="input-group mb-3">
                                    <label class="input-group-text"
                                        for="select_regionmod">Region</label>
                                    <select class="form-select" name="select_regionmod" id="select_regionmod" >
                                        <option value=""></option>
                                            <?php 
                                                foreach($comunas as $com)
                                                {
                                                    echo '<option value="'.$com->id.'">'.$com->nombre.'</option>';
                                                }
                                            ?>  
                                    </select>
                                </div>
                                <label for="select_comunamod">Comuna</label> </label>
                                <div class="input-group mb-3">
                                    <label class="input-group-text"
                                        for="select_comunamod">Comuna</label>
                                        <select class="form-select" name="select_comunamod" id="select_comunamod" >
                                            <option value=""></option>
                                        </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn closemodalmod btn-light-secondary">
                                    <i class="bx bx-x d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Cancelar</span>
                                </button>
                                <input type="submit" value="Modificar"  class="btn btn-primary ml-1">
                                    <i class="bx bx-check d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block"></span>
                                </input>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
   <!-- Footer contiene div de main app div -->
   <?php
        include_once('../nclientesv2/include/footer.php')
    ?>
    <script src="assets/js/jquery-validation/jquery.validate.js"></script>


</body>
<script>
    var id_bodega = 0
    var selectcomuna = 0
    var comunacheck = false
    $('.closemodalmod').on('click',function(){
        $('#ModForm').modal('hide')
    })
    $('.closemodalcrear').on('click',function(){
        $('#inlineForm').modal('hide')
    })

    $('.modbtn').on('click',function(){
        id_bodega = $(this).closest('.card_bodegas').find('#idbod').val()
        console.log(id_bodega)
        $.ajax({
            url: "ws/bodega/getbodegaById.php",
            type: "POST",
            dataType :'json',
            data: {"id_bodega" : id_bodega},
            success:function(resp){
                console.log(resp)
                let form = document.getElementById('ModForm')
                $.each(resp,function(key,value){
                    // console.log(value.comuna);
                    // console.log(value.region);
                    $('#ModForm').find('input[name="nombre"]').val(value.nombre)
                    $('#ModForm').find('input[name="calle"]').val(value.direccion)
                    $('#ModForm').find('input[name="numero"]').val(value.numero)
                    $('#ModForm').find('#select_regionmod').val(value.region).change()
                    selectcomuna = value.comuna
                    comunacheck = true
                })
            }
        })
    })


    $("#crearBodega").click(function(){
            try{
                let vdir = document.getElementById('boddireccion').value
                let vnumero = document.getElementById('bodnumero').value
                let vnombre = document.getElementById('bodnumero').value
                let vcomuna = document.getElementById('select_comunaccre').value
                let vregion = document.getElementById('select_regioncre').value

                let dataajax = {direccion : vdir,
                                numero: vnumero,
                                nombre : vnombre,
                                comuna : vcomuna,
                                region: vregion};

                        $.ajax({
                        url: "ws/bodega/newBodega.php",
                        type: "POST",
                        data: JSON.stringify(dataajax),
                        success:function(resp){
                            console.log(resp)
                            return false
                        }
                    })
            }
            catch(error){
                console.log(error);
            }    
    })


        $("#select_regioncre").on('change',function(){
            
            var idregion = this.value;
            var comuna = document.getElementById("select_comunacre");
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
                                //console.log(data);

                                $.each(data, function (key, value){
                                    let select = document.getElementById("select_comunacre");
                                    select.options[select.options.length] = new Option(value.nombre,value.id)
                                })
                            },
                                error: function(data){
                            }
            })
        })

        $("#select_regionmod").on('change',function(){
            var idregion = this.value;
            var comuna = document.getElementById("select_comunamod");
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
                                //console.log(data);
                                $.each(data, function (key, value){
                                    
                                    let select = document.getElementById("select_comunamod");
                                    
                                    if(selectcomuna == value.id){
                                        select.options[select.options.length] = new Option(value.nombre,value.id,false,true)
                                       
                                    }
                                    else{
                                        select.options[select.options.length] = new Option(value.nombre,value.id,false,false)
                                    }
                                    //select.val(selectcomuna)
                                })
                            },
                                error: function(data){
                            }
            })
        })

        $("#ModForm").validate({
            rules:{
                nombre:{
                    required : true
                },
                calle:{
                    required: true,
                    minlength: 4
                },
                numero: {
                    required:true
                },
                select_regioncli:{
                    required:true
                },
                select_comunacli:{
                    required:true
                }
            },
            messages:{
                nombre:{
                    required : "Debe ingresa un Nombre para el punro de retiro"
                },
                calle:{
                    required: "Debe ingresar la dirección del punto de retiro",
                    minlength: "Debe tener al menos 4 caracteres"
                },
                numero: {
                    required:"Debe ingresar la numeración de la dirección"
                },
                select_regioncli:{
                    required:"Seleccione una región"
                },
                select_comunacli:{
                    required:"Seleccione una comuna"
                }
            },submitHandler: function(form){
                let vnombre =$('#ModForm').find('input[name="nombre"]').val()
                let vdireccion =$('#ModForm').find('input[name="calle"]').val()
                let vnumero =$('#ModForm').find('input[name="numero"]').val()
                let vcomuna =$('#ModForm').find('#select_comunamod').val()
                $.ajax({
                        url: "ws/bodega/modbodega.php",
                        type: "POST",
                        dataType:'json',
                        data: JSON.stringify({
                            "nombre":vnombre,
                            "direccion":vdireccion,
                            "numero":vnumero,
                            "comuna":vcomuna,
                            "id" : id_bodega
                        }),
                        success:function(resp){
                            Swal.fire({
                                icon: 'success',
                                title: 'Hecho!',
                                text: resp.message
                            })
                            $('#ModForm').modal('hide')
                            $(".resumen-bodegas").load(window.location.href +" .resumen-bodegas");
                            console.log(resp)
                            return false
                        }
                    })
            }
        })


        
    $(".deletebodega").on('click',function(){
            id_bodega = $(this).closest('.card_bodegas').find('#idbod').val()
            Swal.fire({
                title: 'Quieres Eliminar este punto de retiro?',
                text: "Se eliminará este punto de retiro",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, borralo!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "ws/bodega/deletebodega.php",
                    type: "POST",
                    data: JSON.stringify({
                        "id_bodega": id_bodega
                    }),
                    dataType : 'json',
                    beforeSend: function() {
                        $("#overlay").fadeIn(300);
                    },
                    success:function(resp){
                        //console.log(resp.status)
                        
                        if(resp.status == 1 ){
                            Swal.fire({
                                title: 'Eliminado!',
                                text: 'Tú punto de retiro ha sido eliminado exitosamente',
                                icon: 'success'
                            }).then((result)=>{
                                Swal.fire(
                                'Importante!',
                                'Tu nueva bodega principal es:  '+resp.namenewmain,
                                'info').then((result)=>{
                                    if(result){
                                        //console.log(resp);
                                        location.reload()
                                    }
                                })
                            })    
                        }
                        if(resp.status==2){
                            Swal.fire({
                                title: 'Eliminado!',
                                text: 'Tú punto de retiro ha sido eliminado exitosamente',
                                icon: 'success'
                            }).then((result)=>{
                                location.reload()
                            })
                        }
                    },error:function(resp){
                        console.log(resp.responseText);
                    },complete: function() {
                        $("#overlay").fadeOut(300);
                    }
                })
                
            }
        })
            
    })
        

        
</script>
<style>
    .error{
        color:red;
    }
    .form-select option{
        color:black;
    }
</style>
</html>