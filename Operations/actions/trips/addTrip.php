<?php

$root = $_SERVER['DOCUMENT_ROOT'] . "/plsuite";
require $root . "/Resources/vendor/autoload.php";

function dateMash($date, $hour, $minute){
  if ($date == ""|| $hour == ""|| $minute == "") {
    return "";
  }
  return date('Y-m-d H:i:s', strtotime($date . " " . $hour . " " . $minute));
}

$data = $_POST;
$movements = [];



$trip = [
  'fkid_trailer' => $data['fkid_trailer'] ?? ''
];

$lh_origin = $data['stops'][0];
$lh_destination = end($data['stops']);

$linehaul = [
  'origin_formatted_address' => $lh_origin['google_address'] ?? '',
  'origin_street_name' => $lh_origin['street_address'] ?? '',
  'origin_street_number' => $lh_origin['street_number'] ?? '',
  'origin_state' => $lh_origin['state'] ?? '',
  'origin_city' => $lh_origin['city'] ?? '',
  'origin_zip' => $lh_origin['zip_code'] ?? '',
  'origin_country' => $lh_origin['country'] ?? '',
  'destination_formatted_address' => $lh_destination['google_address'] ?? '',
  'destination_street_name' => $lh_destination['street_address'] ?? '',
  'destination_street_number' => $lh_destination['street_number'] ?? '',
  'destination_state' => $lh_destination['state'] ?? '',
  'destination_city' => $lh_destination['city'] ?? '',
  'destination_zip' => $lh_destination['zip_code'] ?? '',
  'destination_country' => $lh_destination['country'] ?? '',
  'trip_rate' => (int) $data['trip_rate'] ?? '',
  'fkid_broker' => $data['fkid_broker'] ?? '',
  'broker_reference' => $data['broker_reference'] ?? '',
  'date_appointment' => dateMash($lh_origin['appt_date_from'], $lh_origin['appt_hour_from'], $lh_origin['appt_minute_from']),
  'date_appointment_to' => dateMash($lh_origin['appt_date_to'], $lh_origin['appt_hour_to'], $lh_origin['appt_minute_to']),
];

foreach ($data['stops'] as $index => $stop) {
  if ($index == 0) {
    continue;
  }

  $origin = $data['stops'][$index - 1];

  $movements[] =
  [
    'origin_formatted_address' => $origin['google_address'] ?? '',
    'origin_street_name' => $origin['street_address'] ?? '',
    'origin_street_number' => $origin['street_number'] ?? '',
    'origin_state' => $origin['state'] ?? '',
    'origin_city' => $origin['city'] ?? '',
    'origin_zip' => $origin['zip_code'] ?? '',
    'origin_country' => $origin['country'] ?? '',
    'destination_formatted_address' => $stop['google_address'] ?? '',
    'destination_street_name' => $stop['street_address'] ?? '',
    'destination_street_number' => $stop['street_number'] ?? '',
    'destination_state' => $stop['state'] ?? '',
    'destination_city' => $stop['city'] ?? '',
    'destination_zip' => $stop['zip_code'] ?? '',
    'destination_country' => $stop['country'] ?? '',
    'miles_google' =>(int) $stop['miles'] ?? '',
    'movement_type' => $stop['movement_type'] ?? '',
    'fkid_tractor' => $data['fkid_tractor'] ?? '',
    'fkid_driver' => $data['fkid_driver'] ?? '',
    'fkid_driver_team' => $data['fkid_driver_team'] ?? '',
  ];
}





$fullTripInfo = [$trip, $linehaul, $movements];



$return_array = [
  'data' => $data,
];

echo json_encode($fullTripInfo);
