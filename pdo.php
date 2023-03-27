<!DOCTYPE html>
<html>
<head>
  <title></title>
  <script type="text/javascript" src="Chart.min.js"></script>
  <script type="text/javascript" src="moment.min.js"></script>
  
  <script>
   $(document).ready(function()
   {
    alert();
   })
      function mostrar()
      {
          if($("#usu").val()=='')
          {
            $("#t").hide(0);
          }else 
          {
            $("#t").show(200);
          }
      
        
      }

      function mostrar1()
      {
          if($("#usu1").val()=='')
          {
            $("#t1").hide(0);
          }else 
          {
            $("#t1").show(200);
          }
      
        
      }
  </script>
  </head>
<body>
 <?php
include("conexion.php");
$fecha=date("Y-m-d");

$dia=date("Y-m-d h:i:s");
echo "<input type='text' name='hora' id='hora' value='$dia'";
?>
<div style="background-color: rgb(0,0,0,0.8); width: 60%; height:310px; padding-top: 0.8%; border-radius: 10px;">
    <div style="background-color: black; width: 70%; height: 90%;  margin-left: 9%; border-radius: 10px; float: center;">
        <spam id='t' style='display: none; color: white; margin-left: 0%;'>NOMBRE USUARIO</spam><br>
       <input type="text" name="n" placeholder="NOMBRE USUARIO" id="usu" style="border-right: none; border-left: none; border-top: none; border-bottom-style: double; background-color: rgb(0,0,0,0.8); color: white; width: 99%; padding: 0.5%;" onkeyup="mostrar()"><br><br>
       <spam id='t1' style='display: none; color: white; margin-left: 0%;'>NOMBRE USUARIO</spam><br>
       <input type="text" name="n1" placeholder="NOMBRE USUARIO" id="usu1" style="border-right: none; border-left: none; border-top: none; border-bottom-style: double; background-color: rgb(0,0,0,0.8); color: white; width: 99%; padding: 0.5%;" onkeyup="mostrar1()">
       <button>d</button>
    </div>
</div>


<div style="background-color: white; width: 50%; height: 50%; float: center;">
 <canvas id="myChart" style=" width: 100% height:100%;"></canvas>
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php
        	$c=$conexion2->query("select empacado as codigo from registro where fecha_documento='$fecha' and tipo='p' group by empacado")or die($conexion2->error());
        	while($f=$c->FETCH(PDO::FETCH_ASSOC))
        	{
        		$cod=$f['codigo'];
        		echo "'$cod',";
        	}
        	?>],
        datasets: [{
            label: 'CANTIDAD EMPACADA',
            data: [<?php
        	$c=$conexion2->query("select empacado,count(*) as codigo from registro where fecha_documento='$fecha' and tipo='p' group by empacado")or die($conexion2->error());
        	while($f=$c->FETCH(PDO::FETCH_ASSOC))
        	{
        		$cod=$f['codigo'];
        		echo "'$cod',";
        	}

        	?>],
            backgroundColor: [
                '#000'
            ],
            borderColor: [
                'red',
            ],
            borderWidth: 1
        }]


    },

    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>
</div>
<div style="background-color: black; width: 50%; height: 50%; float: left;">
 <canvas id="myChart1" style="background-color: white; width: 10% height:20%;"></canvas>
<script>
var ctx = document.getElementById('myChart1').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [<?php
            $c=$conexion2->query("select producido as codigo from registro where fecha_documento='$fecha' and tipo='p' group by producido")or die($conexion2->error());
            while($f=$c->FETCH(PDO::FETCH_ASSOC))
            {
                $cod=$f['codigo'];
                echo "'$cod',";
            }
            ?>],
        datasets: [{
            label: 'CANTIDAD PRODUCIDA',
            data: [<?php
            $c=$conexion2->query("select producido,count(*) as codigo from registro where fecha_documento='$fecha' and tipo='p' group by producido")or die($conexion2->error());
            while($f=$c->FETCH(PDO::FETCH_ASSOC))
            {
                $cod1=$f['codigo'];
                echo "'$cod1',";
            }

            ?>],
            backgroundColor: [
                '#fff'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
            ],
            borderWidth: 1
        }]


    },

    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>
</div>

<div style="position: sticky; top: 0; background-color: red; width: 10%; height: 2%; float: right; margin-top: -70%; color: white;">
  vbb
</div>

</body>
</html>