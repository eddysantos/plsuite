<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$sc = [];
$data = $_POST;

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

    $return['date'] = date('Y/m/d', strtotime($datestamp));
    $return['time']['hour'] = date('H', strtotime($datestamp));
    $return['time']['minute'] = date('i', strtotime($datestamp));

    return $return;
  }
}

function numberify($number){
  return number_format($number, 2);
}

$string = "%" . $data['text'] . "%";

if ($data['pp_active'] == 1) {
  $query = "SELECT tl.pk_idlinehaul dbid, tl.lh_number linehaul , t.trip_status trip_status , t.trailer_number trailer_number , tl.origin_city ocity , tl.origin_state ostate , tl.destination_city dcity , tl.destination_state dstate , tl.trip_rate rate, tl.invoice_number invoice_number, tl.invoice_check_number check_number FROM ct_trip t LEFT JOIN ct_trip_linehaul tl ON t.pkid_trip = tl.fk_idtrip AND t.trip_year = tl.fk_tripyear WHERE (tl.lh_number LIKE ? OR t.trailer_number LIKE ?) AND tl.linehaul_status = 'Closed' AND tl.linehaul_status = 'Closed' AND tl.invoice_number <> '' AND tl.invoice_check_number = '' GROUP BY tl.lh_number ORDER BY t.trip_number DESC , tl.pk_idlinehaul ASC";
} else {
  $query = "SELECT tl.pk_idlinehaul dbid, tl.lh_number linehaul , t.trip_status trip_status , t.trailer_number trailer_number , tl.origin_city ocity , tl.origin_state ostate , tl.destination_city dcity , tl.destination_state dstate , tl.trip_rate rate, tl.invoice_number invoice_number, tl.invoice_check_number check_number FROM ct_trip t LEFT JOIN ct_trip_linehaul tl ON t.pkid_trip = tl.fk_idtrip WHERE (tl.lh_number LIKE ? OR t.trailer_number LIKE ?) AND tl.linehaul_status = 'Closed' GROUP BY tl.lh_number ORDER BY t.trip_number DESC , tl.pk_idlinehaul ASC";
}



$stmt = $db->prepare($query);
if (!($stmt)) {
  $sc['code'] = "500";
  $sc['message'] = "Error during trailer query prepare [$db->errno]: $db->error";
  exit_script($sc);
}

$stmt->bind_param('ss', $string, $string);
if (!($stmt)) {
  $sc['code'] = "500";
  $sc['message'] = "Error during trailer variables binding [$stmt->errno]: $stmt->error";
  exit_script($sc);
}

if (!($stmt->execute())) {
  $sc['code'] = "500";
  $sc['message'] = "Error during trailer query execution [$stmt->errno]: $stmt->error";
  exit_script($sc);
}

$rslt = $stmt->get_result();

if ($rslt->num_rows == 0) {
  $sc['code'] = 2;
  $sc['data'] = "<tr><td colspan='4'>No trips found</td></tr>";
  $sc['message'] = "Script called successfully but there are no rows to display. For trip query.";
  exit_script($sc);
} else {
  while ($row = $rslt->fetch_assoc()) {
    $status = "";

    if ($row['invoice_number'] != "") {
      $status = "table-warning";
    }

    if ($row['check_number'] != "") {
      $status = "table-success";
    }

    $rate = numberify($row['rate']);
    $sc['data'] .= "<tr role='button' class='$status' target='#pending-invoice-trip-details' dbid='$row[dbid]'><td>$row[linehaul]</td><td>$row[trailer_number]</td><td>$row[ocity], $row[ostate] - $row[dcity], $row[dstate]</td><td>$$rate</td><td>$row[invoice_number]</td></tr>";
  }
}






$sc['code'] = 1;
$sc['message'] = "Script called successfully!";
exit_script($sc);

?>
