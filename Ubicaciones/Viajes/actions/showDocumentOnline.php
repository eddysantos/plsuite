<?php

$valor1 = $_GET['id'];
$cipher = "AES-256-CBC";
$key =hash('sha256', "ewgdhfjjluo3pip4l");
$iv = substr(hash('sha256', "sdfkljsadf567890saf"), 0, 16);
$file_id = openssl_decrypt(base64_decode($valor1),$cipher, $key, 0, $iv);

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



$query = "SELECT document_url FROM document_catalog WHERE pkid_document = ?";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $sc['code'] = "500";
  $sc['message'] = "Error during query prepare [$db->errno]: $db->error";
  die($sc['message']);
}

$stmt->bind_param('s', $file_id);
if (!($stmt)) {
  $sc['code'] = "500";
  $sc['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
  die($sc['message']);
}

if (!($stmt->execute())) {
  $sc['code'] = "500";
  $sc['message'] = "Error during query execution [$db->errno]: $db->error";
  die($sc['message']);
}

$url = $stmt->get_result()->fetch_assoc();

// $url ="https://yourFile.pdf";
$content = file_get_contents($url);

header('Content-Type: application/pdf');
header('Content-Length: ' . strlen($content));
header('Content-Disposition: inline; filename="YourFileName.pdf"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');
ini_set('zlib.output_compression','0');

die($content);

 ?>
