<?php

/** PRODUCTION DATABASE **/

// $datab = 'plsuite';
// $host = '10.1.4.10';
// $port = 3306;
// $usr = 'prolog';
// $pwd = 'f4Tnps.03';

/** TEST DATABASE **/
$datab = 'plsuite';
$host = '127.0.0.1';
$port = 8889;
$usr = 'root';
$pwd = 'root';

$db = new mysqli($host, $usr, $pwd, $datab, $port) or die ('Could not connect to the database server ' . $login->error );

 ?>
