<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$system_callback = [];

$query = "SELECT *, pkIdUsers id FROM Users";

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

  if ($row['cred_global_status']) {
    $status_switch = "<input type='checkbox' class='custom-control-input' id='cred_global_status$row[id]' switcher='cred_global_status' checked>";
  } else {
    $status_switch = "<input type='checkbox' class='custom-control-input' id='cred_global_status$row[id]' switcher='cred_global_status'>";
  }

  if ($row['cred_mexican_portal']) {
    $mp_switch = "<input type='checkbox' class='custom-control-input' id='cred_mexican_portal$row[id]' switcher='cred_mexican_portal' checked>";
  } else {
    $mp_switch = "<input type='checkbox' class='custom-control-input' id='cred_mexican_portal$row[id]' switcher='cred_mexican_portal'>";
  }

  if ($row['cred_american_portal']) {
    $us_switch = "<input type='checkbox' class='custom-control-input' id='cred_american_portal$row[id]' switcher='cred_american_portal' checked>";
  } else {
    $us_switch = "<input type='checkbox' class='custom-control-input' id='cred_american_portal$row[id]' switcher='cred_american_portal'>";
  }

  if ($row['cred_is_admin']) {
    $is_admin = "<input type='checkbox' class='custom-control-input' id='cred_is_admin$row[id]' switcher='cred_is_admin' checked>";
  } else {
    $is_admin = "<input type='checkbox' class='custom-control-input' id='cred_is_admin$row[id]' switcher='cred_is_admin'>";
  }


  $system_callback['data'] .= "<tr data-id='$row[id]'><td><div class='form'><div class='custom-control custom-switch'>$status_switch<label class='custom-control-label' for='cred_global_status$row[id]'></label></div></div></td><td>$row[Nombre] $row[Apellido]</td><td>$row[NombreUsuario]</td>
  <td>
    <div class='form'>
      <div class='custom-control custom-switch'>$is_admin<label class='custom-control-label' for='cred_is_admin$row[id]'>Admin Privileges</label></div>
      <div class='custom-control custom-switch'>$mp_switch<label class='custom-control-label' for='cred_mexican_portal$row[id]'>Mexican Portal</label></div>
      <div class='custom-control custom-switch'>$us_switch<label class='custom-control-label' for='cred_american_portal$row[id]'>American Portal</label></div>
    </div>
  </td>
  <td></td></tr>";
}

$system_callback['code'] = 1;
$system_callback['message'] = "Script called successfully!";
exit_script($system_callback);
 ?>
