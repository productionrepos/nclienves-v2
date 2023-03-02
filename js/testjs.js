$().ready(function(){
    $('#pressme').click(function(){
        $.ajax({
            url: "ws/bodega/newBodega.php",
            type: "POST",
            data: JSON.stringify(dataajax),
            success:function(resp){
                
                if(resp==="error"){
                    console.log("creado");
                    return false; 
                }
                else{
                    return false;
                }
            }
            
        })

        
    })
})