$(document).ready(function(){
    var estado = document.getElementById("estado");
    var usuario = document.getElementById("usuario");
    if(usuario.value !=='TODOS' || estado.value !=='TODOS'){
        $("#finalizar").prop("disabled",false);
    }else{
        $("#finalizar").prop("disabled",true);
    }
    $("#estado").change(function(){
        if(usuario.value !=='TODOS' && estado.value !=='TODOS'){
            $("#finalizar").prop("disabled",false);
        }else{
            $("#finalizar").prop("disabled",true);
        }

    })
    $("#usuario").change(function(){
        if(usuario.value !=='TODOS' && estado.value !=='TODOS'){
            $("#finalizar").prop("disabled",false);
        }else{
            $("#finalizar").prop("disabled",true);
        }

    })
   
})
$( '#estado' ).select2( {
    theme: 'bootstrap-5'
} );
$( '#usuario' ).select2( {
    theme: 'bootstrap-5'
} );
$(function() {
    $( "#fecha" ).datepicker({
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
        yearSuffix: ''
    
    });
 });




 $("#generar").on("click",function(){
   

    let estado= document.getElementById('estado').value;
    let fecha=document.getElementById('fecha').value;
    let usuario=document.getElementById('usuario').value;
    

    let ruta="getConsultaProduccion.php?fecha="+fecha+"&estado="+estado+"&usuario="+usuario;
    console.log(ruta);

    $.getJSON(ruta,function(data){
            console.log(data);

        
            const tabla=$('#myTable').DataTable({
                "destroy":true,
                "data":data,
                "columns":[
                    {"title":"Articulo","data":"Articulo"},
                    {"title":"Descripcion","data":"Descripcion"},
                    {"title":"Clasificacion","data":"Clasificacion"},
                    {"title":"CodigoBarra","data":"CodigoBarra"},
                    {"title":"Libras","data":"Libras"},
                    {"title":"Unidades","data":"Unidades"},
                    {"title":"UsuarioCreacion","data":"UsuarioCreacion"},
                    {"title":"Estado","data":"Estado"},
                    {"title":"FechaCreacion","data":"FechaCreacion"},
                    
                    {"defaultContent":"<button class='mt-3 btn btn-primary editar'>Editar</button>"},
                    {"defaultContent":"<button class='btn btn-danger eliminar'>Eliminar</button>"}

                ]
            });
            $('#myTable').on('click','.editar',function(){

                let row=$(this).closest('tr');
                let dataRow=tabla.row(row).data();
                let articulo=dataRow.Articulo;
                let descripcion=dataRow.Descripcion;
                let clasificacion=dataRow.Clasificacion;
                let libras=dataRow.Libras;
                let codigoBarra=dataRow.CodigoBarra;
                let estado=dataRow.Estado;

                if(estado==='ELIMINADO' || estado==='FINALIZADO'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'No se puede editar porque el registro ya esta eliminado o finalizado',
                        allowOutsideClick: false
                      
                      })
                    return;
                }
            
                let url='indexEditarProduccion.php?articulo='+encodeURIComponent(articulo)+'&descripcion='+encodeURIComponent(descripcion)+"&clasificacion="+encodeURIComponent(clasificacion)+"&libras="+encodeURIComponent(libras)+"&codigoBarra="+encodeURIComponent(codigoBarra);
                //let url='editar.php?articulo='+encodeURIComponent(articulo);
            
                window.location.href=url;
            
            });
            $('#myTable').on('click','.eliminar',function(){
                Swal.fire({
                    title: 'Estas seguro que deseas eliminar?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Eliminar',
                    denyButtonText: `No eliminar`,
                  }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        let row=$(this).closest('tr');
                        let dataRow=tabla.row(row).data();
                        let estado=dataRow.Estado;
                        let codigoBarra=dataRow.CodigoBarra;

                        let url='estado='+encodeURIComponent(estado)+'&codigoBarra='+encodeURIComponent(codigoBarra);
                        $.ajax({
                            url: 'controladorEliminarProduccion.php',
                            type: 'POST',
                            data: url,
                        })
                        .done(function(res){
                            if(res==='eliminado'){
                                //console.log("No se ha podido eliminar el articulo porque ya esta eliminado");
                                Swal.fire('Registro no se podido eliminar porque ya esta eliminado', '', 'info')
                                return;
                            }else if(res==='finalizado'){
                                Swal.fire('Registro no se podido eliminar porque ya esta finalizado', '', 'info')
                                return;
                            }
        
                        })
                        .fail(function(){
        
                        })
                        .always(function(){
        
                        });
                        Swal.fire({
                          
                            icon: 'success',
                            title: 'Registro eliminado exitosamente',
                            showConfirmButton: false,
                            timer: 1500
                          })
                        .then(()=>{
                            setTimeout(()=>{
                                window.location.replace("indexConsultaComplemento.php");
                            },100);
                          })
                    } else if (result.isDenied) {
                      Swal.fire('No se ha hecho ningun cambio', '', 'info')
                    }
                  })
                
                //window.location.href=url;

            });
            $('#finalizar').on('click',function(){
                // Creamos array con los meses del año
                //console.log(data);
                
                let paquete=document.getElementById('paquete').value;
                let documentoConsecutivo=document.getElementById('documentoConsecutivo').value;
                let documentoInventario=document.getElementById('documentoInventario').value;
                let usuario=document.getElementById('usuarioSession').value;
                let produccion='PRODUCCION';
                let seleccionado='N';
                let fechaCreacion=data.map(function(element){
                    return element.FechaCreacion;
                })
               
                let elementos=data.filter(function(element){
                    return element.Estado==='PROCESO';
                }).map(function(element){
                    return element;
                });

                let elementosJSON= JSON.stringify(elementos);
                //console.log(fechaCreacion[0]);
                
                //let fechaDocumento=dataRow.FechaCreacion;
                let url="paquete="+paquete+"&documentoInventario="+documentoInventario+"&produccion="+produccion+"&seleccionado="+seleccionado+"&fechaCreacion="+fechaCreacion[0]+"&usuario="+usuario+"&elementos="+encodeURIComponent(elementosJSON)+"&documentoConsecutivo="+documentoConsecutivo;
                $.ajax({
                    url: 'controladorFinalizarProduccion.php',
                    type: 'POST',
                    data: url,
                })
                .done(function(res){
                    //console.log(res);
                    //console.log(res);
                    if(!res==="Se pudo ejecutar"){
                        Swal.fire({
                            icon: 'error',
                            title: 'Ocurrio un error',
                            text: 'No se ha podido ejecutar'
                        })
                    }else{
                        Swal.fire({
                           icon: 'success',
                           title: 'Articulos finalizados correctamente',
                           showConfirmButton:false,
                           timer : 1500
                    });
                    }

                    setTimeout(function() {
                        window.location.href='indexConsultaComplemento.php';
                    }, 1500);

                    
                })
                .fail(function(jqXHR, textStatus, errorThrown){
                    console.log("Error: " + errorThrown);
                })
                .always(function(){ 
                });
            });
    });
 });

 $('#editar').click(function(){
    let articulo=document.getElementById('articulo').value;
    let descripcion=document.getElementById('descripcion').value;
    let clasificacion=document.getElementById('clasificacion').value;
    let libras=document.getElementById('libras').value;
    let codigoBarra=document.getElementById('codigoBarra').value;

    var ruta="articulo="+articulo+"&descripcion="+descripcion+"&clasificacion="+clasificacion+"&libras="+libras+"&codigoBarra="+codigoBarra;
    $.ajax({
        url: 'controladorEditarProduccion.php',
        type: 'POST',
        data: ruta,
    })
    .done(function(res){
        if(res==1){
            Swal.fire({
                //position: 'top-end',
                icon: 'success',
                title: 'Registro editado con exito',
                showConfirmButton: false,
                timer: 1500
              }).then(()=>{
                setTimeout(()=>{
                    window.location.replace("indexConsultaComplemento.php");
                },100);
            })

        }else{
            console.log("No se puede editar");
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'El registro no puede ser editado porque no coincide la clasificacion porque '+
                      'la clasificacion del articulo editado no coinciden con la base de datos',
                
              })     
        }
    })
    .fail(function(){
    })
    .always(function(){
 
    });   
});
