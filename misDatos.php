<?php
    session_start();
    include('ws/bd/dbconn.php');

    $id_cliente = $_SESSION['cliente']->id_cliente;
    $conn = new bd();
    $conn -> conectar();

    // $query='Select Nombre_region as nombre,id_region as id from region';

    // if($res = $conn->mysqli->query($query))
    // {
    //     $comunas = array();
        
    //     while($datares = $res ->fetch_object())
    //     {
    //         $comunas [] = $datares;
    //     }
    // }
    // else{
    //     echo $conn->mysqli->error;
    // }

    

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
       
        <div id="main" class="layout-navbar">
            <?php
                include_once('./include/topbar.php');
            ?>

            <div class="page-heading">
                <h3>Mis Datos || Spread</h3>
            </div>
            <div class="page-content">
                
                <div class="row personal">
                    <div class="col-lg-6 col-md-12">
                        <div>
                            <div class="card-header">
                                <h4 class="card-title">Datos Personales</h4>
                            </div>
                            <div class="card-content">
                                <div class="bodycard" id="cngpd">
                                    <form class="form form-vertical">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="first-name-vertical">Nombres</label>
                                                        <input type="text" id="first-name-vertical"
                                                            class="form-control" name="fName"
                                                            placeholder="Nombre" value="<?php echo $_SESSION['cliente']->nombres_datos_contacto ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="first-name-vertical">Apellidos</label>
                                                        <input type="text" id="first-name-vertical"
                                                            class="form-control" name="fLastName"
                                                            placeholder="Apellidos" value="<?php echo $_SESSION['cliente']->apellidos_datos_contacto ?>">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="email-id-vertical">Correo</label>
                                                        <input type="email" id="email-id-vertical"
                                                            class="form-control" name="email-id"
                                                            placeholder="Correo Electronico" value="<?php echo $_SESSION['cliente']->email_datos_contacto ?>">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="contact-info-vertical">Rut</label>
                                                        <input type="text" id="contact-info-vertical"
                                                            class="form-control" name="contact"
                                                            placeholder="Rut" value="<?php echo $_SESSION['cliente']->rut_datos_contacto ?>">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="password-vertical">Celular</label>
                                                        <input type="text" id="password-vertical"
                                                            class="form-control" name="contact"
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




                    <div class="col-lg-6 col-md-12">
                        <div class="">
                            <div class="card-header">
                                <h4 class="card-title">Modificar contraseña</h4>
                            </div>
                            <div class="card-content">
                                <div class="bodycard" id="cngpd">
                                    <form class="form form-vertical">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="first-name-vertical">Constraseña actual</label>
                                                        <input type="text" id="first-name-vertical"
                                                            class="form-control" name="fname" placeholder="Constraseña">
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
                                                        <input type="email" id="email-id-vertical"
                                                            class="form-control" name="email-id"
                                                            placeholder="Nueva contraseña" >
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <input type="number" id="contact-info-vertical"
                                                            class="form-control" name="contact"
                                                            placeholder="Confirmar contraseña" >
                                                    </div>
                                                </div> 
                                            </div>
                                            <br>
                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="submit"
                                                    class="btn btn-primary me-1 mb-1">Submit</button>
                                                <button type="reset"
                                                    class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                </div>
               
            </div>



          
            <div class="row comercial">
                        <div class="col-lg-5 col-md-12">
                            <div>
                                <div class="card-header">
                                    <h4 class="card-title">Modificar Datos Comerciales</h4>
                                </div>
                                <div class="card-content" >
                                    <div class="card-body" id="cngcd">
                                        <form class="form form-vertical">
                                            <div class="form-body">
                                                <h5>Datos comerciales</h5>
                                                <div class="row">
                                                    <div class="col-md-12" >
                                                        <div class="form-group">
                                                        <label for="first-name-vertical">Nombre Fantasia</label>
                                                            <input type="text" id="first-name-vertical"
                                                                class="form-control" name="faname"
                                                                placeholder="Nombre fantasía">
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="form-group col-8">
                                                                <label for="first-name-vertical">Calle</label>
                                                                <input type="text" id="email-id-vertical"
                                                                    class="form-control" name="RutCom"
                                                                    placeholder="RUT">
                                                            </div>
                                                            <div class="form-group col-4">
                                                                <label for="first-name-vertical">Num dirección</label>
                                                                <input type="number" id="email-id-vertical"
                                                                    class="form-control" name="cell"
                                                                    placeholder="Teléfono">
                                                            </div>
                                                        </div>
                                                       
                                                        <div class="form-group">
                                                            <label for="first-name-vertical">Rut</label>
                                                            <input type="text" id="email-id-vertical"
                                                                class="form-control" name=""
                                                                placeholder="Razón Social">
                                                        </div>
                                                      

                                                        <div class="form-group">
                                                            <label for="contact-info-vertical">Teléfono</label>
                                                            <input type="number" id="contact-info-vertical"
                                                                class="form-control" name="contact"
                                                                placeholder="Rut" value="20.136.448-5">
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="contact-info-vertical">Razón Social</label>
                                                            <input type="text" id="contact-info-vertical"
                                                                class="form-control" name="contact"
                                                                placeholder="Rut" value="20.136.448-5">
                                                        </div>

                                                    </div>
                                                    
                                                    <button type="submit"
                                                        class="btn btn-primary me-1 mb-1">Cambiar datos comerciales
                                                    </button>
                                                        
                                                </div>
                                            </div>
                                        </form>
                                    </div>     
                                </div>
                            </div>
                        </div>

                            <div class="col-lg-6">
                                <div class="">
                                    <div class="card-header">
                                        <h4 class="card-title">Cambiar Logo</h4>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body" id="cngcd">
                                            <form class="form form-vertical">
                                                <div class="form-body">
                                                    <h5>Datos comerciales</h5>
                                                    <div class="row">
                                                        <div class="">
                                                            <div class="card-content">
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
            include_once('../nclientesv2/include/footer.php')
           ?>
        </div>
    <!-- Footer contiene div de main app div -->
    <?php
        include_once('../nclientesv2/include/footer.php')
    ?>
    
</body>

</html>