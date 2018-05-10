<?php



function GetDrivingDistance($o, $d)
{
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$o&destinations=$d&language=en";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $response_a = json_decode($response, true);
    $dist = $response_a['rows'][0]['elements'][0]['distance']['value'];
    $time = $response_a['rows'][0]['elements'][0]['duration']['text'];
    //var_dump($response_a);
    return array('distance' => $dist, 'time' => $time);
}

$origin = urlencode($_POST['origin']);
$dest = urlencode($_POST['dest']);
$state = $_POST['state'];

$distance = GetDrivingDistance($origin, $dest);

$resp = array();
$routeDetails[$state] = $distance['distance'];



$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$origin = urldecode($origin);
$dest = urldecode($dest);

$qry = "SELECT * FROM cu_rutas WHERE Origen = ? AND Destino = ?";
$stmt = $db->prepare($qry) or die ("Error en Prepare: " . $db->error);
$stmt->bind_param('ss',$origin, $dest);
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
$stmt->bind_param('ss',$origin, $dest);


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
  $routeId = $stmt->insert_id;
} else {
  $response['code'] = 202;
  $response['systemMessage'] = $stmt->affected_rows;
  // $response['systemMessage'] = "Hubo un problema en el query al agregar la ruta a la base de datos";
}

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
