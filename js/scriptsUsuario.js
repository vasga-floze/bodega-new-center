let digita;
let produce;
let empaca;
let usuario;
let nombre;
$('#enviar').click(function(){

    usuario=document.getElementById('usuario').value;
    nombre=document.getElementById('nombre').value;
    if($('#digita').is(':checked')){
        digita=1;
    }else{
        digita=0;
    }

    if($('#produce').is(':checked')){
        produce=1;
    }else{
        produce=0;
    }

    if($('#empaca').is(':checked')){
        empaca=1;
    }else{
        empaca=0;
    }
    console.log(usuario);
    console.log(nombre);
    let data="usuario="+usuario+"&nombre="+nombre+"&digita="+digita+
              "&produce="+produce+"&empaca="+empaca;
    $.ajax({
        url:'controladorUsuario.php',
        type:'POST',
        data:data,

        success:function(response){
            console.log(response);
        },
        error:function (xhr,status,error){
            console.log(error);
        }
    })
    
})
