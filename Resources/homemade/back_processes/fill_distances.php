<?php
$root = $_SERVER['DOCUMENT_ROOT'];

//require '/home/esantos/plt/plsuite/Resources/PHP/loginDatabase.php';
require '/Applications/MAMP/htdocs/plsuite/Resources/PHP/loginDatabase.php';
// require $root . "/plsuite/Resources/vendor/autoload.php";
require "/Applications/MAMP/htdocs/plsuite/Resources/vendor/autoload.php";

$cps = "SELECT cp.pk_carta_porte carta_porte_id, cp.pk_carta_porte_number carta_porte , cp.trailer_number trailer_number , o.pk_mx_place origin_id , o.place_alias origin , concat( o.address_street , ' ' , o.address_ext_number , ' ,' , o.address_locality , ' ,' , o.address_city , ' ,' , o.address_state , ' ,' , o.address_zip_code) direccion_origen , d.pk_mx_place destination_id , d.place_alias destination , concat( d.address_street , ' ' , d.address_ext_number , ' ,' , d.address_locality , ' ,' , d.address_city , ' ,' , d.address_state , ' ,' , d.address_zip_code) direccion_destino , cp.date_start date_start , cp.date_end date_end , CONCAT(dr.nameFirst , ' ' , dr.nameLast) driver , t.tractor_number tractor , c.client_name client , t.trip_status trip_status , cp.cp_status cp_status FROM mx_carta_porte cp LEFT JOIN mx_places o ON cp.fk_mx_place_origin = o.pk_mx_place LEFT JOIN mx_places d ON cp.fk_mx_place_destination = d.pk_mx_place LEFT JOIN mx_trips t ON cp.fk_mx_trip = t.pk_mx_trip LEFT JOIN ct_drivers dr ON t.fk_driver = dr.pkid_driver LEFT JOIN mx_clients c ON t.fk_mx_client = c.pk_mx_client WHERE cp.date_start BETWEEN '2020-01-01' AND '2020-06-01' AND cp.distance IS NULL AND fk_mx_place_destination <> fk_mx_place_origin";

$google = new GoogleMaps();
$cps = $db->query($cps)  or die($db->error);
$trips = [];

$update_distance = "UPDATE mx_carta_porte SET distance = ? WHERE pk_carta_porte = ?";
$update_distance = $db->prepare($update_distance) or die($db->error);

while ($row = $cps->fetch_assoc()) {
  $trips[] = $row;
}

$total_trips = count($trips);
$i = 0;
foreach ($trips as $row) {
  $i++;
  echo "Operation $i of $total_trips\n";

  if ($row['origin_id'] == 1) {
    $origin = "27.572382,-99.596220";
  } else {
    $origin = $row['direccion_origen'];
    $origin = str_replace(" ", "+", $origin);
  }

  if ($row['destination_id'] == 1) {
    $destination = "27.572382,-99.596220";
  } else {
    $destination = $row['direccion_destino'];
    $destination = str_replace(" ", "+", $destination);
  }


  echo "$row[carta_porte_id]\n";
  $distance_matrix = $google->getDrivingDistance($origin, $destination);
  if ($distance_matrix['distance'] == NULL) {
    die("-------- Make sure everything is working properly!! ------------ \n");
  }
  $distance = $distance_matrix['distance'] / 1000;
  $update_distance->bind_param('ss', $distance, $row['carta_porte_id']);
  $updated = $update_distance->execute();



  echo "Origin: " . $origin . "\n";
  echo "Destin: " . $destination . "\n";
  echo "Status: " . $updated ? "Distance Saved!" : $update_distance->error;
  // echo "Distance: " . $distance_matrix['distance'] / 1000 . "\n";
  // echo json_encode($distance_matrix) . "\n";
  echo "---------------------------------------------------------\n\n";
}





?>
