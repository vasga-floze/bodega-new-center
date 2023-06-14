
$(function () {
    $("#fechaInicio").datepicker({
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
    $("#fechaFinal").datepicker({
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
    $("#generar").click(function () {
        let fechaInicio = document.getElementById("fechaInicio").value;
        let fechaFinal = document.getElementById("fechaFinal").value;
        let codigoArticulo = document.getElementById("codigoArticulo").value;
        let codigoBarra = document.getElementById("codigoBarra").value;
        let ruta = "getConsultaReimpresion.php?fechaInicio=" + fechaInicio + "&fechaFinal=" + fechaFinal + "&codigoArticulo=" + codigoArticulo + "&codigoBarra=" + codigoBarra;
        $.getJSON(ruta, function (data) {
            console.log(data);
            const tabla = $('#myTable').DataTable({
                "destroy": true,
                "data": data,
                "columns": [
                    { "title": "Articulo", "data": "Articulo" },
                    { "title": "Clasificacion", "data": "Clasificacion" },
                    { "title": "CodigoBarra", "data": "CodigoBarra" },
                    { "title": "Descripcion", "data": "Descripcion" },
                    { "title": "Ubicacion", "data": "Ubicacion" },
                    { "title": "Unidades", "data": "Unidades" },
                    { "defaultContent": "<button class='mt-2 btn btn-warning detalle'>Detalle</button>" },
                    { "defaultContent": "<button class='mt-2 btn btn-warning paquete'>Paquete</button>" },
                    { "defaultContent": "<button class='mt-2 btn btn-warning adhesivo'>Adhesivo</button>"}
                ]
            });

            $('#myTable').on('click', '.detalle', function () {

                Swal.fire({
                    title: 'Estas seguro que deseas reimprimir etiquetas detalles',

                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, deseo reimprimir'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let row = $(this).closest('tr');
                        let dataRow = tabla.row(row).data();
                        let CodigoBarra = dataRow.CodigoBarra;
                        let CodigoArticulo = dataRow.Articulo;
                        let descripcion = "DESCRIPCION";
                        let accion = 1;
                        enviarContador(CodigoBarra, accion, function (error, mensaje) {

                            let data = JSON.parse(mensaje);
                            let mensajeServidor=data.message;
                            console.log(data);

                            if(!(data.status==="success")){

                                Swal.fire(
                                    'No se puede reimprimir',
                                    mensajeServidor,
                                    'question'
                                )
                                return;
                                

                            }
                            let url = 'example.php?descripcion='
                            + encodeURIComponent(descripcion) +
                            "&codigo="
                            + encodeURIComponent(CodigoBarra) +
                            "&codigoArticulo="
                            + encodeURIComponent(CodigoArticulo);
                        //let url='editar.php?articulo='+encodeURIComponent(articulo);
                             window.open(url, '_blank');
                           


                        });

                    }
                })
            });

            $('#myTable').on('click', '.adhesivo', function () {

                Swal.fire({
                    title: 'Estas seguro que deseas reimprimir etiquetas detalles',

                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, deseo reimprimir'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let row = $(this).closest('tr');
                        let dataRow = tabla.row(row).data();
                        let CodigoBarra = dataRow.CodigoBarra;
                        let CodigoArticulo = dataRow.Articulo;
                        let descripcion = "DESCRIPCION";
                        let accion = 3;
                        enviarContador(CodigoBarra, accion, function (error, mensaje) {

                            let data = JSON.parse(mensaje);
                            let mensajeServidor=data.message;
                            console.log(data);

                            if(!(data.status==="success")){

                                Swal.fire(
                                    'No se puede reimprimir',
                                    mensajeServidor,
                                    'question'
                                )
                                return;
                                

                            }
                            let url = 'pdfAdhesivo.php?descripcion='
                            + encodeURIComponent(descripcion) +
                            "&codigo="
                            + encodeURIComponent(CodigoBarra) +
                            "&codigoArticulo="
                            + encodeURIComponent(CodigoArticulo);
                        //let url='editar.php?articulo='+encodeURIComponent(articulo);
                             window.open(url, '_blank');
                           


                        });

                    }
                })

                
            });
            let contador = 0;
            $('#myTable').on('click', '.paquete', function () {
                contador++;

                Swal.fire({
                    title: 'Estas seguro que deseas imprimir etiqueta complemento',

                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, reimprimir'
                }).then((result) => {
                    if (result.isConfirmed) {

                        let row = $(this).closest('tr');
                        let dataRow = tabla.row(row).data();
                        let CodigoBarra = dataRow.CodigoBarra;

                        let CodigoArticulo = dataRow.Articulo;
                        let descripcion = "DESCRIPCION";
                        let accion = 2;

                        enviarContador(CodigoBarra, accion);
                        let url = 'barraComplemento.php?descripcion=' + encodeURIComponent(descripcion) + "&codigo=" + encodeURIComponent(CodigoBarra);

                        window.open(url, '_blanck');
                        //let url='editar.php?articulo='+encodeURIComponent(articulo);
                        //window.open(url,'_blank');
                    }
                })
            });
        })
    })
});


function enviarContador(codigoBarra, accion, callback) {
    let data = "codigoBarra=" + codigoBarra + "&accion=" + accion;
    console.log(data);
    $.ajax({
        url: 'contadorPaginas.php',
        type: 'POST',
        data: data,
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


