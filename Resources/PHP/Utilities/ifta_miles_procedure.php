<?php

date_default_timezone_set('America/Monterrey');


//This procedure works in 2 parts, first goes through the entire route, and finds all the 'steps' where there was a state change.

//Variables required for the first part of this process.

$startState;
$currentState;
$routeData;
$index = 0;
$stateChangeSteps = [];
$borderLatLngs = [];
$startLatLng;
$endLatLng;
$routeId = "";



function get_route($o, $d, $time){
    //echo "get route\n";
    $time = time() + 4000;

    $origin       = "origin=zip+$o";
    $destination  = "destination=zip+$d";
    $departure_time = "departure_time=$time";
    $base_url = "https://maps.googleapis.com/maps/api/directions/json?";
    $api_key = "key=AIzaSyCyESDBd2xdkwed-L8ndjifpBlJX9Dpf7w";
    $waypoints = "waypoints=optimize:true";
    $traffic_model = "traffic_model=pessimistic";

    $url          = $base_url
                  . "&$origin"
                  . "&$destination"
                  . "&$api_key"
                  // . "&$waypoints"
                  . "&language=en"
                  . "&$departure_time"
                  . "&$traffic_model";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $response_a = json_decode($response, true);
    return $response_a;
}



function assignInitialState($data){
  //echo "assign initial state\n";
  global $startState;
  global $route;
  $startState = getState($data);
  $currentState = $startState;
  compileStates($route);
}

function getState($data, $debug_info = false){
  $data_count = count($data['results']);
  for ($i=0; $i < $data_count; $i++) {
    foreach ($data['results'][$i]['types'] as $data_type) {
      if ($data_type === "administrative_area_level_1") {
        $state = $data['results'][$i]['address_components'][0]['short_name'];
      }
    }
  }
  if ($debug_info) {
    echo json_encode($data);
    echo "State in function is: $state\n";
  }
  return $state;
}

function geocode($lat, $lng){
  //echo "geocode\n";
  // $start          = $data['routes'][0]['legs'][0]['steps'][0]['start_location'];
  $api_key        = "key=AIzaSyCyESDBd2xdkwed-L8ndjifpBlJX9Dpf7w";
  $base_url       = "https://maps.googleapis.com/maps/api/geocode/json?";

  $url            = $base_url
                  . "latlng=$lat,$lng"
                  . "&$key";

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  $response = curl_exec($ch);
  curl_close($ch);
  $response_a = json_decode($response, true);

  if ($response_a['error_message']) {
    die("GEOCODING ERROR -> Status: $response_a[status] - Error: $response_a[error_message]\n");
  }

  return $response_a;
}

function compileStatesReceiver($response){
  //echo "compile states receiver\n";
  global $currentState;
  global $stateChangeSteps;
  global $index;
  global $route;
  $state = getState($response);
  if ($state != $currentState) {
    $currentState = $state;
    array_push($stateChangeSteps, $index-1);
  }
  $index++;

  echo "State: $state\n";
  echo "Current state: $currentState\n";
  echo "Index: $index\n";
  var_dump($stateChangeSteps);
  die();
  compileStates($route, $index);
}

function compileStates($data, $this_index = false){
  //echo "compile states\n";
  global $index;

  if (!$this_index) {
    $index = 1;
    $strt_lat = $data['routes'][0]['legs'][0]['steps'][0]['start_location']['lat'];
    $strt_lng = $data['routes'][0]['legs'][0]['steps'][0]['start_location']['lng'];
    compileStatesReceiver(geocode($strt_lat, $strt_lng));
  } else {
    if ($index >= count($data['routes'][0]['legs'][0]['steps'])) {
      $index = 0;
      //echo "Start binary search!";
      return false;
    }
  }
}

$route = get_route(78045, 72209, $departure_time);

$startLatLng = $route['routes'][0]['legs'][0]['start_location'];
$endLatLng = $route['routes'][0]['legs'][0]['end_location'];

assignInitialState(geocode($startLatLng['lat'], $startLatLng['lng']));

var_dump($stateChangeSteps);
 ?>
