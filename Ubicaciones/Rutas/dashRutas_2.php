<?php

date_default_timezone_set('America/Monterrey');

$csvFile = fopen("/Users/EduardoSantos/Desktop/ifta_q2_verify.csv", "r");
fgetcsv($csvFile);
while (($data = fgetcsv($csvFile, 10000, ",")) !== FALSE) {
  $num = count($data);
  $ubicaciones[]=$data;
}
fclose($csvFile);


$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$qry = "SELECT pkIdRuta FROM cu_rutas WHERE (Origen = ? AND Destino = ?) OR (Origen = ? And Destino = ?) GROUP BY pkIdRuta ORDER BY pkIdRuta";
$stmt = $db->prepare($qry) or die ("Error en Prepare: " . $db->error);

$i = 0;
foreach ($ubicaciones as $key => $ubiq) {
  $stmt->bind_param('ssss',
    $ubiq[0], $ubiq[1], $ubiq[1], $ubiq[0]
  );

  if (!($stmt->execute())) {
    $response['code'] = "200";
    $response['systemMessage'] = "Hubo un error al procesar el query(" . $stmt->errno . "): $stmt->error.";
  }

  $rslt = $stmt->get_result();
  $row = $rslt->fetch_assoc();
  $ubicaciones[$key][2] = $row['pkIdRuta'];
  $i++;
}

$id_locations = array();
$noid_locations = array();
$rutas = "";


foreach ($ubicaciones as $k => $u) {
  $rutas .= $u['2'] . ",";
  $id_locations[$k] = array(
    'Origen' => $u['0'],
    'Destino' => $u['1'],
    'id'=>$u['2']
    // 'Viajes' => $id_locations[$u['2']]['Viajes'] + 1
  );
}

$rutas = rtrim($rutas, ",");

$qry = "SELECT Estado, Millas FROM cud_rutas WHERE fkIdRuta = ? GROUP BY Estado";
$stmt = $db->prepare($qry) or die ("Error en Prepare: " . $db->error);
if (!($stmt->execute())) {
  $response['code'] = "200";
  $response['systemMessage'] = "Hubo un error al procesar el query(" . $stmt->errno . "): $stmt->error.";
}
$results = array();

foreach ($id_locations as $key => $location) {
  $stmt->bind_param('s', $key);
  $stmt->execute();
  $rslt = $stmt->get_result();
  while ($row = $rslt->fetch_assoc()) {
    $results[$row['Estado']] += $row['Millas'] * $location['Viajes'];
    $results['Total'] += $row['Millas'] * $location['Viajes'];
    $results['Viajes'] += $location['Viajes'];
  }
}




// echo "<script>console.log(" . json_encode($results) .")</script>";
// echo "<script>console.log(" . $rutas .")</script>";
// echo "<script>alert('Fueron: " . $id_locations['Viajes'] . " viajes')</script>";
 ?>

 <!DOCTYPE html>
 <html lang="en">
   <head>
     <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="../../Resources/Bootstrap/css/bootstrap.min.css">
     <!-- <link rel="stylesheet" href="../../Resources/Bootstrap/FontAwesome/css/font-awesome.min.css"> -->
     <script src="/plsuite/Resources/fa_5/fontawesome-all.js" charset="utf-8"></script>
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
           Rutas Registradas
         </div>
         <div class="collapse navbar-collapse text-center" id="menuNavBar">
           <form class="form-inline" action="" method="GET">
             <label class="ml-5 mr-2" for="buscarLugar">Buscar</label>
             <input class="form-control mb-2 mr-sm-2 mb-sm-0" type="text" name="buscarLugar" id="buscarLugar" placeholder="Ciudad o Estado" value="">
             <input class="btn btn-secondary form-control mr-sm-2" type="submit" name="Buscar" value="Buscar" role="button">
           </form>
        </div>
        <ul class="navbar-nav">
          <li class="nav-item dropdown mr-2">
            <a class="nav-link btn btn-secondary" role="button" data-toggle="modal" data-target="#nuevaRutaModal">Nueva Ruta</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link btn btn-secondary" role="button" href="/timetracker/Ubicaciones/Administracion/dashboard.php">Regresar</a>
          </li>
        </ul>
      </nav>
      <br>
      <div class="container-fluid">
        <table class="table talbe-striped">
          <thead>
            <tr>
              <th>Ruta Id</th>
              <th>Origin</th>
              <th>Destination</th>
              <th>Trips</th>
              <th style="max-width: 50px"><button type="button" id="calcPendingRoutes" class="btn btn-primary" name="button">Start</button></th>
            </tr>
            <tbody id="dashruts">
              <?php $i = 1; ?>
              <?php foreach ($id_locations as $kl => $l): ?>
                <tr class="<?echo $l['id'] == "" ? 'no-existe' : ''?>">
                  <td class="id"><?php echo $kl?></td>
                  <td class="origen"><?php echo $l['Origen'] ?></td>
                  <td class="destino"><?php echo $l['Destino'] ?></td>
                  <td class="existe"><?php echo $l['id'] ?></td>
                  <td></td>
                </tr>
              <?php endforeach; ?>

            </tbody>
          </thead>
        </table>
      </div>






     <!-- jQuery first, then Tether, then Bootstrap JS. -->
     <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
     <script src="../../Resources/Bootstrap/js/bootstrap.min.js"></script>
     <script src="../../Resources/JS/functions.js" charset="utf-8"></script>
     <script src="../../Resources/JS/cityList.js" charset="utf-8"></script>
     <script src="../../Resources/JS/dashRutas.js" charset="utf-8"></script>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
     <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8KEktfGXe4z-nByf6_HG3QGsyXXnLBrA"></script>
     <script src="routeCalculations.js" charset="utf-8"></script>
   </body>
 </html>
