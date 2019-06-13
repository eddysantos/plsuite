<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$sc = [];
$data = $_POST;

function encrypt($string){
  $cipher = "AES-256-CBC";
  $key =hash('sha256', "ewgdhfjjluo3pip4l");
  $iv = substr(hash('sha256', "sdfkljsadf567890saf"), 0, 16);
  $token = openssl_encrypt($string, $cipher, $key, 0, $iv);
  $token = base64_encode($token);

  return $token;
  // $token = openssl_decrypt(base64_decode("UmhaN284bEUxeStZWXF0eTJ3ODhNQT09"),$cipher, $key, 0, $iv);
}

function parseDate($datestamp, $option = 1){
  if ($datestamp == "") {
    return $return;
  }

  if ($option == 1) {
    $return = date('Y/m/d', strtotime($datestamp));
    return $return;
  } else {
    $return = array(
      'date'=>"",
      'time'=>array(
        'hour'=>"",
        'minute'=>""
      )
    );

    $return['date'] = date('Y-m-d', strtotime($datestamp));
    $return['time']['hour'] = date('H', strtotime($datestamp));
    $return['time']['minute'] = date('i', strtotime($datestamp));

    return $return;
  }
}
function numberify($number){
  return number_format($number, 2);
}


$query = "SELECT lh.pk_idlinehaul linehaulid, t.trailer_number trailer_number, lh.lh_number lh_number, lh.fk_tripyear trip_year, lh.linehaul_status lh_status, lh.fk_idtrip tripid , lh.date_begin trip_start , lh.date_end trip_end , lh.trip_rate rate , lh.fuel_surcharge fuel_surcharge , lh.origin_state origin_state , lh.origin_city origin_city , lh.origin_zip origin_zip , lh.destination_state destination_state , lh.destination_city destination_city , lh.destination_zip destination_zip , lh.fkid_broker brokerid , b.brokerName broker , sum(tlm.miles_google) total_miles , SUM( CASE tlm.movement_type WHEN 'E' THEN tlm.miles_google ELSE 0 END) empty_miles , SUM( CASE tlm.movement_type WHEN 'L' THEN tlm.miles_google ELSE 0 END) loaded_miles, date_departure departure, date_arrival arrival, date_delivery delivery, date_appointment appointment, date_appointment_to appointment_to, broker_reference broker_reference, lh.lh_comment lh_comment FROM ct_trip_linehaul lh LEFT JOIN ct_brokers b ON lh.fkid_broker = b.pkid_broker LEFT JOIN ct_trip_linehaul_movement tlm ON tlm.fkid_linehaul = lh.pk_idlinehaul LEFT JOIN ct_trip t ON t.pkid_trip = lh.fk_idtrip WHERE pk_idlinehaul = ? GROUP BY linehaulid";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $sc['code'] = "500";
  $sc['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($sc);
}

$stmt->bind_param('s', $data['lhid']);
if (!($stmt)) {
  $sc['code'] = "500";
  $sc['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
  exit_script($sc);
}

if (!($stmt->execute())) {
  $sc['code'] = "500";
  $sc['message'] = "Error during query execution [$db->errno]: $db->error";
  exit_script($sc);
}

$rslt = $stmt->get_result();

if ($rslt->num_rows == 0) {
  $sc['code'] = 2;
  $sc['message'] = "<tr><td>No movements were found.</td></tr>";
  $sc['data'] = $data;
  exit_script($sc);
}

// while ($row = $rslt->fetch_assoc()) {
//   $sc['data'] = $row;
// }

$sc['data'] = $rslt->fetch_assoc();
// $sc['data']['lh_number'] = $sc['data']['trip_year'] . str_pad($sc['data']['tripid'], 4, 0, STR_PAD_LEFT) . $sc['data']['lh_number'];
$sc['data']['plscope_target'] = "/plsuite/public/PlScope/plscope.php?lh_reference=" . encrypt($sc['data']['linehaulid']);
$sc['data']['lh_number'] = $sc['data']['lh_number'];
$sc['data']['rpm'] = numberify($sc['data']['rate']/$sc['data']['total_miles']);
$sc['data']['rate'] = numberify($sc['data']['rate']);
$sc['data']['appointment'] = parseDate($sc['data']['appointment'], 2);
$sc['data']['appointment_to'] = parseDate($sc['data']['appointment_to'], 2);
$sc['data']['delivery'] = parseDate($sc['data']['delivery'], 2);
$sc['data']['departure'] = parseDate($sc['data']['departure'], 2);
$sc['data']['arrival'] = parseDate($sc['data']['arrival'], 2);
$sc['code'] = 1;
$sc['message'] = "Script called successfully!";
exit_script($sc);

?>
