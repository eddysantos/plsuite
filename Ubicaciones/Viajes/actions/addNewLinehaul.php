<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';



$trip = $_POST;
$user = $_SESSION['user_info']['NombreUsuario'];
$this_year = date('y', strtotime('today'));
$system_callback = [];

function fuel_surcharge($rpm){
  if ($rpm < 1.30) {
    return ".10";
  } elseif ($rpm >= 1.30 && $rpm < 1.40) {
    return ".15";
  } elseif ($rpm >= 1.4 && $rpm < 1.5) {
    return ".20";
  } elseif ($rpm >= 1.5) {
    return ".25";
  }
}

$db->query('LOCK TABLES ct_trip_linehaul, ct_trip_linehaul_movement WRITE;');
$db->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;");

try {
  $db->begin_transaction();

  $origin = array(
    'city'=>$trip['linehaul']['routes'][0]['ocity'],
    'state'=>$trip['linehaul']['routes'][0]['ostate'],
    'zip'=>$trip['linehaul']['routes'][0]['ozip']
  );

  $destination = array(
    'city'=>end($trip['linehaul']['routes'])['dcity'],
    'state'=>end($trip['linehaul']['routes'])['dstate'],
    'zip'=>end($trip['linehaul']['routes'])['dzip']
  );

  $db->commit();
  $system_callback['code'] = 1;
  $system_callback['message'] = "Query executed successfully!";
  $system_callback['insertid'] = $pk_trip;
  $system_callback['tripyear'] = $this_year;
  $db->query('UNLOCK TABLES;');
  exit_script($system_callback);
} catch (Exception $e) {
    // An exception has been thrown
    // We must rollback the transaction
    $db->rollback();
    $system_callback['query']['code'] = "2";
    $system_callback['query']['message'] = "There was a problem executing the query[$db->errno]: $db->error";
    exit_script($system_callback);
}





?>
