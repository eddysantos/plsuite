<?php

require 'Resources/vendor/autoload.php';

// include 'src/qb.php';
use Homemade\qb;

require 'Resources/PHP/loginDatabase.php';

echo $db instanceof MySQLi;

$test_var = new \Homemade\queryBuilder('Eduardo');

 ?>
