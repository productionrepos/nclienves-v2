<?php

$token_cliente = filter_input(INPUT_GET, "token", FILTER_SANITIZE_STRING);
if(strlen($token_cliente)!=32) {
	echo 'Enlace inválido';
	exit();
}

require_once('./ws/bd/dbconn.php');
$conexion = new bd();
$conexion->conectar();

if($datos = $conexion->mysqli->query("SELECT * FROM cliente WHERE token_cliente='$token_cliente' AND verificado_cliente=0")) {
	if($datos->num_rows==1) {
		$datos_cliente = $datos->fetch_object();
	}
	else {
		$conexion->desconectar();
		exit();
	}
}
else {
	echo $conexion->mysqli->error;
	exit();
}
$conexion->desconectar();
?>
<!DOCTYPE html>
<html lang="es">

	<?php
    $head = 'Completar registro';
    include_once('./include/head.php');
    ?>

<div class="auth-wrapper d-flex align-items-center justify-content-center">
	<div class="auth-content">
		<div class="card">
			<div class="row align-items-center text-center">
				<div class="col-md-12">
					<div class="card-body">
						<h2 class="mb-3"><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/include/Logotipo_Spread.png" width=100%></h2>
						<div class="progress mb-4" style="height: 30px;">
							<div class="progress-bar" role="progressbar" style="width: 50%; font-size:16px" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">50%</div>
						</div>
						<div class="alert alert-success" role="alert">
							¡Estás a un paso! <br>Solo necesitamos un par de datos para completar tu registro
						</div>

						<form class="form" id="completar_registro">
							<input type="hidden" name="token_cliente" id="token_cliente" value="<?=$token_cliente?>" />
							<div class="form-group mb-3">
								<label class="floating-label" for="nombres_datos_contacto">Nombres</label>
								<input type="text" class="form-control" name="nombres_datos_contacto" id="nombres_datos_contacto">
							</div>
						    <div class="form-group mb-3">
						        <label class="floating-label" for="apellidos_datos_contacto">Apellidos</label>
						        <input type="text" class="form-control" name="apellidos_datos_contacto" id="apellidos_datos_contacto" placeholder="">
						    </div>
						    <div class="form-group mb-3">
						        <label class="floating-label" for="rut_datos_contacto">RUT</label>
						        <input type="text" class="form-control" name="rut_datos_contacto" id="rut_datos_contacto" placeholder="">
						    </div>
						    <div class="form-group mb-3">
						        <label class="floating-label" for="telefono_datos_contacto">Celular</label>
						        <input type="text" class="form-control" name="telefono_datos_contacto" id="telefono_datos_contacto" placeholder="">
						    </div>
						    <div class="form-group mb-3">
						        <label class="floating-label" for="email_datos_contacto">Email</label>
						        <input type="text" class="form-control" name="email_datos_contacto" id="email_datos_contacto" value="<?=$datos_cliente->email_cliente?>" placeholder="">
						        <br><span class="text-muted">* Correo personal, no cambiará el de ingreso</span>
						    </div>
						    <button type="button" class="btn btn-success btn-block mb-4">Completar registro</button>
						</form>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Required Js -->
<!-- <script src="assets/js/jquery.js"></script>
<script src="assets/js/plugins/bootstrap.min.js"></script>
<script src="assets/js/ripple.js"></script>
<script src="assets/js/pcoded.min.js"></script>
<script src="assets/js/rut.js"></script>
<script src="assets/js/jquery-validation/jquery.validate.js"></script>
<script src="assets/js/plugins/sweetalert.min.js"></script> -->

<?php
    include_once('./include/footer.php')
?>
<script src="./assets/extensions/jquery/jquery.js"></script>
<script src="./assets/js/jquery-validation/jquery.validate.js"></script>
<script src="./assets/extensions/sweetalert2/sweetalert2.min.js"></script>

<script>
$(document).ready(function(){
	console.log('script');
	// $("input#rut_datos_contacto").rut({
	// 	formatOn: 'keyup',
	//     minimumLength: 6,
	// 	validateOn: 'change'
	// });

	// $("#btn-ingresar").click(function() {
	// 	$("#completar_registro").submit();
	// });

	
	$("#completar_registro").validate({
        rules: {
            nombres_datos_contacto: {
                required: true,
                minlength: 4
            },
            apellidos_datos_contacto: {
                required: true,
				minlength: 6
            },
            rut_datos_contacto: {
                required: true,
				minlength: 8
            },
            telefono_datos_contacto: {
                required: true,
				minlength: 9,
				maxlength: 9
            },
            email_datos_contacto: {
                required: true,
                email: true
            }
        },
        messages: {
            nombres_datos_contacto: {
				required: "Por favor ingrese sus nombres",
				minlength: "Debe poseer por lo menos 4 caracteres"
			},
            apellidos_datos_contacto: {
				required: "Por favor ingrese sus apellidos",
				minlength: "Debe poseer por lo menos 6 caracteres"
			},
            rut_datos_contacto: {
				required: "Por favor ingrese su rut",
				minlength: "Debe poseer por lo menos 8 caracteres"
			},
            telefono_datos_contacto: "Por favor ingrese un teléfono válido",
            email_datos_contacto: "Por favor ingrese un email válido"
        },
        // highlight: function(element) {
        //     var $el = $(element);
        //     var $parent = $el.parents(".form-group");

        //     $el.addClass("es-invalido");

        //     // Select2 and Tagsinput
        //     if ($el.hasClass("select2-hidden-accessible") || $el.attr("data-role") === "tagsinput") {
        //         $el.parent().addClass("es-invalido");
        //     }
        // },
        // unhighlight: function(element) {
        //     $(element).parents(".form-group").find(".es-invalido").removeClass("es-invalido");
        // },
        submitHandler: function(form) {
			console.log('leyo form');
            $.ajax({
                type: "POST",
                url: "ws/cliente/completar_registro.php",
                data: $("#completar_registro").serialize(),
                success: function(data) {
                    if(data.success==1) {
						$(".toggle-block").toggle();
						swal.fire({
							title:"¡Modificado!",
							text: data.message,
							icon: "success",
							confirmButtonColor: '#3085d6',
							confirmButtonText: 'OK'
						}).then((result) => {
							if (result.isConfirmed) {
								console.log('modificado');
								// window.location = "index.php";
							}else{
								// window.location = "index.php";
							}
						})
                    }else {
						swal("Error", data.message, "error");
                    }
                	$("#registro").trigger("reset");
                },
                error: function(data){
                }
            });
        }
    });
});
</script>
<style>
.auth-wrapper .auth-content:not(.container) {
    width: 600px;
}
.es-invalido {
    border-color: #ff5252;
    padding-right: calc(1.5em + 1.25rem);
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.3125rem) center;
    background-size: calc(0.75em + 0.625rem) calc(0.75em + 0.625rem);
}
.error {
	color: red;
}
</style>

</body>

</html>
