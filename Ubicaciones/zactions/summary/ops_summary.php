<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';
$sc = array();

function reverseGeocode($lat, $lng){
    global $sc;

    $latlng = "latlng=$lat,$lng";

    $url = "https://maps.googleapis.com/maps/api/geocode/json?$latlng&key=AIzaSyDuBCFwHZCWMgyeTJ1MI32sXlGnJtIIsUA";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    // $sc['api'] = json_decode($response);
    return json_decode($response);
}




$query = "SELECT tl.lh_number linehaul , tl.origin_city o_city , tl.origin_state o_state, t.trailer_number trailer_number, t.pkid_trip fkid_trip, d.nameFirst first_name, d.nameLast last_name, tl.destination_city d_city , tl.destination_state d_state , trk.truckNumber truck, b.brokerName broker, tl.date_appointment appt FROM ct_trip_linehaul tl LEFT JOIN ct_brokers b ON b.pkid_broker = tl.fkid_broker LEFT JOIN ct_trip t ON t.pkid_trip = tl.fk_idtrip LEFT JOIN ct_trip_linehaul_movement tlm ON tlm.pkid_movement = t.last_movement LEFT JOIN ct_drivers d ON tlm.fkid_driver = d.pkid_driver LEFT JOIN ct_truck trk ON trk.pkid_truck = tlm.fkid_tractor WHERE tl.date_arrival IS NULL AND tl.fk_idtrip <> '' AND tl.linehaul_status NOT IN('Closed' , 'Cancelled') AND tl.origin_zip = '78041'";

$stmt = $db->prepare($query);
$stmt->execute();
$rslt = $stmt->get_result();
$rows = $rslt->num_rows;

if ($rows == 0) {
  $sc['data']['nb_trips']['count'] = 0;
  $sc['data']['nb_trips']['table'] = "<tr><td colspan='3'>No trips found</td></tr>";
} else {
  while ($row = $rslt->fetch_assoc()) {
    $sc['data']['nb_trips']['count']++;
    $sc['data']['nb_trips']['table'] .= "<tr class='linehaul' db-id='$row[fkid_trip]' role='button'>
    <td style='width: 80px'>$row[linehaul]</td>
    <td style='width: 80px'>$row[truck]</td>
    <td style='width: 160px'>$row[trailer_number]</td>
    <td>$row[first_name] $row[last_name]</td>
    <td>$row[d_city], $row[d_state]</td>
    <td>$row[broker]</td>
    <td>$row[appt]</td>
    </tr>";
  }
}



$query = "SELECT tl.lh_number linehaul, tl.origin_city o_city , tl.origin_state o_state , tl.destination_city d_city , tl.destination_state d_state , trk.truckNumber truck, tl.date_begin start_date, b.brokerName broker, d.nameFirst first_name, d.nameLast last_name, t.trailer_number trailer_number, t.pkid_trip pkid_trip FROM ct_trip_linehaul tl LEFT JOIN ct_brokers b ON tl.fkid_broker = b.pkid_broker LEFT JOIN ct_trip t ON tl.fk_idtrip = t.pkid_trip LEFT JOIN ct_trip_linehaul_movement tlm ON t.last_movement = tlm.pkid_movement LEFT JOIN ct_truck trk ON trk.pkid_truck = tlm.fkid_tractor LEFT JOIN ct_drivers d ON tlm.fkid_driver = d.pkid_driver WHERE tl.date_arrival IS NULL AND tl.fk_idtrip <> '' AND tl.linehaul_status NOT IN('Closed' , 'Cancelled') AND (tl.origin_zip <> '78041' AND tl.origin_zip <> '78045')";

$stmt = $db->prepare($query);
$stmt->execute();
$rslt = $stmt->get_result();
$rows = $rslt->num_rows;

if ($rows == 0) {
  $sc['data']['sb_trips']['count'] = 0;
  $sc['data']['sb_trips']['table'] = "<tr><td colspan='3'>No trips found</td></tr>";
} else {
  while ($row = $rslt->fetch_assoc()) {
    $sc['data']['sb_trips']['count']++;
    $sc['data']['sb_trips']['table'] .= "
    <tr class='linehaul' db-id='$row[pkid_trip]' role='button'>
    <td style='width: 80px'>$row[linehaul]</td>
    <td style='width: 80px'>$row[truck]</td>
    <td style='width: 160px'>$row[trailer_number]</td>
    <td>$row[first_name] $row[last_name]</td>
    <td>$row[o_city], $row[o_state]</td>
    <td>$row[broker]</td>
    <td>$row[start_date]</td>
    </tr>";
  }
}



$query = "SELECT t.trip_number trip , t.pkid_trip pkid_trip , t.trailer_number trailer , max(tl.date_departure) departure , max(tl.date_appointment) appointment , datediff( date(CURDATE()) , tl.date_appointment) days , trk.truckNumber truck_number , d.nameFirst first_name , d.nameLast last_name , SUBSTRING_INDEX( GROUP_CONCAT( pos.tran_ts ORDER BY pos.tran_ts DESC) , ',' , 1) pos_ts , SUBSTRING_INDEX( GROUP_CONCAT(pos.lat ORDER BY pos.tran_ts DESC) , ',' , 1) lat , SUBSTRING_INDEX( GROUP_CONCAT(pos.lon ORDER BY pos.tran_ts DESC) , ',' , 1) lon FROM ct_trip t LEFT JOIN ct_trip_linehaul tl ON t.pkid_trip = tl.fk_idtrip LEFT JOIN ct_trip_linehaul_movement tlm ON tlm.pkid_movement = t.last_movement LEFT JOIN ct_drivers d ON tlm.fkid_driver = d.pkid_driver LEFT JOIN ct_truck trk ON tlm.fkid_tractor = trk.pkid_truck LEFT JOIN omni_pos_log pos ON trk.truckNumber = pos.tractor WHERE t.trip_status NOT IN('Cancelled' , 'Closed') AND tl.linehaul_status NOT IN('Cancelled') AND tlm.destination_zip NOT IN(78045 , 78041 , 78040) GROUP BY t.trip_number , trk.truckNumber"; //HAVING count(tl.fk_idtrip) = 1

$stmt = $db->prepare($query);
$stmt->execute();
$rslt = $stmt->get_result();
$rows = $rslt->num_rows;



if ($rows == 0) {
  $sc['data']['pr_trips']['count'] = 0;
  $sc['data']['pr_trips']['table'] = "<tr><td colspan='3'>No trips found</td></tr>";
} else {
  while ($row = $rslt->fetch_assoc()) {

    $location_response = reverseGeocode($row['lat'], $row['lon']);
    $location_control_array = array('locality', 'political');

    // $location = $location_response['results'][4]['formatted_address'];
    // $location = $location_response->results->{4}->formatted_address;

    // foreach ($location_response->results as $response) {
    //   $test_value = array_diff($location_control_array, $response->types);
    // }
    $location = $location_response->results[7]->formatted_address;

    $sc['api'] = $location_response->results;
    $sc['latlng'] = "$row[lat], $row[lon]";

    $sc['data']['pr_trips']['count']++;
    $sc['data']['pr_trips']['table'] .= "<div class='card' id='$row[pkid_trip]'><div class='card-body'><h5 class='card-title'>$row[trip]</h5><div class='clearfix'><p class='card-text mb-0 float-left'>$row[first_name] $row[last_name] ($row[truck_number] - $row[trailer])</p><p class='float-right'>$row[appointment]($row[days])</p></div><p class='mb-0'>Truck Location: $location</p><p class='mb-0'>Distance to Return:</p><p class='mb-0'>ETA to Return:</p></div></div>";
    // $sc['data']['pr_trips']['table'] .= "<div class='card' id='$row[pkid_trip]'><div class='card-body'><h5 class='card-title'>$row[trip]</h5><p class='card-text mb-0'>$row[first_name] $row[last_name] ($row[truck_number] - $row[trailer])</p><div class='clearfix'><p class='card-text mb-0 float-left'>Laredo, TX - Little Rock, AR</p><p class='float-right'>$row[appointment]($row[days])</p></div><p class='mb-0'>Truck Location:</p><p class='mb-0'>Distance to Return:</p><p class='mb-0'>ETA to Return:</p></div></div>";
    // $sc['data']['pr_trips']['table'] .= "<tr db-id='$row[pkid_trip]' class='linehaul' role='button'><td style='width: 80px'>$row[trip]</td><td style='width: 80px'>$row[truck_number]</td><td style='width: 160px'>$row[trailer]</td><td>$row[first_name] $row[last_name]</td><td>$row[appointment] ($row[days])</td></tr>";
  }
}



$query = "SELECT tl.lh_number linehaul , t.trailer_number trailer, tl.trip_rate rate , date(tl.date_delivery) date_end , datediff(date(curdate()) , tl.date_delivery) days, b.brokerName broker, t.pkid_trip pkid_trip FROM ct_trip_linehaul tl LEFT JOIN ct_trip t ON t.pkid_trip = tl.fk_idtrip LEFT JOIN ct_brokers b ON tl.fkid_broker = b.pkid_broker WHERE tl.fk_idtrip <> '' AND (tl.linehaul_status = 'Closed' OR (tl.date_delivery <> '' AND tl.linehaul_status <> 'Cancelled')) AND tl.invoice_number IS NULL ORDER BY days DESC, linehaul ASC";

$stmt = $db->prepare($query);
$stmt->execute();
$rslt = $stmt->get_result();
$rows = $rslt->num_rows;

if ($rows == 0) {
  $sc['data']['pi_trips']['count'] = 0;
  $sc['data']['pi_trips']['amount'] = 0;
  $sc['data']['pi_trips']['table'] = "<tr><td colspan='3'>No trips found</td></tr>";
} else {
  while ($row = $rslt->fetch_assoc()) {
    $sc['data']['pi_trips']['count']++;
    $sc['data']['pi_trips']['amount'] += $row['rate'];
    $sc['data']['pi_trips']['table'] .= "<tr class='linehaul' db-id='$row[pkid_trip]' role='button'><td style='width: 80px'>$row[linehaul]</td><td style='width: 160px'>$row[trailer]</td><td>$row[date_end] ($row[days])</td><td>$$row[rate]</td><td>$row[broker]</td></tr>";
  }
}


$sc['data']['pi_trips']['amount'] = number_format($sc['data']['pi_trips']['amount'], 2);


$query = "SELECT tl.lh_number linehaul , t.trailer_number trailer , tl.trip_rate rate , tl.invoice_payment_due payment_due , datediff( date(curdate()) , tl.invoice_payment_due) days, tl.invoice_number invoice_number, b.brokerName broker, tl.invoice_number invoice, tl.broker_reference br_reference, t.pkid_trip pkid_trip FROM ct_trip_linehaul tl LEFT JOIN ct_trip t ON t.pkid_trip = tl.fk_idtrip LEFT JOIN ct_brokers b ON tl.fkid_broker = b.pkid_broker WHERE tl.fk_idtrip <> '' AND tl.linehaul_status = 'Closed' AND tl.invoice_payment_due < curdate() AND tl.invoice_payment_date IS NULL ORDER BY days DESC, linehaul ASC";

$stmt = $db->prepare($query);
$stmt->execute();
$rslt = $stmt->get_result();
$rows = $rslt->num_rows;

if ($rows == 0) {
  $sc['data']['pp_trips']['count'] = 0;
  $sc['data']['pp_trips']['amount'] = 0;
  $sc['data']['pp_trips']['table'] = "<tr><td colspan='3'>No trips found</td><td></td><td></td></tr>";
} else {
  while ($row = $rslt->fetch_assoc()) {
    $due = date('Y-m-d', strtotime($row['payment_due']));
    $sc['data']['pp_trips']['count']++;
    $sc['data']['pp_trips']['amount'] += $row['rate'];
    $sc['data']['pp_trips']['table'] .= "<tr class='linehaul' db-id='$row[pkid_trip]' role='button'><td style='width: 80px'>$row[linehaul]</td><td style='width: 80px'>$row[invoice]</td><td>$row[broker]</td><td>$due ($row[days])</td><td style='width: 80px'>$$row[rate]</td><td style='width: 120px'>$row[br_reference]</td></tr>";
  }
}


$sc['data']['pp_trips']['amount'] = number_format($sc['data']['pp_trips']['amount'], 2);


$query = "SELECT tl.lh_number linehaul , t.trailer_number trailer , trk.truckNumber tractor , tl.date_appointment appointment , b.brokerName broker , tl.broker_reference br_reference, tl.origin_city o_city, tl.origin_state o_state, datediff(date(curdate()) , tl.date_appointment) days, d.nameFirst first_name, d.nameLast last_name, t.pkid_trip pkid_trip FROM ct_trip_linehaul tl LEFT JOIN ct_trip t ON t.pkid_trip = tl.fk_idtrip LEFT JOIN ct_brokers b ON tl.fkid_broker = b.pkid_broker LEFT JOIN ct_trip_linehaul_movement tlm ON tlm.pkid_movement = t.last_movement LEFT JOIN ct_drivers d ON tlm.fkid_driver = d.pkid_driver LEFT JOIN ct_truck trk ON tlm.fkid_tractor = trk.pkid_truck WHERE tl.date_delivery IS NULL AND tl.fk_idtrip <> '' AND tl.linehaul_status NOT IN('Closed' , 'Cancelled') AND( tl.origin_zip <> '78041' AND tl.origin_zip <> '78045') ORDER BY appointment ASC";

$stmt = $db->prepare($query);
$stmt->execute();
$rslt = $stmt->get_result();
$rows = $rslt->num_rows;

if ($rows == 0) {
  $sc['data']['pd_trips']['count'] = 0;
  $sc['data']['pd_trips']['amount'] = 0;
  $sc['data']['pd_trips']['table'] = "<tr><td colspan='6'>No trips found</td><td></td><td></td></tr>";
} else {
  while ($row = $rslt->fetch_assoc()) {
    if ($row['days'] >= 0) {
      $sc['data']['pd_trips']['count']++;
    }

    $appt = date('Y-m-d H:i',strtotime($row['appointment']));
    $sc['data']['pd_trips']['table'] .= "<tr class='linehaul' db-id='$row[pkid_trip]' role='button'>
    <td style='width: 80px'>$row[linehaul]</td>
    <td style='width: 160px'>$row[trailer]</td>
    <td style='width: 80px'>$row[tractor]</td>
    <td>$row[first_name] $row[last_name]</td>
    <td>$row[broker]</td>
    <td>$row[o_city], $row[o_state]</td>
    <td>$appt ($row[days])</td>
    <td style='width: 80px'><button class='btn btn-sm btn-outline-success disabled' disabled>Deliver</button></td>
    </tr>";
  }
}


// $sc['data']['pd_trips']['count'] = $sc['data']['pd_trips']['count'];







$sc['code'] = 1;
exit_script($sc);

 ?>
