<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

function GetDrivingDistance($o, $d){
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

$user_name = $_SESSION['user_info']['NombreUsuario'];

$thisYear = date('y', strtotime('today'));

// $system_callback['driver']['data']['id'] = $_POST['driverid'];
$system_callback['trailer']['data']['id'] = $_POST['trailerid'];
$system_callback['broker']['data']['id'] = $_POST['broker']['brokerid'];
$system_callback['broker']['data']['reference'] = $_POST['broker']['broker_reference'];
// $system_callback['truck']['data']['id'] = $_POST['truckid'];
$system_callback['trip'] = $_POST['trip'];

/** Fetch information on the trailer. **/

$db->query('LOCK TABLES ct_trip WRITE ;');
$db->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;");


try {

  $db->begin_transaction();

  $query = "SELECT * FROM ct_trailer WHERE deletedTrailer IS NULL AND pkid_trailer = ?";

  $stmt = $db->prepare($query);
  if (!($stmt)) {
    $system_callback['query']['code'] = "500";
    $system_callback['query']['query'] = $query;
    $system_callback['query']['message'] = "Error during trailer query prepare [$db->errno]: $db->error";
    exit_script($system_callback);
  }

  $stmt->bind_param('s', $system_callback['trailer']['data']['id']);
  if (!($stmt)) {
    $system_callback['query']['code'] = "500";
    $system_callback['query']['query'] = $query;
    $system_callback['query']['message'] = "Error during trailer variables binding [$stmt->errno]: $stmt->error";
    exit_script($system_callback);
  }

  if (!($stmt->execute())) {
    $system_callback['query']['code'] = "500";
    $system_callback['query']['query'] = $query;
    $system_callback['query']['message'] = "Error during trailer query execution [$stmt->errno]: $stmt->error";
    exit_script($system_callback);
  }

  $rslt = $stmt->get_result();

  if ($rslt->num_rows == 0) {
    $system_callback['query']['code'] = 2;
    $system_callback['query']['message'] = "Script called successfully but there are no rows to display. For trailer query.";
    $system_callback['query']['data'] .= $row;
    exit_script($system_callback);
  } else {
    $row = $rslt->fetch_assoc();
    $system_callback['trailer']['data']['trailerNumber'] = $row['trailerNumber'];
    $system_callback['trailer']['data']['trailerPlates'] = $row['trailerPlates'];
  }


  /** Fetch information on the truck. **/

  // $query = "SELECT * FROM ct_truck WHERE deletedTruck IS NULL AND pkid_truck = ?";
  //
  // $stmt = $db->prepare($query);
  // if (!($stmt)) {
  //   $system_callback['query']['code'] = "500";
  //   $system_callback['query']['query'] = $query;
  //   $system_callback['query']['message'] = "Error during truck query prepare [$db->errno]: $db->error";
  //   exit_script($system_callback);
  // }
  //
  // $stmt->bind_param('s', $system_callback['truck']['data']['id']);
  // if (!($stmt)) {
  //   $system_callback['query']['code'] = "500";
  //   $system_callback['query']['query'] = $query;
  //   $system_callback['query']['message'] = "Error during truck variables binding [$stmt->errno]: $stmt->error";
  //   exit_script($system_callback);
  // }
  //
  // if (!($stmt->execute())) {
  //   $system_callback['query']['code'] = "500";
  //   $system_callback['query']['query'] = $query;
  //   $system_callback['query']['message'] = "Error during truck query execution [$stmt->errno]: $stmt->error";
  //   exit_script($system_callback);
  // }
  //
  // $rslt = $stmt->get_result();
  //
  // if ($rslt->num_rows == 0) {
  //   $system_callback['query']['code'] = 2;
  //   $system_callback['query']['message'] = "Script called successfully but there are no rows to display. For truck query.";
  //   $system_callback['query']['data'] .= $row;
  //   exit_script($system_callback);
  // } else {
  //   $row = $rslt->fetch_assoc();
  //   $system_callback['truck']['data']['truckNumber'] = $row['truckNumber'];
  //   $system_callback['truck']['data']['truckPlates'] = $row['truckPlates'];
  // }


  /** Load trip information into the Database. **/

    /* Calculate trip number, bases on the year, and how many trips there are. */
    $query = "SELECT max(pkid_trip) max_trip FROM ct_trip WHERE trip_year = $thisYear";
    $stmt = $db->query($query);
    $trips = $stmt->fetch_assoc();
    $tripno = $thisYear . str_pad($trips['max_trip'] + 1, 4, 0, STR_PAD_LEFT);

  $query = "INSERT INTO ct_trip(fkid_trailer, trailer_number, trailer_plates, trip_year, trip_number, added_by) VALUES(?,?,?,?,?,?)";

  $stmt = $db->prepare($query);
  if (!($stmt)) {
    $system_callback['query']['code'] = "500";
    $system_callback['query']['query'] = $query;
    $system_callback['query']['message'] = "Error during INSERT TRIP query prepare [$stmt->errno]: $stmt->error";
    exit_script($system_callback);
  }

  $stmt->bind_param('ssssss',
  $system_callback['trailer']['data']['id'],
  $system_callback['trailer']['data']['trailerNumber'],
  $system_callback['trailer']['data']['trailerPlates'],
  $thisYear,
  $tripno,
  $user_name
  );
  if (!($stmt)) {
    $system_callback['query']['code'] = "500";
    $system_callback['query']['query'] = $query;
    $system_callback['query']['message'] = "Error during INSERT TRIP variables binding [$stmt->errno]: $stmt->error";
    exit_script($system_callback);
  }

  if (!($stmt->execute())) {
    $system_callback['query']['code'] = "500";
    $system_callback['query']['query'] = $query;
    $system_callback['query']['message'] = "Error during INSERT TRIP query execution [$stmt->errno]: $stmt->error";
    exit_script($system_callback);
  }

  if ($db->affected_rows == 0) {
    $system_callback['query']['code'] = $db->error;
    $system_callback['query']['message'] = "Something happened, no data was added to the database during TRIP INSERT query.";
    $system_callback['query']['data'] .= $row;
    exit_script($system_callback);
  }

  $trip_insert_id = $db->insert_id;


  /** Create firts Line Haul for the trip**/

  /* Calculate next linehaul id number */

  $query = "SELECT count(pk_idlinehaul) count FROM ct_trip_linehaul WHERE fk_idtrip = ?";

  $stmt = $db->prepare($query);
  if (!($stmt)) {
    $system_callback['query']['code'] = "500";
    $system_callback['query']['db'] = $db;
    $system_callback['query']['message'] = "Error during count TRIP LINEHAULS query prepare [$db->errno]: $db->error";
    exit_script($system_callback);
  }

  $stmt->bind_param('s',
  $trip_insert_id
  );
  if (!($stmt)) {
    $system_callback['query']['code'] = "500";
    $system_callback['query']['query'] = $query;
    $system_callback['query']['message'] = "Error during count TRIP LINEHAULS variables binding [$stmt->errno]: $stmt->error";
    exit_script($system_callback);
  }

  if (!($stmt->execute())) {
    $system_callback['query']['code'] = "500";
    $system_callback['query']['query'] = $query;
    $system_callback['query']['message'] = "Error during count TRIP LINEHAULS query execution [$stmt->errno]: $stmt->error";
    exit_script($system_callback);
  }

  $rslt = $stmt->get_result();

  $row = $rslt->fetch_assoc();

  $new_lid = $row['count'];

  $new_lid += 1;

  /**************************************/

  $query = "INSERT INTO ct_trip_linehaul(fk_idtrip, origin_state, origin_city, origin_zip, destination_state, destination_city, destination_zip, trip_rate, fkid_broker, fk_tripyear, pk_linehaul_number, broker_reference, lh_number, added_by, date_appointment, current_tractor) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

  $appt = date('Y-m-d H:i', strtotime($system_callback['trip']['appt']['date'] . " " . $system_callback['trip']['appt']['hour'] . ":" . $system_callback['trip']['appt']['min']));

  $stmt = $db->prepare($query);
  if (!($stmt)) {
    $system_callback['query']['code'] = "500";
    $system_callback['query']['query'] = $query;
    $system_callback['query']['message'] = "Error during INSERT TRIP_LINEHAUL query prepare [$stmt->errno]: $stmt->error";
    exit_script($system_callback);
  }

  $stmt->bind_param('ssssssssssssssss',
  $trip_insert_id,
  $system_callback['trip']['origin']['state'],
  $system_callback['trip']['origin']['city'],
  $system_callback['trip']['origin']['zip'],
  $system_callback['trip']['destination']['state'],
  $system_callback['trip']['destination']['city'],
  $system_callback['trip']['destination']['zip'],
  $system_callback['trip']['rate'],
  $system_callback['broker']['data']['id'],
  $thisYear,
  $new_lid,
  $system_callback['broker']['data']['reference'],
  $lh_number = $tripno.$new_lid,
  $user_name,
  $appt,
  $system_callback['trip']['conveyance']['truck']
  );
  if (!($stmt)) {
    $system_callback['query']['code'] = "500";
    $system_callback['query']['query'] = $query;
    $system_callback['query']['message'] = "Error during INSERT TRIP_LINEHAUL variables binding [$stmt->errno]: $stmt->error";
    exit_script($system_callback);
  }

  if (!($stmt->execute())) {
    $system_callback['query']['code'] = "500";
    $system_callback['query']['query'] = $query;
    $system_callback['query']['message'] = "Error during INSERT TRIP_LINEHAUL query execution [$stmt->errno]: $stmt->error";
    exit_script($system_callback);
  }

  if ($db->affected_rows == 0) {
    $system_callback['query']['code'] = $db->error;
    $system_callback['query']['message'] = "Something happened, no data was added to the database during INSERT TRIP_LINEHAUL query.";
    $system_callback['query']['data'] .= $row;
    exit_script($system_callback);
  }

  $linehaul_id = $db->insert_id;
  $mov_distance = GetDrivingDistance("zip+".$system_callback['trip']['origin']['zip']."+USA", "zip+".$system_callback['trip']['destination']['zip']."+USA");
  $miles = ceil($mov_distance['distance'] * 0.000621371);
  $mv_type = "L";

  /* Calculate next movement id number */

  $query = "SELECT count(pkid_movement) count FROM ct_trip_linehaul_movement WHERE fkid_linehaul = ?";

  $stmt = $db->prepare($query);
  if (!($stmt)) {
    $system_callback['query']['code'] = "500";
    $system_callback['query']['query'] = $query;
    $system_callback['query']['message'] = "Error during INSERT TRIP query prepare [$stmt->errno]: $stmt->error";
    exit_script($system_callback);
  }

  $stmt->bind_param('s',
  $linehaul_id
  );
  if (!($stmt)) {
    $system_callback['query']['code'] = "500";
    $system_callback['query']['query'] = $query;
    $system_callback['query']['message'] = "Error during INSERT TRIP variables binding [$stmt->errno]: $stmt->error";
    exit_script($system_callback);
  }

  if (!($stmt->execute())) {
    $system_callback['query']['code'] = "500";
    $system_callback['query']['query'] = $query;
    $system_callback['query']['message'] = "Error during INSERT TRIP query execution [$stmt->errno]: $stmt->error";
    exit_script($system_callback);
  }

  $rslt = $stmt->get_result();

  $row = $rslt->fetch_assoc();

  $new_mid = $row['count'];

  $new_mid += 1;

  /**************************************/

  $query = "INSERT INTO ct_trip_linehaul_movement(fkid_linehaul, origin_city, origin_state, origin_zip, destination_city, destination_state, destination_zip, miles_google, movement_type, fkid_tractor, fkid_driver, fkid_driver_team, pk_movement_number, added_by) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

  $stmt = $db->prepare($query);

  if (!($stmt)) {
    $system_callback['query']['code'] = "500";
    $system_callback['query']['query'] = $query;
    $system_callback['query']['message'] = "Error during INSERT TRIP_LINEHAUL_MOVEMENT query prepare [$db->errno]: $db->error";
    exit_script($system_callback);
  }

  if (
    !(
      $stmt->bind_param('ssssssssssssss',
      $linehaul_id,
      $system_callback['trip']['origin']['city'],
      $system_callback['trip']['origin']['state'],
      $system_callback['trip']['origin']['zip'],
      $system_callback['trip']['destination']['state'],
      $system_callback['trip']['destination']['city'],
      $system_callback['trip']['destination']['zip'],
      $miles,
      $mv_type,
      $system_callback['trip']['conveyance']['truck'],
      $system_callback['trip']['conveyance']['driver'],
      $system_callback['trip']['conveyance']['team'],
      $new_mid,
      $user_name
    )
      )
  ) {
    $system_callback['query']['code'] = "500";
    $system_callback['query']['data'] = $system_callback;
    $system_callback['query']['message'] = "Error during INSERT TRIP_LINEHAUL_MOVEMENT variables binding [$stmt->errno]: $stmt->error";
  }

  if (!($stmt->execute())) {
    $system_callback['query']['code'] = "500";
    $system_callback['query']['query'] = $query;
    $system_callback['query']['message'] = "Error during INSERT TRIP_LINEHAUL_MOVEMENT query execution [$stmt->errno]: $stmt->error";
  }

  if ($system_callback['query']['code'] == "500") {
    exit_script($system_callback);
  }

  $db->commit();
  $system_callback['query']['code'] = 1;
  $system_callback['query']['message'] = "Query executed successfully!";
  $system_callback['query']['insertid'] = $trip_insert_id;
  $system_callback['query']['tripyear'] = $thisYear;
  $db->query('UNLOCK TABLES;');
  exit_script($system_callback);
} catch (\Exception $e) {
  $db->rollback();
  $system_callback['query']['code'] = "2";
  $system_callback['query']['message'] = "There was a problem executing the query[$db->errno]: $db->error";
  $db->query('UNLOCK TABLES;');
  exit_script($system_callback);
}



?>
