<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$sc = [];
$data = $_POST;
$pre_where = array();
$where = "";
$s = "";
$bind_params = array();

// $bind_params[] =& $s;

// NOTE: brokerName and trailerNumber must have '%' at the beginning and end for the LIKE clause.



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
  $query = "SELECT tl.pk_idlinehaul dbid, tl.lh_number linehaul , t.trip_status trip_status , t.trailer_number trailer_number , tl.origin_city ocity , tl.origin_state ostate , tl.destination_city dcity , tl.destination_state dstate , tl.trip_rate rate, tl.invoice_number invoice_number, tl.invoice_check_number check_number, tl.date_departure dep_date, tl.invoice_date invoice_date, tl.invoice_payment_date payment_date, tl.fkid_broker broker FROM ct_trip t LEFT JOIN ct_trip_linehaul tl ON t.pkid_trip = tl.fk_idtrip AND t.trip_year = tl.fk_tripyear WHERE (tl.lh_number LIKE ? OR t.trailer_number LIKE ?) AND tl.linehaul_status = 'Closed' AND tl.linehaul_status = 'Closed' AND tl.invoice_number <> '' AND tl.invoice_check_number = '' GROUP BY tl.lh_number ORDER BY t.trip_number ASC , tl.pk_idlinehaul ASC";
} else {
  $query = "SELECT tl.pk_idlinehaul dbid, tl.lh_number linehaul , t.trip_status trip_status , t.trailer_number trailer_number , tl.origin_city ocity , tl.origin_state ostate , tl.destination_city dcity , tl.destination_state dstate , tl.trip_rate rate, tl.invoice_number invoice_number, tl.invoice_check_number check_number, tl.date_departure dep_date, tl.invoice_date invoice_date, tl.invoice_payment_date payment_date, tl.fkid_broker broker FROM ct_trip t LEFT JOIN ct_trip_linehaul tl ON t.pkid_trip = tl.fk_idtrip WHERE (tl.lh_number LIKE ? OR t.trailer_number LIKE ?) GROUP BY tl.lh_number ORDER BY t.trip_number ASC , tl.pk_idlinehaul ASC";
} // NOTE: Removed AND tl.linehaul_status = 'Closed'

// NOTE: Creating a new query to test new implementation...

// NOTE: All the WHERE's statements come from the javascript (AJAX) information.

if ($data['trips']['from'] != "") {
  $pre_where[] = "tl.date_departure BETWEEN ? AND ?";
  $s .= "ss";
  $bind_params[] =& $data['trips']['from'];
  $bind_params[] =& $data['trips']['to'];
}

if ($data['trips']['client']['id'] != "") {
  $pre_where[] = "tl.fkid_broker = ?";
  $s .= "s";
  $bind_params[] =& $data['trips']['client']['id'];
}

if ($data['trips']['client']['name'] != "") {
  $data['trips']['client']['name'] = "%" . $data['trips']['client']['name'] . "%";
  $pre_where[] = "b.brokerName LIKE ?";
  $s .= "s";
  $bind_params[] =& $data['trips']['client']['name'];
}

if ($data['trips']['trailer']['id'] != "") {
  $pre_where[] = "t.fkid_trailer = ?";
  $s .= "s";
  $bind_params[] =& $data['trips']['trailer']['id'];
}

if ($data['trips']['trailer']['name'] != "") {
  $data['trips']['trailer']['name'] = "%" . $data['trips']['trailer']['name'] . "%";
  $pre_where[] = "t.trailer_number LIKE ?";
  $s .= "s";
  $bind_params[] =& $data['trips']['trailer']['name'];
}

if ($data['invoice']['from'] != "") {
  $pre_where[] = "tl.invoice_date BETWEEN ? AND ?";
  $s .= "ss";
  $bind_params[] =& $data['invoice']['from'];
  $bind_params[] =& $data['invoice']['to'];
}

if ($data['payment']['from'] != "") {
  $pre_where[] = "tl.invoice_payment_date BETWEEN ? AND ?";
  $s .= "ss";
  $bind_params[] =& $data['payment']['from'];
  $bind_params[] =& $data['payment']['to'];
}

if ($data['invoice']['no_invoice'] != "") {
  $pre_where[] = "((tl.invoice_number IS NULL OR tl.invoice_number = '') AND tl.linehaul_status = 'Closed')";
}

if ($data['payment']['no_payment'] != "") {
  $pre_where[] = "((tl.invoice_payment_date IS NULL OR tl.invoice_payment_date = '') AND (tl.invoice_number IS NOT NULL AND tl.invoice_number <> ''))";
}

foreach ($pre_where as $clause) {
  // if ($where == "") {
  //   $where .= $clause;
  //   continue;
  // }

  $where .= " AND $clause";
}

array_unshift($bind_params, $s);

//
// $sc['data'] = $data;
// $sc['pre_where'] = $pre_where;
$sc['where'] = $where;
$sc['bind'] = $bind_params;
// $sc['s'] = $s;
// exit_script($sc);



$query = "SELECT
  tl.pk_idlinehaul dbid,
	tl.invoice_number invoice ,
	tl.lh_number lh_number ,
	tl.date_departure departure ,
	t.trailer_number trailer ,
	tl.origin_city ocity ,
	tl.origin_state ostate ,
	tl.destination_city dcity ,
	tl.destination_state dstate ,
	tl.trip_rate rate ,
	tl.invoice_date inv_date ,
	tl.invoice_payment_due payment_due ,
	tl.invoice_payment_date payment_date ,
  tl.linehaul_status lh_status,
	b.brokerName broker
FROM
	ct_trip t
LEFT JOIN ct_trip_linehaul tl ON t.pkid_trip = tl.fk_idtrip
LEFT JOIN ct_brokers b ON tl.fkid_broker = b.pkid_broker
WHERE
  tl.linehaul_status <> 'Cancelled'
  $where";



$stmt = $db->prepare($query);
if (!($stmt)) {
  $sc['code'] = "500";
  $sc['message'] = "Error during trailer query prepare [$db->errno]: $db->error";
  exit_script($sc);
}

// $stmt->bind_param('ss', $data['trips']['from'], $data['trips']['to']);

if ($s != "") {
  call_user_func_array(array($stmt, 'bind_param'), $bind_params);

  if (!($stmt)) {
    $sc['code'] = "500";
    $sc['message'] = "Error during trailer variables binding [$stmt->errno]: $stmt->error";
    exit_script($sc);
  }
}


if (!($stmt->execute())) {
  $sc['code'] = "500";
  $sc['query'] = $query;
  $sc['stmt'] = $stmt->param_count;
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


    if ($row['payment_date'] != "" && $row['invoice'] != "" && $row['lh_status'] == 'Closed') {
      $status = "table-success";
      $data_target = "target='#pending-invoice-trip-details' dbid='$row[dbid]'";
    }

    if ($row['payment_date'] == "") {
      $status = "table-warning";
      $data_target = "target='#pending-invoice-trip-details' dbid='$row[dbid]'";
    }

    if ($row['invoice'] == "") {
      $status = "table-danger";
      $data_target = "target='#pending-invoice-trip-details' dbid='$row[dbid]'";
    }

    if ($row['lh_status'] != 'Closed') {
      $status = "";
      $data_target = "target='no-contest'";
    }


    // if ($row['invoice_number'] != "") {
    //   $status = "table-warning";
    // }
    //
    // if ($row['check_number'] != "") {
    //   $status = "table-success";
    // }

    $rate = numberify($row['rate']);
    $trip_date = parseDate($row['departure']);
    $pay_date = parseDate($row['payment_date']);
    $invoice_date = parseDate($row['inv_date']);
    $sc['data'] .= "<tr role='button' class='$status' $data_target ><td>US$row[lh_number]</td><td>$trip_date</td><td>$row[trailer]</td><td>$row[ocity], $row[ostate] - $row[dcity], $row[dstate]</td><td>Driver</td><td>$row[broker]</td><td>$$rate</td><td>$row[invoice]</td><td>$invoice_date</td><td>$pay_date</td></tr>";
  }
}






$sc['code'] = 1;
$sc['message'] = "Script called successfully!";
exit_script($sc);

?>
