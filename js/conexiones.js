
$( '#respuesta' ).select2( {
    theme: 'bootstrap-5'
} );
//FUNCIONES DEL LOGIN
$('#btnIngresar').click(function(){


    //console.log("ESTAS TRATATANDO DE ENTRAR AL LOGIN")
    let usuario= document.getElementById('usuario').value;
    let password=document.getElementById('contrasenia').value;
    let compania=$('#respuesta').val();

    if(usuario.length==0){
        toastr["error"]("No puedes dejar el campo usuario vacio");
        return;
    }
    if(password.length==0){
        toastr["error"]("No puedes dejar el campo contrasenia vacio");
        return;
    }

  

    //console.log("usuario"+usuario+"password"+ password);
    let ruta="usuario="+usuario+"&password="+password
    $.ajax({
        url:'controladorLogin.php',
        type:'POST',
        data:ruta
    }).done(function(res){

       console.log(res);
       console.log(ruta);
        //let data=JSON.parse(res);
        if(res==1){

            Swal.fire({
                //position: 'top-end',
                icon: 'success',
                title: 'Registro exitoso',
            
                showConfirmButton: false,
                timer: 1500
              }).then(()=>{
                setTimeout(()=>{
                    //window.location.replace("indexDashboard.php");
                    window.location.href="indexDashboard.php";
                    console.log("Index dashboard");
                },100);
              })
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Algo sucedio',
                text: res,
                footer: 'Intenta registrarte de nuevo o pide ayuda al administrador del sistema'
              })
        }
    }).fail(function(){
   

    }).always(function(){

    });
   
});

