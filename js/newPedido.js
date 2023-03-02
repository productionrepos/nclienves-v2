
var tipo = 0;
    $().ready(function(){
        $('#toValdiateBulto').validate({
            rules:{
                nombredestinatario:{
                    required :true,
                    minlength:4
                },
                dir:{
                    required :true,
                    minlength :8
                },
                numtel:{
                    required: true,
                    minlength:9
                },
                correo:{
                    required:true,
                    email:true
                },
                select_region:{
                    required:true
                },
                select_comuna:{
                    required:true
                },
                rut_datos_contacto:{
                    required: true,
                    minlength:11
                }
                // ,
                // item:{
                //     required : true
                // },
                // cost:{
                //     required:true
                // },
                // select_type:{
                //     required:true
                // }
            },
            messages:{
                nombredestinatario:{
                    required : "Debe ingresar un destinatario",
                    minlength : "El nombre debe tener al menos 4 caracteres"
                },
                dir:{
                    required :"Debe ingresar una direccion valida",
                    minlength :"la direccióndebe tener al menos 8 caracteres"
                },
                numtel:{
                    required: "Debe ingresar el télefono del destinatario",
                    minlength:"El teléfono debe tener al menos 9 números"
                },
                correo:{
                    required:"Debe ingresar un correo",
                    email:"Formato de correo no valido ej:'ejemplo@correo.com'"
                },
                select_region:{
                    required:"Debe seleccionar una Región",
                },
                select_comuna:{
                    required:"Debe ingresar la comuna de destino"
                },
                rut_datos_contacto:{
                    required: "Debe ingresar un RUT",
                    minlength:"RUT invalido, por favor verificar info."
                }
                // ,
                // item:{
                //     required : "Ingrese el objeto que se va a despachar"
                // },
                // cost:{
                //     required:"Ingrese costo del Item a despachar"
                // },
                // select_type:{
                //     required:"Debe Seleccionar el tipo de envío"
                // }
            },
            submitHandler: function(form){ 
                $('.formdisplay').addClass('show');
                
                    
            }   
        })
        
        $('#deployform').validate({

            rules:{
                item:{
                    required : true
                },
                cost:{
                    required:true
                },
                Usar:{
                    required:true
                }
            },
            messages:{
                item:{
                    required : "Ingrese el objeto que se va a despachar"
                },
                cost:{
                    required:"Ingrese costo del Item a despachar"
                },
                Usar:{
                    required:""
                }
            }, errorPlacement: function(error, element) {
                if (element.type == 'radio') {
                    error.appendTo(element.parent());
                }
                else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form,event){ 
                event.preventDefault()

                if (document.getElementById('useMini').checked) {
                    tipo = 1
                }
                if (document.getElementById('useMedium').checked) {
                    tipo =2;
                }
                
               //console.log(tipo);
                
               try{
                  

                    let nombredest = document.getElementById("nombredestinatario").value;
                    let vdir = document.getElementById('dir').value;
                    let vnumero = document.getElementById('numtel').value;
                    let vcorreo = document.getElementById('correo').value;
                    let vitem = document.getElementById('item').value;
                    let vcosto = document.getElementById('cost').value;
                    // let vidpaquete = document.getElementById('select_type').value;
                    let vcomuna = document.getElementById('select_comuna').value;
                    let vergion = document.getElementById('select_region').value;
                    let vrut = document.getElementById('rut_datos_contacto').value;
                  
        
                    let dataajax = {
                        nombre: nombredest,
                        direccion: vdir,
                        telefono : vnumero,
                        correo : vcorreo,
                        item: vitem,
                        costo: vcosto,
                        idpaquete : tipo,
                        comuna : vcomuna,
                        region : vergion,
                        idbodega : id_bodega,
                        rut : vrut
                    };

                    console.log("EL CREAR CLIENTE ES :"+crearcliente.checked);
                    if(crearcliente.checked){
                        $.ajax({
                            url: "ws/cliente/newclienteFrecuente.php",
                            type: "POST",
                            data: JSON.stringify(dataajax)
                            ,success:function(resp){
                                console.log(resp);
                            },
                            error : function(resp){
                                    console.log(resp);
                                    return false;
                                }
                        });
                    }else{
                        console.log("Cliente No guardado");
                    }
                    
                    
                    //alert(JSON.stringify(dataajax));
                            $.ajax({
                            url: "ws/pedidos/newPedido.php",
                            type: "POST",
                            data: JSON.stringify(dataajax),
                            success:function(resp){
                                console.log(resp);
                                swal.fire({
                                    title : "Hecho",
                                    text : "Tú pedido fue creado exitosamente!",
                                    icon: "success",
                                    showConfirmButton: false,
                                    type : "success",
                                    timer : 2500
                                    
                                }).then(function() {
                                    document.getElementById("deployform").reset();
                                    document.getElementById("toValdiateBulto").reset();
                                    window.location = "confirmarpedido.php?id_pedido="+resp;
                                })
                            },
                            error : function(resp){
                                    return false;

                                }
                        });
                       
                }
                catch(error){
                    console.log(error);
                    return false;
                }   
             }
           
                    
        })
    })