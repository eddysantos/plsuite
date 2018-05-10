<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$datos_rutas = array();
$millas_totales = array();
$rutas = $_POST;
foreach ($rutas as $ruta => $valor) {
  $rutaIn .= $ruta . ",";
  $datos_rutas[$ruta]['cantidad_viajes'] = intval($valor);
}
$rutaIn = rtrim($rutaIn, ",");

$qry = "SELECT * FROM cud_rutas WHERE fkIdRuta IN ($rutaIn) AND Estado <> 'undefined'";
$stmt = $db->prepare($qry) or die ("Error en Prepare: " . $db->error);


if (!($stmt->execute())) {
  $response['code'] = "200";
  $response['systemMessage'] = "Hubo un error al procesar el query(" . $stmt->errno . "): $stmt->error.";
}

$rslt = $stmt->get_result();

$response = array(
  'code'=>"",
  'systemMessage'=>$rutaIn,
  'data'=>""
);

if ($rslt->num_rows > 0) {
  while ($row = $rslt->fetch_assoc()) {
    $idDetRuta = $row['pkIdRegistro'];
    $fkIdRuta = $row['fkIdRuta'];
    $estado = $row['Estado'];
    $millas = $row['Millas'];
    $kilometros = $row['Metros'] / 1000;

    $datos_rutas[$fkIdRuta]['estados'][$estado]['Millas'] = floatval($millas);
    $datos_rutas[$fkIdRuta]['estados'][$estado]['Kilometros'] = $kilometros;
  }
} else {
  $response['code'] = 0;
  $response['data'] = "No se encontraron resultados!";
}

foreach ($datos_rutas as $ruta) {
  foreach ($ruta['estados'] as $estado => $distancia) {
    $millas_totales[$estado]['Cantidad_viajes'] += $ruta['cantidad_viajes'];
    $millas_totales[$estado]['Millas'] += $distancia['Millas'] * $ruta['cantidad_viajes'];
    $millas_totales[$estado]['Kilometros'] += $distancia['Kilometros'] * $ruta['cantidad_viajes'];
  }
}



$response['code'] = 1;

foreach ($millas_totales as $estado => $datos) {
  $viajes = $datos['Cantidad_viajes'];
  $millas = $datos['Millas'];
  $kilometros = $datos['Kilometros'];
  $response['data'] .= "<tr><td>$estado</td><td>$viajes</td><td>$millas</td><td>$kilometros</td></tr>";
}

$resp = json_encode($response);
echo $resp;

 ?>
