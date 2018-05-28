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

    $return['date'] = date('Y-m-d', strtotime($datestamp));
    $return['time']['hour'] = date('H', strtotime($datestamp));
    $return['time']['minute'] = date('i', strtotime($datestamp));

    return $return;
  }
}

function numberify($number){
  return number_format($number, 2);
}

$string = $data['dbid'];


$query = "SELECT tl.pk_idlinehaul linehaulid, tl.lh_number linehaul , b.brokerName broker , tl.broker_reference broker_reference , t.trip_status trip_status , t.trailer_number trailer_number , tl.origin_city ocity , tl.origin_state ostate , tl.destination_city dcity , tl.destination_state dstate , tl.trip_rate rate , tl.date_departure departure , tl.date_arrival arrival , tl.invoice_number invoice_number , tl.invoice_amount invoice_amount , tl.invoice_payment_date invoice_payment_date , tl.invoice_check_number check_number , tl.invoice_bank_name bank_name , tl.invoice_comments invoice_comments, tl.invoice_payment_due payment_due, tl.lh_comment lh_comment FROM ct_trip t LEFT JOIN ct_trip_linehaul tl ON t.pkid_trip = tl.fk_idtrip LEFT JOIN ct_brokers b ON tl.fkid_broker = b.pkid_broker AND t.trip_year = tl.fk_tripyear WHERE tl.pk_idlinehaul = ? GROUP BY tl.lh_number ORDER BY t.trip_number DESC , tl.pk_idlinehaul ASC";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $sc['code'] = "500";
  $sc['message'] = "Error during trailer query prepare [$db->errno]: $db->error";
  exit_script($sc);
}

$stmt->bind_param('s', $data['dbid']);
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
  $sc['data'] = "No trip was found.";
  $sc['message'] = "Script called successfully but no info was found.";
  exit_script($sc);
} else {
  $sc['data'] = $rslt->fetch_assoc();
}

$sc['data']['departure'] = parseDate($sc['data']['departure'], 2);
$sc['data']['arrival'] = parseDate($sc['data']['arrival'], 2);
$sc['data']['payment_due'] = parseDate($sc['data']['payment_due'], 2);

$sc['data']['departure_date'] = $sc['data']['departure']['date'];
$sc['data']['departure_time_hour'] = $sc['data']['departure']['time']['hour'];
$sc['data']['departure_time_minute'] = $sc['data']['departure']['time']['minute'];
$sc['data']['arrival_date'] = $sc['data']['arrival']['date'];
$sc['data']['arrival_time_hour'] = $sc['data']['arrival']['time']['hour'];
$sc['data']['arrival_time_minute'] = $sc['data']['arrival']['time']['minute'];
$sc['data']['payment_due_date'] = $sc['data']['payment_due']['date'];
$sc['data']['payment_due_hour'] = $sc['data']['payment_due']['time']['hour'];
$sc['data']['payment_due_minute'] = $sc['data']['payment_due']['time']['minute'];

$sc['code'] = 1;
$sc['message'] = "Script called successfully!";
exit_script($sc);

?>
