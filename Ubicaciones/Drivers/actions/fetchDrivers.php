<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];

$query = "SELECT d.pkid_driver pkid_driver, d.nameFirst nameFirst, d.nameLast nameLast, d.isOwner isOwner, d.isDriver isDriver, d.dateAdded dateAdded, t.truckNumber truck FROM ct_drivers d LEFT JOIN ct_truck t ON t.pkid_truck = d.default_truck WHERE deletedDriver IS NULL and d.portal_assignment = 'us'";

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
  $system_callback['message'] = "Script called successfully but there are no rows to display.";
  //$system_callback['data'] .= $row;
  exit_script($system_callback);
}

while ($row = $rslt->fetch_assoc()) {
  $system_callback['data'] .=
  "<tr role='button' driverid='$row[pkid_driver]'>
    <td>$row[pkid_driver]</td>
    <td>$row[nameFirst] $row[nameLast]</td>
    <td>$row[isOwner]</td>
    <td>$row[isDriver]</td>
    <td>" . date('Y-m-d', strtotime($row[dateAdded])) . "</td>
    <td>$row[truck]</td>
    <td class='text-right'>
      <button type='button' class='btn btn-outline-secondary' z-index=9999 name='button' driverid='$row[pkid_driver]'> <i class='fas fa-pencil-alt'></i> </button>
      <button type='button' class='btn btn-outline-danger deleteDriver' z-index=9999 name='button' driverid='$row[pkid_driver]'> <i class='far fa-trash-alt'></i> </button>
    </td>
  </tr>";
}

$system_callback['code'] = 1;
$system_callback['message'] = "Script called successfully!";
exit_script($system_callback);
 ?>
