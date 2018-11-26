<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8_encode($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}

$system_callback = [];
$trucks = [];

// for ($i=0; $i < 1000; $i++) {
//   echo "Chingas a $i";
// }

$query = "SELECT t.truckNumber truckNumber FROM ct_truck t WHERE truckStatus = 'Active'";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query execution [$db->errno]: $db->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();

if ($rslt->num_rows == 0) {
  $system_callback['code'] = 2;
  $system_callback['message'] = "Script called successfully but there are no rows to display.";
  //$system_callback['data'] .= $row;
  exit_script($system_callback);
}

while ($row = $rslt->fetch_assoc()) {
  $trucks[$row['truckNumber']] = array(
    'lat'=>'',
    'lng'=>''
  );
}

$query = "SELECT lat, lon, heading, speed FROM omni_pos_log WHERE tractor = ? ORDER BY tran_ts DESC LIMIT 1";
$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

foreach ($trucks as $truck => $values) {
  if (!($stmt->bind_param('s', $truck))) {
    $system_callback['code'] = "500";
    $system_callback['message'] = "Error during query execution [$db->errno]: $db->error";
    exit_script($system_callback);
  }
  if (!($stmt->execute())) {
    $system_callback['code'] = "500";
    $system_callback['query'] = $query;
    $system_callback['message'] = "Error during query execution [$db->errno]: $db->error";
    exit_script($system_callback);
  }
  $rslt = $stmt->get_result();
  $rows = $rslt->num_rows;

  if ($rows == 0) {
    unset($trucks[$truck]);
  } else {
    while ($row = $rslt->fetch_assoc()) {
      $trucks[$truck]['lat'] = (double)$row['lat'];
      $trucks[$truck]['lng'] = (double)$row['lon'];
      $trucks[$truck]['rotation'] = (double)$row['heading'];
      $trucks[$truck]['speed'] = (double)$row['speed'];
    }
  }

}


$system_callback['code'] = 1;
$system_callback['trucks'] = $trucks;
$system_callback['message'] = "Script called successfully!";
exit_script($system_callback);


 ?>