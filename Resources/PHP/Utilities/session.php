<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$root = $_SERVER['DOCUMENT_ROOT'];

date_default_timezone_set('America/Monterrey');

if (!(isset($_SESSION['user_info']))) {
  header("location:/plsuite");
}

 ?>
