<!DOCTYPE html>
<html lang="en">
  <?php
    include_once('./include/head.php')
  ?>

  <body>
  <!-- <body style="background-image: url('./include/img/spread_background.png'); 
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: 100% 100%;"> -->

  
  <div id="auth">
      <div class="row h-100 m-0">
        <div class="col-lg-4 col-12 mt-5">
          <div id="auth-left">
            <div class="auth-logo mb-5">
              <img src="./include/img/logo_horizontal - copia.png" style="position:flex; width:100%"/>
            </div>
            <h3 class="auth-title mb-3">Inicio Sesión</h3>

            <form id="ingreso">
              <div class="form-group position-relative has-icon-left mb-4">
                <input
                  type="email"
                  class="form-control form-control-xl"
                  placeholder="example@correo.cl"
                  name="email_cliente"
                  id="email_cliente"
                  required
                />
                <div class="form-control-icon">
                  <i class="bi bi-person"></i>
                </div>
              </div>
              <div class="form-group position-relative has-icon-left mb-2">
                <input
                  type="password"
                  class="form-control form-control-xl"
                  placeholder="Contraseña"
                  name="password_cliente"
                  id="password_cliente"
                  required
                />
                <div class="form-control-icon">
                  <i class="bi bi-shield-lock"></i>
                </div>
              </div>
              <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5" id="btn-ingresar">
                Iniciar Sesión
              </button>
              <!-- <button class="btn btn-primary btn-block btn-lg shadow-lg mt-4">
                Inicia Sesión
              </button> -->
            </form>
            <div class="text-center mt-5 text-lg fs-4">
              <p class="text-gray-600">
                No tienes cuenta, puedes crearte una aca
                <a href="auth-register.php" class="font-bold">Crear Acá</a>.
              </p>
              <p>
                <a class="font-bold" href="auth-forgot-password.php">Olvistaste tu clave?</a>
              </p>
            </div>
          </div>
        </div>
        <div class="col-lg-8 m-0">
          <img src="./include/img/spread_bd_vertical.png" style="position:float; width:100%">
        </div>
      </div>
    </div>


  <script src="./assets/extensions/jquery/jquery.js"></script>
  <script src="./assets/js/jquery-validation/jquery.validate.js"></script>
  <script src="./assets/extensions/sweetalert2/sweetalert2.min.js"></script>
  
  <!-- <script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/pages/pcoded.min.js"></script>
  <script src="assets/js/pages/ripple.js"></script> -->
  
  <script>
    $(document).ready(function(){
      // $("#btn-ingresar").click(function() {
    	// 	$("#ingreso").submit();
    	// });

      $('#ingreso').validate({
        rules: {
                email_cliente: {
                    required: true,
                    email: true
                },
                password_cliente: {
                    required: true,
					minlength: 6
                }
            },
          messages: {
                email_cliente: "Por favor ingrese un email válido",
                password_cliente: {
                  required: "Por favor ingrese su password",
                  minlength: "Debe poseer por lo menos 6 caracteres"
				        }
            },highlight: function(element) {
                var $el = $(element);
                console.log($el);
                var $parent = $el.parents(".form-group");
                $el.addClass("es-invalido");

                // Select2 and Tagsinput
                if ($el.hasClass("select2-hidden-accessible") || $el.attr("data-role") === "tagsinput") {
                    $el.parent().addClass("es-invalido");
                }
            },
            unhighlight: function(element) {
                $(element).parents(".form-group").find(".es-invalido").removeClass("es-invalido");
            },
            submitHandler: function(form) {
                $(".btn").prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "./ws/cliente/ingresar.php",
                    data: $("#ingreso").serialize(),
                    dataType: 'json',
                    success: function(data) {
                      console.log(data);
                        if(data.success==1) {
                        	window.location.href = "./";
                        }
                        else {
							            swal.fire(data.titulo, data.message, "error");
                        	$("#password_cliente").val("");
                        }
                    	$("#registro").trigger("reset");
                    },
                    error: function(data){
                    }
                });
                $(".btn").prop('disabled', false);
            }
      });



    });
  </script>
    
  
  </body>
</html>


