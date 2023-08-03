
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


$(document).ready(function(){
   cargarTrasladosPendientes();
   
})

const cargarTrasladosPendientes=()=>{
    console.log("Hola mundo");
    let documento= document.getElementById("fechaHoraTemporal").value;

    let data="documento="+documento;
    $.ajax({
        url: 'getTrasladosPendientes.php',
        type: 'GET',
        data: data,

        success: function(response){
            imprimirTabla(response);
        },

        error:function (xhr,status,error){

        }
    })
}

$( '#bodegaOrigen' ).select2( {
    theme: 'bootstrap-5',
    placeholder: 'Seleccione la bodega de origen',
   
    
} );
$( '#bodegaOrigen2' ).select2( {
    theme: 'bootstrap-5',
    placeholder: 'Seleccione la bodega de origen',
   
    
} );
$( '#bodegaDestino2' ).select2( {
    theme: 'bootstrap-5',
    placeholder: 'Seleccione la bodega de origen',
   
    
} );
$( '#bodegaOrigen2' ).prop('disabled',true);



$( '#bodegaDestino' ).select2( {
    theme: 'bootstrap-5',
    placeholder: 'Seleccione la bodega destino',
   
    
} );

$( '#codigoBarra' ).select2( {
    theme: 'bootstrap-5',
    placeholder: 'Digite el codigo de barra',
   
    
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
        yearSuffix: '',
        onSelect:function(textoFecha, objetoDatepicker){
            if ($("#pendiente").val() === "") {
                return; // No se ejecuta el evento
            }
            if(!confirm("Deseas cambiar la fecha actual " +textoFecha)){
                $("#fecha").val("");
                return;
            }
            introducirFecha(textoFecha);
        }
    
    });
 });



$(".btn-info").click(function(){
    let fechaHoraTemporal = document.getElementById("fechaHoraTemporal").value;
    //console.log(codigoBarraSession);
    let data="fechaTemporal="+fechaHoraTemporal;
    let filas='';
    $.ajax({
        url: 'getConsultaResumenTraslado.php',
        type: 'GET',
        data: data,
        success: function (response) {
            let parseo=JSON.parse(response);
            let sumaCantidad = parseo.reduce(function (total, element) {
                return Number(total) + Number(element.Cantidad);
              }, 0);
            let sumaLibras = parseo.reduce(function (total, element) {
            return Number(total) + Number(element.Libras);
            }, 0);
            sumaLibras=sumaLibras.toFixed(2);
            let libras;
            $("#tablaCuerpo").empty();
            parseo.map((element)=>{
                libras=parseFloat(element.Libras);
                console.log(libras);
                var id_row='row'+cant;
                var fila=
                '<tr id='+id_row+'><td >'+element.Articulo+'</td><td>'
                +element.Descripcion+'</td><td >'+element.Cantidad+'</td><td>'
                +libras+'</td></tr>';
                $("#tablaCuerpo").prepend(fila);
                cant++;

            })
            $("#sumaCantidad").text(sumaCantidad);
            $("#sumaLibras").text(sumaLibras);
        },
        error: function (xhr, status, error) {
           
        }
    })
})

$("#codigoBarra").change(function(){
    let selectedValue=$(this).val();
    let selectedArticulo=$('option:selected',this).data('articulo');
    let selectedDescripcion=$('option:selected',this).data('descripcion');
    let selectedUsuario=$('option:selected',this).data('usuario');
    $('#descripcion').val(selectedDescripcion);
    $('#articulo').val(selectedArticulo);
    $('#usuario').val(selectedUsuario);
   //console.log(selectedArticulo);
   //console.log(selectedDescripcion);
});
$('#bodegaOrigen').change(function(){
    let esteDia=new Date();

    let ahora =esteDia.toLocaleString();
    console.log(ahora);
    $('#fechaHoraTemporal').val(ahora);
    
})


let botonAgregarDetalle=document.getElementById("agregarDetalle");
//let inputCodigo=document.getElementById("introCodigo");

let data=[];
let cant=0;
let storedValue="";
let storedValueFecha="";
const insertar=(data,callback)=>{
    const datos=JSON.stringify(data);
    console.log(datos);
    postData="datos="+datos;
    $.ajax({
        url: 'controladorTraslado.php',
        type: 'POST',
        data: postData,
        success: function (response) {
            // console.log('Envío de contador exitoso. Respuesta:', response);
            if (typeof callback === 'function') {
                callback(null, response)
            }
            //let url='contadorPaginas.php';
            //window.open(url,'_blank');
        },
        error: function (xhr, status, error) {
            //console.log('Error al enviar el contador', error);
            if (typeof callback === 'function') {
                callback(error, error);
            }
        }
    })

}



const agregar=()=>{
    let bodegaOrigen;
    let bodegaDestino;
    let fechaOrigen;

    let pendiente=document.getElementById('pendiente').value;
    if(pendiente==''){
        bodegaOrigen=document.getElementById('bodegaOrigen').value;
    }else{
        bodegaOrigen=document.getElementById('bodegaOrigen2').value;
    }

    if(pendiente==''){
        bodegaDestino=document.getElementById('bodegaDestino').value;
    }else{
        bodegaDestino=document.getElementById('bodegaInputDestino').value;
    }

   
    
 
    let descripcion=document.getElementById("descripcion").value;
    let articulo=document.getElementById("articulo").value;
    let codigoBarra=document.getElementById("barraCodigo").value;
    let usuarioCreacion=document.getElementById("usuario").value;

    if(pendiente==''){
         fechaOrigen=document.getElementById("fecha").value;
    }else{
        fechaOrigen=document.getElementById("fechaInputDestino").value;
    }

    let fechaHoraTemporal=document.getElementById("fechaHoraTemporal").value;
    let libras=document.getElementById("libras").value;
    if(bodegaOrigen.trim()===""){
        console.log("El campo bodega origen esta vacio");
        return;
    }
    if(bodegaDestino.trim()===""){
        console.log("el campo bodega destino esta vacio");
        return;
    }
    if(fechaOrigen.trim()===""){
        console.log("el campo fecha origen esta vacio");
        return;
    }
    if(codigoBarra.trim()===""){
        console.log("el campo codigo de barra esta vacio ");
        return;
    }


    let esteDia=new Date();

    let ahora =esteDia.toLocaleString();
    
    data.push(
        {
            "id":cant,
            "articulo":articulo,
            "bodegaOrigen":bodegaOrigen,
            "bodegaDestino":bodegaDestino,
            "codigoBarra":codigoBarra,
            "fechaOrigen":fechaOrigen,
            "usuario":usuarioCreacion,
            "documento":fechaHoraTemporal,
            "descripcion":descripcion,
            "libras":libras
           
            

        }
    );



    if(bodegaOrigen === bodegaDestino){
        Swal.fire({
            icon: 'error',
            title: 'Ocurrio error',
            text: 'No se puede elegir las mismas bodegas'
        })
        return;
    }
    
    insertar(data,function(error,response){
        if(error){
            console.log("Error al enviar los datos");
        }else{
            agregarFilaTabla(response);
        }
    });
}

const agregarFilaTabla=(data)=>{
    console.log(data);
    const filas = JSON.parse(data);
    let codigoBarra;
    console.log(filas);
    const status=filas.status;
    const articulo=filas.articulo;
    const descripcion=filas.descripcion;
    const libras=filas.libras;
    codigoBarra=filas.codigoBarra;
    if (status==="error") {
        Swal.fire(
            'Ha ocurrido un error',
            ''+data,
            'question'
          )
        return;
        
    }
    var id_row='row'+cant;
    var fila=
    '<tr id='+id_row+'><td>'+articulo+'</td><td>'
    +descripcion+'</td><td>'+codigoBarra+'</td><td>'
    +libras+'</td><td><a href="#" class="btn btn-primary" onclick="eliminarFila(\''+codigoBarra+'\','+cant+')";>Eliminar</a></td></tr>';
    $("#articulos").prepend(fila);
    cant++;
}
function eliminarFila(codigo,row){
    Swal.fire({
        title: 'Estas seguro que quieres eliminar este registro',
        text: "Si eliminas el registro no se podra revertir los cambios",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
            $(document).ready(function(){
                let fechaHoraTemporal = document.getElementById("fechaHoraTemporal").value;
                postData="fechaTemporal="+fechaHoraTemporal+"&codigo="+codigo;
          
                //console.log("Codigo"+codigo+"Documento"+fechaHoraTemporal);
              $.ajax({
                  url: 'deleteTraslado.php',
                  type: 'GET',
                  data: postData,
                  success: function (response) {
                    Swal.fire(
                        'Eliminado',
                        'Tu registro se elimino correctamente',
                        'success'
                      )
                      eliminar(row);
                      
                  },
                  error: function (xhr, status, error) {
                     
                  }
              })
          })
         
        }
      })    
}

const eliminar=(row)=>{
    //remover la fila de la tabla html
    console.log(row);
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
   
  
 
}

//botonAgregarDetalle.addEventListener("click",agregar);

$("#introCodigo").on('keyup',function(e){
    let keycode = e.KeyCode || e.which;
    if(keycode  == 13){
        let contenido =$(this).val();

        buscarCodigo(contenido);
       
        $(this).val('');
        
    }
})

const buscarCodigo=(codigo)=>{

    $.ajax({

        url: 'getArchivo.php',
        method:'POST',
        data:{codigo:codigo},
        success: function(response){
            //const parseo= JSON.parse(response);
            //let articulo=response.Articulo;
            let articulo;
            let codigoBarra;
            let descripcion;
            let libras;

            response.map((element)=>{
                articulo=element.Articulo;
                codigoBarra=element.CodigoBarra;
                descripcion=element.Descripcion;
                libras=element.Libras;

            })

            $('#articulo').val(articulo);
            $('#barraCodigo').val(codigoBarra);
            $('#descripcion').val(descripcion);
            $('#libras').val(libras);
            agregar();

            
        },
        error:function(xhr,status,error){
            console.log(error);
        }
    })


}

$("#pendiente").click(function(){
    
})
//METODOS PARA CONTINUAR LOS TRASLADOS

//const imprimirTabla=()=>{
      //var id_row='row'+cant;
const imprimirTabla = (dataObj) => {

    console.log(JSON.stringify(dataObj));
  
    let get=document.getElementById('pendiente').value;
    let parseo=JSON.parse(dataObj);
    data=parseo;
    console.log(data);
    let contador=1;
    console.log(parseo);
    if(get ===''){
        alert("jsdfjklsdjklfjsdklf")
        return;
    }
    
    parseo.map((element)=>{
        var id_row='row'+contador;
        var row = '<tr id='+id_row+'>';
        //row += "<td>"+ contador + "</td>"; 
        row += "<td>" + element.Articulo + "</td>";
        row += "<td>" + element.Descripcion + "</td>";
        row += "<td>" + element.CodigoBarra + "</td>";
        row += "<td>" + element.Libras + "</td>";
        row+=  '<td><a href="#" class="btn btn-primary" onclick="eliminarFila(\''+element.CodigoBarra+'\','+contador+')";>Eliminar</a></td>'
        row += "</tr>";

        $("#articulos").prepend(row);

        contador++;
        

    })
    
};


$(document).ready(function() {
   
$("#bodegaDestino2").change(function(){
    let opcion=document.getElementById("opcion").value;
    console.log(opcion);
   
   
    var selectedValue = $(this).val();
    var selectedText = $(this).find("option:selected").text();
    let bodegaOrigen2=document.getElementById("bodegaOrigen2").value;
    if(selectedValue===bodegaOrigen2){
        Swal.fire('No se puede modificar la bodega destino porque coincide con bodega origen')
        return;
    }
    if (confirm("¿Desea seleccionar '" + selectedText + "'?")) {
       introducir(selectedValue);
      } else {
        
        var firstOption = $("#bodegaDestino2 option:first");
        //$("#bodegaInputDestino").value(firstOption);
        // Establecer la primera opción como seleccionada
        firstOption.prop("selected", true);
        $("#bodegaDestino2").select2({
            theme: 'bootstrap-5',
            placeholder: 'Seleccione la bodega de origen',
        })

    }

})
});

//EVENTO  DE LA FECHA
function introducirFecha(fecha){
    $("#fechaInputDestino").val(fecha);
    let fechaInputDestino=document.getElementById("fechaInputDestino").value;
    localStorage.setItem("fechaInputValue",fechaInputDestino);
    introducirLocalFecha();
    
    let fechaHoraTemporal=document.getElementById("fechaHoraTemporal").value;
    let url="fecha="+fecha+"&fechaHoraTemporal="+fechaHoraTemporal;
    

    $.ajax({
        url: 'controladorEditarFecha.php',
        type:"POST",
        data: url,
        success:function(response){
            console.log(response);
        },
        error: function(xhr,status,error){
            console.log(error);
        }
    })


}


function introducir(valor){
    $("#bodegaInputDestino").val(valor);
    let xd=document.getElementById("bodegaInputDestino").value;
    localStorage.setItem("bodegaInputValue",xd);
    
    introducirLocal();
   
   let fechaHoraTemporal=document.getElementById("fechaHoraTemporal").value;
   
    let url="dato="+valor+"&fechaHoraTemporal="+fechaHoraTemporal;
    $.ajax({
        url: 'controladorEditarTraslado.php',
        type: 'POST',
        data: url,
        success: function (response) {
            console.log(response);
            // console.log('Envío de contador exitoso. Respuesta:', response);
            
            //let url='contadorPaginas.php';
            //window.open(url,'_blank');
        },
        error: function (xhr, status, error) {
            //console.log('Error al enviar el contador', error);
        
   
        }
    })
}

function introducirLocal(){
    storedValue=localStorage.getItem("bodegaInputValue");
    $("#bodegaLocal").val(storedValue);
    console.log(storedValue);
}

function introducirLocalFecha(){
    storedValueFecha=localStorage.getItem("fechaInputValue");
    $("#fechaLocal").val(storedValueFecha);
    console.log(storedValueFecha);
}
window.onload=function() {
    introducirLocal();
    introducirLocalFecha();

}


$("#cancelar").click(function(){
   let fechaHoraTemporal=document.getElementById('fechaHoraTemporal').value;

    if(fechaHoraTemporal===''){
        Swal.fire(
            'Ocurre un problema',
            'El campo bodega origen no puede estar vacio, tiene que seleccionar'+ 
            'la bodega de origen para que se genere el DocInv',
            'question'
          )

          return;
    }

    if(data.length===0){
        Swal.fire(
            'Ocurre un problema',
            'No se ha agregado ningun articulo a la tabla por eso no se puede cancelar',
            'question'
          )

          return;
    }
   let dataUrl="fechaHoraTemporal="+fechaHoraTemporal;


    Swal.fire({
        title: 'Estas seguro que deseas eliminar el traslado',
        text: "Si eliminas ya no se podra revertir estos cambios",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({

                url:"controladorEliminarTraslado.php",
                type:'POST',
                data:dataUrl,
                success:function(response){
                    let respuesta=JSON.parse(response);
                   console.log(respuesta);
                   if(respuesta.status==="success"){
                    Swal.fire({
                     
                        icon: 'success',
                        title: 'Traslados cancelados exitosamente',
                        showConfirmButton: false,
                        timer: 1500
                      })
                        setTimeout(function(){
                            window.location.href="indexPrincipalTraslado.php";
                        },
                        1000);
                   }
                },
                error:function(xhr,status,error){
                    console.log(error);
                }
               })
        }
    })

  
})

$("#finalizar").click(function(){
   
    let fechaHoraTemporal=document.getElementById("fechaHoraTemporal").value;
    let bodegaOrigen;
    let bodegaDestino;
    let fechaOrigen;
    let json=JSON.stringify(data);

    let pendiente=document.getElementById('pendiente').value;
    if(pendiente==''){
        bodegaOrigen=document.getElementById('bodegaOrigen').value;
    }else{
        bodegaOrigen=document.getElementById('bodegaOrigen2').value;
    }

    if(pendiente==''){
        bodegaDestino=document.getElementById('bodegaDestino').value;
    }else{
        bodegaDestino=document.getElementById('bodegaInputDestino').value;
    }
    if(pendiente==''){
        fechaOrigen=document.getElementById("fecha").value;
   }else{
       fechaOrigen=document.getElementById("fechaInputDestino").value;
   }

   if(bodegaOrigen === bodegaDestino){
        console.log("No se pueden elegir las mismas bodegas");
        return;
   }


    let objetoDatos={
        fechaHoraTemporal:fechaHoraTemporal,
        bodegaOrigen: bodegaOrigen,
        bodegaDestino: bodegaDestino,
        fechaOrigen: fechaOrigen,
        json:json
    }
    
    $.ajax({

        url:"controladorFinalizarTraslado.php",
        type:'POST',
        data:objetoDatos,
        success:function(response){
            console.log(response);
            let data=JSON.parse(response);
            let documento=data.documentoConsecutivo;
            if(data.message === "Registro exitoso"){
                //console.log(data);
                window.location.href="pdfTraslado.php?documento="+documento;
            }
           //console.log(response);
        },
        error:function(xhr,status,error){
            console.log(error);
        }
    })
    //enviarObjetoDatos();
})









