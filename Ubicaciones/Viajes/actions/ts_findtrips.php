<?php

// $root = $_SERVER['DOCUMENT_ROOT'];
$r = "/Applications/MAMP/htdocs";
require $r . '/plsuite/Resources/PHP/Utilities/session.php';
require $r . '/plsuite/Resources/PHP/loginDatabase.php';
require $r . '/plsuite/Resources/vendor/autoload.php';

use Homemade\qb;

$test = new \Homemade\queryBuilder($db);
// echo $db instanceOf MySQLi . "\n";

// echo $r;

// echo $db instanceof MySQLi . "\n";

 ?>
