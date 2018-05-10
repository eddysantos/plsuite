<?php
session_start();
date_default_timezone_set('America/Monterrey');

if (
  !(ISSET($_COOKIE['Nombre']) AND
  ISSET($_COOKIE['Apellido']) AND
  ISSET($_COOKIE['Usuario']) AND
  ISSET($_COOKIE['idUsuario']))
) {
  header('location:../../index.php');
}

if (isset($_POST['addButton'])) {
  include('../../Resources/PHP/Usuarios/agregarUsuario.php');
}

$idUsuario = $_GET['id'];
$registros = array();

if (isset($_GET['fechaDesde']) && isset($_GET['fechaHasta'])) {
  $fechaDesde = $_GET['fechaDesde'];
  $fechaHasta = $_GET['fechaHasta'];
} else {
  $fechaDesde = date('Y-m-d', strtotime('-1 Monday'));
  $fechaHasta = new DateTime();
  $fechaDesde = $fechaDesde;
  $fechaHasta = $fechaHasta->format('Y-m-d');
}

$getVars = array(
  "id" => $idUsuario,
  "fechaDesde" => $fechaDesde,
  "fechaHasta" => $fechaHasta
);
$getRequest = http_build_query($getVars);

include('../../Resources/PHP/loginDatabase.php');

if (isset($_GET['Detalles'])) {
  $qry =
  " SELECT
  l.pkTimelog AS Id,
  l.FechaEntrada AS Fecha,
  l.TimeStampEntrada AS Entrada,
  l.TimeStampSalida AS Salida,
  u.pkIdUsers AS idOperador,
  CONCAT(u.Nombre, ' ', u.Apellido) AS NombreOperador,
  TIMESTAMPDIFF(MINUTE, TimeStampEntrada, TimeStampSalida) AS DuracionTurno
  FROM
  Operator_TimeLog l
  LEFT JOIN Users u ON l.Operador = u.nombreUsuario
  WHERE
  u.nombreUsuario = ? AND
  l.FechaEntrada BETWEEN ? AND ?
  ";
} else {
  $qry =
  " SELECT
  l.pkTimelog AS Id,
  l.FechaEntrada AS Fecha,
  l.TimeStampEntrada AS Entrada,
  MAX(l.TimeStampSalida) AS Salida,
  u.pkIdUsers AS idOperador,
  CONCAT(u.Nombre, ' ', u.Apellido) AS NombreOperador,
  SUM(TIMESTAMPDIFF(MINUTE, TimeStampEntrada, TimeStampSalida)) AS DuracionTurno
  FROM
  Operator_TimeLog l
  LEFT JOIN Users u ON l.Operador = u.nombreUsuario
  WHERE
  u.nombreUsuario = ? AND
  l.FechaEntrada BETWEEN ? AND ?
  GROUP BY l.FechaEntrada
  ";
}

$stmt = $login->prepare($qry);
$stmt->bind_param('sss',
  $idUsuario,
  $fechaDesde,
  $fechaHasta
);
$stmt->execute();
$rslt = $stmt->get_result();

while ($row = $rslt->fetch_assoc()) {
  $registros[]=$row;
}

 ?>

 <!DOCTYPE html>
 <html lang="en">
   <head>
     <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="../../Resources/Bootstrap/css/bootstrap.min.css">
     <link rel="stylesheet" href="../../Resources/Bootstrap/FontAwesome/css/font-awesome.min.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="../../Resources/CSS/main.css">
     <link rel="stylesheet" media="screen and (max-device-width: 700px)" href="../../Resources/CSS/mainMobile.css">
     <link href="https://fonts.googleapis.com/css?family=Sansita" rel="stylesheet">
   </head>
   <body>

      <nav class="navbar navbar-toggleable-md">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#menuNavBar" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
         <div class="navbar-brand text-primary">
           <?php echo $_GET['nombreOperador'] ;?>
         </div>
         <div class="collapse navbar-collapse text-center" id="menuNavBar">
           <form class="form-inline" action="" method="GET">
             <label class="ml-5 mr-2" for="fechaDesde">Desde</label>
             <input class="form-control mb-2 mr-sm-2 mb-sm-0" type="date" name="fechaDesde" id="fechaDesde" value="<?php echo $fechaDesde?>">
             <label class="m-2" for="fechaHasta">Desde</label>
             <input class="form-control mb-2 mr-sm-2 mb-sm-0" type="date" name="fechaHasta" id="fechaHasta" value="<?php echo $fechaHasta?>">
             <input type="text" name="nombreOperador" id="nombreOperador" value="<?php echo $_GET['nombreOperador']?>" hidden>
             <input type="text" name="id" id="id" value="<?php echo $_GET['id']?>" hidden>
             <input class="btn btn-secondary form-control mr-sm-2" type="submit" name="Resumen" value="Resumen" role="button">
             <input class="btn btn-secondary form-control mr-sm-2" type="submit" name="Detalles" value="Detalles" role="button">
           </form>
        </div>
        <ul class="navbar-nav">
          <li class="nav-item mr-2">
            <a href="/timetracker/Resources/PHP/Utilities/createXLS.php?<?php echo $getRequest?>"><button class="btn btn-secondary" type="button" role="button"><i class="fa fa-file-excel-o"></i> Detalles</button></a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link btn btn-secondary" role="button" href="dashboard.php">Regresar</a>
          </li>
        </ul>
      </nav>
      <div class="container-fluid">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Fecha Registrada</th>
              <th>Entrada</th>
              <th>Salida</th>
              <th>Duración</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($registros as $registro): ?>
              <tr>
                <td><?php echo $registro['Fecha']; ?></td>
                <td><?php echo $registro['Entrada']; ?></td>
                <td><?php echo $registro['Salida'] ?></td>
                <td><?php echo $registro['DuracionTurno']; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

      </div>



     <!-- jQuery first, then Tether, then Bootstrap JS. -->
     <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
     <script src="../../Resources/Bootstrap/js/bootstrap.min.js"></script>
     <script src="../../Resources/JS/functions.js" charset="utf-8"></script>
   </body>
 </html>
