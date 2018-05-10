<?php

$routeId = $_POST['idRuta'];

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';


$qry = "DELETE FROM cu_rutas WHERE pkIdRuta = ?";
$stmt = $db->prepare($qry);
$stmt->bind_param('s', $routeId);
$stmt->execute();

$qry = "DELETE FROM cud_rutas WHERE fkIdRuta = ?";
$stmt = $db->prepare($qry);
$stmt->bind_param('s', $routeId);
$stmt->execute();

 ?>
