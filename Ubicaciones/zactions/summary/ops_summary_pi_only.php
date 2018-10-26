<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$sc = array();
$and_where = "";

$day = date('Y-m-d', strtotime('-7 days'));

if ($_POST['this_week'] == 'true') {
  $and_where = "AND tl.date_end < '$day'";
}

$query = "SELECT tl.lh_number linehaul , t.trailer_number trailer, tl.trip_rate rate , date(tl.date_delivery) date_end , datediff(date(curdate()) , tl.date_delivery) days, b.brokerName broker, t.pkid_trip pkid_trip FROM ct_trip_linehaul tl LEFT JOIN ct_trip t ON t.pkid_trip = tl.fk_idtrip LEFT JOIN ct_brokers b ON tl.fkid_broker = b.pkid_broker WHERE tl.fk_idtrip <> '' AND tl.linehaul_status = 'Closed' AND tl.invoice_number IS NULL $and_where ORDER BY days DESC, linehaul ASC";

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



$sc['code'] = 1;
exit_script($sc);

 ?>
