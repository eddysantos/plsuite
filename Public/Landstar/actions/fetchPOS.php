<?php
function encrypt($string){
 $cipher = "AES-256-CBC";
 $key =hash('sha256', "ewgdhfjjluo3pip4l");
 $iv = substr(hash('sha256', "sdfkljsadf567890saf"), 0, 16);
 $token = openssl_encrypt($string, $cipher, $key, 0, $iv);
 $token = base64_encode($token);

 return $token;
 // $token = openssl_decrypt(base64_decode("UmhaN284bEUxeStZWXF0eTJ3ODhNQT09"),$cipher, $key, 0, $iv);
}

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/vendor/autoload.php';
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$callback['code'] = 501;
$callback['message'] = "No message specified";
$callback['data'] = "";

$data = $_POST;

$tripHandle = new Trip();
if ($data['status'] == "Unassigned") {
  $pos = $tripHandle->getOpenPOs();
} else {
  $pos = $tripHandle->getPOs($data['date_from'], $data['date_to'], $data['status']);
}

if (!$pos) {
  $callback['message'] = $tripHandle->last_error;
  $callback['code'] = 501;
  exit_script($callback);
} else {
  foreach ($pos as $key => $po) {
    $plscope = "";
    if ($po['idLinehaul']) {
      $encrypted_lh = encrypt($po['idLinehaul']);
      $plscope = "<a href='/plsuite/public/PlScope/plscope.php?lh_reference=$encrypted_lh' target='_blank' class='btn btn-outline-dark btn-sm'><i class='fas fa-map-marked-alt'></i></a>";
    }

    $appt_date = date('m/d H:i', strtotime($po['po_pickup_date'] . ' ' . $po['po_pickup_time']));

      $callback['data'] .=
      "<tr>
        <td>$po[po_number]</td>
        <td>$appt_date</td>
        <td>$po[lhNumber]</td>
        <td>$po[trailer]</td>
        <td>$po[tractor]</td>
        <td>$po[driver]</td>
        <td>$po[status]</td>
        <td>$plscope</td>
      </tr>";
  }
}


$callback['code'] = 1;
exit_script($callback);
?>
