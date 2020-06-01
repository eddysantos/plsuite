<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';
require $root . '/plsuite/Resources/vendor/autoload.php';

$db = new Queryi();
$po_lines = [];

$callback = [
  "code"=>500,
  "message"=>"No error specified"
];

foreach($_POST as $post_po){
  $time = date("H:i:s", strtotime($post_po['po_hour'] .":". $post_po['po_minute']));
  error_log($time);
  $po_lines[] = [
      "po_number"=>$post_po['po_number'],
      "po_pickup_date"=>$post_po['po_date'],
      "po_pickup_time"=>$time
  ];
};

foreach ($po_lines as $po) {
  $insert = $db->insert("client_po", $po);
  if (!$insert) {
    $callback['code'] = 501;
    $callback['message'] = "Error in db: $db->last_error";
    exit_script($callback);
  }
}

$callback['code'] = 1;
exit_script($callback);



?>
