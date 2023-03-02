<?php 
	session_start();
	$id_cliente = $_SESSION['cliente']->id_cliente;
    include('ws/bd/dbconn.php');

    $conn = new bd();
	$conn->conectar();
	
    $query='Select Nombre_region as nombre,id_region as id from region where id_region in (6,7,8)';


	if($res = $conn->mysqli->query($query))
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


?>


<!doctype html>
<html lang="en">
<?php
	include_once('./include/head.php')
?>
<body>
<div id="app" >
        <!-- SideBar -->
        <?php
            include_once('../nclientesv2/include/sidebar.php');
        ?>
            



        <div id="main"  class="layout-navbar">
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

				<h2 style="color:green">GeeksforGeeks</h2>
				<strong> Adding and Deleting Input fields Dynamically</strong>
				<div >
						<div class="">
							<div class="col-lg-12">
								<div class="everyclass" id="row">
									<div class="input-group m-3">
										<section>
											<div class="row match-height">
												<div >
													<div class="card">
													<div class="card-header">
														<h4 class="card-title">Formulario de envío(Datos destinatario)</h4>
														<input type="text" class="form-control m-input" value="1"/>
													</div>
													<div class="card-content">
														<div class="form-bodyenvio">
															<form class="formvalidar form form" id="toValdiateBulto1">
																<div class="form-body">
																<div class="row justify-content-center">
																	<div class="col-lg-6 col-md-6 col-sm-9 col-12">
																		<div class="form-group">
																			<label for="gg">Nombre</label>
																			<input type="text" id="nombredestinatario" class="form-control" name="nombredestinatario[]" placeholder="Nombre Destinatario"/>
																		</div>
																	</div>
																	<div class="col-lg-6 col-md-6 col-sm-9 col-12">
																		<div class="form-group mb-3">
																			<label class="floating-label" for="rut_datos_contacto">RUT</label>
																			<input type="text" class="form-control" name="rut_datos_contacto[]" id="rut_datos_contacto" placeholder="">
																		</div>
																	</div>
																	<div class="col-lg-6 col-md-6 col-sm-9 col-12">
																		<div class="form-group">
																			<label for="contact">Teléfono</label >
																			<input type="number" id="numtel" class="form-control" name="numtel[]" placeholder="Teléfono"/>
																		</div>
																	</div>
																	<div class="col-lg-6 col-md-6 col-sm-9 col-12">
																	<div class="form-group">
																		<label for="email-id">Dirección</label>
																		<input type="text" id="dir" class="form-control" name="dir[]" placeholder="Dirección"/>
																	</div>
																	</div>
																	
																	<div class="col-lg-6 col-md-6 col-sm-9 col-12">
																		<div class="form-group">
																			<label for="Correo">Correo </label>
																			<input type="email" id="correo" class="form-control" name="correo[]" placeholder="Correo"/>
																		</div>
																	</div>
																	
																	<div class="col-lg-6 col-md-6 col-sm-9 col-12">
																		<label for="select_region">Región </label>
																		<select name="select_region[]" class="form-select" id="select_region">
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
																		<select name="select_comuna[]" class="form-select" id="select_comuna">
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
																			<button type="button" class="deploy btn btn-primary me-1 mb-1 col-12" id="deploy"> Continuar</button>
																		</div>
																	
																	</div>        
																	
																</div>
															</form>
															<div class="card" id="packagedata" style="margin-top:20px; padding:30px">
                                <div class="card-content">
                                    <form id="deployform">
                                        <div class="row formdisplay justify-content-center align-items-end " >
                                            <div class="col-lg-5 col-md-4 col-sm-8 col-12  align-items-end  mb-2" >
                                                <div class="form-group">
                                                    <label for="Item">Describe brevemente lo que estas enviando </label>
                                                    <input type="text" id="item" class="form-control" name="item[]" placeholder="producto"/>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-4 col-sm-8 col-12  align-items-end  mb-2">
                                                <div class="form-group">
                                                    <label for="Costo">Costo producto </label>
                                                    <input type="text" id="cost" class="form-control" name="cost[]" placeholder="Precio"/>
                                                </div>
                                            </div>
											<!-- Tomar valor de radiobutton -->
											<input style="display: none;" type="text" name="tipo[]" id="">

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
                                                            <label style="cursor: pointer;" name="mini[]">
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
                                                            <label style="cursor: pointer;" name="medium[]">
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
											<button class="btn btn-danger col-md-2 col-12"
													id="DeleteRow" type="button">
													<i class="bi bi-trash"></i>
												Borrar
											</button>
                                            <div class="col-4 ">
                                                <button type="submit" class="btn btn-primary  col-12" id="submitpedido" > Enviar </button>
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
		</section>
	</div>
</div>



								
			
								<div id="newinput"></div>
									<button id="rowAdder" type="button"
										class="btn btn-dark">
										<span class="bi bi-plus-square-dotted">
										</span> ADD
									</button>
									<button id="formValidate" type="submit"
										class="btn btn-dark">
										<span class="bi bi-plus-square-dotted">
										</span> VALIDAR
									</button>
									<button id="getarraydata" type="submit"
										class="btn btn-dark">
										<span class="bi bi-plus-square-dotted">
										</span> ARRAY
									</button>
							</div>
						</div>
					
				</div>

			</div>
			<button>get array data</button>

	<?php
		include_once('./include/footer.php')
	?>
	  <script src="assets/js/jquery-validation/jquery.validate.js"></script>
	  <script src="./js/rut.js"></script>

	
<script type="text/javascript">

	counter = 1;
	counterelementindex= 0 ;
	var countbodegas = <?php echo $counterbodegas;?>;
	var existbodegas=<?php echo $existbodegas;?>;
	var pass = true;

	$("input#rut_datos_contacto").rut({
		formatOn: 'keyup',
	    minimumLength: 6,
		validateOn: 'change'
	});


	$('#toValdiateBulto1').on('click','.deploy',function(){
		console.log(getpassStatus(0,"deploy"));
		return false
	})
	// getpassStatus(index,action)
	 $('#getarraydata').click(function() {
		let nombres = document.getElementsByName('nombredestinatario[]');
		let telefono = document.getElementsByName('numtel[]');
		let direccion = document.getElementsByName('dir[]');
		let correo = document.getElementsByName('correo[]');
		let region = document.getElementsByName('select_region[]');
		let comuna = document.getElementsByName('select_comuna[]');
		let item = document.getElementsByName('item[]');
		let costo = document.getElementsByName('cost[]');
		let rut = document.getElementsByName('rut_datos_contacto[]');
		// let tipo = document.getElementsByName('tipo[]');
		// let mini = document.getElementsByName('mini[]');
		// let medium = document.getElementsByName('medium[]');
		
		
		var inps = document.getElementsByName('nombredestinatario[]');
		let countererr = 0;
		
			for (var i = 0; i < inps.length; i++){
				let anombres= nombres[i] 
				let atelefono= telefono[i] 
				let adireccion= direccion[i] 
				let acorreo= correo[i] 
				let aregion= region[i] 
				let acomuna= comuna[i] 
				let aitem= item[i] 
				let acosto= costo[i] 
				let arut = rut[i]
				// let arrtipo= tipo[i] 
				// let amini = mini[i]
				// let medium = medium[i]
				
				console.log("nombres  =>"+anombres.value)
				console.log("telefono  =>"+atelefono.value)
				console.log("direccion  =>"+adireccion.value)
				console.log("correo  =>"+acorreo.value)
				console.log("region  =>"+aregion.value)
				console.log("comuna  =>"+acomuna.value)
				console.log("item  =>"+aitem.value)
				console.log("costo  =>"+acosto.value)
				console.log("RUT  =>"+arut.value)
				//Validar Nombre
				if(anombres.value == ""){
					console.log(nombres[i].classList.add("vlderr"));
					countererr=+1
				}
				else if(anombres.value.length <= 5){
					console.log(nombres[i].classList.add("vlderr"));
					countererr=+1
				}
				else{
					nombres[i].classList.remove("vlderr");
				}

				//VALIDAR RUT
				if(arut.value == ""){
					console.log(rut[i].classList.add("vlderr"));
					countererr=+1
				}
				else if(arut.value.length <= 11){
					console.log(rut[i].classList.add("vlderr"));
					countererr=+1
				}
				else{
					rut[i].classList.remove("vlderr");
				}

				//VALIDAR TELEFONO 
				if(atelefono.value == ""){
					telefono[i].classList.add("vlderr")
					console.log("telvacio");
					countererr=+1
				}
				else if(atelefono.value.length <= 5){
					telefono[i].classList.add("vlderr")
					countererr=+1
				}
				else{
					telefono[i].classList.remove("vlderr");
				}
				
				//VALIDAR DIRECCION
				if(adireccion.value == ""){
					direccion[i].classList.add("vlderr")
					countererr=+1
					console.log("dirvacio");
				}
				else if(adireccion.value.length <= 8){
					direccion[i].classList.add("vlderr")
					countererr=+1
				}
				else{
					direccion[i].classList.remove("vlderr");
				}
				// VALIDAR CORREO 
				if(acorreo.value == ""){
					correo[i].classList.add("vlderr")
					countererr=+1
				}
				else if(acorreo.value.length <= 8){
					correo[i].classList.add("vlderr")
					countererr=+1
				}
				else{
					correo[i].classList.remove("vlderr");
				}
				// VALIDAR REGION 
			
				if(aregion.value == ""){
					region[i].parentNode.classList.add('vlderr')
					countererr=+1
				}
				else{
					region[i].parentNode.classList.remove("vlderr");
				}
				// VALIDAR COMUNA 
				if(acomuna.value == ""){
					comuna[i].parentNode.classList.add('vlderr')
					countererr=+1
				}
				else{
					comuna[i].parentNode.classList.remove("vlderr");
				}

				// console.log("tipo  =>"+arrtipo.value)
				// console.log("RUT  =>"+amini.value)
				// console.log("RUT  =>"+amedium.value)
			}
		
		// if(countererr > 0 )
		// {
		// 	pass = false;
		// 	return pass
		// }
		// else{
		// 	return pass;
		// }
	})












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
						console.log("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa");
				
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
										location.reload()
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

			
				
		$(document).ready(function(){
			let options = {rules:{
						'nombredestinatario[]': {
							required : true,
							minlength:5
						},
						numtel:{
							required:true}
						}}

			$( "#toValdiateBulto" ).each( function() {
				$( this ).validate( options );
			} );
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
 
        $("#rowAdder").click(function () {

			if(counter < 10)
			{
				
				//v variable
				//e elemento
				
				// let vname = $('#toValidateBulto'+counter).find('.nombre').val()
				// let ename = $('#toValidateBulto1').find('.nombre')
				// let vlblnamerr = $('#toValidateBulto1').find('#lblnameerr')
				// console.log(vname);
				// if(vname == ""){
				// 	console.log("No pasas bandido");
				// 	ename.addClass('vlderr');
				// 	vlblnamerr.innerHTML = "Ingrese un nombre"
				// }else if(vname.length < 5){
				// 	console.log("");
				// 	ename.addClass('vlderr');
				// 	vlblnamerr.innerHTML = "Mínimo 5 caracteres"
				// }	
				// else{}
					counter ++
					console.log(counter);
					let index = counter - 1
					let clone = $('#row').clone()
					clone.find("#nombredestinatario").val("")

					// let nombre = clone.find(".nombre").attr('name')
					let form = clone.find(".formvalidar")
					let idform = form.attr('id')
					console.log(idform);
					let newformid = idform.replace( idform,"toValidateBulto"+counter)
					form.attr('id', newformid);
					//console.log(newformid);

					
					
					
					// clone.attr('name',name)
					// clone.find("#numtel").val("") 
					// let etelefono = find("#numtel1")
					// let idtel = etelefono.attr('id')
					// let newidtel = idtel.replace[idtel,'numtel'+counter]
					// etelefono.attr('id', newidtel);

					clone.find("#dir").val("") 
					clone.find("#correo").val("") 
					clone.find("#select_region").val("") 
					clone.find("#select_region").addClass("clonedreg") 
					clone.find("#select_comuna").val("") 
					clone.find("#select_comuna").addClass("clonedcom")
					clone.find("#cost").val("") 
					clone.find("#select_type").val("") 
					clone.find(".m-input").val(counter) 
					clone.appendTo("#newinput")
				
				

				

			}
			else{
				console.log("Limite de 10 alcanzado");
			}
        });
 
        $("body").on("click", "#DeleteRow", function () {
            
			if(counter<=1){
			 		console.log("no se pueden tener 0 registros")
				}
				else{
					counter--;
					console.log(counter);
					//console.log(counter);
					let actual = $(this).closest('#row').find('.m-input').val();
					// console.log(actual);
					let minput = document.getElementsByClassName('m-input')
					
					var nombreind = document.getElementsByClassName('nombre')
					var formgetid = document.getElementsByClassName("formvalidar")

					var telefonoids = document.getElementById("#numtel1")
					//let substarter = actual; 

					for(var index=0;index < minput.length;index++){

						if(index+1>actual){
							minput[index].value = index

							//console.log(nombreind);
							let indexfixed = index-1
							let indexplus = index+1

							//fix nombre name
							// let nombre = nombreind[index].getAttribute('name')
							// let nombrenew = nombre.replace('['+(index)+']', '['+indexfixed+']')  
							// //console.log("El nuevo nombre a Asignar es "+nombrenew);
							// nombreind[index].name = nombrenew;

							//FIX TELEFONO ID
							// let telefono = telefonoids[index].getAttribute('id')
							// let telefononew = telefono.replace('numtel'+index, '['+indexfixed+']')  
							// nombreind[index].name = nombrenew;
							//console.log("El nuevo nombre es: "+ nombrenew);
							let idform = formgetid[index].id;
						    console.log("EL ID ENTRANTE ES "+idform+" VAMOS EN EL INDEX "+index);
							console.log("SE INTENTARA REEMPLAZAR ESTO "+"toValidateBulto"+indexplus);
							console.log("SE INTENTARA REEMPLAZAR ESTO "+"toValidateBulto"+index);
							let newformid = idform.replace( "toValidateBulto"+indexplus, "toValidateBulto"+index )
							console.log("LA NUEVA CADENA DE FORM ES " + newformid);
							idform[index].id = newformid; 
						}	
					}
					$(this).parents("#row").remove();
				}
        })

		$(".clonedcom").on('click',function(){
			let idregion = $(this).closest("#row").find(".clonedreg").val();
			console.log(idregion);
			

		})


		$(".sel_comuna").on('click',function(){
			let idregion = $(this).closest("#row").find(".sel_region").val();
			console.log(idregion);
		})







</script>

		
</body>

</html>
