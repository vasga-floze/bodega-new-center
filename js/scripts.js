

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
                "&observaciones="+observaciones;

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

        alert(res);
    
        
        
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

});






