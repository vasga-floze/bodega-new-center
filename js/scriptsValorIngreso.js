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
            
            $('#myTable').DataTable({
                "data": data.data,
                "columns": [
                    {"data": "articulo"},
                    {"data": "descripcion"},
                    {"data": "Cantidad"},
                    {"data": "subtotal"},
                    {"defaultContent": "<button class='btn btn-primary calcular'>Calcular</button>"}
                ],
                "rowId": "articulo"
            })
        }
    })
})

$(document).on("click", ".calcular",function(){

    fila=$(this).closest("tr");
    let articulo = fila.find('td:eq(0)').text();
    let nombre = fila.find('td:eq(1)').text();
    let cantidad = fila.find('td:eq(2)').text();
    let subtotal = fila.find('td:eq(3)').text();
    console.log(cantidad);
    console.log(subtotal);

    let resultado=parseFloat(cantidad)+parseFloat(subtotal);
    let table = $('#myTable').DataTable();
    table.cell('#' + articulo, 3).data(resultado.toFixed(2)).draw();

    $('#cantidad').val(cantidad);
    $('#precio').val(subtotal);
    $('#modalEditar').modal('show')
})

$('#cerrar').click(function(){
    $('#modalEditar').modal('hide')
})
