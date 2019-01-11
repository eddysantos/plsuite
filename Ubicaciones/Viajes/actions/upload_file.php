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

$system_callback['return'] = [
  "File name"=>$file_name,
  "Directory"=>$directory,
  "Path"=>$path,
  "added_by"=>$added_by,
  "related_id"=>$related_id,
  "file_title"=>$file_title
];

try {

move_uploaded_file($file_name, $directory);
$system_callback['message'] = "File uploaded correctly!";

} catch (\Exception $e) {

$system_callback['code'] = "500";
$system_callback['message'] = "Unable to upload file: $e";

}


exit_script($system_callback);

 ?>
