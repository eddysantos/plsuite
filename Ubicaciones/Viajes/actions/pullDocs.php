<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$sc = [];
$data = $_POST;

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

    $return['date'] = date('Y-m-d', strtotime($datestamp));
    $return['time']['hour'] = date('H', strtotime($datestamp));
    $return['time']['minute'] = date('i', strtotime($datestamp));

    return $return;
  }
}

function numberify($number){
  return number_format($number, 2);
}

function encrypt($string){
  $cipher = "AES-256-CBC";
  $key =hash('sha256', "ewgdhfjjluo3pip4l");
  $iv = substr(hash('sha256', "sdfkljsadf567890saf"), 0, 16);
  $token = openssl_encrypt($string, $cipher, $key, 0, $iv);
  $token = base64_encode($token);

  return $token;
  // $token = openssl_decrypt(base64_decode("UmhaN284bEUxeStZWXF0eTJ3ODhNQT09"),$cipher, $key, 0, $iv);
}



$query = "SELECT document_type , document_name , document_url , date_added , added_by , pkid_document FROM document_catalog WHERE fk_related_id = ? AND fk_related_type ='linehaul'";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $sc['code'] = "500";
  $sc['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($sc);
}

$stmt->bind_param('s', $data['lhid']);
if (!($stmt)) {
  $sc['code'] = "500";
  $sc['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
  exit_script($sc);
}

if (!($stmt->execute())) {
  $sc['code'] = "500";
  $sc['message'] = "Error during query execution [$db->errno]: $db->error";
  exit_script($sc);
}

$rslt = $stmt->get_result();

if ($rslt->num_rows == 0) {
  $sc['code'] = 1;
  $sc['data'] = "<tr><td>No documents were found.</td><td></td><td></td><td></td></tr>";
  // $sc['data'] = $data;
  exit_script($sc);
}

while ($row = $rslt->fetch_assoc()) {

  $encrypted_id = encrypt($row['pkid_document']);

  $table_row = "<tr>
    <td>$row[document_name]</td>
    <td>$row[date_added]</td>
    <td>$row[added_by]</td>
    <td class='text-right'>
      <i class='far fa-file-pdf mr-1 show-pdf' data-toggle='modal' data-target='#docs_viewer' role='button' document_id='$encrypted_id'></i>
      // <i class='far fa-trash-alt mr-1 text-danger' role='button'></i>
    </td>
  </tr>";
  $sc['data'] .= $table_row;
}

$sc['code'] = 1;
$sc['message'] = "Script called successfully!";
exit_script($sc);

 ?>
