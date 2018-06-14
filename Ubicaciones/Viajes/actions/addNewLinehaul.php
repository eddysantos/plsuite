<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$thisYear = date('y', strtotime('today'));

// $system_callback['driver']['data']['id'] = $_POST['driverid'];
// $system_callback['trailer']['data']['id'] = $_POST['trailerid'];
// $system_callback['broker']['data']['id'] = $_POST['brokerid'];
// $system_callback['truck']['data']['id'] = $_POST['truckid'];
$system_callback['data'] = $_POST;
$system_callback['session'] = $_SESSION['current_linehaul'];
$user_name = $_SESSION['user_info']['NombreUsuario'];

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

try {
    // First of all, let's begin a transaction
    $db->begin_transaction();

    /* Calculate next linehaul id number */

    $query = "SELECT count(pk_idlinehaul) count FROM ct_trip_linehaul WHERE fk_idtrip = ?";

    $stmt = $db->prepare($query);
    if (!($stmt)) {
      $system_callback['query']['code'] = "500";
      $system_callback['query']['query'] = $query;
      $system_callback['query']['message'] = "Error during INSERT TRIP query prepare [$stmt->errno]: $stmt->error";
      exit_script($system_callback);
    }

    $stmt->bind_param('s',
    $system_callback['data']['linehaul']['tripid']
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

    $new_lid = $row['count'];

    $new_lid += 1;

    /**************************************/

    /** add new Line Haul on the trip**/

    $query = "INSERT INTO ct_trip_linehaul(fk_idtrip, origin_state, origin_city, origin_zip, destination_state, destination_city, destination_zip, trip_rate, fkid_broker, fk_tripyear, rpm, fuel_surcharge, pk_linehaul_number, broker_reference, added_by, lh_number, date_appointment) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $lh_number = $tripno = $system_callback['data']['linehaul']['tripyear'] . str_pad($system_callback['data']['linehaul']['tripid'], 4, 0, STR_PAD_LEFT) . $new_lid;
    $appt = date('Y-m-d H:i', strtotime($system_callback['data']['linehaul']['appt']['date'] . " " . $system_callback['data']['linehaul']['appt']['hour'] . ":" . $system_callback['data']['linehaul']['appt']['min']));

    $stmt = $db->prepare($query);
    if (!($stmt)) {
      $system_callback['query']['code'] = "500";
      $system_callback['query']['query'] = $query;
      $system_callback['query']['message'] = "Error during INSERT TRIP_LINEHAUL query prepare [$db->errno]: $db->error";
      exit_script($system_callback);
    }

    $fuel_surcharge = fuel_surcharge($system_callback['data']['linehaul']['rpm']);

    $stmt->bind_param('sssssssssssssssss',
    $system_callback['data']['linehaul']['tripid'],
    $system_callback['data']['linehaul']['origin']['state'],
    $system_callback['data']['linehaul']['origin']['city'],
    $system_callback['data']['linehaul']['origin']['zip'],
    $system_callback['data']['linehaul']['destination']['state'],
    $system_callback['data']['linehaul']['destination']['city'],
    $system_callback['data']['linehaul']['destination']['zip'],
    $system_callback['data']['linehaul']['rate'],
    $system_callback['data']['linehaul']['broker'],
    $system_callback['data']['linehaul']['tripyear'],
    $system_callback['data']['linehaul']['rpm'],
    $fuel_surcharge,
    $new_lid,
    $system_callback['data']['linehaul']['broker_reference'],
    $user_name,
    $lh_number,
    $appt
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
    exit_script($system_callback);
  }

  $system_callback['query']['insertid'] = $db->insert_id;

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
  $system_callback['query']['insertid']
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

  /* Add linehaul movements to database. */

  $query = "INSERT INTO ct_trip_linehaul_movement(fkid_linehaul, origin_city, origin_state, origin_zip, destination_city, destination_state, destination_zip, miles_google, movement_type, fkid_tractor, fkid_driver, fkid_driver_team, pk_movement_number, added_by) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

  $stmt = $db->prepare($query);

  if (!($stmt)) {
    $system_callback['query']['code'] = "500";
    $system_callback['query']['query'] = $query;
    $system_callback['query']['message'] = "Error during INSERT TRIP_LINEHAUL_MOVEMENT query prepare [$db->errno]: $db->error";
    exit_script($system_callback);
  }

  for ($i=0; $i < count($system_callback['data']['movements']) - 1; $i++) {
    if (
      !(
        $stmt->bind_param('ssssssssssssss',
        $system_callback['query']['insertid'],
        $system_callback['data']['movements'][$i]['city'],
        $system_callback['data']['movements'][$i]['state'],
        $system_callback['data']['movements'][$i]['zip_code'],
        $system_callback['data']['movements'][$i+1]['city'],
        $system_callback['data']['movements'][$i+1]['state'],
        $system_callback['data']['movements'][$i+1]['zip_code'],
        $system_callback['data']['movements'][$i+1]['google_miles'],
        $system_callback['data']['movements'][$i+1]['type'],
        $system_callback['data']['conveyance']['tractorid'],
        $system_callback['data']['conveyance']['driver']['id'],
        $system_callback['data']['conveyance']['team']['id'],
        $new_mid,
        $user_name
      )
        )
    ) {
      $system_callback['query']['code'] = "500";
      $system_callback['query']['data'] = $system_callback;
      $system_callback['query']['message'] = "Error during INSERT TRIP_LINEHAUL_MOVEMENT_$i variables binding [$stmt->errno]: $stmt->error";
      break;
    }

    // if (!($stmt)) {
    //   $system_callback['query']['code'] = "500";
    //   $system_callback['query']['query'] = $query;
    //   $system_callback['query']['message'] = "Error during INSERT TRIP_LINEHAUL_MOVEMENT_$i variables binding [$db->errno]: $db->error";
    //   break;
    // }

    if (!($stmt->execute())) {
      $system_callback['query']['code'] = "500";
      $system_callback['query']['query'] = $query;
      $system_callback['query']['message'] = "Error during INSERT TRIP_LINEHAUL_MOVEMENT_$i query execution [$stmt->errno]: $stmt->error";
      break;
    }
    $new_mid += 1;
  }
  if ($system_callback['query']['code'] == "500") {
    exit_script($system_callback);
  }


    $db->commit();
    $system_callback['query']['code'] = "1";
    $system_callback['query']['message'] = "Query happened perfectly!";
    exit_script($system_callback);
} catch (Exception $e) {
    // An exception has been thrown
    // We must rollback the transaction
    $db->rollback();
    $system_callback['query']['code'] = "2";
    $system_callback['query']['message'] = "There was a problem executing the query[$db->errno]: $db->error";
    exit_script($system_callback);
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





?>
