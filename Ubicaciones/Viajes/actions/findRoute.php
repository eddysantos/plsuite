<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

function GetDrivingDistance($o, $d){
    global $system_callback;

    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$o&destinations=$d&language=en&key=AIzaSyCyESDBd2xdkwed-L8ndjifpBlJX9Dpf7w";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $system_callback['api_response'] = $response;
    $response_a = json_decode($response, true);
    $dist = $response_a['rows'][0]['elements'][0]['distance']['value'];
    $time = $response_a['rows'][0]['elements'][0]['duration']['text'];
    //var_dump($response_a);
    return array('distance' => $dist, 'time' => $time);
}

$zips = json_decode($_POST['zips']);
$routes = array();

$query = "SELECT * FROM cs_routes WHERE origin = ? AND destination = ?";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

for ($i=1; $i < count($zips) ; $i++) {

  $stmt->bind_param('ss', $zips[$i - 1], $zips[$i]);
  if (!($stmt)) {
    $system_callback['code'] = "500";
    $system_callback['query'] = $query;
    $system_callback['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
    exit_script($system_callback);
  }

  if (!($stmt->execute())) {
    $system_callback['code'] = "500";
    $system_callback['query'] = $query;
    $system_callback['message'] = "Error during query execution [$stmt->errno]: $stmt->error";
    exit_script($system_callback);
  }

  $rslt = $stmt->get_result();

  // if ($rslt->num_rows == 0) {
  if (true) {
    $routes[] = array(
      'origin'=>$zips[$i - 1],
      'destination'=>$zips[$i],
      'route_code'=>'2',
      'distance'=>""
    );
  } else {
    $routes[] = array(
      'origin'=>$zips[$i - 1],
      'destination'=>$zips[$i],
      'route_code' => '1',
      'message'=>"Distance was extracted from database.",
      'distance' => 'Distance'
    );
  }
  //$system_callback['calculations'][] = "Origin: " . $zips[$i-1] . " -> Destination: " . $zips[$i];
}

for ($i=0; $i < count($routes); $i++) {
  $result = GetDrivingDistance("zip+".$routes[$i]['origin']."+USA", "zip+".$routes[$i]['destination']."+USA");
  $routes[$i]['distance'] = ceil($result['distance'] * 0.000621371);
  $totalMiles += ceil($result['distance'] * 0.000621371);
}



$_SESSION['current_linehaul']['routes'] = $routes;
$system_callback['zips'] = $zips;
$system_callback['routes'] = $routes;
$system_callback['totalMiles'] = $totalMiles;
exit_script($system_callback);

//
 ?>
