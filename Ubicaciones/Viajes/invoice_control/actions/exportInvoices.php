<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$data = $_POST;

$get_invoice_data = "SELECT tl.invoice_number invoice , tl.lh_number lh_number , tl.date_departure departure , t.trailer_number trailer , tl.origin_city ocity , tl.origin_state osate , tl.destination_city dcity , tl.destination_state dstate , tl.trip_rate rate , tl.invoice_date inv_date , tl.invoice_payment_due payment_due , tl.invoice_payment_date payment_date , b.brokerName broker FROM ct_trip t LEFT JOIN ct_trip_linehaul tl ON t.pkid_trip = tl.fk_idtrip LEFT JOIN ct_brokers b ON tl.fkid_broker = b.pkid_broker WHERE tl.date_departure BETWEEN ? AND ? OR tl.invoice_date BETWEEN ? AND ? OR tl.invoice_payment_due BETWEEN ? AND ? OR tl.fkid_broker = ? OR b.brokerName LIKE ? OR t.fkid_trailer = ? OR t.trailer_number LIKE ?";

$get_invoice_data = $db->prepare($get_invoice_data);
$query_identifier = "INVOICE EXPORT";
if (!($get_invoice_data)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during $query_identifier query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$get_invoice_data->bind_param('ssssssssss',

);
if (!($get_invoice_data)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during $query_identifier variables binding [$get_invoice_data->errno]: $get_invoice_data->error";
  exit_script($system_callback);
}

if (!($get_invoice_data->execute())) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during $query_identifier query execution [$get_invoice_data->errno]: $get_invoice_data->error";
  exit_script($system_callback);


}

 ?>
