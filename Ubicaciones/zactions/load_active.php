<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];
$data = $_POST;

$query = "SELECT pk_idlinehaul linehaulid, linehaul_status status, fk_idtrip trip , fk_tripyear tyear , pk_linehaul_number linehaul , origin_city ocity , origin_state ostate , destination_city dcity , destination_state dstate FROM ct_trip_linehaul WHERE linehaul_status NOT IN('Cancelled' , 'Closed') AND fk_idtrip <> ''";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during TRIP_LINEHAUL query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['query']['code'] = "500";
  $system_callback['query']['query'] = $query;
  $system_callback['query']['message'] = "Error during TRIP_LINEHAUL query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();
$linehauls = array();

if ($rslt->num_rows == 0) {
  $system_callback['query']['code'] = 2;
  $system_callback['query']['message'] = "Script called successfully but there are no rows to display. For trailer query.";
} else {
  while ($row = $rslt->fetch_assoc()) {
    $linehauls[] = $row;
  }
}

foreach ($linehauls as $lh) {
  global $system_callback;
  $system_callback['number']++;
  $system_callback['data'] .= "<tr role='button' db-id='$lh[linehaulid]' tripnumber='$lh[trip]' lh-number='$lh[linehaul]' tripyear='$lh[tyear]'>
  <td>
  <div class=''><i class='fa fa-circle $lh[status] mr-1'></i><span class='font-weight-bold'>" . $lh['tyear'] .  str_pad($lh['trip'], 4, 0, STR_PAD_LEFT) . $lh['linehaul'] . "</span>
  :$lh[ocity], $lh[ostate] -> $lh[dcity], $lh[dstate]</div>
  </td>
  </tr>";
}

$system_callback['query']['code'] = 1;
exit_script($system_callback);

?>
