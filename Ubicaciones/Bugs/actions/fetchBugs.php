<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];

$query = "SELECT * FROM cu_bugs WHERE bug_status = 'Open'";

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
  $system_callback['data'] .= "<tr><td colspan='6'>There are no bugs to display!</td></tr>";
  exit_script($system_callback);
}

while ($row = $rslt->fetch_assoc()) {

  $bug_status = $row['bug_status'] == "Open" ? "" : "table-success";

  $system_callback['data'] .=
  "<tr role='button' db-id='$row[pkid_bug]'>
  <td class='type'>$row[bug_type]</td>
  <td class='area'>$row[bug_area]</td>
  <td class='reportedBy'>$row[bug_reportedby]</td>
  <td class='status'>$row[bug_status]</td>
  <td class='subject'>$row[bug_subject]</td>
  <td class='description'>$row[bug_description]</td>
  <td class='$bug_status'><button class='bug-fixed btn btn-outline-success'><i class='fa fa-check'></i></button></td>
  </tr>";
}

$system_callback['code'] = 1;
$system_callback['message'] = "Script called successfully!";
exit_script($system_callback);
 ?>
