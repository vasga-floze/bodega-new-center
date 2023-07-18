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
 $(document).ready(function () {
    $('#tabledScroll').DataTable({
      "scrollY": "40vh",
      "scrollCollapse": true,
    });
    $('.dataTables_length').addClass('bs-select');
  });
 $( '#bodega' ).select2( {
    theme: 'bootstrap-5',
    placeholder: 'Seleccione la bodega',
} );


$('#agregar').click(function(){

    ///console.log("Agregar el articulo");
    const fecha=document.getElementById('fecha').value
    const bodega=document.getElementById('bodega').value
    const codigo=document.getElementById('codigoBarra').value

    const data="fecha="+fecha+"&bodega="+bodega+"&codigo="+codigo;

    $.ajax({
        url:"controladorTrabajoMesa.php",
        type:"POST",
        data: data,

        success:function(response){
            console.log(response);
        }


    })

})


$(document).ready(function(){
    const datos=[
        {articulo:'Producto A', libras:10},
        {articulo:'Producto B', libras:5},
        {articulo: 'Producto C', libras:7},
        {articulo:'Producto A', libras:10},
        {articulo:'Producto B', libras:5},
        {articulo: 'Producto C', libras:7},
        {articulo:'Producto A', libras:10},
        {articulo:'Producto B', libras:5},
        {articulo: 'Producto C', libras:7},
        {articulo:'Producto A', libras:10},
        {articulo:'Producto B', libras:5},
        {articulo: 'Producto C', libras:7},
        {articulo:'Producto A', libras:10},
        {articulo:'Producto B', libras:5},
        {articulo: 'Producto C', libras:7},
        {articulo:'Producto A', libras:10},
        {articulo:'Producto B', libras:5},
        {articulo: 'Producto C', libras:7}
    ]

    const tabla=document.getElementById('tabledScroll').getElementsByTagName('tbody')[0];
    for(const dato of datos){
        const fila = tabla.insertRow();


        const celdaArticulo=fila.insertCell();
        const celdaLibras=fila.insertCell();


        celdaArticulo.textContent=dato.articulo
        celdaLibras.textContent=dato.libras
    }
})