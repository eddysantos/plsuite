<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];

$query = "SELECT * FROM ct_trailer WHERE deletedTrailer IS NULL";

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
  "<tr role='button' trailerid='$row[pkid_trailer]'>
    <td>$row[trailerNumber]</td>
    <td>$row[trailerVIN]</td>
    <td>$row[trailerOwnedBy]</td>
    <td>$row[trailerStatus]</td>
    <td>" . date('Y-m-d', strtotime($row['date_added'])) . "</td>
    <td>$row[trailerPlates]</td>
    <td class='text-right'>
      <button type='button' class='btn btn-outline-secondary' z-index=9999 name='button' driverid='$row[pkid_trailer]'> <i class='fas fa-pencil-alt'></i> </button>
      <button type='button' class='btn btn-outline-danger deleteTrailer' z-index=9999 name='button' trailerid='$row[pkid_trailer]'> <i class='far fa-trash-alt'></i> </button>
    </td>
  </tr>";
}

$system_callback['code'] = 1;
$system_callback['message'] = "Script called successfully!";
exit_script($system_callback);
 ?>
