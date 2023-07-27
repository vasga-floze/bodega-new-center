
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
    theme: 'bootstrap-5',
   
} );

$( '#empaque' ).select2( {
    theme: 'bootstrap-5'
} );
$( '#empaqueDetalle' ).select2( {
    theme: 'bootstrap-5',
    placeholder: 'Seleccione el articulo detalle',
   
    
} );
$( '#dirigido' ).select2( {
    theme: 'bootstrap-5',
    placeholder: 'Seleccione el tipo de etiqueta',
   
    
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

$(function() {
    $( "#fechaProduccion" ).datepicker({
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'yy-mm-dd',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: '',
        maxDate: '0'
    
    });
 });


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




var botonRopaPresionado = false;
var botonCarteraPresionado=false;
var botonCinchoPresionado=false;
var botonGorraPresionado=false;
var botonJuguetePresionado=false;
var botonZapatoPresionado=false;
var botonOtroPresionado=false;
var botonGanchoPresionado=false;

$(document).ready(function() {


$('#ropa').click(function() {
    botonRopaPresionado = true;
    if (botonRopaPresionado) {
        const inputs= guardarInputs();
        let bandera="ROPA";
        var ruta="unidades="+inputs.unidades
                +"&fecha="+inputs.fecha
                +"&libras="+inputs.libras
                +"&ubicacion="+inputs.ubicacion
                +"&usuario="+inputs.usuario
                +"&bodega="+inputs.bodega
                +"&empaque="+inputs.empaque
                +"&empacado="+inputs.empacado
                +"&producido="+inputs.producido
                +"&observaciones="+inputs.observaciones
                +"&tipoRegistro="+inputs.tipo
                +"&bandera="+bandera;
            $.ajax({
                url: 'controladorProduccion.php',
                type: 'POST',
                data: ruta,
            })
            .done(function(res){

                if(res==="Registro exitoso"){
                    $(window).attr('location','indexComplemento.php');

                    //console.log(res);
                }else{
                    console.log("REGISTRO INVALIDO");
                }
            })
            .fail(function(){
            })
            .always(function(){ 
            });
        
    } else {
    console.log("El botón no ha sido presionado");
    }

});
$('#cartera').click(function(){
 botonCarteraPresionado=true;
 if(botonCarteraPresionado){
    const inputs= guardarInputs();

        let bandera="CARTERAS";
        var ruta="unidades="+inputs.unidades
                +"&fecha="+inputs.fecha
                +"&libras="+inputs.libras
                +"&ubicacion="+inputs.ubicacion
                +"&usuario="+inputs.usuario
                +"&bodega="+inputs.bodega
                +"&empaque="+inputs.empaque
                +"&empacado="+inputs.empacado
                +"&producido="+inputs.producido
                +"&observaciones="+inputs.observaciones
                +"&tipoRegistro="+inputs.tipo
                +"&bandera="+bandera;
            $.ajax({
                url: 'controladorProduccion.php',
                type: 'POST',
                data: ruta,
            })
            .done(function(res){

                if(res){
                    $(window).attr('location','indexComplemento.php');

                    //console.log(res);
                }
            })
            .fail(function(){
            })
            .always(function(){ 
            });
 }
});
$('#cincho').click(function(){
    botonCinchoPresionado=true;
    if(botonCinchoPresionado){
       const inputs= guardarInputs();
           let bandera="CINCHOS";
           var ruta="unidades="+inputs.unidades
           +"&fecha="+inputs.fecha
           +"&libras="+inputs.libras
           +"&ubicacion="+inputs.ubicacion
           +"&usuario="+inputs.usuario
           +"&bodega="+inputs.bodega
           +"&empaque="+inputs.empaque
           +"&empacado="+inputs.empacado
           +"&producido="+inputs.producido
           +"&observaciones="+inputs.observaciones
           +"&tipoRegistro="+inputs.tipo
           +"&bandera="+bandera;
               $.ajax({
                   url: 'controladorProduccion.php',
                   type: 'POST',
                   data: ruta,
               })
               .done(function(res){
   
                   if(res){
                       $(window).attr('location','indexComplemento.php');
   
                      // console.log(res);
                   }
               })
               .fail(function(){
               })
               .always(function(){ 
               });
    }
   });
   $('#gorra').click(function(){
    botonGorraPresionado=true;
    if(botonGorraPresionado){
       const inputs= guardarInputs();
           let bandera="GORRAS";
           var ruta="unidades="+inputs.unidades+
           "&fecha="+inputs.fecha+
           "&libras="+inputs.libras+
           "&ubicacion="+inputs.ubicacion+
           "&usuario="+inputs.usuario+
           "&bodega="+inputs.bodega+
           "&empaque="+inputs.empaque+
           "&empacado="+inputs.empacado+
           "&producido="+inputs.producido+
           "&observaciones="+inputs.observaciones+
           "&tipoRegistro="+inputs.tipo+
           "&bandera="+bandera;
               $.ajax({
                   url: 'controladorProduccion.php',
                   type: 'POST',
                   data: ruta,
               })
               .done(function(res){
   
                   if(res){
                       $(window).attr('location','indexComplemento.php');
   
                       //console.log(res);
                   }
               })
               .fail(function(){
               })
               .always(function(){ 
               });
    }
   });
   $('#juguete').click(function(){
    botonJuguetePresionado=true;
    if(botonJuguetePresionado){
       const inputs= guardarInputs();
           let bandera="JUGUETES";
           var ruta="unidades="+inputs.unidades+
           "&fecha="+inputs.fecha+
           "&libras="+inputs.libras+
           "&ubicacion="+inputs.ubicacion+
           "&usuario="+inputs.usuario+
           "&bodega="+inputs.bodega+
           "&empaque="+inputs.empaque+
           "&empacado="+inputs.empacado+
           "&producido="+inputs.producido+
           "&observaciones="+inputs.observaciones+
           "&tipoRegistro="+inputs.tipo+
           "&bandera="+bandera;
               $.ajax({
                   url: 'controladorProduccion.php',
                   type: 'POST',
                   data: ruta,
               })
               .done(function(res){
   
                   if(res){
                       $(window).attr('location','indexComplemento.php');
   
                       //console.log(res);
                   }
               })
               .fail(function(){
               })
               .always(function(){ 
               });
    }
   });
   $('#zapato').click(function(){
    botonZapatoPresionado=true;
    if(botonZapatoPresionado){
       const inputs= guardarInputs();
   
        let bandera="ZAPATOS";
        var ruta="unidades="+inputs.unidades+
        "&fecha="+inputs.fecha+
        "&libras="+inputs.libras+
        "&ubicacion="+inputs.ubicacion+
        "&usuario="+inputs.usuario+
        "&bodega="+inputs.bodega+
        "&empaque="+inputs.empaque+
        "&empacado="+inputs.empacado+
        "&producido="+inputs.producido+
        "&observaciones="+inputs.observaciones+
        "&tipoRegistro="+inputs.tipo+
        "&bandera="+bandera;
            $.ajax({
                url: 'controladorProduccion.php',
                type: 'POST',
                data: ruta,
            })
            .done(function(res){

                if(res){
                    $(window).attr('location','indexComplemento.php');

                    //console.log(res);
                }
            })
            .fail(function(){
            })
            .always(function(){ 
            });
    }
   });
   $('#otro').click(function(){
    botonOtroPresionado=true;
    if(botonOtroPresionado){
       const inputs= guardarInputs();
   
          
           let bandera="OTROS";
           var ruta="unidades="+inputs.unidades
           +"&fecha="+inputs.fecha
           +"&libras="+inputs.libras
           +"&ubicacion="+inputs.ubicacion
           +"&usuario="+inputs.usuario
           +"&bodega="+inputs.bodega
           +"&empaque="+inputs.empaque
           +"&empacado="+inputs.empacado
           +"&producido="+inputs.producido
           +"&observaciones="+inputs.observaciones
           +"&tipoRegistro="+inputs.tipo
           +"&bandera="+bandera;
               $.ajax({
                   url: 'controladorProduccion.php',
                   type: 'POST',
                   data: ruta,
               })
               .done(function(res){
   
                   if(res){
                       $(window).attr('location','indexComplemento.php');
   
                       //console.log(res);
                   }
               })
               .fail(function(){
               })
               .always(function(){ 
               });
    }
   });
   $('#gancho').click(function(){
    botonGanchoPresionado=true;
    if(botonGanchoPresionado){
       const inputs= guardarInputs();
        let bandera="GANCHOS";
        var ruta="unidades="+inputs.unidades+
        "&fecha="+inputs.fecha+
        "&libras="+inputs.libras+
        "&ubicacion="+inputs.ubicacion+
        "&usuario="+inputs.usuario+
        "&bodega="+inputs.bodega+
        "&empaque="+inputs.empaque+
        "&empacado="+inputs.empacado+
        "&producido="+inputs.producido+
        "&observaciones="+inputs.observaciones+
        "&tipoRegistro="+inputs.tipo+
        "&bandera="+bandera;
            $.ajax({
                url: 'controladorProduccion.php',
                type: 'POST',
                data: ruta,
            })
            .done(function(res){

                if(res){
                    $(window).attr('location','indexComplemento.php');

                    //console.log(res);
                }
            })
            .fail(function(){
            })
            .always(function(){ 
            });

    }
   });
});
  
   
function guardarInputs(){
    //OBJETO PARA  GUARDAR TODOS LOS INPUTS
    let valoresInputs={};
        valoresInputs.unidades= document.getElementById('unidades').value;
//let fecha= document.getElementById('fechaProduccion').value;
        valoresInputs.fecha=document.getElementById('fechaProduccion').value;
        valoresInputs.libras= document.getElementById('libras').value;
        valoresInputs.observaciones = document.getElementById('observaciones').value;
        valoresInputs.ubicacion = document.getElementById('ubicacion').value;
        valoresInputs.empaque=document.getElementById('empaque').value;
        valoresInputs.usuario=document.getElementById('usuario').value;
        valoresInputs.bodega=document.getElementById('bodega').value;
        valoresInputs.tipo=document.getElementById('tipoRegistro').value;
    //var empaque=$('#empaque').val();
        valoresInputs.empacado=$('#empacado').val();
        valoresInputs.producido=$('#producido').val();
    if(valoresInputs.unidades.length===0){
        toastr["error"]("No puedes dejar el campo unidades vacio","Ocurrio un error");
        $("#unidades").focus();
    }else if(valoresInputs.libras.length===0){
        toastr["error"]("No puedes dejar el campo libras vacio","Ocurrio un error");
        $("#libras").focus();
    }else if(valoresInputs.ubicacion.length===0){
        toastr["error"]("No puedes dejar el select ubicacion vacio","Ocurrio un error");
        $('#ubicacion').select2('open').focus();
    }else if(valoresInputs.empaque.length===0){
        toastr["error"]("No puedes dejar el select tipo empaque vacio","Ocurrio un error");
        $('#empaque').select2('open').focus();
    
    }else if(valoresInputs.empacado.length===0){
        toastr["error"]("No puedes dejar el select empacado por vacio","Ocurrio un error");
        $('#empacado').select2('open').focus();
    }else if(valoresInputs.producido.length===0){
        toastr["error"]("No puedes dejar el select producido por vacio","Ocurrio un error");
        $('#producido').select2('open').focus();
    }else{
        return valoresInputs;
    }

};
 // Verificar si el botón ha sido presionado
 function filterInteger(evt,input){
    evt.stopPropagation();
    let key= window.Event ? evt.which : evt.keyCode;
    let chark = String.fromCharCode(key);
    let tempValue = input.value+chark;
    if((key>=48 && key<= 57)){
        return filter(tempValue);
    }else{
        return key==8 || key==13 || key==0;
    }

};

function filter(__val__){
    let preg = /^[0-9]*$/;
    return preg.test(__val__);
};
$('#unidades').on('keypress',function(evt){
    if(filterInteger(evt, evt.target)===false){
        evt.preventDefault();
    }
});

    
///////////// FIN DE FUNCIONES DE LA PRODUCCION /////////////////////////////
    


    /*const unidades=document.getElementById('unidades').value;
    var fecha=document.getElementById('fechaProduccion').value;
    const libras=document.getElementById('libras').value;
    var observaciones=document.getElementById('observaciones').value;
    const ubicacion=document.getElementById('ubicacion').value;
    var empaque=document.getElementById('empaque').value;
    var usuario=document.getElementById('usuario').value;
    var bodega=document.getElementById('bodega').value;
    var tipo=document.getElementById('tipoRegistro').value;
    //var empaque=$('#empaque').val();
    var empacado=$('#empacado').val();
    var producido=$('#producido').val();
    if(unidades.length===0){
       
        toastr["error"]("No puedes dejar el campo unidades vacio","Ocurrio un error");
        $("#unidades").focus();
        
    }else if(libras.length===0){
        toastr["error"]("No puedes dejar el campo libras vacio","Ocurrio un error");
        $("#libras").focus();
        
    }else if($("#ubicacion").val()=== null || $("#ubicacion").val() === ''){
        toastr["error"]("No puedes dejar el select ubicacion vacio","Ocurrio un error");
        $('#ubicacion').select2('open').focus();

    }else if(empacado.length === 0){
        toastr["error"]("No puedes dejar el select empacado por vacio","Ocurrio un error");
        $('#empacado').select2('open').focus();

    }else if(empaque.length === 0){
        toastr["error"]("No puedes dejar el select tipo empaque vacio","Ocurrio un error");
        $('#empaque').select2('open').focus();
    

    }else if(producido.length === 0){
        toastr["error"]("No puedes dejar el select producido por vacio","Ocurrio un error");
        $('#producido').select2('open').focus();
    }else{
    
        var ruta="unidades="+unidades+"&fecha="+fecha+"&libras="+libras+"&ubicacion="+
                ubicacion+"&usuario="+usuario+"&bodega="+
                bodega+"&empaque="+empaque+"&empacado="+empacado+"&producido="+producido+
                "&observaciones="+observaciones+"&tipoRegistro="+tipo;
        $.ajax({
            url: 'controladorProduccion.php',
            type: 'POST',
            data: ruta,
        })
        .done(function(res){
        
            if(res){
                $(window).attr('location','indexComplemento.php');
            }
        })
        .fail(function(){
        })
        .always(function(){ 
        });
    }  */ 


// BOTON GUARDAR CARTERAS
/*let buttonCarteras = document.getElementById('cartera');
buttonCarteras.addEventListener('click', function(){
    const bandera = document.getElementById('banderaArticulo')
    bandera.value="CARTERAS";
    console.log(bandera.value);

})*/



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

$(document).ready(function(){

    $("#empaqueDetalle").change(function(){
        //console.log("leyendo");
        $("#detalleBandera").val("DETALLE");
       
        var id =$(this).find(":selected").val();
        
        //console.log(id);
        var data = 'empid='+id;
        console.log(data);

        $.ajax({
            url: 'getSelectDetalle.php',
            dataType: "json",
            data: data,
            cache: false,

            success : function(empData){
                if(empData){
                    empData.map((element)=>{
                        console.log(element);
                        $("#descripcionDetalle").val(element.DESCRIPCION);
                        $("#codigoDetalle").val(element.ARTICULO);
                        $("#precioDetalle").val(parseFloat(element.PRECIO).toFixed(2));
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



//---------FUNCIONES PARA INDEXCOMPLEMENTO--------////////

/* Disabling the input fields. */

$('#descripcionDetalle').prop('disabled', true);
$('#cantidadDetalle').prop('disabled', true);
$('#empaqueDetalle').prop('disabled', true);
$('#agregarDetalle').prop('disabled', true);
$('#guardarDetalle').prop('disabled', true);
$('#empaque').prop('disabled', false);

/**
 * If the user clicks on the button with the id of 'agregarDetalle', 
 * then the function
 * habilitarComponentes() will be called.
 */
function habilitarComponentes(){
    $('#descripcionDetalle').prop('disabled', false);
    $('#cantidadDetalle').prop('disabled', false);
    $('#empaqueDetalle').prop('disabled', false);
    $('#agregarDetalle').prop('disabled', false);
    $('#guardarDetalle').prop('disabled', false);
    $('#empaque').prop('disabled', true);
    $('#finalizar').prop('disabled', true);
    $('#empaque').prop('disabled', true);
    $('#buscarModal').prop('disabled', true);
}




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




/* Creating a variable called articulos and assigning it to the element with the id of articulos. It is
also creating a variable called boton and assigning it to the element with the id of agregarDetalle.
It is also creating a variable called data and assigning it to an empty array. It is also creating a
variable called cant and assigning it to 0. */
var articulos=document.getElementById('articulos');
var boton=document.getElementById('agregarDetalle');
var botonGuardar=document.getElementById('guardarDetalle');
let cantidadTotal=document.getElementById('total').value;
var data=[];
var cant=0;
let cantidad=document.getElementById('cantidadDetalle');
boton.addEventListener("click",agregar);
botonGuardar.addEventListener("click",guardar);
$("#cantidadDetalle").on('keyup',function(e){
    let keycode = e.keyCode || e.which;
    if(keycode==13){
        agregar();
      
      
    }
})



/**
 * It adds a row to a table.
 */
function agregar(){
    var descripcionDetalle=document.getElementById('descripcionDetalle').value;
    var cantidadDetalle=document.getElementById('cantidadDetalle').value;
    var codigoDetalle=document.getElementById('codigoDetalle').value;
    var precioDetalle=document.getElementById('precioDetalle').value;
    var detalleBandera=document.getElementById('detalleBandera').value;
    var cantidadTotal=document.getElementById('total').value;
    //var selectEmpaqueDetalle=document.querySelector('#empaqueDetalle');
    if(descripcionDetalle.length==0){
        toastr["error"]("Tienes que seleccionar un paquete");
        
    }else if(cantidadDetalle.length==0){
        toastr["error"]("Tienes que digitar la cantidad");
    }else{

    let existe=false;
    
    /**
     * *RECORRE EL OBJETO DATA 
     * TODO: SI ENCUENTRA EL CODIGO EN LA TABLA
     * TODO: LE SUMA LA CANTIDAD DIGITADA POR EL USUARIO
     * TODO: Y ACTUALIZA LA COLUMNA CANTIDAD DE LA TABLA
     * ?data es un objeto mutable
     */
    for (let i = 0; i < data.length; i++) {
        const element = data[i];
        if(element.codigo===codigoDetalle){
            element.cantidad=parseInt(element.cantidad)+parseInt(cantidadDetalle);
            existe=true;
            console.log(element);
            let fila =document.getElementById('row'+element.id);
            let celdaCantidadTotal=fila.getElementsByTagName('td')[2];
            celdaCantidadTotal.textContent=element.cantidad;
            //sumarCantidadAcomulado();
            break;
        }
        
    }
    
    /**
     * *SI EXISTE EL CODIGO AGREGA LA FILA
     */


    if (!existe) {

        let dirigido=document.getElementById('dirigido').value;
         //LLENA LOS DATOS AL ARREGLO
        data.push(
            {
                "id":cant,
                "codigo":codigoDetalle,
                "descripcion":descripcionDetalle,
                "cantidad":cantidadDetalle,
                "precio": precioDetalle,
                "detalleBandera":detalleBandera,
                "cantidadTotal":cantidadTotal,
                "dirigido":dirigido
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
        console.log(data);
        cant++;

        
    }
   
    $("#descripcionDetalle").val('');
    

    $("#cantidadDetalle").val('');
    $("#codigoDetalle").val('');
    $("#precioDetalle").val('');
    //empaqueDetalle.selectedIndex=4;
   //console.log(empaqueDetalle.selectedIndex=0);
    sumarCantidad()
    $('#empaqueDetalle').val(null).trigger('change');

}



}

function guardar(){
    var json=JSON.stringify(data);
    const sum = data.reduce((previous, current) => {
        return Number(previous) + Number(current.cantidad); // sumar el valor de una propiedad
      }, 0);

    console.log(sum);
    let dirigido=document.getElementById('dirigido').value;
    
    
    let cantidadSession=document.getElementById('unidadesSession').value;
    console.log(json);
    
    if(sum == cantidadSession ){
        $.ajax({
            type: "POST",
            url: "controladorComplemento.php",
            data: "json="+json+"&dirigido="+dirigido,
            success:function(res){
            console.log(res);
            $(document).ready(function(){
                // Abrir una nueva pestaña con la página que se desea redireccionar

               
                if($('#imprimir').prop('checked')){
               
                window.open('barraComplemento.php', '_blank');
                window.open('example.php','_blank');
                window.open('pdfAdhesivo.php', '_blank');
                //window.open('pdfAdhesivo.php');
            // Esperar a que la página se cargue completamente
                
                setTimeout(function(){
                    window.location.replace('indexProduccion.php');
                }, 2000);
                } else {
                        const newTab2 = window.open('barraComplemento.php', '_blank');
                    // Esperar a que la página se cargue completamente
                        $(newTab2).on('load', function(){
                            // Posicionarse en la pestaña abierta
                            newTab2.focus();
                        });
                        
                        setTimeout(function(){
                            window.location.replace('indexProduccion.php');
                        }, 2000);
                    }
                });
            }
        })
        
    }else{
        Swal.fire({
            title: 'Desea continuar',
            text: "La cantidad total que se digito en produccion " 
                    +cantidadSession+ " no coincide con la can"+
                    "tidad total que aparece en el detalle " + sum,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, deseo continuar'
          }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "controladorComplemento.php",
                    data: "json="+json,
                    success:function(res){
                    console.log(res);
                    $(document).ready(function(){
                        
                        // Abrir una nueva pestaña con la página que se desea redireccionar
                        if($('#imprimir').prop('checked')){
                        //const newTab1 = window.open('example.php', '_blank');
                        window.open('barraComplemento.php', '_blank');
                        window.open('example.php','_blank');

                        if($('#imprimirAdhesivo').prop('checked')){
                            window.open('pdfAdhesivo.php', '_blank');
                        }
                        
                    // Esperar a que la página se cargue completamente
                        
                        setTimeout(function(){
                            window.location.replace('indexProduccion.php');
                        }, 2000);
                        } else {
                                const newTab2 = window.open('barraComplemento.php', '_blank');
                            // Esperar a que la página se cargue completamente
                                $(newTab2).on('load', function(){
                                    // Posicionarse en la pestaña abierta
                                    newTab2.focus();
                                });
                                
                                setTimeout(function(){
                                    window.location.replace('indexProduccion.php');
                                }, 2000);
                            }
                        });
                    }
                })
              
            }
          })
        
       
    }
    
}

function sumarCantidad(){ 
    const sum = data.reduce((previous, current) => {
        return Number(previous) + Number(current.cantidad); // sumar el valor de una propiedad
      }, 0);
      let totalElement=document.getElementById('total');
      totalElement.innerHTML="Total cantidad articulos :"+sum+"</strong>";
}



function eliminar(row){
    //remover la fila de la tabla html
    $("#row"+row).remove();
    var i=0;
    var pos=0;
    for(x of data){
        if(x.id==row){
            pos=i;
        }
        i++;
    }
    data.splice(pos,1);
    sumarCantidad();
}



let buttonFinalizar= document.getElementById('finalizar');
const overlay = document.getElementById('modal-overlay');
/* Creating a toastr message with two buttons. */
buttonFinalizar.addEventListener('click',function(){
    const input = document.getElementById('descripcion');
    if(input.value.trim().length===0){
        toastr["error"]
                ("Debes seleccionar un articulo en la descripcion y guardar la operacion", 
                "Ocurrio un error"),
                {
                    positionClass: "toast-top-right"
                }
    }else{

    //overlay.style.display = 'block';
    Swal.fire({
        title: 'Estas seguro de seguir',
        text: "Si precionas en continuar podras imprimir las etiquetas al detalle " + 
                "si precionas en cancelar se imprimira la etiqueta de fardo y no se imprimiran las etiquetas detalle",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        allowOutsideClick:false,
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, continuar'
      }).then((result) => {
        if (result.isConfirmed) {
            clickear();
            const newTab2 = window.open('barraComplemento.php', '_blank');
            $(newTab2).on('load', function(){
                // Posicionarse en la pestaña abierta
                newTab2.focus();
            });
            setTimeout(function(){
                window.location.replace('indexProduccion.php');
            }, 2000);
            
          
        }else{
            if(input.value.trim().length ===0){
                toastr["error"]
                ("Debes seleccionar un articulo en la descripcion y guardar la operacion", 
                "Ocurrio un error"),
                {
                    positionClass: "toast-top-right"
                }
            }else{
            overlay.style.display = 'none';
            habilitarComponentes();
            clickear();
            }
        }
      })
    
    /*toastr.warning(
        "<br /><br /><button type='button' id='positivo' class='btn btn-light'>Si </button>&nbsp;<button type='button' id='negativo' class='btn btn-light'>No</button>",
        'Si finalizas la operacion no podras llenar el articulo detalle<br>Deseas finalizar la operacion',
    {
      closeButton: false,
      allowHtml: true,
      positionClass: "toast-top-center",
      timeOut: 0,
      tapTopDismiss:false,
      debug : false,
      newestOnTop: false,
      preventDuplicates:false,
      onclick:null,
      extendedTimeOut: 0,
      onShown: function (toast) {
          $("#positivo").click(function(){
            clickear();
            const newTab2 = window.open('barraComplemento.php', '_blank');
            // Esperar a que la página se cargue completamente
                $(newTab2).on('load', function(){
                    // Posicionarse en la pestaña abierta
                    newTab2.focus();
                });
                setTimeout(function(){
                    window.location.replace('indexProduccion.php');
                }, 2000);
            
          });
          $("#negativo").click(function(){
            if(input.value.trim().length ===0){
                toastr["error"]
                ("Debes seleccionar un articulo en la descripcion y guardar la operacion", 
                "Ocurrio un error"),
                {
                    positionClass: "toast-top-right"
                }
            }else{
            overlay.style.display = 'none';
            habilitarComponentes();
            clickear();

            
            }
          });
        }
    
  });*/
}


});


/**
 * It takes the values of the inputs and sends them to a php file.
 */
function clickear(){
    $(document).ready(function(){
        var descripcion=document.getElementById('descripcion').value;
        let dirigido=document.getElementById('dirigido').value;
        //var ropa=document.getElementById('ropa').value;
        var codigo=document.getElementById('codigo').value;
        var detalleBandera=document.getElementById('detalleBandera').value;
        var ruta="descripcion="+descripcion+
                "&codigo="+codigo+
                "&detalleBandera="+detalleBandera+"&dirigido="+dirigido;
        console.log(ruta);
        if(descripcion.length==0){
            toastr["error"]("Tienes que seleccionar un paquete o buscar un paquete","Ocurrio un error");
        }else{
        $.ajax({
            url: 'controladorComplemento.php',
            type: 'POST',
            data: ruta,
        })
        .done(function(res){
            //let url= "indexUbicacion.php";
           //console.log(res);
           //console.log("dfdfd");
        setTimeout(function(){
            toastr["success"]("Registro exitoso");
        },1000)
        //$(window).attr('location','barraComplemento.php');  
        })
        .fail(function(){
        })
        .always(function(){     
        });
    }
    });
}













