<?php


require $root . '/plsuite/Resources/PHP/Utilities/session.php';

include($root . '/plsuite/Resources/PHP/loginDatabase.php');
date_default_timezone_set('America/Monterrey');

function exit_script($input_array){
  $json_string = json_encode($input_array);
  echo $json_string;
  global $db;
  $db->close();
  die();
}


 ?>
