<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

if ($_POST['text'] == "") {
  $search_term = "%%";
} else {
  $search_term = "%$_POST[text]%";
}

$id = $_POST['id'];

$system_callback = [];
$query = "SELECT * FROM mx_places WHERE place_name LIKE ? AND fk_mx_client = ?";

$system_callback['post'] = $_POST;

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('ss', $search_term, $id);
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
  $system_callback['message'] = "Este cliente no tiene destinos establecidos";
  exit_script($system_callback);
}

while ($row = $rslt->fetch_assoc()) {
  if ($row['address_int_number'] == "") {
    $interior = "";
  } else {
    $interior = " , $row[address_int_number]";
  }
  $system_callback['data'] .=
  "<tr>
    <td class='d-flex justify-content-between'>
      <div class='''>
        <b>$row[place_name]</b> - $row[place_alias]
        <div class='''>$row[address_street] $row[address_ext_number]$interior, $row[address_locality]</div>
        <div class='''>$row[address_city], $row[address_state], $row[address_zip_code]</div>
      </div>
    </td>
  </tr>";
}

$system_callback['code'] = 1;
$system_callback['message'] = "Script called successfully!";
exit_script($system_callback);



 ?>
