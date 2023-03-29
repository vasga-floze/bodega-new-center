

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});


$( '#ubicacion' ).select2( {
    theme: 'bootstrap-5'
} );

$( '#empaque' ).select2( {
    theme: 'bootstrap-5'
} );
$( '#empaqueDetalle' ).select2( {
    theme: 'bootstrap-5'
} );
$( '#bodega' ).select2( {
    theme: 'bootstrap-5'
} );
$( '#producido' ).select2( {
    theme: "bootstrap-5",
    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
    closeOnSelect: false,
} );
$( '#empacado' ).select2( {
    theme: "bootstrap-5",
    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
    closeOnSelect: false,
} );


//FUNCION INDEX PRODUCCION 

/* A jQuery click event handler. */
$('#enviar').click(function(){
    var ubicacion=document.getElementById('ubicacion').value;
    var ruta="ubicacion="+ubicacion;
    let bandera;

    if(ubicacion.trim()==""){
        bandera=false;
    }else{
        bandera=true;
    }

    $.ajax({
        url: 'controladorUbicacion.php',
        type: 'POST',
        data: ruta,
    })
    .done(function(res){
        let url= "indexUbicacion.php";
        if(res == true && bandera!=false){
            setTimeout(function(){
                $(window).attr('location','indexUbicacion.php')
            },
            3000)
            toastr["success"]("Registro Exitoso");
            
        }else{
            toastr["error"]("Ocurrio un error");

        }
    
        
        
    })
    .fail(function(){
       
    })
    .always(function(){
        
    });
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "3000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      }
});

$('#enviarEmpaque').click(function(){
    var empaque=document.getElementById('empaque').value;
    var ruta="empaque="+empaque;
    let bandera;

    if(empaque.trim()==""){
        bandera=false;
    }else{
        bandera=true;
    }

    $.ajax({
        url: 'controladorTipoEmpaque.php',
        type: 'POST',
        data: ruta,
    })
    .done(function(res){
        //let url= "indexUbicacion.php";
        if(res == true && bandera!=false){
            setTimeout(function(){
                $(window).attr('location','indexTipoEmpaque.php')
            },
            1000)
            toastr["success"]("Registro Exitoso");
            
        }else{
            toastr["error"]("Ocurrio un error");

        }
    
        
        
    })
    .fail(function(){
       
    })
    .always(function(){
        
    });
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "100",
        "hideDuration": "1000",
        "timeOut": "1000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      }
});

$('#enviarTransaccion').click(function(){
    var transaccion=document.getElementById('transaccion').value;
    var ruta="transaccion="+transaccion;
    let bandera;

    if(transaccion.trim()==""){
        bandera=false;
    }else{
        bandera=true;
    }

    $.ajax({
        url: 'controladorTipoTransaccion.php',
        type: 'POST',
        data: ruta,
    })
    .done(function(res){
        //let url= "indexUbicacion.php";
        if(res == true && bandera!=false){
            setTimeout(function(){
                $(window).attr('location','indexTipoTransaccion.php')
            },
            1000)
            toastr["success"]("Registro Exitoso");
            
        }else{
            toastr["error"]("Ocurrio un error");

        }
    
        
        
    })
    .fail(function(){
       
    })
    .always(function(){
        
    });
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "100",
        "hideDuration": "1000",
        "timeOut": "1000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      }
});

$(document).ready(function(){
$('#ropa').click(function(){
    var unidades=document.getElementById('unidades').value;
    var fecha=document.getElementById('fechaProduccion').value;
    var libras=document.getElementById('libras').value;
    var observaciones=document.getElementById('observaciones').value;
    var ubicacion=document.getElementById('ubicacion').value;
    var empaque=document.getElementById('empaque').value;
    var usuario=document.getElementById('usuario').value;
    var bodega=document.getElementById('bodega').value;
    var tipo=document.getElementById('tipoRegistro').value;
    //var empaque=$('#empaque').val();
    var empacado=$('#empacado').val();
    var producido=$('#producido').val();
    

    //alert(empaque);
    

    /*var transaccion=document.getElementById('transaccion').value;
    var ruta="transaccion="+transaccion;
    let bandera;*/
    var ruta="unidades="+unidades+"&fecha="+fecha+"&libras="+libras+"&ubicacion="+
                ubicacion+"&usuario="+usuario+"&bodega="+
                bodega+"&empaque="+empaque+"&empacado="+empacado+"&producido="+producido+
                "&observaciones="+observaciones+"&tipoRegistro="+tipo;

    /*if(transaccion.trim()==""){
        bandera=false;
    }else{
        bandera=true;
    }*/

    $.ajax({
        url: 'controladorProduccion.php',
        type: 'POST',
        data: ruta,
    })
    .done(function(res){
        //let url= "indexUbicacion.php";
        /*if(res == true && bandera!=false){
            setTimeout(function(){
                $(window).attr('location','indexTipoTransaccion.php')
            },
            1000)
            toastr["success"]("Registro Exitoso");
            
        }else{
            toastr["error"]("Ocurrio un error");

        }*/

        console.log(res);
    
        
        
    })
    .fail(function(){
       
    })
    .always(function(){
        
    });
    /*toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "100",
        "hideDuration": "1000",
        "timeOut": "1000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      }*/
});

//funciones tablas



});

function clickear(){
    $(document).ready(function(){
        
        
        var descripcion=document.getElementById('descripcion').value;
        var ropa=document.getElementById('ropa').value;
        var codigo=document.getElementById('codigo').value;
        var ruta="descripcion="+descripcion+"&ropa="+ropa+"&codigo="+codigo;
        //let bandera;
    
        /*if(transaccion.trim()==""){
            bandera=false;
        }else{
            bandera=true;
        }*/
    
        $.ajax({
            url: 'controladorComplemento.php',
            type: 'POST',
            data: ruta,
        })
        .done(function(res){
            //let url= "indexUbicacion.php";
           console.log(res);
        
            
            
        })
        .fail(function(){
           
        })
        .always(function(){
            
        });
        
        
        
    });

}







$('#finalizar').click(function(){
    clickear();
    mostrarTabla();
});


  




/* Getting the table and the inputs and then adding an event listener to the table. When the table is
clicked, it will get the data from the table and fill the inputs with the data. */
const tabla = document.getElementById('datatablesSimple')
const inputs = document.querySelectorAll('#descripcion')
const inputs2 = document.querySelectorAll('#codigo')
//const boton = document.querySelector("#miBoton")
tabla.addEventListener("click", function(evento){
	// Aquí todo el código que se ejecuta cuando se da click al botón
    agregarPaquete(evento);
    //console.log("asdsdsd");  
   
});

function agregarPaquete(e){
    e.stopPropagation();
    let data= e.target.parentElement.parentElement.children;
    fillData(data); 
    fillData2(data); 
    $('#exampleModal').modal('hide')  
}


const fillData = (data)=>{
    for(let index of inputs){
        //console.log(index);
        index.value=data[1].textContent
    }
}
const fillData2 = (data)=>{
    for(let index of inputs2){
        //console.log(index);
        index.value=data[0].textContent
    }
}



//FUNCIONES DEL CONTROLADOR DE COMPLEMENTOS SELECT A INPUT

$(document).ready(function(){

    $("#empaque").change(function(){
        //console.log("leyendo");

        var id =$(this).find(":selected").val();
        //console.log(id);
        var data = 'empid='+id;
        console.log(data);

        $.ajax({
            url: 'getSelect.php',
            dataType: "json",
            data: data,
            cache: false,

            success : function(empData){
                if(empData){
                    empData.map((element)=>{
                        //console.log(element.ARTICULO);
                        $("#descripcion").val(element.DESCRIPCION);
                        $("#codigo").val(element.ARTICULO);
                    })
                    
                    $("#errorMassage").addClass
                    ('hidden').text("");
                    $("#recordListing").removeClass
                    ('hidden');

                    //$('#descripcion').text();
                }else{

                    $("#recordListing").addClass
                    ('hidden');
                    $("#errorMassage").removeClass
                    ('hidden').text("No record found");

                }
            }
        });
    })
})


var articulos=document.getElementById('articulos');
var boton=document.getElementById('agregarDetalle');
var data=[];
var cant=0;

boton.addEventListener("click",agregar);

function agregar(){
    var descripcionDetalle=document.getElementById('descripcionDetalle').value;
    var cantidadDetalle=document.getElementById('cantidadDetalle').value;
    var codigoDetalle=document.getElementById('codigoDetalle').value;
    var precioDetalle=document.getElementById('precioDetalle').value;
    
    //LLENA LOS DATOS AL ARREGLO
    data.push(
        {
            "id":cant,
            "codigo":codigoDetalle,
            "descripcion":descripcionDetalle,
            "cantidad":cantidadDetalle,
            "precio": precioDetalle
        }
    );
    var id_row='row'+cant;
    var fila=
    '<tr id='+id_row+'><td>'+codigoDetalle+'</td><td>'
    +descripcionDetalle+'</td><td>'
    +cantidadDetalle+'</td><td>'
    +precioDetalle+
    '</td><td><a href="#" class="btn btn-primary" onclick="eliminar('+cant+')";>Eliminar</a></td></tr>';
    $("#articulos").append(fila);
    cant++;



    



}








