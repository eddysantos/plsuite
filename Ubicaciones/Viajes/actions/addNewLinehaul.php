<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';



$trip = $_POST;
$user = $_SESSION['user_info']['NombreUsuario'];
$this_year = date('y', strtotime('today'));
$system_callback = [];

function fuel_surcharge($rpm){
  if ($rpm < 1.30) {
    return ".10";
  } elseif ($rpm >= 1.30 && $rpm < 1.40) {
    return ".15";
  } elseif ($rpm >= 1.4 && $rpm < 1.5) {
    return ".20";
  } elseif ($rpm >= 1.5) {
    return ".25";
  }
}

$db->query('LOCK TABLES ct_trip_linehaul, ct_trip_linehaul_movement WRITE;');
$db->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;");

try {
  $db->begin_transaction();


  /* ADD TRIP LINEHAUL */
  // NOTE: Always declare variables $origin, $destination, $pk_trip, $tripno when not preceded by createTrip.php

  $origin = array(
    'city'=>$trip['linehaul']['origin']['city'],
    'state'=>$trip['linehaul']['origin']['state'],
    'zip'=>$trip['linehaul']['origin']['zip']
  );

  $destination = array(
    'city'=>$trip['linehaul']['destination']['city'],
    'state'=>$trip['linehaul']['destination']['state'],
    'zip'=>$trip['linehaul']['destination']['zip']
  );

  $pk_trip = $trip['linehaul']['trip']['id'];
  $tripno = $trip['linehaul']['trip']['number'];


  require 'createLinehaul.php';

  /* ADD MOVEMENTS */
  require 'createMovement.php';

  /* UPDATE ct_trip WITH THE LAST MOVEMENT */
  $query = "UPDATE ct_trip SET last_movement = ? WHERE pkid_trip = ?";

  $stmt = $db->prepare($query);
  if (!($stmt)) {
    $system_callback['code'] = "500";
    $system_callback['query'] = $query;
    $system_callback['message'] = "Error during UPDATE FIRST AND LAST MOVEMENT query prepare [$stmt->errno]: $stmt->error";
    exit_script($system_callback);
  }

  $stmt->bind_param('ss',
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
} catch (Exception $e) {
    // An exception has been thrown
    // We must rollback the transaction
    $db->rollback();
    $system_callback['code'] = "2";
    $system_callback['message'] = "There was a problem executing the query[$db->errno]: $db->error";
    exit_script($system_callback);
}





?>
