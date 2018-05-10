<?php
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();

if (!isset($_COOKIE['idUsuario'])) {
  //header('location:/Websites/TimeTracking/index.php');
  var_dump($_COOKIE);
} else {
  $idUsuario = $_COOKIE['nombreUsuario'];
}

include('../Resources/PHP/loginDatabase.php');

$qry = "SELECT * FROM Operator_TimeLog WHERE Operador = '$idUsuario' ORDER BY pkTimelog DESC LIMIT 1";
$stmt = $login->query($qry) or die ('Error ('.$login->errno.'): '.$login->error);
$rslt = $stmt->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../Resources/Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Resources/Clock/jquery.flipcountdown.css">
    <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="../Resources/CSS/main.css">
    <link rel="stylesheet" media="screen and (max-device-width: 700px)" href="../Resources/CSS/mainMobile.css">
    <link href="https://fonts.googleapis.com/css?family=Sansita" rel="stylesheet">
  </head>
  <body>

    <div class="container-fluid text-left time-clock" style="padding:0% 5% 5% 5% !important;">
      <div class="text-center" id="clock" style="padding-top: 3%;">
      </div>

      <div class="">
        <p class=""><?php echo $_COOKIE['Nombre'] . " " . $_COOKIE['Apellido']?></p>
      </div>
      <hr>
      <div id="botonesPonchador" class="w-100">
        <?php if ($rslt['SiguienteRegistro'] == "Salida"): ?>

          <button class="btn btn-danger punch-btn w-100" type="button" name="marcarSalida" id="btnMarcarSalida" style="height: 100px">Marcar Salida</button>

          <button class="btn btn-success punch-btn w-100" type="button" name="marcarEntrada" id="btnMarcarEntrada" style="height: 100px; display:none">Marcar Entrada</button>
        <?php else: ?>

          <button class="btn btn-danger punch-btn w-100" type="button" name="marcarSalida" id="btnMarcarSalida" style="height: 100px; display: none">Marcar Salida</button>

          <button class="btn btn-success punch-btn w-100" type="button" name="marcarEntrada" id="btnMarcarEntrada" style="height: 100px">Marcar Entrada</button>
        <?php endif; ?>


        <!--button class="btn btn-success punch-btn w-100" type="button" name="marcarEntrada" id="btnMarcarEntrada" style="height: 100px" onclick="punch_card('Entrada', <?php //echo $idUsuario ?>);">Marcar Entrada</button>
        <button class="btn btn-danger punch-btn w-100" type="button" name="marcarSalida" id="btnMarcarSalida" style="height: 100px; display: none" onclick="punch_card('Salida', <?php //echo $idUsuario ?>)">Marcar Salida</button-->
      </div>
      <br>
      <div class="card" style="margin-top: -25px">
        <div class="card-header">
        <h3 class="">Último Registro</h3>
      </div>
        <div class="card-block">
          <p class="card-text"><span id="divTipoRegistro">Entrada</span><br>
          <span id="spanFechaEntrada"><?php echo $rslt['FechaEntrada'] . '</span> | <span id="spanHoraEntrada">' . $rslt['HoraEntrada'] ?></span></p>
          <p class="card-text"><span id="divTipoRegistro">Salida</span><br>
          <span id="spanFechaSalida"><?php echo $rslt['FechaSalida'] . '</span> | <span id="spanHoraSalida">' . $rslt['HoraSalida'] ?></span></p>
        </div>
      </div>
    </div>
    <button type="submit" name="cerrarSesion" class="fixed-bottom btn btn-info" style="font-size: 2em; border-radious: 0px;" onclick="cerrar_sesion();">
      Cerrar Sesión
    </button>

    <!-- jQuery first, then Tether, then Bootstrap JS. -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    <script src="../Resources/Clock/jquery.flipcountdown.js" charset="utf-8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="../Resources/Bootstrap/js/bootstrap.min.js"></script>
    <script src="../Resources/JS/functions.js" charset="utf-8"></script>

    <!--script type="text/javascript">
      jQuery(function(){
        jQuery('#clock').flipcountdown();
      })
    </script-->

    <script type="text/javascript">
        setInterval(function clock_date(){
          $.ajax({
            method: 'POST',
            url:'/timetracker/Resources/PHP/clock.php',
            success: function(result){
              $('#clock').html(result);
            },
            error: function(exception){
              console.log(exception);
            }
          });
        }, 1000);

        $('#btnMarcarSalida, #btnMarcarEntrada').on('click', function(){
          var accion = $(this).attr('id').slice(9);
          punch_card(<?php echo "'".$idUsuario."'"?>, accion);
        })
    </script>

  </body>
</html>
