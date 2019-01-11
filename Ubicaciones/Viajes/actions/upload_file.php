<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$data = $_FILES;
$post = $_POST;

/* CIPHERING TEST CODE..*/

// $valor1 = "linehaul, 1";
// $cipher = "AES-256-CBC";
// $key =hash('sha256', "ewgdhfjjluo3pip4l");
// $iv = substr(hash('sha256', "sdfkljsadf567890saf"), 0, 16);
// $token = openssl_decrypt(base64_decode("UmhaN284bEUxeStZWXF0eTJ3ODhNQT09"),$cipher, $key, 0, $iv);
//
// $token = openssl_encrypt($valor1, $cipher, $key, 0, $iv);
// $token = base64_encode($token);

// $file = $data[0];


$authorized_files = ['pdf', 'jpg', 'jpeg', 'xls', 'xlsx', 'doc', 'docx', 'bmp', 'txt'];
$extension = pathinfo($data['file']['name'],PATHINFO_EXTENSION);
$test_extension = in_array($extension, $authorized_files);

if (!$test_extension) {
  $system_callback['code'] = '502';
  $system_callback['message'] = 'The uploaded file does not match the authorized file extensions';
  exit_script($system_callback);
}

$file_name = uniqid() . ".$extension";
$directory = '/home/transport_files/uploads/';
$path = $directory . $file_name;
$added_by = $_SESSION['user_info']['NombreUsuario'];
$related_id = $post['id_related'];
$file_title = $post['identifier'];
$doc_category = "linehaul";

try {

  $db->begin_transaction();

  $query = "INSERT INTO document_catalog(document_type, document_name, document_url, added_by, fk_related_id, fk_related_type) VALUES (?,?,?,?,?,?)";

  $stmt = $db->prepare($query);
  if (!($stmt)) {
    $system_callback['code'] = "500";
    $system_callback['query'] = $query;
    $system_callback['message'] = "Error during ADD DOCUMENT REGISTRY query prepare [$stmt->errno]: $stmt->error";
    throw new \Exception("Did not add nothing to database", 1);

    exit_script($system_callback);
  }

  error_log("Inserting?");

  $stmt->bind_param('ssssss',
    $extension,
    $file_title,
    $path,
    $added_by,
    $related_id,
    $doc_category
  );
  if (!($stmt)) {
    $system_callback['code'] = "500";
    $system_callback['query'] = $query;
    $system_callback['message'] = "Error during ADD DOCUMENT REGISTRY variables binding [$stmt->errno]: $stmt->error";
    exit_script($system_callback);
  }

  if (!($stmt->execute())) {
    $system_callback['code'] = "500";
    $system_callback['query'] = $query;
    $system_callback['message'] = "Error during ADD DOCUMENT REGISTRY query execution [$stmt->errno]: $stmt->error";
    exit_script($system_callback);
  }

  if ($db->affected_rows == 0) {
    $system_callback['code'] = $db->error;
    $system_callback['message'] = "Unable to modify trip to include the first and last movement.";
    exit_script($system_callback);
  }

  $moved = move_uploaded_file($data['file']['tmp_name'], $path);
  if (!$moved) {
    throw new \Exception("Error moving file.", 1);

  }
  $system_callback['message'] = "File uploaded correctly!";
  $system_callback['code'] = "1";

  $db->commit();
} catch (\Exception $e) {
  $db->rollback();
  $system_callback['code'] = "500";
  $system_callback['message'] = "Unable to upload file: $db->error";
  $system_callback['error'] = "Unable to upload file: $e";

}




exit_script($system_callback);

 ?>
