let php = "<"+"?"+"php"
            newRowAdd =
			'<section>'+
                        '<div class="row match-height">'+
                            '<div >'+
                                '<div class="card">'+
                                '<div class="card-header">'+
                                    '<h4 class="card-title">Formulario de envío(Datos destinatario)</h4>'+
                                '</div>'+
                                '<div class="card-content">'+
                                    '<div class="form-bodyenvio">'+
                                    '<form class="form form" id="toValdiateBulto">'+
                                        '<div class="form-body">'+
            '<div class="row">'+
				'<div class="col-6">'+
				'<div class="form-group">'+
					'<label for="gg">Nombre</label>'+
					'<input type="text" id="nombredestinatario" class="form-control" name="nombredestinatario" placeholder="Nombre Destinatario"/>'+
				'</div>'+
				'</div>'+
				'<div class="col-6">'+
					'<div class="form-group">'+
						'<label for="contact">Teléfono</label >'+
						'<input type="number" id="numtel" class="form-control" name="numtel" placeholder="Teléfono"/>'+
					'</div>'+
				'</div>'+
				'<div class="col-6">'+
				'<div class="form-group">'+
					'<label for="email-id">Dirección</label>'+
					'<input type="text" id="dir" class="form-control" name="dir" placeholder="Dirección"/>'+
				'</div>'+
				'</div>'+
				'<div class="col-6">'+
					'<div class="form-group">'+
						'<label for="Correo">Correo </label>'+
						'<input type="email" id="correo" class="form-control" name="correo" placeholder="Correo"/>'+
					'</div>'+
				'</div>'+
				'<div class="col-6">'+
					'<label for="select_region">Región </label>'+
					'<select name="select_region" class="form-select" id="select_region">'+
						'<option value=""></option>'+
					'</select>'+
				'</div>'+
				'<div class="col-6">'+
					'<label for="Comuna">Comuna</label>'+
					'<select name="select_comuna" class="form-select" id="select_comuna">'+
						'<option value=""></option>'+
					'</select>'+
				'</div>'+
				'<div class="col-6">'+
					'<div class="form-group">'+
						'<label for="Item">Item a enviar </label>'+
						'<input type="text" id="item" class="form-control" name="item" placeholder="Item"/>'+
					'</div>'+
				'</div>'+
				'<div class="col-3">'+
					'<div class="form-group">'+
						'<label for="Costo">Costo item </label>'+
						'<input type="text" id="cost" class="form-control" name="cost" placeholder="Precio Item"/>'+
					'</div>'+
				'</div>'+
				'<div class="col-3" style="text-align: center;">'+
					'<div class="form-group">'+
					'<label for="Costo"> Tipo envío </label>'+
						'<select name="select_type" class="form-select" id="select_type" value="">'+
							'<option value="1"></option>'+
							'<option value="1">mini</option>'+
							'<option value="2">medio</option>'+
						'</select>'+
					'</div>'+
					'<label id="tipoenvio">Rango de peso</label>'+
				'</div>'+
				'<div class="col-4 justify-content-start">'+
					'<button type="submit" class="submit btn btn-primary me-1 mb-1 col-12" value="Submit"> Enviar </button>'+
				'</div>'+
			'</div>'