<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];
$txt = $_POST['txt'];

$query = "SELECT * FROM cs_usa_zipcode_catalogue WHERE zip_code = ? AND (type = 'STANDARD' OR type = 'UNIQUE') LIMIT 1";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('s', $txt);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();
$row = $rslt->fetch_assoc();

if ($rslt->num_rows == 0) {
  $system_callback['code'] = 2;
  $system_callback['message'] = "Script called successfully but there are no rows to display.";
  exit_script($system_callback);
}

$system_callback['data']['city'] = $row['primary_city'];
$system_callback['data']['state'] = $row['primary_state'];
$system_callback['code'] = 1;
$system_callback['message'] = "Script called successfully!";
exit_script($system_callback);
 ?>
