<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$routeDetails = $_POST['routeDetails'];
$routeId = $_POST['routeId'];

$response = array(
  'code'=>"",
  'systemMessage'=>"",
  'data'=>"",
  'errores'=>array()
);

$qry = "DELETE FROM cud_rutas WHERE fkIdRuta = ?";
$stmt = $db->prepare($qry) or die ("Error en Prepare: " . $db->error);
$stmt->bind_param('s',$routeId);
if (!($stmt->execute())) {
  $response['code'] = "200";
  $response['systemMessage'] = "Hubo un error al procesar el query(" . $stmt->errno . "): $stmt->error.";
  $resp = json_encode($response);
  echo $resp;
  die();
}



$qry = "INSERT INTO cud_rutas(fkIdRuta, Estado, Millas, Metros) VALUES (?,?,?,?)";
$stmt = $db->prepare($qry) or die ("Error en Prepare: " . $db->error);

foreach ($routeDetails as $state => $distance) {
  $meters = $distance;
  $miles = $distance / 1609.344;

  $stmt->bind_param('ssss', $routeId, $state, $miles, $meters);
  if (!($stmt->execute())) {
    $response['code'] = "300";
    $response['systemMessage'] = "Hubo un error al procesar el query(" . $stmt->errno . "): $stmt->error.";
    $response['errores'][] = "Hubo un error al procesar el query(" . $stmt->errno . "): $stmt->error.";
  } else {
    $response['code'] = "1";
    $response['systemMessage'] = "Todo cool!";
  }
}

$resp = json_encode($response);
echo $resp;

 ?>
