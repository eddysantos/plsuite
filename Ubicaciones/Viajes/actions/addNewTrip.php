<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

// function GetDrivingDistance($o, $d){
//     $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$o&destinations=$d&language=en";
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $url);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//     curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//     $response = curl_exec($ch);
//     curl_close($ch);
//     $response_a = json_decode($response, true);
//     $dist = $response_a['rows'][0]['elements'][0]['distance']['value'];
//     $time = $response_a['rows'][0]['elements'][0]['duration']['text'];
//     //var_dump($response_a);
//     return array('distance' => $dist, 'time' => $time);
// }



$db->query('LOCK TABLES ct_trip, ct_trip_linehaul, ct_trip_linehaul_movement WRITE;');
$db->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;");

// Trip Variables:
$trip = $_POST;
$this_year = date('y', strtotime('y'));
$user = $_SESSION['user_info']['NombreUsuario'];
$system_callback = [];


try {

  $db->begin_transaction();

    /* Calculate trip number, bases on the year, and how many trips there are. */
  $query = "SELECT max(trip_number_i) max_trip FROM ct_trip WHERE trip_year = $this_year";
  $stmt = $db->query($query);
  $trips = $stmt->fetch_assoc();
  $trip_number_i = $trips['max_trip'] +1;
  $tripno = $this_year . str_pad($trip_number_i, 4, 0, STR_PAD_LEFT);
  $first_movement = "";
  $last_movement = "";

  /* INSERT TRIP INTO DATABASE*/
  require 'createTrip.php';



  /* ADD TRIP LINEHAUL */
  // Required to pass origin and destination to Linehaul create.
  $origin = array(
    'city'=>$trip['linehaul']['routes'][0]['ocity'],
    'state'=>$trip['linehaul']['routes'][0]['ostate'],
    'zip'=>$trip['linehaul']['routes'][0]['ozip']
  );

  $destination = array(
    'city'=>end($trip['linehaul']['routes'])['dcity'],
    'state'=>end($trip['linehaul']['routes'])['dstate'],
    'zip'=>end($trip['linehaul']['routes'])['dzip']
  );
  require 'createLinehaul.php';

  /* ADD MOVEMENTS */
  require 'createMovement.php';

/* UPDATE ct_trip WITH THE FIRST AND LAST MOVEMENT */

$query = "UPDATE ct_trip SET first_movement = ?, last_movement = ? WHERE pkid_trip = ?";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during UPDATE FIRST AND LAST MOVEMENT query prepare [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

$stmt->bind_param('sss',
  $first_movement,
  $last_movement,
  $pk_trip
);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during UPDATE FIRST AND LAST MOVEMENT variables binding [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during UPDATE FIRST AND LAST MOVEMENT query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if ($db->affected_rows == 0) {
  $system_callback['code'] = $db->error;
  $system_callback['message'] = "Unable to modify trip to include the first and last movement.";
  exit_script($system_callback);
}



  $db->commit();
  $system_callback['code'] = 1;
  $system_callback['message'] = "Query executed successfully!";
  $system_callback['insertid'] = $pk_trip;
  $system_callback['tripyear'] = $this_year;
  $db->query('UNLOCK TABLES;');
  exit_script($system_callback);
} catch (\Exception $e) {
  $db->rollback();
  $system_callback['code'] = "2";
  $system_callback['message'] = "There was a problem executing the query[$db->errno]: $db->error";
  $db->query('UNLOCK TABLES;');
  exit_script($system_callback);
}



?>
