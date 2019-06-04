<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

if ($_POST == []) {
  $search_term = "%%";
} else {
  $search_term = "%$_POST[text]%";
}

$system_callback = [];
$query = "SELECT * FROM mx_clients WHERE client_name LIKE ?";

$system_callback['post'] = $_POST;

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('s', $search_term);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
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
  $system_callback['message'] = "No existen clientes registrados.";
  exit_script($system_callback);
}

while ($row = $rslt->fetch_assoc()) {
  if ($row['address_int_number'] == "") {
    $interior = "";
  } else {
    $interior = " , $row[address_int_number]";
  }
  $system_callback['data'] .=
  "<tr data-id='$row[pk_mx_client]' role='button'>
    <td class='d-flex justify-content-between align-items-center'>
      <div class=''>
        <b>$row[client_alias]</b> [<i>$row[client_name]</i>]
        <p class='m-0 p-0'>$row[address_street] $row[address_ext_number]$interior, $row[address_locality]</p>
        <p clas='m-0 p-0'>$row[address_city], $row[address_state], $row[address_zip_code]</p>
      </div>
      <!--i class='far fa-arrow-alt-circle-right' style='font-size: xx-large'></i-->
    </td>
  </tr>";
}

$system_callback['code'] = 1;
$system_callback['message'] = "Script called successfully!";
exit_script($system_callback);



 ?>
