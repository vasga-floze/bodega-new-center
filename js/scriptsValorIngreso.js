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




$(function(){
    $("#fecha").datepicker({
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

$("#precio").on('keypress',function(evento){
    let char =String.fromCharCode(evento.which);
    if(!/[0-9\.]/.test(char)){
     evento.preventDefault();
    }
 
    if(char === "." && input.indexOf(".") !== -1 ){
     evento.preventDefault();
    }
  })
 
let table=$('#myTable').DataTable();
let fila;
$("#generar").click(function(){
    table.destroy();

    let fecha=document.getElementById('fecha').value;
    let contenedor=document.getElementById('contenedor').value;

    let data="fecha="+fecha+"&documento="+contenedor;

    $.ajax({
        url: "getContenedores.php",
        type: "POST",
        data: data,

        success:function(response){
           let data=JSON.parse(response);
            console.log(data);
            var tabla=$('#myTable').DataTable({
                "bPaginate":false,
                "data": data.data,
                "footerCallback": function ( row, data, start, end, display ) {
        
                    total = this.api()
                        .column(4)//numero de columna a sumar
                        //.column(1, {page: 'current'})//para sumar solo la pagina actual
                        .data()
                        .reduce(function (a, b) {
                            return parseFloat(a) + parseFloat(b);
                        }, 0 );
                    totalArticulo=this.api()
                            .column(6)
                            .data()
                            .reduce(function(a,b){
                                return parseFloat(a)+parseFloat(b);
                            },0);
                        $("#total").text(total)
                        $("#totalArticulo").text(totalArticulo)
                    
                },
                "columns": [
                    {"data": "nombreArticulo"},
                    {"data": "articulo"},
                    
                    {"data": "descripcion"},
                    {"data": "Cantidad"},
                    {"data": "subtotal"},
                    {"data": "porcentaje"},
                    
                    {"data": "totalArticulo"},
                    {"data": "precioUnitario"},
                    {"defaultContent": "<button class='btn btn-primary calcular'>Calcular</button>"}
                ],
                "rowId": "articulo"
            })

          

        }
    })
})
let articulo;
let idArticulo
let subtotal;
let porcentaje;
let cantidad;
let subtotalArreglo;
let rowData;
let objetoDatos;
$(document).on("click", ".calcular",function(){
    
    fila=$(this).closest("tr");
    nombreArticulo = fila.find('td:eq(0)').text();
    articulo=fila.find('td:eq(1)').text();
    let nombre = fila.find('td:eq(2)').text();
    cantidad = fila.find('td:eq(3)').text();
    subtotal = fila.find('td:eq(4)').text();
    porcentaje = fila.find('td:eq(5)').text();
 
    let table = $('#myTable').DataTable();
    $('#articulo').val(articulo);
    $('#descripcion').val(nombre);
    $('#cantidad').val(cantidad);
    $('#precio').val(subtotal);
    $('#modalEditar').modal('show')
})

let dataArray=[];

$('#guardar').click(function(){
    let tablaActualizada;
    let fechaCabecera=document.getElementById('fecha').value;
    let contenedorCabecera=document.getElementById('contenedor').value;
    let precioUnitario = document.getElementById('precio').value;
    let gasto=document.getElementById('gasto').value;
    let table = $('#myTable').DataTable();

    let resultado=parseFloat(precioUnitario)*parseFloat(cantidad);
    table.cell('#' + articulo, 4).data(resultado.toFixed(2)).draw();

    /*let porcentaje=parseFloat(resultado)/parseFloat(total);
    table.cell('#' + articulo, 4).data(porcentaje.toFixed(2)).draw();*/


    dataArray=[]
    table.rows().every(function(rowIdx, tableLoop, rowLoop) {
       // let porcentaje=parseFloat(resultado)/parseFloat(total);
        rowData = this.data();
        //console.log(rowData);
        let subtotalFila=parseFloat(rowData.subtotal);
        let cantidadFila=parseFloat(rowData.Cantidad);
        let bodega=rowData.bodega
        let articuloFila=rowData.articulo
        let nombreArticulo=rowData.nombreArticulo
        
     
        subtotalArreglo=subtotalFila;
  
        //console.log(cantidadFila);
        //console.log(parseFloat(rowData.subtotal));
        let porcentaje=subtotalFila /total;
        let gastoCalculado=gasto*porcentaje;
        let articulo = gastoCalculado+subtotalFila; 
        let precio=articulo/cantidadFila;
        objetoDatos={
            articulo:nombreArticulo,
            subtotalFila:subtotalFila,
            porcentaje:porcentaje,
            totalArticulo:articulo,
            precioUnitario:precio,
            fecha:fechaCabecera,
            contenedor:contenedorCabecera,
            cantidadFila:cantidadFila,
            bodega:bodega
            
        }
        //console.log(rowIdx);
        dataArray.push(objetoDatos);
        //dataActualizada.push(objetoDatos);
        table.cell(rowIdx, 5).data(porcentaje.toFixed(4)).draw();
      
        table.cell(rowIdx, 6).data(articulo.toFixed(2)).draw();
        table.cell(rowIdx, 7).data(precio.toFixed(6)).draw();
        tablaActualizada=table.cell(rowIdx,6).data();

        
        
    });
    
    console.log(dataArray);
   // console.log(subtotalFila);
})


$('#finalizar').click(function(){
    if (dataArray.length===0) {
        console.log("No se puede finalizar porque no has calculado ningun dato");

        return;
        
    }
    
    $.ajax({
        url: 'controladorValorContenedor.php',
        type: 'POST',
        data: {dataArray:dataArray},

        success:function(response){
            const respuesta=JSON.parse(response);
            console.log(respuesta);
            if(respuesta.success==="1"){
                Swal.fire({
                    
                    icon: 'success',
                    title: respuesta.message,
                    showConfirmButton: false,
                    timer: 1500
                  })

                  setTimeout(() => {
                    window.location.reload()
                  }, 1500);
               
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: respuesta.message
                  })
            }
        }


    })

})



$('#cerrar').click(function(){
    $('#modalEditar').modal('hide')
})
