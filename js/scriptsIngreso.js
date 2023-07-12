$(document).ready(function(){

    $('#cantidad').prop('disabled', true).prop('readonly', true);
    $('#peso').prop('disabled', true).prop('readonly', true);
    $('#articulo').prop('disabled', true).prop('readonly', true);
    $('#generar').prop('disabled', true);
    let numeroDocumento=document.getElementById('fechaDocumento');
    if(numeroDocumento){
        imprimirTabla();
        
       /* let numero=numeroDocumento.value;
        if(!numero.length === 0){
            imprimirTabla();
    
        }*/

    }
})




$( '#bodega' ).select2( {
    theme: 'bootstrap-5',
    placeholder: 'Seleccione la bodega',
   
    
} );
$( '#articulo' ).select2( {
    theme: 'bootstrap-5',
    placeholder: 'Seleccione el articulo',
   
    
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
 ///VALIDAR CON JQUERY

 $(document).ready(function(){
    $("#cantidad").on('input',function(e){
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
    })

 })

let input=document.getElementById('peso').value;

 $("#peso").on('keypress',function(evento){
   let char =String.fromCharCode(evento.which);
   if(!/[0-9\.]/.test(char)){
    evento.preventDefault();
   }

   if(char === "." && input.indexOf(".") !== -1 ){
    evento.preventDefault();
   }
 })


 $('#articulo').change(function(){
    let articulo=$(this).val();
    var selectedOption = $('#articulo option:selected');
          
    var clasificacion = selectedOption.data('clasificacion');
    let descripcion=$('#articulo option:selected').text().split('-')[1].trim();
    $('#clasificacion').val(clasificacion);
   
    $('#descripcion').val(descripcion);
    
 })



$('#siguiente').click(function(){
    let fecha=document.getElementById('fecha').value;
    let contenedor=document.getElementById('contenedor').value;
    let bodega=document.getElementById('bodega').value;

    console.log(fecha);
    console.log(contenedor);

    let data="fecha="+fecha+"&contenedor="+contenedor+"&bodega="+bodega;

    $.ajax({
        url:'controladorContenedorValidar.php',
        data:data,
        type:'POST',

        success:function(response){
            let respuesta = JSON.parse(response);
            console.log(respuesta);
            if (respuesta.success === "1") {
                
                console.log("hola");
                $('#cantidad').prop('disabled', false).prop('readonly', false);
                $('#peso').prop('disabled', false).prop('readonly', false);
                $('#articulo').prop('disabled', false).prop('readonly', false);
                $('#generar').prop('disabled', false);
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Hubo un error',
                    text: 'No se puede trabajar el contenedor porque ya existe'
                    
                  })
            }
            

        }
    })
})

let cant=0;
let arregloData=[]
let numeroFecha;
let numeroDocumento;
$('#generar').click(function(){
    let descripcion=document.getElementById('descripcion').value;
    let clasificacion=document.getElementById('clasificacion').value;
    let articulo=document.getElementById('articulo').value;
    let libra=document.getElementById('peso').value;
    //let bodega=document.getElementById('bodega').value;
   
    if( document.getElementById('numeroDocumento')){
         numeroDocumento=document.getElementById('numeroDocumento').value;
    }else{
        numeroDocumento=document.getElementById('contenedor').value;
    }
    let fecha=document.getElementById('fecha')
    if(fecha){
        numeroFecha=document.getElementById('fecha').value;
    }else{
        numeroFecha=document.getElementById('fechaDocumento').value;
    }
    let bodega=document.getElementById('bodegaDocumento')
    if(bodega){
        bodega=document.getElementById('bodegaDocumento').value;
    }else{

        bodega=document.getElementById('bodega').value;
    }

    let cantidad=document.getElementById('cantidad').value;
    let data="descripcion="+descripcion+"&clasificacion="+clasificacion+
               "&articulo="+articulo+"&libra="+libra+"&fecha="+numeroFecha+
               "&cantidad="+cantidad+"&numeroDocumento="+numeroDocumento+
               "&bodega="+bodega;


    $.ajax({

        url:'controladorInsertarContenedor.php',
        type: 'POST',
        data:data,
        success: function(response){
            console.log(response);
            let datos=JSON.parse(response);
       
           arregloData.push({
                articulo:datos.codigo,
                peso: datos.peso,
                nombre:datos.nombre,
                cantidad:datos.cantidad,
                totalPeso: datos.totalPeso,
            })
            console.log(arregloData);
            Swal.fire({
             
                icon: 'success',
                title: 'Your work has been saved',
                showConfirmButton: false,
                timer: 1500
              })
            
           
            var id_row='row'+cant;
            var fila=
            '<tr id='+id_row+'><td>'+datos.codigo+'</td><td>'
            +datos.nombre+'</td><td>'
            +datos.cantidad+'</td><td>'
            +datos.peso+'</td><td>'
            +datos.totalPeso+'</td><td>'
            +datos.contenedor+'</td><td>'
            +datos.fecha+'</td><td><a href="#" class="btn btn-primary" onclick="eliminarFilaBase(\'' + datos.codigo + '\', \'' + datos.fecha + '\', \'' + datos.contenedor + '\', ' + cant + ');">Eliminar</a> <a href="#" class="btn btn-warning mt-0" onclick="imprimirFila(\''+datos.codigo+'\',\''+ datos.contenedor + '\',\''+datos.fecha+ '\')";>Imprimir</a></td></tr>';
            $("#myTable").append(fila);
        
            cant++;
            }

    })

})

$('#finalizar').click(function(){
    $.ajax({
        url:'controladorFinalizarContenedor.php',
        type:'POST',
        data: {
            arregloData:JSON.stringify(arregloData)
        },
        success: function(response){
            let data=JSON.parse(response);
            let mensaje=data.message;
            if(data.success==="1"){
                Swal.fire({
                    
                    icon: 'success',
                    title: mensaje,
                    showConfirmButton: false,
                    timer: 1500
                })

                setTimeout(() => {
                    window.location.href = "indexContenedor.php";
                }, 1500);
            }
        }
    })
})



const imprimirTabla=()=>{
    $('#cantidad').prop('disabled', false).prop('readonly', false);
    $('#peso').prop('disabled', false).prop('readonly', false);
    $('#articulo').prop('disabled', false).prop('readonly', false);
    $('#generar').prop('disabled', false);
    $('#fechaDocumento').prop('disabled', true).prop('readonly', true);
    $('#numeroDocumento').prop('disabled', true);
    $('#bodegaDocumento').prop('disabled', true);

    let contador=1;
    let contadorTotal;
    let numeroDocumento=document.getElementById('numeroDocumento').value;

    let fechaDocumento=document.getElementById('fechaDocumento').value;

    let data="numero="+numeroDocumento+"&fecha="+fechaDocumento;


    $.ajax({
        url:'getListadoContenedor.php',
        type:'POST',
        data:data,

        success:function(response){
            console.log(response);
            let dataResponse=JSON.parse(response);
           
            dataResponse.map((element)=>{

                $('#bodegaDocumento').val(element.BodegaActual)
                console.log(element.Articulo);
                contadorTotal=element.cantidad*element.libras;
                arregloData.push({
                    articulo:element.Articulo,
                    peso: element.libras,
                    nombre:element.Descripcion,
                    cantidad:element.cantidad,
                    totalPeso:contadorTotal
                })
                var id_row='row'+contador;
                var row = '<tr id='+id_row+'>';
                //row += "<td>"+ contador + "</td>"; 
                row += "<td>" + element.Articulo + "</td>";
                row += "<td>" + element.Descripcion + "</td>";
                row += "<td>" + element.cantidad + "</td>";
                row += "<td>" + element.libras + "</td>";
                row += "<td>" + contadorTotal + "</td>";
                row += "<td>" + element.DOCUMENTO_INV + "</td>";
                row += "<td>" + element.FechaCreacion + "</td>";
                row+=  '<td><a href="#" class="btn btn-primary" onclick="eliminarFilaBase(\'' + element.Articulo + '\', \'' + element.FechaCreacion + '\', \'' + element.DOCUMENTO_INV + '\', ' + contador + ');">Eliminar</a> <a href="#" class="btn btn-primary" onclick="imprimirFila(\''+element.Articulo+'\',\''+ element.DOCUMENTO_INV + '\',\''+element.FechaCreacion+ '\')";>Imprimir</a></td>'
                row += "</tr>";
               
                $("#myTable").prepend(row);
        
                contador++;
            })
           
            //console.log(response);
        }

    })

  


}
function eliminarFilaBase(codigo,fecha,contenedor,fila){
    console.log(codigo);
    console.log(fecha);
    console.log(contenedor);

    let data="codigo="+codigo+"&fecha="+fecha+"&contenedor="+contenedor;

    $.ajax({
        url:"controladorEliminarContenedor.php",
        type:"POST",
        data: data,

        success:function(response){
            console.log(response);
            let data=JSON.parse(response);
            if(data.success==="1"){


                eliminarLista(fila)
            }

        }
    })

    
} 



function eliminarLista(fila) {
    $("#row"+fila).remove();
    var i=0;
    var pos=0;
    for(x of arregloData){
        if(x.id==fila){
            pos=i;
        }
        i++;
    }
    arregloData.splice(pos,1);
    
}

function imprimirFila(articulo,contenedor,fecha) {
    // Construir la URL con los parámetros
    var url = "pdfContenedor.php" +
        "?articulo=" + encodeURIComponent(articulo) +
        "&contenedor=" + encodeURIComponent(contenedor) +
        "&fecha=" + encodeURIComponent(fecha);

    // Redireccionar a la nueva página
    window.location.href = url;

    
}


 

