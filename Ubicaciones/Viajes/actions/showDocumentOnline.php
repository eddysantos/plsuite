<?php

$valor1 = $_GET['id'];
$cipher = "AES-256-CBC";
$key =hash('sha256', "ewgdhfjjluo3pip4l");
$iv = substr(hash('sha256', "sdfkljsadf567890saf"), 0, 16);
$file_id = openssl_decrypt(base64_decode($valor1),$cipher, $key, 0, $iv);

echo $file_id;
//
// $url ="https://yourFile.pdf";
// $content = file_get_contents($url);
//
// header('Content-Type: application/pdf');
// header('Content-Length: ' . strlen($content));
// header('Content-Disposition: inline; filename="YourFileName.pdf"');
// header('Cache-Control: private, max-age=0, must-revalidate');
// header('Pragma: public');
// ini_set('zlib.output_compression','0');

// die($content);

 ?>
