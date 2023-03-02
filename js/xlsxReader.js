




class Excel{

    constructor(content){
        this.content = content;
    }
    header(){
        return new row(this.content[5]);
        //return this.content[0];
    }
    rows(){
        return new rowCollection(this.content.slice(6,this.content.length));
    }
}

class rowCollection{
    constructor(rows){ 
        this.rows = rows;
    }
  
    first(){
        return  new row(this.rows[0]);
    }
    get(index)
    {
        return new row(this.rows[index]);
    }
    count(){
        return this.rows.length;
    }
}

class row{
    constructor(row)
    {
        this.row = row;
    }
    trackid(){
        return this.row[2];
    }
    documentNumber(){
        return this.row[0];
    }
    cliente(){
        return this.row[3];
    }
    customrow(index){
        return this.row[index];
    }
}

class excelPrinter{

    static printexc(table, excel){

        const  tabla =document.getElementById(table);

        for (let i =0; i<= 30 ; i++){
            if (i==0 || i==2 || i==3)
            {
                let headtotable = excel.header().customrow(i);
                tabla.querySelector("thead>tr").innerHTML += `<td>${headtotable}</td>`
            }
        }
        const largo = excel.rows().count();
        for(let ind =0 ; ind <= largo; ind ++){

            const trackid = excel.rows().get(ind).trackid();
            const documentNumber = excel.rows().get(ind).documentNumber();
            const cliente = excel.rows().get(ind).cliente();
            tabla.querySelector("tbody").innerHTML += `<tr><td>${trackid}</td>
                                                        <td>${documentNumber}</td>
                                                        <td>${cliente}</td></tr>`
        }
    }
}
        



const excelInput = document.getElementById('excel-input');


excelInput.addEventListener('change',async function(){
    const content = await readXlsxFile(excelInput.files[0])
        $('tbody tr').remove();
        const excel = new Excel(content);
        let arrays = excel.header();
        let headcorrect = ["NOMBRE CLIENTE FINAL","DIRECCION ENTREGA","TELEFONO","Email cliente (opcion)","COMUNA ENTREGA","NOMBRE ITEM","COSTO ITEM","PAQUETE"]
        let rows = excel.rows()
        let length = excel.rows().count()
        counter = 0
        let ingresados = 0 
        let countererr = 0
        var arrayerr = []
        var filatabla = ""
        //console.log(rows);
        //CAPTURADORES DE ERORRES
        var nombre = ""
        var nomerr = ""        
        var direrr = ""
        var telerr = ""
        var corerr = ""
        var comerr = ""
        var deserr = ""
        var coserr = ""
        var typeerr = ""

        const heads = []
        let tdrow = []
        let incorrecto = 0
        for(i=0; i < 7;i++ )
        {
            heads.push(arrays.row[i]);

            //console.log(arrays.row[i])
            if(headcorrect[i] == heads[i]){
                //console.log("correcto")
            }
            else if(heads[i] == null){
                //console.log("incorrecto");
                incorrecto ++
            }else{
                //console.log("incorrecto");
                incorrecto ++
            }
        }
        //console.log(incorrecto);
        // 
        if(incorrecto>0)
        {
            //console.log("ARCHIVO NO CUMPLE CON EL FORMATO")
            let alerta = ("El formato del documento es incorrecto, por favor descargue nuestro excel tipo para continuar") 
            document.getElementById('comprobador').value = alerta
        }
        else{
            let array =rows.rows
                array.forEach(row => {
                    //console.log(row)
                    if(countererr>6)
                    {

                    }
                    else{
                        for(i=0 ; i<8 ; i++){
                            if(counter == 8){
                                counter = 0
                                countererr = 0
                                console.log("FILA 2 COUNTER = 0");
                                tdrow =[]
                                filatabla = ""
                                arrayerr = []
                                nomerr = ""
                                direrr = ""
                                telerr = ""
                                corerr = ""
                                comerr = ""
                                deserr = ""
                                coserr = ""
                                typeerr = ""
                            }
                            
                            console.log("EL CONTADOR VA EN"+counter)
                            console.log(row[i])
                            tdrow.push(row[i])

                            if(i==0 && row[i] == null)
                            {
                                nomerr = "Debe ingresar un nombre";
                                console.log(nomerr);
                                arrayerr.push(nomerr)
                                countererr++
                            }
                            else if(i==0 && row[i].length < 5){
                                nomerr = "El nombre debe tener al menos 5 caracteres";
                                arrayerr.push(nomerr)
                                //console.log(direrr);
                            }
                            else if(i==0 && nomerr == ""){
                                
                                arrayerr.push("")

                            }

                            if(i==1 && row[i] == null)
                            {
                                direrr = "Debe ingresar una dirección";
                                console.log(direrr);
                                arrayerr.push(direrr)
                                countererr++
                            }
                            else if(i==1 && row[i].length < 5){
                                direrr = "La dirección debe tener al menos 5 caracteres";
                                arrayerr.push(direrr)
                                //console.log(direrr);
                            }
                            else if(i==1 && direrr == ""){
                                
                                arrayerr.push("")

                            }

                            if(i==2 && row[i] == null)
                            {
                                telerr = "Debe ingresar un telefono";
                                console.log(telerr);
                                arrayerr.push(telerr)

                                countererr++
                            }
                            else if(i==2 && row[i].length <= 9){
                                telerr = "El teléfono debe tener al menos 9 caracteres";
                                arrayerr.push(telerr)
                            }
                            else if(i==2 && telerr == ""){
                                
                                arrayerr.push("")

                            }

                            if(i==3 && row[i] == null)
                            {
                                corerr = "Debe ingresar un correo";
                                console.log(corerr);
                                arrayerr.push(corerr)
                                countererr++
                            }else if(i==3 && row[i].length < 7){
                                corerr = "El correo debe tener al menos 7 caracteres";
                                arrayerr.push(corerr)
                            }
                            else if(i==3 && corerr == ""){
                                
                                arrayerr.push("")

                            }

                            if(i==4 && row[i] == null)
                            {
                                comerr = "Debe ingresar una comuna";
                                console.log(comerr);
                                arrayerr.push(comerr)
                                countererr++
                            }
                            else if(i==4 && comerr == ""){
                                
                                arrayerr.push("")

                            }


                            if(i==5 && row[i] == null)
                            {
                                deserr = "Debe ingresar una descripcion";
                                console.log(deserr);
                                arrayerr.push(deserr)
                                countererr++
                            }else if(i==5 && row[i].length < 3){
                                deserr = "La descripción debe tener al menos 3 caracteres";
                                arrayerr.push(deserr)
                            }
                            else if(i==5 && deserr == ""){
                                
                                arrayerr.push("")

                            }

                            if(i==6 && row[i] == null)
                            {
                                coserr = "Debe ingresar el costo";
                                console.log(coserr);
                                arrayerr.push(coserr)

                                countererr++
                            }else if(i==6 && row[i] > 500000){
                                coserr = "El valor declarado no puede superar los $500.000";
                                countererr ++
                                arrayerr.push(coserr)
                                console.log(coserr);
                            }
                            else if(i==6 && coserr == ""){
                                
                                arrayerr.push("")

                            }
                            console.log("PUSHEAR EL ERROR DE TIPO ENVIO");
                            if(i==7 && row[i] == null)
                            {
                                typeerr = "Debe ingresar el tipo de envio";
                                console.log(typeerr);
                                arrayerr.push(typeerr)
                                countererr++
                            }
                            else if(i==7 && typeerr == ""){
                                
                                arrayerr.push("")
                                console.log(typeerr);

                            }
                            counter ++
                            console.log("EL CONTADOR DE ERRORES VA EN "+countererr);

                            if(countererr == 8)
                            {
                                break
                            }
                            else if(counter == 8){
                                //console.log(json_error);
                                // console.log(tdrow);
                                 console.log(arrayerr);
                                let index =0
                                
                                tdrow.forEach(td=>{
                                    index++
                                    if(td == null){
                                        td = ""
                                    }
                                    
                                    if(index == 1){
                                        let arrerr = arrayerr[0]
                                        if(arrerr != ""){
                                            filatabla += `<td class="tdnom err" style="border:1px solid red" title="${arrerr}" contenteditable>`+ td +"</td>"
                                        }
                                        else{
                                            filatabla += `<td class="tdnom" contenteditable>`+ td +"</td>"
                                        }
                                    }
                                    if(index == 2){
                                        let arrerr = arrayerr[1]
                                        if(arrerr != ""){
                                            filatabla += `<td class="tddir err" style="border:1px solid red" title="${arrerr}" contenteditable>`+ td +"</td>"
                                        }
                                        else{
                                            filatabla += `<td class="tddir" contenteditable>`+ td +"</td>"
                                        }
                                    }
                                    if(index == 3){
                                        let arrerr = arrayerr[2]
                                        if(arrerr != ""){
                                            filatabla += `<td class="tdtel err" style="border:1px solid red" title="${arrerr}" contenteditable>`+ td +"</td>"
                                            console.log("SE CREO LA FILA DE TELEFONO ERRONEO");
                                        }
                                        else{
                                            filatabla += `<td class="tdtel" contenteditable>`+ td +"</td>"
                                        }
                                    }
                                    if(index == 4){
                                        let arrerr = arrayerr[3]
                                        if(arrerr != ""){
                                            filatabla += `<td class="tdcorr err" style="border:1px solid red" title="${arrerr}" contenteditable>`+ td +"</td>"
                                            console.log(arrerr);
                                            //console.log("SE CREO LA FILA DE CORREO ERRONEO");
                                        }
                                        else{
                                            filatabla += `<td class="tdcorr" contenteditable>`+ td +"</td>"
                                        }
                                    }
                                    if(index == 5){
                                        let comunas = ["","Algarrobo","Buin","Cabildo","Calera de Tango","Calle Larga","Cartagena","Casablanca","Catemu","Cerrillos","Cerro Navia","Colina","Conchalí","Concón","Curacavi","El Bosque","El Monte","El Quisco","El Tabo","Estación Central","Hijuelas","Huechuraba","Independencia","Isla de Maipo","La Calera","La Cisterna","La Cruz","La Florida","La Granja","La Ligua","La Pintana","La Reina","Lampa","Las Condes","Limache","Llay Llay","Lo Barnechea","Lo Espejo","Lo Prado","Los Andes","Macul","Maipú","María Pinto","Melipilla","Nogales","Ñuñoa","Olmué","Padre Hurtado","Paine","Panquehue","Papudo","Pedro Aguirre Cerda","Peñaflor","Peñalolén","Petorca","Pirque","Providencia","Puchuncaví","Pudahuel","Puente Alto","Putaendo","Quilicura","Quillota","Quilpué","Quinta Normal","Quintero","Recoleta","Renca","Rinconada","San Antonio","San Bernardo","San Esteban","San Felipe","San Joaquín","San José de Maipo","San Miguel","San Ramón","Santa María","Santiago","Santo Domingo","Talagante","Valparaíso","Villa Alemana","Viña del Mar","Vitacura","Zapallar"]
                                        let options =""
                                        let arrerr = arrayerr[4]
                                        //console.log(arrerr);
                                        if(arrerr != ""){
                                            comunas.forEach(comuna => {
                                                    options += "<option>"+comuna+"</option>"
                                                
                                            });
                                            filatabla += `<td class="tdcom err" style="border:1px solid red" title="Debe seleccionar una comuna"><select id="select_comuna">`+options+"</select></td>"
                                        }
                                        else{
                                            comunas.forEach(comuna => {
                                                if(comuna == td){
                                                    options += "<option selected>"+comuna+"</option>"
                                                }
                                                else{
                                                    options += "<option>"+comuna+"</option>"
                                                }
                                            });
                                            filatabla += `<td class="tdcom"><select id="select_comuna">`+options+"</select></td>"
                                        }
                                    }
                                    if(index == 6){
                                        let arrerr = arrayerr[5]
                                        if(arrerr != ""){
                                            filatabla += `<td class="tditem err" style="border:1px solid red" title="${arrerr}" contenteditable>`+ td +"</td>"
                                        }
                                        else{
                                            filatabla += `<td class="tditem" contenteditable>`+ td +"</td>"
                                        }
                                    }
                                    if(index == 7){
                                        let arrerr = arrayerr[6]
                                        if(arrerr != ""){
                                            filatabla += `<td class="tdval err" style="border:1px solid red" title="${arrerr}" contenteditable>`+ td +"</td>"
                                        }
                                        else{
                                            filatabla += `<td class="tdval" contenteditable>`+ td +"</td>"
                                        }
                                    }
                                    if(index == 8){
                                        let arrerr = arrayerr[7]
                                        let tipos = ["","Mini","Medium"]
                                        let options =""
                                        if(arrerr != ""){
                                            tipos.forEach(tipo => {
                                                    options += "<option>"+tipo+"</option>"
                                            });
                                            filatabla += `<td class="tdtype err" style="border:1px solid red" title="${arrerr}"><select id="select_type">`+options+"</select></td>"
                                        }
                                        else{
                                            tipos.forEach(tipo =>{
                                                if(tipo == td){
                                                    options += "<option selected>"+tipo+"</option>"
                                                }
                                                else{
                                                    options += "<option>"+tipo+"</option>"
                                                }
                                            });
                                            filatabla += `<td class="tdtype"><select id="select_type">`+options+"</select></td>"
                                            
                                        }
                                    }
                                })

                                // console.log("------------------------");
                                // console.log("VER ARREGLO DE DATOS PARA TABLA");
                                // console.log(filatabla);
                                // console.log("-------------------------");

                                $('#excel_table > tbody:last').append("<tr>"+ filatabla +'<td> <button id="btnEliminar" class="btn btn-danger" data-bs-toggle="tooltip" title="Eliminar"> <i class="fa-solid fa-trash"></i></button></td>' +"<tr>");
                            }
                        }
                    }
                })
                console.log("HE SALIDO DEL INFIERNO => DEMASIADOS ERRORES");
               

                
        }

    //console.log(excelPrinter.printexc('excel-table',excel));
    //console.log(excel.header());
    // console.log(excel.header().trackid());
    // console.log(excel.header().documentNumber());
    // console.log(excel.header().cliente());
    //console.log(excel.header().customrow(3));
    //console.log(excel.header());
    // console.log(excel.rows().first());
    // console.log(excel.rows().get(35).trackid());
    // console.log(excel.rows().count());
    // console.log(excel.rows().first().cliente());
    // console.log(excel.rows().first().trackid());
    // console.log(excel.rows().first().documentNumber());
    // for(i=0 ; i<100 ;i ++){
    //     console.log(excel.rows().get(i).trackid())
    // }

})




