<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$sc = array();

$query = "SELECT tl.lh_number linehaul , tl.origin_city o_city , tl.origin_state o_state , tl.destination_city d_city , tl.destination_state d_state , trk.truckNumber truck FROM ct_trip_linehaul tl LEFT JOIN ct_truck trk ON trk.pkid_truck = tl.current_tractor WHERE tl.date_arrival IS NULL AND tl.fk_idtrip <> '' AND tl.linehaul_status NOT IN('Closed' , 'Cancelled') AND tl.origin_zip = '78041'";

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
    $sc['data']['nb_trips']['table'] .= "<tr><td class='fit'>$row[linehaul]</td><td class='fit'>$row[truck]</td><td>$row[d_city], $row[d_state]</td></tr>";
  }
}



$query = "SELECT tl.lh_number linehaul , tl.origin_city o_city , tl.origin_state o_state , tl.destination_city d_city , tl.destination_state d_state , trk.truckNumber truck FROM ct_trip_linehaul tl LEFT JOIN ct_truck trk ON trk.pkid_truck = tl.current_tractor WHERE tl.date_arrival IS NULL AND tl.fk_idtrip <> '' AND tl.linehaul_status NOT IN('Closed' , 'Cancelled') AND (tl.origin_zip <> '78041' AND tl.origin_zip <> '78045')";

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
    $sc['data']['sb_trips']['table'] .= "<tr><td class='fit'>$row[linehaul]</td><td class='fit'>$row[truck]</td><td>$row[o_city], $row[o_state]</td></tr>";
  }
}



$query = "SELECT t.trip_number trip , t.trailer_number trailer , max(tl.date_departure) departure , max(tl.date_appointment) appointment FROM ct_trip t LEFT JOIN ct_trip_linehaul tl ON t.pkid_trip = tl.fk_idtrip WHERE t.trip_status NOT IN ('Cancelled', 'Closed') AND tl.linehaul_status NOT IN('Cancelled') GROUP BY t.trip_number HAVING count(tl.fk_idtrip) = 1";

$stmt = $db->prepare($query);
$stmt->execute();
$rslt = $stmt->get_result();
$rows = $rslt->num_rows;

if ($rows == 0) {
  $sc['data']['pr_trips']['count'] = 0;
  $sc['data']['pr_trips']['table'] = "<tr><td colspan='3'>No trips found</td></tr>";
} else {
  while ($row = $rslt->fetch_assoc()) {
    $sc['data']['pr_trips']['count']++;
    $sc['data']['pr_trips']['table'] .= "<tr><td class='fit'>$row[trip]</td><td class='fit'>$row[trailer]</td><td>$row[appointment]</td></tr>";
  }
}



$query = "SELECT tl.lh_number linehaul , t.trailer_number trailer, tl.trip_rate rate , tl.date_end date_end , datediff(date(curdate()) , tl.date_end) days, b.brokerName broker FROM ct_trip_linehaul tl LEFT JOIN ct_trip t ON t.pkid_trip = tl.fk_idtrip LEFT JOIN ct_brokers b ON tl.fkid_broker = b.pkid_broker WHERE tl.fk_idtrip <> '' AND tl.linehaul_status = 'Closed' AND tl.invoice_number IS NULL ORDER BY days DESC, linehaul ASC";

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
    $sc['data']['pi_trips']['table'] .= "<tr><td>$row[linehaul]</td><td>$row[trailer]</td><td>$row[date_end] ($row[days])</td><td>$$row[rate]</td><td>$row[broker]</td></tr>";
  }
}


$sc['data']['pi_trips']['amount'] = number_format($sc['data']['pi_trips']['amount'], 2);


$query = "SELECT tl.lh_number linehaul , t.trailer_number trailer , tl.trip_rate rate , tl.invoice_payment_due payment_due , datediff( date(curdate()) , tl.invoice_payment_due) days, tl.invoice_number invoice_number, b.brokerName broker, tl.invoice_number invoice, tl.broker_reference br_reference FROM ct_trip_linehaul tl LEFT JOIN ct_trip t ON t.pkid_trip = tl.fk_idtrip LEFT JOIN ct_brokers b ON tl.fkid_broker = b.pkid_broker WHERE tl.fk_idtrip <> '' AND tl.linehaul_status = 'Closed' AND tl.invoice_payment_due < curdate() AND tl.invoice_payment_date IS NULL ORDER BY days DESC, linehaul ASC";

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
    $sc['data']['pp_trips']['table'] .= "<tr><td>$row[invoice]</td><td>$row[broker]</td><td>$due ($row[days])</td><td>$$row[rate]</td><td>$row[br_reference]</td></tr>";
  }
}


$sc['data']['pp_trips']['amount'] = number_format($sc['data']['pp_trips']['amount'], 2);


$query = "SELECT tl.lh_number linehaul , t.trailer_number trailer , trk.truckNumber tractor , tl.date_appointment appointment , b.brokerName broker , tl.broker_reference br_reference, tl.origin_city o_city, tl.origin_state o_state, datediff(date(curdate()) , tl.date_appointment) days FROM ct_trip_linehaul tl LEFT JOIN ct_trip t ON t.pkid_trip = tl.fk_idtrip LEFT JOIN ct_brokers b ON tl.fkid_broker = b.pkid_broker LEFT JOIN ct_truck trk ON trk.pkid_truck = tl.current_tractor WHERE tl.date_delivery IS NULL AND tl.fk_idtrip <> '' AND tl.linehaul_status NOT IN('Closed' , 'Cancelled') AND( tl.origin_zip <> '78041' AND tl.origin_zip <> '78045') ORDER BY appointment ASC";

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
    $appt = date('Y-m-d H:i',strtotime($row['appointment']));


    $sc['data']['pd_trips']['count']++;
    $sc['data']['pd_trips']['amount'] += $row['rate'];
    $sc['data']['pd_trips']['table'] .= "<tr>
    <td style='width: 80px'>$row[linehaul]</td>
    <td style='width: 160px'>$row[trailer]</td>
    <td style='width: 80px'>$row[tractor]</td>
    <td>$row[broker]</td><td>$row[o_city], $row[o_state]</td>
    <td>$appt ($row[days])</td>
    </tr>";
    // <td><button class='btn btn-outline-success'>Deliver</button></td>
  }
}


// $sc['data']['pd_trips']['count'] = $sc['data']['pd_trips']['count'];







$sc['code'] = 1;
exit_script($sc);

 ?>
