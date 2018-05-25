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


$query = "UPDATE ct_trip_linehaul SET invoice_number = ?, invoice_amount = ?, invoice_payment_date = ?, invoice_check_number = ?, invoice_bank_name = ?, invoice_comments = ? WHERE pk_idlinehaul = ?";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $sc['code'] = "500";
  $sc['message'] = "Error during trailer query prepare [$db->errno]: $db->error";
  exit_script($sc);
}

$stmt->bind_param('sssssss',
  $data['invoice_number'],
  $data['invoice_amount'],
  $data['payment_date'],
  $data['check_number'],
  $data['bank_name'],
  $data['check_comments'],
  $data['dbid']
);

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

if ($stmt->affected_rows == 0) {
  $sc['code'] = 2;
  $sc['message'] = "No record was updated.";
  exit_script($sc);
} else {
  $sc['code'] = 1;
  $sc['message'] = "Record was updated successfully.";
  exit_script($sc);
}

?>
