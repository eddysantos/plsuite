<?php

$root = $_SERVER['DOCUMENT_ROOT'] . "/plsuite";
require $root . "/Resources/vendor/autoload.php";

$pl = new PlSuite();
// error_log(json_encode($_POST));
$filter_value = $_POST['query'];
$return = [
  'code' => 1,
  'suggestions' => [],
  'message' => ""
];

$brokers = $pl->getFilteredBrokerList($filter_value);

if ($brokers) {
  foreach ($brokers as $id => $broker) {
    $return['suggestions'][] = [
      'value'=>$broker,
      'data'=>$id
    ];
  }
} else {
  $return['message'] = $pl->last_error;
  $return['code'] = 2;
}

echo json_encode($return);





 ?>
