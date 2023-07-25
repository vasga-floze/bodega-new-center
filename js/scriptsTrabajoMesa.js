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
    $('#').DataTable({
      "scrollY": "40vh",
      "scrollCollapse": true,
    });
    $('.dataTables_length').addClass('bs-select');
  });
 $( '#bodega' ).select2( {
    theme: 'bootstrap-5',
    placeholder: 'Seleccione la bodega',
} );

let cant=0;
let captura=[]
const suma=0;
let objetoSuma={}

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
            let data=JSON.parse(response);
            console.log(data);
            let success=data.success
            let message=data.message
            let descripcion=data.descripcion
            let articulo=data.articulo
            let libras=data.libras
            let codigoBarra=data.codigoBarra
            
            /*if(success !=="1"){
                Swal.fire({
                    icon: 'error',
                    title: 'Hubo un error',
                    text: message
                  })
                return;

            }*/

            //console.log(libras);
            if(libras===undefined){
                libras=0;
            }
            let costo=data.costo
            const sum = Number(libras);
            console.log(sum);
            const costoConvert=Number(costo);
            objetoSuma={
                id:cant,
                suma:sum,
                sumaCosto:costoConvert
            }

            captura.push(objetoSuma);
            const sumLibras=captura.reduce((previous, current) => {
                return previous + Number(current.suma);
            }, 0);

            const cumCosto=captura.reduce((previous, current) =>{
                return previous + Number(current.sumaCosto)
            },0)
           
            document.getElementById("inputCumCosto").value=cumCosto

           // console.log(sumLibras);
           // console.log(captura);
            document.getElementById('totalLibras').innerHTML=`TOTAL DE LIBRAS: ${sumLibras}`

            if (success==="1") {
                
                let id_row='row'+cant;
                let fila=
                '<tr id='+id_row+'><td>'+articulo+'</td><td>'
                +descripcion+'</td><td>'+libras+'</td><td>'+codigoBarra+'</td>'+
                '<td><a href="#" class="btn btn-primary" onclick="eliminar('+cant+',\'' + codigoBarra + '\')";>Eliminar</a></td></tr>'
           
                
                $("#tablaContenedores").append(fila);
                cant++
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Hubo un error',
                    text: message
                })
                
            }
        }


    })

})


function eliminar(fila,codigo) {
    let dataUrl="codigo="+codigo;
    $.ajax({
        url:'controladorEliminarMesa.php',
        type:'POST',
        data:dataUrl,
        success: function(response){
            let responseData=JSON.parse(response)
            let success=responseData.success
            let message=responseData.message
            if(success !=="1"){
                console.log(message);
                return;
            }

            $("#row"+fila).remove();
            var i=0;
            var pos=0;
            for(x of captura){
                if(x.id==fila){
                    pos=i;
                }
                i++;
            }
            captura.splice(pos,1);
            sumaTotalLibras()
        }


    })
}

function sumaTotalLibras(){
    console.log("Entro a la funcion");
    console.log(captura);
    const sumLibras=captura.reduce((previous, current) => {
        return previous + Number(current.suma);
    }, 0);
    document.getElementById('totalLibras').innerHTML=`TOTAL DE LIBRAS: ${sumLibras}`

}
