<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];
$query = "SELECT * FROM ct_drivers WHERE portal_assignment = 'mx'";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query execution [$db->errno]: $db->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();

if ($rslt->num_rows == 0) {
  $system_callback['code'] = 2;
  $system_callback['message'] = "No existen operadores registrados";
  exit_script($system_callback);
}

while ($row = $rslt->fetch_assoc()) {
  if ($row['address_int_number'] == "") {
    $interior = "";
  } else {
    $interior = " , $row[address_int_number]";
  }
  $system_callback['data'] .=
  "<tr data-id='$row[pkid_driver]'>
    <td class=''>
      $row[nameFirst] $row[nameSecond] $row[nameLast] $row[nameLastSecond]
    </td>
  </tr>";
}

$system_callback['code'] = 1;
$system_callback['message'] = "Script called successfully!";
exit_script($system_callback);



 ?>
