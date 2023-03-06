<?php 
	session_start();
	if(!isset($_SESSION['cliente'])){
        header('Location: index.php');
    }
	$id_cliente = $_SESSION['cliente']->id_cliente;
    include('ws/bd/dbconn.php');

    $conn = new bd();
	$conn->conectar();
	
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
                        where bo.id_cliente ='.$id_cliente.' and IsDelete = 0';

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
            include_once('./include/sidebar.php');
        ?>
            



        <div id="main"  class="layout-navbar">
            <?php
                include_once('./include/topbar.php');
            ?>
            <form class="form hidewhenbod1" id="formdir2">
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
                                        <input type="text" id="form_dir2" name="form_dir2" class="form-control"
                                            placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="form_numero" >Número</label>
                                        <input type="text" id="form_numero2" name="form_numero2" class="form-control"
                                            placeholder="Casa, Depto, Bodega, etc.">
                                    </div>
                                </div>
								<div class="col-md-3 col-lg-3 col-sm-6">
									<div class="form-group">
										<label for="first-name-column">Depto/casa/block etc.</label>
										<input type="text" id="form_detalledir2" name="form_detalledir2" class="form-control"
											placeholder="Casa, Depto, Bodega, etc.">
									</div>
								</div>
                                <div class="col-md-3 col-lg-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="first-name-column">Nombre</label>
                                        <input type="text" id="form_nombre2" name="form_nombre2" class="form-control"
                                            placeholder="Casa, Depto, Bodega, etc.">
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-8">
                                    <label for="Comuna">Región </label>
                                    <select class="form-select" name="select_regioncli2" id="select_regioncli2">
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
                                    <select class="form-select" name="select_comunacli2" id="select_comunacli2">
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
							<button class="btn btn-spread col-12" style="padding: 5px;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
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
																<a class="btn btn-spread" data-bs-toggle="collapse" data-bs-target="#collapseotherdir" 
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
																					<div class="col-md-3 col-lg-3 col-sm-6">
																						<div class="form-group">
																							<label for="first-name-column">Depto/casa/block etc.</label>
																							<input type="text" id="form_detalledir" name="form_detalledir" class="form-control"
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
																					<button  type="submit" class="submit btn btn-spread me-1 mb-1" value="Submit"> Usar esta dirección </button>
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
																			<input type="text" id="nombredestinatario" class="nombredestinatario form-control" name="nombredestinatario[]" placeholder="Nombre Destinatario"/>
																		</div>
																	</div>
																	<div class="col-lg-6 col-md-6 col-sm-9 col-12">
																		<div class="form-group mb-3">
																			<label class="floating-label" for="rut_datos_contacto">RUT</label>
																			<input type="text" class="form-control rut_datos_contacto" name="rut_datos_contacto[]" id="rut_datos_contacto" placeholder="">
																		</div>
																	</div>
																	<div class="col-lg-6 col-md-6 col-sm-9 col-12" >
																		<div class="form-group" id="selecttelefono">
																			<label for="contact">Teléfono</label >
																			<input type="number" id="numtel" class="form-control" name="numtel[]" placeholder="Teléfono"/>
																		</div>
																	</div>
																	<div class="col-lg-6 col-md-6 col-sm-9 col-12">
																		<div class="form-group">
																			<label for="email-id">Calle</label>
																			<input type="text" id="dir" class="form-control" name="dir[]" placeholder="Calle"/>
																		</div>
																	</div>
																	<div class="col-lg-6 col-md-6 col-sm-9 col-12">
																		<div class="form-group">
																			<label for="email-id">Número</label>
																			<input type="text" id="numerodir" class="form-control" name="numerodir[]" placeholder="Dirección"/>
																		</div>
																	</div>
																	<div class="col-lg-6 col-md-6 col-sm-9 col-12">
																		<div class="form-group">
																			<label for="email-id">Casa/Depto/Bloque etc.</label>
																			<input type="text" id="detalle" class="form-control" name="detalle[]" placeholder="Casa/Depto/Bloque etc."/>
																		</div>
																	</div>
																	
																	<div class="col-lg-6 col-md-6 col-sm-9 col-12">
																		<div class="form-group">
																			<label for="Correo">Correo </label>
																			<input type="email" id="correo" class="form-control" name="correo[]" placeholder="Correo"/>
																		</div>
																	</div>
																	
																	<div class="col-lg-6 col-md-6 col-sm-9 col-12" id="selectregion">
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
																	<div class="col-lg-6 col-md-6 col-sm-9 col-12" id="selectcomuna">
																		<label for="Comuna">Comuna</label>
																		<select name="select_comuna[]" class="select_comuna form-select" id="select_comuna">
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
                                                <div class="form-group novalue">
                                                    <label for="Item">Describe brevemente lo que estas enviando </label>
                                                    <input type="text" id="item" class="form-control" name="item[]" placeholder="producto"/>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-4 col-sm-8 col-12  align-items-end  mb-2">
                                                <div class="form-group novalue">
                                                    <label for="Costo">Costo producto </label>
                                                    <input type="text" id="cost" class="form-control" name="cost[]" placeholder="Precio"/>
                                                </div>
                                            </div>
											<!-- Toma valor de radiobutton -->
											<input type="text" style="display: none;" class="getcheckvalue novalue" name="tipo[]" id="getcheckvalue">

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
                                                            <label class="lblcheckmini" style="cursor: pointer;" name="mini[]">
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
                                                            <label class="lblcheckmedium" style="cursor: pointer;" name="medium[]">
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
										class="btn btn-spread">
										<span class="bi bi-plus-square-dotted">
										</span> ADD
									</button>
									<button id="formValidate" type="submit"
										class="btn btn-spread">
										<span class="bi bi-plus-square-dotted">
										</span> VALIDAR
									</button>
									<button 
										type="button" 
										class="btn btn-spread" 
										id="submitpedido"> 
										<span class="bi bi-plus-square-dotted">
											Enviar 
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
	id_bodega = 0;
	counter = 1;
	counterelementindex= 0 ;
	var selectcomuna = 0;
	var countbodegas = <?php echo $counterbodegas;?>;
	var existbodegas=<?php echo $existbodegas;?>;
	var pass = true;


	<?php 
        foreach($bodegas as $bodega):
            if($bodega->principal == 1):
    ?>

    var id_bodega=<?=$bodega->id?>;
    
    <?php
            endif;
        endforeach;
    ?>


	$("#select_region").on('change', function(){

		var idregion = this.value;
		var comuna = $(this).closest('#row').find(".select_comuna")
		var options = $(this).closest('#row').find(".select_comuna options")

		$.ajax({
			type: "POST",
			url: "ws/pedidos/getComunaByRegion.php",
			dataType: 'json',
			data: {
				"idregion" : idregion
			},
			success: function(data) {
				firstwarehouse = false
				// console.log(data);
				comuna.empty()
				comuna.append('<option></option>')
				$.each(data, function (key, value){
					if(selectcomuna == value.id){
						comuna.append('<option value="'+value.id+'">'+value.nombre+'</option>')
					}
					else{
						comuna.append('<option value="'+value.id+'">'+value.nombre+'</option>')
					}
				})
				
			},
				error: function(data){
			}
		})

	})

	$('.getcheckvalue').focusout(function(){
		let index = $(this).closest('section').find('.m-input').val()
		let subindex = index-1
		let tipo = document.getElementsByName('tipo[]');
		for (var i = subindex; i < tipo.length; i++){
			if(tipo[subindex].value == "" ){
				tipo[subindex].classList.add('vlderr')
			}
		}
	})

	$('lblcheckmedium').on('click',function(){
		let index = $(this).closest('section').find('.m-input').val()
		let subindex = index-1
		let tipo = document.getElementsByName('tipo[]');
		for (var i = subindex; i < nombres.length; i++){
			tipo[subindex].value == 2
		}
	 })

	$('.chcksize').change(function(){
		let index = $(this).closest('section').find('.m-input').val()
		console.log(index)
		let subindex = index-1		
		let valuetipo = $(this).closest('label').find('h4').text()
		console.log(valuetipo);
		let tipo = 0
		let inputs =  document.getElementsByName('tipo[]')
		if(valuetipo == "Mini"){
			tipo = 1
		}
		if(valuetipo == "Medium"){
			tipo = 2
		}
		
		for(i = subindex  ; i <inputs.length ;i++)
		{
			inputs[subindex].value = tipo
			inputs[subindex].classList.remove('vlderr')
		}
	})


	$('.deploy').on('click',function(){
		let index = $(this).closest('#row').find('.m-input').val()
		index--
		//console.log(getpassStatus(index,"tipo"));
		let response = getpassStatus(index,"tipo")

		console.log(response);
		if(response){
			$(this).closest('#row').find('.formdisplay').addClass('show')
		}
		else{
			Swal.fire({
				icon: 'error',
				title: 'Ups',
				text: 'Complete todos los campos antes de continuar'
			})
		}
	})

	

	$('#nombredestinatario').focusout(function(){

		let index = $(this).closest('#row').find('.m-input').val()
		let subindex = index-1
		let nombres = document.getElementsByName('nombredestinatario[]');
		for (var i = subindex; i < nombres.length; i++){
				
			if(nombres[subindex].value == ""){
				nombres[subindex].classList.add('vlderr')
			}else if (nombres[subindex].value.length < 5){
				nombres[subindex].classList.add('vlderr')
			}else{
				nombres[subindex].classList.remove('vlderr')
			}
		}
	})

	$('#rut_datos_contacto').focusout(function(){

		let index = $(this).closest('#row').find('.m-input').val()
		let subindex = index-1
		let ruts = document.getElementsByName('rut_datos_contacto[]');
		for (var i = subindex; i < ruts.length; i++){
				
			if(ruts[subindex].value == ""){
				ruts[subindex].classList.add('vlderr')
			}else if (ruts[subindex].value.length < 9){
				ruts[subindex].classList.add('vlderr')
			}else{
				ruts[subindex].classList.remove('vlderr')
			}
		}
	})

	$('#numtel').focusout(function(){
		let index = $(this).closest('#row').find('.m-input').val()
		let subindex = index-1
		let inputs = document.getElementsByName('numtel[]');
		for (var i = subindex; i < inputs.length; i++){
				
			if(inputs[subindex].value == ""){
				inputs[subindex].parentNode.classList.add('vlderr')
			}else if (inputs[subindex].value.length < 9){
				inputs[subindex].parentNode.classList.add('vlderr')
			}else if (inputs[subindex].value.length > 9){
				inputs[subindex].parentNode.classList.add('vlderr')
			}else{
				inputs[subindex].parentNode.classList.remove('vlderr')
			}
		}
	})

	$('#dir').focusout(function(){

		let index = $(this).closest('#row').find('.m-input').val()
		let subindex = index-1
		let inputs = document.getElementsByName('dir[]');
		for (var i = subindex; i < inputs.length; i++){
				
			if(inputs[subindex].value == ""){
				inputs[subindex].classList.add('vlderr')
			}else if (inputs[subindex].value.length < 5){
				inputs[subindex].classList.add('vlderr')
			}else{
				inputs[subindex].classList.remove('vlderr')
			}
		}
	})
	$('#numerodir').focusout(function(){

		let index = $(this).closest('#row').find('.m-input').val()
		let subindex = index-1
		let inputs = document.getElementsByName('numerodir[]');
		for (var i = subindex; i < inputs.length; i++){

			if(inputs[subindex].value == ""){
				inputs[subindex].classList.add('vlderr')
			}else if (inputs[subindex].value.length < 2){
				inputs[subindex].classList.add('vlderr')
			}else{
				inputs[subindex].classList.remove('vlderr')
			}
		}
	})
	$('#detalle').focusout(function(){

		let index = $(this).closest('#row').find('.m-input').val()
		let subindex = index-1
		let inputs = document.getElementsByName('detalle[]');
		for (var i = subindex; i < inputs.length; i++){
			if(inputs[subindex].value == ""){
				inputs[subindex].classList.add('vlderr')
			}else if (inputs[subindex].value.length < 2){
				inputs[subindex].classList.add('vlderr')
			}else{
				inputs[subindex].classList.remove('vlderr')
			}
		}
	})

	$('#correo').focusout(function(){

		let index = $(this).closest('#row').find('.m-input').val()
		let subindex = index-1
		let inputs = document.getElementsByName('correo[]');
		for (var i = subindex; i < inputs.length; i++){
				
			if(inputs[subindex].value == ""){
				inputs[subindex].classList.add('vlderr')
			}else if (inputs[subindex].value.length < 10){
				inputs[subindex].classList.add('vlderr')
			}else{
				inputs[subindex].classList.remove('vlderr')
			}
		}
	})

	$('#select_region').focusout(function(){

		let index = $(this).closest('#row').find('.m-input').val()
		let subindex = index-1
		let inputs = document.getElementsByName('select_region[]');
		for (var i = subindex; i < inputs.length; i++){
				
			if(inputs[subindex].value == ""){
				inputs[subindex].parentNode.classList.add('vlderr')
			}else{
				inputs[subindex].parentNode.classList.remove('vlderr')
			}
		}
	})
	$('#select_comuna').on('blur',function(){

		let index = $(this).closest('#row').find('.m-input').val()
		let subindex = index-1
		let inputs = document.getElementsByName('select_comuna[]');
		
		for (var i = subindex; i < inputs.length; i++){
				
			if(inputs[subindex].value == ""){
				inputs[subindex].parentNode.classList.add('vlderr')
			}else{
				inputs[subindex].parentNode.classList.remove('vlderr')
			}
		}
	})

	$('#item').focusout(function(){

		let index = $(this).closest('#row').find('.m-input').val()
		let subindex = index-1
		let inputs = document.getElementsByName('item[]');
		for (var i = subindex; i < inputs.length; i++){
				
			if(inputs[subindex].value == ""){
				inputs[subindex].classList.add('vlderr')
			}else if (inputs[subindex].value.length < 4){
				inputs[subindex].classList.add('vlderr')
			}else{
				inputs[subindex].classList.remove('vlderr')
			}
		}
	})
	$('#cost').focusout(function(){

		let index = $(this).closest('#row').find('.m-input').val()
		let subindex = index-1
		let inputs = document.getElementsByName('cost[]');
		for (var i = subindex; i < inputs.length; i++){
				
			if(inputs[subindex].value == ""){
				inputs[subindex].classList.add('vlderr')
			}else if (inputs[subindex].value < 1000 ||  inputs[subindex].value > 500000){
				inputs[subindex].classList.add('vlderr')
			}else{
				inputs[subindex].classList.remove('vlderr')
			}
		}
	})
	
		
	function getpassStatus(index,action){
		let nombres = document.getElementsByName('nombredestinatario[]');
		let telefono = document.getElementsByName('numtel[]');
		let direccion = document.getElementsByName('dir[]');
		let correo = document.getElementsByName('correo[]');
		let region = document.getElementsByName('select_region[]');
		let comuna = document.getElementsByName('select_comuna[]');
		let item = document.getElementsByName('item[]');
		let costo = document.getElementsByName('cost[]');
		let rut = document.getElementsByName('rut_datos_contacto[]');
		let tipo = document.getElementsByName('tipo[]');
		let detalle = document.getElementsByName('detalle[]');
		let numerodir = document.getElementsByName('numerodir[]');
		
		// let mini = document.getElementsByName('mini[]');
		// let medium = document.getElementsByName('medium[]');
		
		var inps = document.getElementsByName('nombredestinatario[]');
		pass =true
		let countererr = 0;
		if(action == "tipo" || action == "add"){
			for (var i = index; i < inps.length; i++){

				if(nombres[index].value == ""){
					nombres[index].classList.add("vlderr")
					countererr++
				}
				else if(nombres[index].value.length <= 5){
					nombres[index].classList.add("vlderr")
					countererr++
				}
				else{
					nombres[index].classList.remove("vlderr");
				}

				//VALIDAR RUT
				if(rut[index].value == ""){
					rut[index].classList.add("vlderr")
					countererr++
				}
				else if(rut[index].value.length <= 8){
					rut[index].classList.add("vlderr")
					countererr++
				}
				else{
					rut[index].classList.remove("vlderr");
				}

				//VALIDAR TELEFONO 
				if(telefono[index].value == ""){
					telefono[index].parentNode.classList.add("vlderr")
					console.log("telvacio");
					countererr++
				}
				else if(telefono[index].value.length <= 5){
					telefono[index].parentNode.classList.add("vlderr")
					countererr++
				}else if(telefono[index].value.length > 9){
					telefono[index].parentNode.classList.add("vlderr")
					countererr++
				}
				else{
					telefono[index].parentNode.classList.remove("vlderr");
				}
				
				//VALIDAR DIRECCION
				if(direccion[index].value == ""){
					direccion[index].classList.add("vlderr")
					countererr++
					console.log("dirvacio");
				}
				else if(direccion[index].value.length <= 5){
					direccion[index].classList.add("vlderr")
					countererr++
				}
				else{
					direccion[index].classList.remove("vlderr");
				}

				//VALIDAR NUMERO CALLE
				if(numerodir[index].value == ""){
					numerodir[index].classList.add("vlderr")
					countererr++
					console.log("dirvacio");
				}
				else if(numerodir[index].value.length <= 2){
					numerodir[index].classList.add("vlderr")
					countererr++
				}
				else{
					numerodir[index].classList.remove("vlderr");
				}

				//VALIDAR DETALLE
				if(detalle[index].value == ""){
					detalle[index].classList.add("vlderr")
					countererr++
					console.log("dirvacio");
				}
				else if(detalle[index].value.length <= 3){
					detalle[index].classList.add("vlderr")
					countererr++
				}
				else{
					detalle[index].classList.remove("vlderr");
				}

				// VALIDAR CORREO 
				if(correo[index].value == ""){
					correo[index].classList.add("vlderr")
					countererr++
				}
				else if(correo[index].value.length <= 8){
					correo[index].classList.add("vlderr")
					countererr++
				}
				else{
					correo[index].classList.remove("vlderr");
				}

				// VALIDAR REGION 
				if(region[index].value == ""){
					region[index].parentNode.classList.add('vlderr')
					countererr++
				}
				else{
					region[index].parentNode.classList.remove("vlderr");
				}

				// VALIDAR COMUNA 
				if(comuna[index].value == ""){
					comuna[index].parentNode.classList.add('vlderr')
					countererr++
				}
				else{
					comuna[index].parentNode.classList.remove("vlderr");
				}

				if(action == "add" || action == "send"){

					if(item[index].value == ""){
						item[index].classList.add('vlderr')
						countererr++
					}else if(item[index].value.length == ""){
						item[index].classList.add('vlderr')
						countererr++
					}else{
						item[index].classList.remove("vlderr");
					}

					if(costo[index].value == ""){
						costo[index].classList.add('vlderr')
						countererr++
					}else if(costo[index].value <1000 ||costo[index].value >500000){
						costo[index].classList.add('vlderr')
						countererr++
					}
					else{
						comuna[index].classList.remove("vlderr");
					}
					if(tipo[index].value == ""){
						tipo[index].classList.add('vlderr')
						countererr++
					}
					else{
						tipo[index].classList.remove("vlderr");
					}
				}
			}
		}
		//VALIDAR TODOS LOS FORMULARIOS ANTES DEL ENVIO 
		if(action == "send"){
			let subindex = counter - 1
			for (var i = 0; i <= subindex; i++){

				if(nombres[i].value == ""){
					nombres[i].classList.add("vlderr")
					countererr++
				}
				else if(nombres[i].value.length <= 5){
					nombres[i].classList.add("vlderr")
					countererr++
				}
				else{
					nombres[i].classList.remove("vlderr");
				}

				//VALIDAR RUT
				if(rut[i].value == ""){
					rut[i].classList.add("vlderr")
					countererr++
				}
				else if(rut[i].value.length <= 8){
					rut[i].classList.add("vlderr")
					countererr++
				}
				else{
					rut[i].classList.remove("vlderr");
				}

				//VALIDAR TELEFONO 
				if(telefono[i].value == ""){
					telefono[i].parentNode.classList.add("vlderr")
					console.log("telvacio");
					countererr++
				}
				else if(telefono[i].value.length <= 5){
					telefono[i].parentNode.classList.add("vlderr")
					countererr++
				}
				else{
					telefono[i].parentNode.classList.remove("vlderr");
				}
				
				//VALIDAR DIRECCION
				if(direccion[i].value == ""){
					direccion[i].classList.add("vlderr")
					countererr++
					console.log("dirvacio");
				}
				else if(direccion[i].value.length <= 5){
					direccion[i].classList.add("vlderr")
					countererr++
				}
				else{
					direccion[i].classList.remove("vlderr");
				}
				// VALIDAR CORREO 
				if(correo[i].value == ""){
					correo[i].classList.add("vlderr")
					countererr++
				}
				else if(correo[i].value.length <= 8){
					correo[i].classList.add("vlderr")
					countererr++
				}
				else{
					correo[i].classList.remove("vlderr");
				}

				// VALIDAR REGION 
				if(region[i].value == ""){
					region[i].parentNode.classList.add('vlderr')
					countererr++
				}
				else{
					region[i].parentNode.classList.remove("vlderr");
				}

				// VALIDAR COMUNA 
				if(comuna[i].value == ""){
					comuna[i].parentNode.classList.add('vlderr')
					countererr++
				}
				else{
					comuna[i].parentNode.classList.remove("vlderr");
				}
				if(item[i].value == ""){
					item[i].classList.add('vlderr')
					countererr++
				}else if(item[i].value.length == ""){
					item[i].classList.add('vlderr')
					countererr++
				}else{
					item[i].classList.remove("vlderr");
				}

				if(costo[i].value == ""){
					costo[i].classList.add('vlderr')
					countererr++
				}else if(costo[i].value <1000 ||costo[i].value >500000){
					costo[i].classList.add('vlderr')
					countererr++
				}
				else{
					comuna[i].classList.remove("vlderr");
				}
				if(tipo[i].value == ""){
					tipo[i].classList.add('vlderr')
					countererr++
				}
				else{
					tipo[i].classList.remove("vlderr");
				}
			}
		}
			
		if(action == "tipo"){	
			for(i=index ; i<=index ; i ++ ){
				if(nombres[index].classList.contains('vlderr')){
					countererr++
				}
				if(telefono[index].classList.contains('vlderr')){
					countererr++
				}
				if(direccion[index].classList.contains('vlderr')){
					countererr++
				}
				if(correo[index].classList.contains('vlderr')){
					countererr++
				}
				if(region[index].classList.contains('vlderr')){
					countererr++
				}
				if(comuna[index].classList.contains('vlderr')){
					countererr++
				}
				if(detalle[index].classList.contains('vlderr')){
					countererr++
				}
				if(numerodir[index].classList.contains('vlderr')){
					countererr++
				}
			}
				
		}




		if(action == "add"){	

			let tipo = document.getElementsByName('tipo[]');
			let mini = document.getElementsByName('mini[]');
			let medium = document.getElementsByName('tipo[]');

			for(i=index ; i<=index ; i ++ ){
				if(nombres[index].classList.contains('vlderr')){
					countererr++
				}
				if(telefono[index].classList.contains('vlderr')){
					countererr++
				}
				if(direccion[index].classList.contains('vlderr')){
					countererr++
				}
				if(correo[index].classList.contains('vlderr')){
					countererr++
				}
				if(region[index].classList.contains('vlderr')){
					countererr++
				}
				if(comuna[index].classList.contains('vlderr')){
					countererr++
				}
				if(item[index].classList.contains('vlderr')){
					countererr++
				}
				if(costo[index].classList.contains('vlderr')){
					countererr++
				}
				if(rut[index].classList.contains('vlderr')){
					countererr++
				}
				if(tipo[index].classList.contains('vlderr')){
					countererr++
				}
				if(detalle[index].classList.contains('vlderr')){
					countererr++
				}
				if(numerodir[index].classList.contains('vlderr')){
					countererr++
				}
			}
		}

		if(action == "send"){	

			let tipo = document.getElementsByName('tipo[]');
			let mini = document.getElementsByName('mini[]');
			let medium = document.getElementsByName('tipo[]');
			let subindex = counter-1

			for(i=0 ; i<= subindex ; i ++ ){
				if(nombres[i].classList.contains('vlderr')){
					countererr++
				}
				if(telefono[i].classList.contains('vlderr')){
					countererr++
				}
				if(direccion[i].classList.contains('vlderr')){
					countererr++
				}
				if(correo[i].classList.contains('vlderr')){
					countererr++
				}
				if(region[i].classList.contains('vlderr')){
					countererr++
				}
				if(comuna[i].classList.contains('vlderr')){
					countererr++
				}
				if(item[i].classList.contains('vlderr')){
					countererr++
				}
				if(costo[i].classList.contains('vlderr')){
					countererr++
				}
				if(rut[i].classList.contains('vlderr')){
					countererr++
				}
				if(tipo[index].classList.contains('vlderr')){
					countererr++
				}

				if(detalle[i].classList.contains('vlderr')){
					countererr++
				}
				if(numerodir[index].classList.contains('vlderr')){
					countererr++
				}
			}
		}
			console.log(countererr);
		if(countererr > 0 )
		{
			pass = false;
			return pass
		}
		else{
			return pass;
		}
	}

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
					},form_detalledir:{
						required :true,
						minlength:4
					},form_nombre:{
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
					},form_detalledir:{
						required :"Debe ingresar una descripción",
						minlength:"El largo mínimo es de 4 caracteres"
					},form_nombre:{
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
					try{
						let vdir = document.getElementById('form_dir').value;
						let vnumero = document.getElementById('form_numero').value;3
						let vdesc = document.getElementById('form_detalledir').value;
						let vnombre = document.getElementById('form_nombre').value;
						let vcomuna = document.getElementById('select_comunacli');
						let vcomunavalue = vcomuna.value;
						let vregion = document.getElementById('select_regioncli').value;

						let dataajax = {direccion : vdir,
										detalle : vdesc,
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
									//console.log(resp);
									location.reload()
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
		document.querySelectorAll("#usardir").forEach(el => {
            el.addEventListener("click", e => {
                let id = e.target.getAttribute("value");
                id_bodega = id;
                console.log(id_bodega);
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
							document.getElementById("resumemyData").innerHTML = ""
                            document.getElementById("resumemyData").innerHTML = ""+value.nombre+'| '+value.direccion+' '+value.numero+""
                        })
                        
                    },
                        error: function(data){
                    }
                })
            })
        })

		$('#formdir2').validate({
			rules:{
				form_dir2:{
					required :true,
					minlength : 4
				},
				form_numero2:{
					required: true
				},form_detalledir2:{
					required:true,
					minlength:2
				},
				form_nombre2:{
					required:true
				},
				form_comunacli2:{
					required:true
				},
				form_regioncli2:{
					reqiured:true
				}
			},
			messages:{
				form_dir2:{
					required :"Debe ingresar una dirección para el retiro",
					minlength : "La direccion debe tener al menos 4 caracteres"
				},
				form_numero2:{
					required: "Debe ingresar un numero de dirección",
				},form_detalledir2:{
					required:"Información necesaria",
					minlength:"Largo mínimo 2 caracteres"
				},
				form_nombre2:{
					required:"Ingrese un nombre para su dirección"
				},
				form_comunacli2:{
					required:"Seleccione una comuna"
				},
				form_regioncli2:{
					required:"Debe Seleccionar una región"
				}
			},
			submitHandler: function(form){
			
				try{
					let vdir = document.getElementById('form_dir2').value;
					let vnumero = document.getElementById('form_numero2').value;
					let vnombre = document.getElementById('form_nombre2').value;
					let vdetalle = document.getElementById('form_detalledir2').value
					let vcomuna = document.getElementById('select_comunacli2');
					let vcomunavalue = vcomuna.value; 
					let vregion = document.getElementById('select_regioncli2').value;

					let dataajax = {direccion : vdir,
									numero: vnumero,
									nombre : vnombre,
									detalle : vdetalle,
									comuna : vcomunavalue,
									region: vregion};
					
			
					//alert(JSON.stringify(dataajax));
							$.ajax({
							url: "ws/bodega/newBodega.php",
							type: "POST",
							dataType: 'json',
							data: JSON.stringify(dataajax),
							success:function(resp){
								// console.log(resp);
								// console.log(existbodegas);
								if(existbodegas){
									location.reload()
								}
								if(existbodegas == false){
									location.reload()
								}
							},error:function(resp){
								// console.log(resp);
									// console.log(existbodegas);
									if(existbodegas){
										location.reload()
									}
									if(existbodegas == false){
										location.reload()
									}
							}
							
						})
				}
				catch(error){
					// console.log(error);
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
					// console.log(data);

					$.each(data, function (key, value){
						let select = document.getElementById("select_comunacli");
						select.options[select.options.length] = new Option(value.nombre,value.id);
					})
					
				},
					error: function(data){
				}
			})
		})

		$("#select_regioncli2").on('change',function(){
			var idregion = this.value;
			var comuna = document.getElementById("select_comunacli2");
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
					// console.log(data);

					$.each(data, function (key, value){
						let select = document.getElementById("select_comunacli2");
						select.options[select.options.length] = new Option(value.nombre,value.id);
					})
					
				},
					error: function(data){
				}
			})
		})
 
	$("#rowAdder").click(function () {
		let subindex = counter-1
		if(counter < 10)
		{
			let response = getpassStatus(subindex,"add")
			response = true
			if(response == false){

			}else{
				counter ++
				let index = counter - 1
				let clone = $('#row').clone(true)
				console.log(clone.find('#clifreselect').addClass('clonedclifre'))

				clone.find("#nombredestinatario").val("")
				clone.find("#nombredestinatario").removeClass('vlderr')
				let form = clone.find(".formvalidar")
				let idform = form.attr('id')
				let newformid = idform.replace( idform,"toValidateBulto"+counter)
				form.attr('id', newformid);
				//Copiar y limpiar valores De original para el clon
				clone.find("#dir").val("") 
				clone.find("#numtel").val("") 
				clone.find("#correo").val("") 
				clone.find("#select_region").val("") 
				clone.find("#select_region").addClass("clonedreg") 
				clone.find("#select_comuna").val("") 
				clone.find("#select_comuna").addClass("clonedcom")
				clone.find("#cost").val("") 
				clone.find("#select_type").val("") 
				clone.find("#rut_datos_contacto").val("")
				clone.find("#numerodir").val("")  
				clone.find("#detalle").val("")
				clone.find("#item").val("") 
				clone.find("#getcheckvalue").val("")  
				clone.find("#getcheckvalue").val("")

				//LIMPIAR CLASE ERROR VLDERR PARA EL CLON
				clone.find("#dir").removeClass("vlderr") 
				clone.find("#selecttelefono").removeClass('vlderr')
				clone.find("#correo").removeClass("vlderr")  
				clone.find("#selectregion").removeClass('vlderr')
				clone.find("#selectcomuna").removeClass('vlderr')
				clone.find("#cost").removeClass("vlderr")  
				clone.find("#select_type").removeClass("vlderr")  
				clone.find("#rut_datos_contacto").removeClass("vlderr")  
				clone.find("#numerodir").removeClass("vlderr")  
				clone.find("#detalle").removeClass("vlderr")  
				clone.find("#item").removeClass("vlderr")  
				clone.find("#getcheckvalue").removeClass("vlderr")  
				clone.find("#useMedium").prop('checked',false)
				clone.find("#useMini").prop('checked',false)
				clone.find(".formdisplay ").removeClass('show')
				clone.find("#getcheckvalue").removeClass('vlderr')
				
				clone.find(".m-input").val(counter)
				clone.appendTo("#newinput")
			}
		}else{
			swal.fire("","Máximo de formularios alcanzado","error")
		}
	})

	$(submitpedido).on('click',function(){
		let response  = getpassStatus(0,"send")
		let nombres = document.getElementsByName('nombredestinatario[]');
		let telefono = document.getElementsByName('numtel[]');
		let direccion = document.getElementsByName('dir[]');
		let correo = document.getElementsByName('correo[]');
		let region = document.getElementsByName('select_region[]');
		let comuna = document.getElementsByName('select_comuna[]');
		let item = document.getElementsByName('item[]');
		let costo = document.getElementsByName('cost[]');
		let rut = document.getElementsByName('rut_datos_contacto[]');
		let tipo = document.getElementsByName('tipo[]');
		let detalle = document.getElementsByName('detalle[]');
		let numerodir = document.getElementsByName('numerodir[]');
		

		
		let arraydatos =[]
		if(response){
			Swal.fire({
				icon: 'success',
				title: 'Excelente',
				text: 'Deseas envíar estos paquetes?',
				confirmButtonColor : '#00a77f'
			}).then((result)=>{
				if(result.isConfirmed){

					for(i=0 ; i<counter ; i++){

						arraydatos[i] =[   {nombres: nombres[i].value,
											telefono: telefono[i].value,
											direccion: direccion[i].value,
											correo: correo[i].value,
											region: region[i].value,
											comuna: comuna[i].value,
											item: item[i].value,
											costo: costo[i].value,
											rut: rut[i].value,
											tipo: tipo[i].value,
											detalle:detalle[i].value,
											numerodir:numerodir[i].value}]
					}
					console.log(arraydatos);
					
					$.ajax({
						type: "POST",
						url: "ws/pedidos/newpedidomulti.php",
						dataType: 'json',
						data: JSON.stringify({"idbodega":id_bodega, 
												arraydatos
						}),success: function(data) {
							data.id_pedido
							window.location = "confirmarpedido.php?id_pedido="+data.id_pedido
						},error: function(data){
							data.id_pedido
							wwindow.location = "confirmarpedido.php?id_pedido="+data.id_pedido;
						}
					})
					
					
				// 	swal.fire({
				// 		icon: "success",
				// 		title: "Creado",
				// 		text:"Tú pedido ha sido creado exitosamente",
				// 		timer : 2000
				// 	})
				}
			})
		}else{
			Swal.fire({
				icon: 'error',
				title: 'Ups',
				text: 'Complete todos los campos antes de continuar'
			})
		}
	})

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
