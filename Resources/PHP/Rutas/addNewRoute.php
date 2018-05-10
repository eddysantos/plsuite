<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$origen = $_POST['origen'];
$destino = $_POST['destino'];

$qry = "SELECT * FROM cu_rutas WHERE Origen = ? AND Destino = ?";
$stmt = $db->prepare($qry) or die ("Error en Prepare: " . $db->error);
$stmt->bind_param('ss',$origen, $destino);
if (!($stmt->execute())) {
  $response['code'] = "200";
  $response['systemMessage'] = "Hubo un error al procesar el query(" . $stmt->errno . "): $stmt->error.";
}
$rslt = $stmt->get_result();

if ($rslt->num_rows != 0) {
  $response['code'] = 201;
  $response['systemMessage'] = "Esa ruta ya existe en la base de datos, no se puede volver a agregar.";
  $resp = json_encode($response);
  echo $resp;
  die();
}

$qry = "INSERT INTO cu_rutas(Origen, Destino) VALUES (?,?)";
$stmt = $db->prepare($qry) or die ("Error en Prepare: " . $db->error);
$stmt->bind_param('ss',$origen, $destino);


if (!($stmt->execute())) {
  $response['code'] = "200";
  $response['systemMessage'] = "Hubo un error al procesar el query(" . $stmt->errno . "): $stmt->error.";
}

$response = array(
  'code'=>"",
  'systemMessage'=>"",
  'data'=>""
);

if ($stmt->affected_rows > 0) {
  $response['code'] = 1;
  $response['systemMessage'] = $stmt->insert_id;
} else {
  $response['code'] = 202;
  $response['systemMessage'] = $stmt->error;
  // $response['systemMessage'] = "Hubo un problema en el query al agregar la ruta a la base de datos";
}

$resp = json_encode($response);
echo $resp;
 ?>
