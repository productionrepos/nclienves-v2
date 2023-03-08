<?php session_start();
if(isset($_SESSION['cliente'])) {
	header("Location: ./");
}

$token_cliente = filter_input(INPUT_GET, "token", FILTER_SANITIZE_STRING);
if(strlen($token_cliente)!=32) {
	echo 'Enlace inválido';
	exit();
}

require_once('./ws/bd/dbconn.php');
$conexion = new bd();
$conexion->conectar();

if($datos = $conexion->mysqli->query("SELECT * FROM cliente
							LEFT JOIN datos_contacto ON (cliente.id_cliente=datos_contacto.id_cliente)
							WHERE token_cliente='$token_cliente' AND verificado_cliente=1")) {
	if($datos->num_rows==1) {
		$datos_cliente = $datos->fetch_object();
		$nombre = ucfirst(strtolower(explode(" ", $datos_cliente->nombres_datos_contacto)[0]));
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
    $head = 'Spread | Reinicio contraseña';
    include_once('./include/head.php');
    ?>

<div class="auth-wrapper d-flex align-items-center justify-content-center">
	<div class="auth-content">
		<div class="card">
			<div class="row align-items-center text-center">
				<div class="col-md-12">
					<div class="card-body">
						<h2 class="mb-3"><span class="text-c-blue">Spread</span></h2>
							<?=$nombre?> hemos validado tus datos, ahora puedes reiniciar tu contraseña.
							<br>
							<br>
							<br>
						<form id="cambiar_password">
							<input type="hidden" name="token_cliente" id="token_cliente" value="<?=$token_cliente?>" />
						    <div class="form-group mb-3">
								<label class="floating-label" for="password_cliente">Ingresa una nueva contraseña</label>
								<input type="password" class="form-control" name="password_cliente" id="password_cliente">
							</div>
							<div class="form-group mb-3">
								<label class="floating-label" for="password_cliente2">Confirma tu nueva contraseña</label>
								<input type="password" class="form-control" name="password_cliente2" id="password_cliente2">
							</div>
						    <button type="submit" class="btn btn-spread btn-block mb-4">Reiniciar contraseña</button>
						</form>
						<p class="mb-2 text-muted">¿La has recordado? <a href="./" class="f-w-400">Volver al ingreso</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- [ auth-signup ] end -->

<!-- Required Js -->
<!-- <script src="assets/js/jquery.js"></script>
<script src="assets/js/plugins/bootstrap.min.js"></script>
<script src="assets/js/ripple.js"></script>
<script src="assets/js/pcoded.min.js"></script>
<script src="assets/js/rut.js"></script>
<script src="assets/js/jquery-validation/jquery.validate.js"></script>
<script src="assets/js/plugins/sweetalert.min.js"></script> -->

<script src="./assets/extensions/jquery/jquery.js"></script>
<script src="./assets/js/jquery-validation/jquery.validate.js"></script>
<script src="./assets/extensions/sweetalert2/sweetalert2.min.js"></script>


<script>
$(document).ready(function(){

	
	$("#cambiar_password").validate({
        rules: {
            password_cliente: {
                required: true,
				minlength: 6
            },
            password_cliente2: {
                required: true,
				minlength: 6,
				equalTo: "#password_cliente"
            }
        },
        messages: {
            password_cliente: {
				required: "Por favor ingrese una contraseña",
				minlength: "Debe poseer por lo menos 6 caracteres"
			},
            password_cliente2: {
				required: "Por favor ingrese una contraseña",
				minlength: "Debe poseer por lo menos 6 caracteres",
				equalTo: "Las contraseñas no coinciden"
			}
        },
        highlight: function(element) {
            var $el = $(element);
            var $parent = $el.parents(".form-group");

            $el.addClass("es-invalido");

            if ($el.hasClass("select2-hidden-accessible") || $el.attr("data-role") === "tagsinput") {
                $el.parent().addClass("es-invalido");
            }
        },
        unhighlight: function(element) {
            $(element).parents(".form-group").find(".es-invalido").removeClass("es-invalido");
        },
        submitHandler: function(form) {
        	$(':input[type="submit"]').prop('disabled', true);
            $.ajax({
                type: "POST",
                url: "ws/cliente/cambiar_password.php",
                data: $("#cambiar_password").serialize(),
                success: function(data) {
					console.log(data);
                    if(data.success==1) {
                            $(".toggle-block").toggle();
                            swal.fire({
                                title:"¡Clave Cambiada con exito!",
                                text: data.message,
                                icon: "success",
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location = "index.php";
                                }else{
                                    window.location = "index.php";
								}
                            })
                    }
                    else {
						swal("Error", data.message, "error");
                    }
        			$(':input[type="submit"]').prop('disabled', false);
                	$("#cambiar_password").trigger("reset");
                },
                error: function(data){
					console.log(data);
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
