
//SCRIPT DESGLOSE 
let message='';
$("#desglosar").click(function(){
    console.log("Desglosar");
    const codigoDesglose=document.getElementById('codigoDesglose').value;
    const valorDesglose=document.getElementById('valorDesglose').value;
    let data="codigoDesglose="+codigoDesglose+"&valorDesglose="+valorDesglose;
    $.ajax({
        url: 'controladorDesglose.php',
        type: 'POST',
        data: data,

        success: function(response){
            let data=JSON.parse(response);
            let success=data.success;
            message=data.message;
            //console.log(message);
            if(success==="false"){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: message,
                    
                  })
                return;
            }
            Swal.fire({
             
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 1500
              })

            setTimeout(() => {
                
            }, timeout);
        },

        error:function (xhr,status,error){

        }
    })
})