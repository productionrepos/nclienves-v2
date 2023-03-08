<?php session_start();
if(isset($_SESSION['cliente'])) {
	header("Location: ./");
}
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
			<div class="row align-items-center text-center vertical-center-row">
				<div class="col-md-12">
					<div class="card-body">
						<h2 class="mb-3"><span class="text-c-blue">Spread</span></h2>
							Lamentamos que hayas olvidado tu contraseña, te ayudaremos a poder volver a utilizar nuestro servicio.
							<br>
							<br>
							<br>
						<form id="olvido_password">
						    <div class="form-group mb-3">
						        <label class="floating-label" for="email_cliente">Ingresa tu Email de registro</label>
						        <input type="email" class="form-control" name="email_cliente" id="email_cliente">
						    </div>
						    <button type="button" class="btn btn-spread btn-block mb-4" id="reiniciarPassword">Reiniciar contraseña</button>
						</form>
						<p class="mb-2 text-muted">¿La has recordado? <a href="./" class="f-w-400">Volver al ingreso</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
    include_once('./include/scriptsFooter.php')
?>

<script>
    $('#reiniciarPassword').on('click',function(){
        var email_cliente = document.getElementById('email_cliente').value
        console.log(email_cliente);
        $.ajax({
            type: "POST",
            url: "ws/cliente/olvido_password.php",
            data: JSON.stringify({"email_cliente":email_cliente}),
            dataType: 'json',
            success: function(data) {
                console.log(data);
            },
            error: function(data){
                console.log(data.responseText);
            }
        });
    })
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
