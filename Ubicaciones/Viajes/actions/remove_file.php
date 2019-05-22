<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$post = $_POST;
$file_path = "";
$document_url = "";
$deleted_by = $_SESSION['user_info']['NombreUsuario'];
$deleted_on = date('Y-m-d H:i:s', strtotime('now'));
/* CIPHERING TEST CODE..*/

$valor1 = $post['doc_id'];
$cipher = "AES-256-CBC";
$key =hash('sha256', "ewgdhfjjluo3pip4l");
$iv = substr(hash('sha256', "sdfkljsadf567890saf"), 0, 16);
$token = openssl_decrypt(base64_decode($valor1),$cipher, $key, 0, $iv);


$file_name = uniqid() . ".$extension";
$directory = '/home/transport_files/uploads/';
$path = $directory . $file_name;
$added_by = $_SESSION['user_info']['NombreUsuario'];
$related_id = $post['id_related'];
$file_title = $post['identifier'];
$doc_category = "linehaul";


//Find the filepath to be deleted:

$get_file_path = "SELECT document_url FROM document_catalog WHERE pkid_document = ?";
$stmt = $db->prepare($get_file_path);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during FETCHING DOC PATH query prepare [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}
$stmt->bind_param('s',
  $token
);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during FETCHING DOC PATH variables binding [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}
if (!($stmt->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during FETCHING DOC PATH query execution [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();
$rows = $rslt->num_rows;


if ($rows == 0) {
  $system_callback['code'] = "500";
  $system_callback['token'] = $token;
  $system_callback['message'] = "No records were found for this document.";
  exit_script($system_callback);
} else {
  $file_path = $rslt->fetch_assoc();
  $file_path = $file_path['document_url'];
}

try {

  $db->begin_transaction();

  $query = "UPDATE document_catalog SET document_url = ?, deleted_by = ?, deleted_on = ? WHERE pkid_document = ?";

  $stmt = $db->prepare($query);
  if (!($stmt)) {
    $system_callback['code'] = "500";
    $system_callback['query'] = $query;
    $system_callback['message'] = "Error during MARK DOCUMENT AS DELETED query prepare [$db->errno]: $db->error";
    throw new \Exception($system_callback['message'], 1);

    exit_script($system_callback);
  }

  $stmt->bind_param('ssss',
    $document_url,
    $deleted_by,
    $deleted_on,
    $token
  );
  if (!($stmt)) {
    $system_callback['code'] = "500";
    $system_callback['query'] = $query;
    $system_callback['message'] = "Error during MARK DOCUMENT AS DELETED variables binding [$stmt->errno]: $stmt->error";
    exit_script($system_callback);
  }

  if (!($stmt->execute())) {
    $system_callback['code'] = "500";
    $system_callback['query'] = $query;
    $system_callback['message'] = "Error during MARK DOCUMENT AS DELETED query execution [$stmt->errno]: $stmt->error";
    exit_script($system_callback);
  }

  if ($db->affected_rows == 0) {
    $system_callback['code'] = $db->error;
    $system_callback['message'] = "Unable to modify trip to include the first and last movement.";
    exit_script($system_callback);
  }


  $removed_file = unlink($file_path);
  if (!$removed_file) {
    throw new \Exception("Error deleting file file.", 1);
  }


  $system_callback['message'] = "File uploaded correctly!";
  $system_callback['code'] = "1";

  $db->commit();
} catch (\Exception $e) {
  $db->rollback();
  $system_callback['code'] = "500";
  // $system_callback['message'] = "Unable to remove file: $db->error";
  $system_callback['error'] = "Unable to remove file: $e";

}




exit_script($system_callback);

 ?>
