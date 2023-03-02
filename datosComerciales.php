<!DOCTYPE html>
<html lang="en">

<head>
    <?php
         include_once('../nclientesv2/include/head.php')
    ?>
</head>

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
                <h3>Datos Comerciales|| Spread</h3>
            </div>
            <div class="page-content">
                
                <div class="row">
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
                                                <div class="col-md-6" >
                                                    <div class="form-group">
                                                        <input type="text" id="first-name-vertical"
                                                            class="form-control" name="faname"
                                                            placeholder="Nombre fantasía">
                                                    </div>
                                                
                                                    <div class="form-group">
                                                        <input type="text" id="email-id-vertical"
                                                            class="form-control" name="RutCom"
                                                            placeholder="RUT">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" id="email-id-vertical"
                                                            class="form-control" name=""
                                                            placeholder="Razón Social">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" id="email-id-vertical"
                                                            class="form-control" name="cell"
                                                            placeholder="Teléfono">
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="contact-info-vertical">Rut</label>
                                                        <input type="number" id="contact-info-vertical"
                                                            class="form-control" name="contact"
                                                            placeholder="Rut" value="20.136.448-5">
                                                    </div>
                                                
                                               
                                                    <div class="form-group">
                                                        <label for="password-vertical">Celular</label>
                                                        <input type="text" id="password-vertical"
                                                            class="form-control" name="contact"
                                                            placeholder="Celular" value="953061585">
                                                    </div>
                                                    
                                                </div>
                                                <button type="submit"
                                                    class="btn btn-primary me-1 mb-1">Cambiar datos comerciales
                                                </button>
                                                    
                                            </div>
                                            </div>
                                        </div>
                                    </form>
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
    <!-- Footer contiene div de main app div -->
    <?php
        include_once('../nclientesv2/include/footer.php')
    ?>
</body>

</html>