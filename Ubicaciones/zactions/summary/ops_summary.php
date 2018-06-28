<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$sc = array();

$query = "SELECT tl.lh_number linehaul , tl.origin_city o_city , tl.origin_state o_state , tl.destination_city d_city , tl.destination_state d_state , trk.truckNumber truck FROM ct_trip_linehaul tl LEFT JOIN ct_truck trk ON trk.pkid_truck = tl.current_tractor WHERE tl.date_arrival IS NULL AND tl.fk_idtrip <> '' AND tl.linehaul_status NOT IN('Closed' , 'Cancelled') AND tl.origin_zip = '78041'";

$stmt = $db->prepare($query);
$stmt->execute();
$rslt = $stmt->get_result();

while ($row = $rslt->fetch_assoc()) {
  $sc['data']['nb_trips']['count']++;
  $sc['data']['nb_trips']['table'] .= "<tr><td class='fit'>$row[linehaul]</td><td class='fit'>$row[truck]</td><td>$row[d_city], $row[d_state]</td></tr>";
}


$query = "SELECT tl.lh_number linehaul , tl.origin_city o_city , tl.origin_state o_state , tl.destination_city d_city , tl.destination_state d_state , trk.truckNumber truck FROM ct_trip_linehaul tl LEFT JOIN ct_truck trk ON trk.pkid_truck = tl.current_tractor WHERE tl.date_arrival IS NULL AND tl.fk_idtrip <> '' AND tl.linehaul_status NOT IN('Closed' , 'Cancelled') AND tl.origin_zip <> '78041'";

$stmt = $db->prepare($query);
$stmt->execute();
$rslt = $stmt->get_result();

while ($row = $rslt->fetch_assoc()) {
  $sc['data']['sb_trips']['count']++;
  $sc['data']['sb_trips']['table'] .= "<tr><td class='fit'>$row[linehaul]</td><td class='fit'>$row[truck]</td><td>$row[o_city], $row[o_state]</td></tr>";
}


$query = "SELECT t.trip_number trip , t.trailer_number trailer , max(tl.date_departure) departure , max(tl.date_appointment) appointment FROM ct_trip t LEFT JOIN ct_trip_linehaul tl ON t.pkid_trip = tl.fk_idtrip WHERE tl.linehaul_status NOT IN('Closed' , 'Cancelled') GROUP BY t.trip_number HAVING count(tl.fk_idtrip) = 1";

$stmt = $db->prepare($query);
$stmt->execute();
$rslt = $stmt->get_result();

while ($row = $rslt->fetch_assoc()) {
  $sc['data']['pr_trips']['count']++;
  $sc['data']['pr_trips']['table'] .= "<tr><td class='fit'>$row[trip]</td><td class='fit'>$row[trailer]</td><td>$row[appointment]</td></tr>";
}


$query = "SELECT tl.lh_number linehaul , t.trailer_number trailer, tl.trip_rate rate , tl.date_end , datediff(date(curdate()) , tl.date_end) days FROM ct_trip_linehaul tl LEFT JOIN ct_trip t ON t.pkid_trip = tl.fk_idtrip WHERE tl.fk_idtrip <> '' AND tl.linehaul_status = 'Closed' AND tl.invoice_number IS NULL";

$stmt = $db->prepare($query);
$stmt->execute();
$rslt = $stmt->get_result();

while ($row = $rslt->fetch_assoc()) {
  $sc['data']['pi_trips']['count']++;
  $sc['data']['pi_trips']['amount'] += $row['rate'];
  $sc['data']['pi_trips']['table'] .= "<tr><td class='fit'>$row[linehaul]</td><td class='fit'>$row[trailer]</td><td>$row[date_end] ($row[days])</td></tr>";
}

$sc['data']['pi_trips']['amount'] = number_format($sc['data']['pi_trips']['amount'], 2);


$query = "SELECT tl.lh_number linehaul , t.trailer_number trailer , tl.trip_rate rate , tl.invoice_payment_due payment_due , datediff( date(curdate()) , tl.invoice_payment_due) days FROM ct_trip_linehaul tl LEFT JOIN ct_trip t ON t.pkid_trip = tl.fk_idtrip WHERE tl.fk_idtrip <> '' AND tl.linehaul_status = 'Closed' AND tl.invoice_payment_due < curdate()";

$stmt = $db->prepare($query);
$stmt->execute();
$rslt = $stmt->get_result();

while ($row = $rslt->fetch_assoc()) {
  $due = date('Y-m-d', strtotime($row['payment_due']));
  $sc['data']['pp_trips']['count']++;
  $sc['data']['pp_trips']['amount'] += $row['rate'];
  $sc['data']['pp_trips']['table'] .= "<tr><td class='fit'>$row[linehaul]</td><td class='fit'>$row[trailer]</td><td>$due ($row[days])</td></tr>";
}

$sc['data']['pp_trips']['amount'] = number_format($sc['data']['pi_trips']['amount'], 2);







$sc['code'] = 1;
exit_script($sc);

 ?>
