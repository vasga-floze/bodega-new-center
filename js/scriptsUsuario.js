
$('#crearNuevo').click(function(){
    document.getElementById('editar').disabled=true;
    document.getElementById('enviar').disabled=false;
    $('#usuario').val('');
    $('#id').val('');
    $('#nombre').val('');
    let digitaCheckbox = document.getElementById('digita');
    let produceCheckbox = document.getElementById('produce');
    let empacaCheckbox=document.getElementById('empaca');
    let activoCheckbox=document.getElementById('activo');

    digitaCheckbox.checked=false;
    produceCheckbox.checked=false;
    empacaCheckbox.checked=false;
    activoCheckbox.checked=false;
})

let digita;
let produce;
let empaca;
let usuario;
let nombre;
let activo;
let id;
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
    
    if (usuario.length===0) {
        Swal.fire(
            'Problemas',
            'No puedes dejar el campo usuario',
            'question'
        )
        return;
    }
    if(nombre.length===0){
        Swal.fire(
            'Problemas',
            'No puedes dejar el campo usuario',
            'question'
        )
        return;
    }


    let data="usuario="+usuario+"&nombre="+nombre+"&digita="+digita+
              "&produce="+produce+"&empaca="+empaca;
    $.ajax({
        url:'controladorUsuario.php',
        type:'POST',
        data:data,

        success:function(response){
            if(!response==="Se registro exitosamente"){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'No se pudo guardar',
                   
                  })
                  return;
            }

            Swal.fire({
                
                icon: 'success',
                title: response,
                showConfirmButton: false,
                timer: 1500
              })
            setTimeout(() => {
                window.location.href="indexUsuario.php";
            }, 1500);
        },
        error:function (xhr,status,error){
            console.log(error);
        }
    })
    
})


const table= document.getElementById('datatablesSimple');
const modal=document.getElementById('modalForm');
const inputs=document.querySelectorAll('input');
let digitaCheckbox = document.getElementById('digita');
let produceCheckbox = document.getElementById('produce');
let empacaCheckbox=document.getElementById('empaca');
let activoCheckbox=document.getElementById('activo');

$('#datatablesSimple').click(function(e){
    if(e.target.classList.contains('editar')){
        document.getElementById('enviar').disabled=true;
        let data=e.target.parentElement.parentElement.children;
        let digita=data[3].textContent;
        let produce=data[4].textContent;
        let empaca=data[5].textContent;
        let activo=data[6].textContent;
        console.log(empaca);
        if(digita==1){
            digitaCheckbox.checked=true;
        }else{
            digitaCheckbox.checked=false;
        }
    
        if(empaca==1){
            empacaCheckbox.checked=true;
        }else{
            empacaCheckbox.checked=false;
        }
        if(produce==1){
            produceCheckbox.checked=true;
        }else{
            produceCheckbox.checked=false;
        }
        if(activo==1){
            activoCheckbox.checked=true;
    
        }else{
            activoCheckbox.checked=false;
        }
        fillData(data)
        $('#modalForm').modal('show')
        document.getElementById('editar').disabled=false;

    }
   
})
/*table.addEventListener('click',(e)=>{
    if (e.target.matches(".btn-primary ")) {
        e.stopPropagation();
        document.getElementById('enviar').disabled=true;
        let data=e.target.parentElement.parentElement.children;
        let digita=data[3].textContent;
        let produce=data[4].textContent;
        let empaca=data[5].textContent;
        let activo=data[6].textContent;
        console.log(empaca);
        if(digita==1){
            digitaCheckbox.checked=true;
        }else{
            digitaCheckbox.checked=false;
        }

        if(empaca==1){
            empacaCheckbox.checked=true;
        }else{
            empacaCheckbox.checked=false;
        }
        if(produce==1){
            produceCheckbox.checked=true;
        }else{
            produceCheckbox.checked=false;
        }
        if(activo==1){
            activoCheckbox.checked=true;

        }else{
            activoCheckbox.checked=false;
        }
    



    
        fillData(data)
        $('#modalForm').modal('show')
        document.getElementById('editar').disabled=false;
        
    }
    
})*/

$('#editar').click(function(){
    usuario=document.getElementById('usuario').value;
    id=document.getElementById('id').value;
    nombre=document.getElementById('nombre').value;
    let editar='EDITAR';
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

    if($('#activo').is(':checked')){
        activo=1;
    }else{
        activo=0;
    }
    console.log(usuario);
    console.log(nombre);
    let data="usuario="+usuario+"&nombre="+nombre+"&digita="+digita+
              "&produce="+produce+"&empaca="+empaca+"&editar="+editar+
              "&id="+id+"&activo"+activo;
    $.ajax({
        url:'controladorUsuario.php',
        type:'POST',
        data:data,

        success:function(response){
            console.log(response);
            if(!response==="Se edito correctamente"){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'No se pudo editar',
                   
                  })
                  return;
            }

            Swal.fire({
                
                icon: 'success',
                title: response,
                showConfirmButton: false,
                timer: 1500
              })

              setTimeout(() => {
                window.location.href="indexUsuario.php";
            }, 1500);
        },
        error:function (xhr,status,error){
            console.log(error);
        }
    })
    
})




const fillData=(data)=>{
    
    $('#id').val(data[0].textContent);
    $('#usuario').val(data[1].textContent)
    $('#nombre').val(data[2].textContent);
    
    
}
